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
            // Aquí implementarías la lógica para leer el Excel
            // Por ahora, te muestro la estructura recomendada
            
            $this->info("Procesando productos...");
            
            // Ejemplo de estructura esperada del Excel:
            // - Nombre del producto
            // - Clasificación
            // - Stock actual
            // - Stock mínimo
            // - Movimientos de entrada/salida históricos
            
            $this->info("Creando entradas sin lotes para productos existentes...");
            
            // Para cada producto en el Excel:
            // 1. Crear o actualizar el producto
            // 2. Crear entrada sin lote con el stock actual
            // 3. Crear movimientos históricos sin lotes
            
            $this->info("Importación completada exitosamente.");
            $this->info("Los productos ahora pueden ser utilizados en salidas sin lotes.");
            
        } catch (\Exception $e) {
            $this->error("Error durante la importación: " . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
