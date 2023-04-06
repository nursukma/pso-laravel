<?php

namespace App\Imports;

use App\Models\ModelVariabel;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ModelImport implements ToCollection, WithStartRow
{
    public function collection(Collection $rows)
    {
        if ($rows < 5) {
            return back()->with('warning', 'Template tidak sesuai!');
        }

        foreach ($rows as $row) {

            ModelVariabel::create([
                0 => $row[0],
                1 => $row[1],
                2 => $row[2],
                3 => $row[3],
                4 => $row[4],
                // 5 => $row[5]
                // "bdv" => $row['bdv'],
                // "water" => $row['water'],
                // "acid" => $row['acid'],
                // "ift" => $row['ift'],
                // "color" => $row['color'],
            ]);
        }
    }

    /**
     * @return int
     */
    public function startRow(): int
    {
        return 2;
    }
}
