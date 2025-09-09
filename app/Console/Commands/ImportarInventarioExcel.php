<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Producto;
use App\Models\Movimiento;
use App\Models\Clasificacion;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class ImportarInventarioExcel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'inventario:importar-excel {archivo : Ruta del archivo Excel} {--fecha= : Fecha de inventario (YYYY-MM-DD)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Importa datos del inventario desde Excel y crea entradas sin lotes para productos existentes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $archivo = $this->argument('archivo');
        $fecha = $this->option('fecha') ? Carbon::parse($this->option('fecha')) : Carbon::now();

        if (!file_exists($archivo)) {
            $this->error("El archivo {$archivo} no existe.");
            return 1;
        }

        $this->info("Iniciando importación del inventario desde: {$archivo}");
        $this->info("Fecha de inventario: {$fecha->format('d/m/Y')}");

        try {
            $this->info("Procesando archivo...");

            // Leer archivo según extensión (CSV/XLSX)
            $extension = strtolower(pathinfo($archivo, PATHINFO_EXTENSION));
            $rows = [];

            if ($extension === 'csv' || $extension === 'txt') {
                // Lectura robusta de CSV y autodetección de delimitador
                $this->info("Detectando delimitador del CSV...");
                $handle = fopen($archivo, 'r');
                if ($handle === false) {
                    throw new \RuntimeException('No se pudo abrir el archivo CSV.');
                }

                $firstLine = fgets($handle);
                if ($firstLine === false) {
                    fclose($handle);
                    throw new \RuntimeException('El CSV está vacío.');
                }
                $semicolonCount = substr_count($firstLine, ';');
                $commaCount = substr_count($firstLine, ',');
                $delimiter = $semicolonCount > $commaCount ? ';' : ',';
                $this->info("Delimitador detectado: '" . $delimiter . "'");

                // Volver al inicio y leer todo con el delimitador detectado
                rewind($handle);
                while (($data = fgetcsv($handle, 0, $delimiter)) !== false) {
                    // Normalizar: recortar espacios de cada celda
                    $rows[] = array_map(function ($cell) {
                        return is_string($cell) ? trim($cell) : $cell;
                    }, $data);
                }
                fclose($handle);
            } else {
                // XLS/XLSX: usar Laravel Excel
                $rows = Excel::toArray(new class {
                }, $archivo)[0];
            }

            // Detectar si hay encabezados y saltar la primera fila si es necesario
            $startRow = 0;
            if (isset($rows[0])) {
                $headersJoined = strtolower(implode(' ', $rows[0]));
                if (strpos($headersJoined, 'detalle') !== false || strpos($headersJoined, 'cantidad') !== false) {
                $startRow = 1;
                }
            }



            $importados = 0;
            for ($i = $startRow; $i < count($rows); $i++) {
                $row = $rows[$i];
                // Nuevo formato:
                // 0: Fecha
                // 1: Detalle (producto)
                // 2: Cantidad
                // 3: E/S (tipo de movimiento)
                // 4: Observacion
                // 5: Clasificacion
                $fechaMovimiento = null;
                if (!empty($row[0])) {
                    try {
                        $fechaMovimiento = Carbon::createFromFormat('d/m/Y', $row[0]);
                    } catch (\Exception $e1) {
                        try {
                            $fechaMovimiento = Carbon::createFromFormat('Y-m-d', $row[0]);
                        } catch (\Exception $e2) {
                            $fechaMovimiento = Carbon::parse($row[0]);
                        }
                    }
                } else {
                    $fechaMovimiento = $fecha;
                }

                $nombreProducto = trim((string)($row[1] ?? ''));
                $cantidad = (int)($row[2] ?? 0);
                $tipoMovimiento = trim((string)($row[3] ?? ''));
                $observacion = trim((string)($row[4] ?? ''));
                $clasificacionNombre = trim((string)($row[5] ?? ''));

                if (!in_array(strtolower($tipoMovimiento), ['entrada', 'salida']) || !$nombreProducto || $cantidad <= 0) {
                    continue; // Solo procesar entradas y salidas válidas
                }

                // Buscar o crear la clasificación
                $clasificacion = Clasificacion::firstOrCreate([
                    'nombre' => $clasificacionNombre
                ]);

                // Buscar o crear el producto
                $producto = Producto::firstOrCreate([
                    'nombre' => $nombreProducto
                ], [
                    'clasificacion_id' => $clasificacion->id,
                    'stock_actual' => 0,
                    'stock_minimo' => 1
                ]);

                // Actualizar clasificación si cambió
                if ($producto->clasificacion_id !== $clasificacion->id) {
                    $producto->clasificacion_id = $clasificacion->id;
                }

                // Registrar movimiento de entrada o salida sin lote
                Movimiento::create([
                    'tipo_movimiento' => ucfirst(strtolower($tipoMovimiento)),
                    'producto_id' => $producto->id,
                    'cantidad' => $cantidad,
                    'fecha' => $fechaMovimiento,
                    'clasificacion_id' => $clasificacion->id,
                    'lote' => null,
                    'fecha_vencimiento' => null,
                    'solicitante_id' => null,
                    'responsable_id' => null,
                    'motivo' => null,
                    'observaciones' => $observacion,
                    'creado_por' => 'Importación CSV'
                ]);

                // Actualizar stock del producto
                if (strtolower($tipoMovimiento) === 'entrada') {
                    $producto->stock_actual += $cantidad;
                } else {
                    $producto->stock_actual -= $cantidad;
                }
                $producto->save();

                $importados++;
            }

            $this->info("Importación completada exitosamente. Productos importados: {$importados}");
            $this->info("Los productos ahora pueden ser utilizados en salidas sin lotes.");

        } catch (\Exception $e) {
            $this->error("Error durante la importación: " . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
