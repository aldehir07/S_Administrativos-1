<?php

namespace App\Imports;

use App\Models\Clasificacion;
use App\Models\Producto;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductosImport implements ToModel, WithHeadingRow, WithCustomCsvSettings
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */

    public function getCsvSettings(): array
    {
        return [
            'delimiter' => ';'
        ];
    }

    public function model(array $row)
    {
        $clasificacion = Clasificacion::where('nombre', trim(strtolower($row['clasificacion'])))->first();

        return new Producto([
            'nombre' => $row['nombre'] ?? null,
            'clasificacion_id' => $clasificacion ? $clasificacion->id : null,
        ]);
    }
}
