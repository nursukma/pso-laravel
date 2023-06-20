<?php

namespace App\Imports;

use App\Models\ModelVariabelAsli;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ModelImportAsli implements ToCollection, WithStartRow
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $rows)
    {
        if ($rows < 5) {
            return back()->with('warning', 'Template tidak sesuai!');
        }

        foreach ($rows as $row) {
            ModelVariabelAsli::create([
                0 => $row[0],
                1 => $row[1],
                2 => $row[2],
                3 => $row[3],
                4 => $row[4],
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
