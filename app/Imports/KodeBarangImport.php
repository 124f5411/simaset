<?php

namespace App\Imports;

use App\Models\KodeBarang;
use Maatwebsite\Excel\Concerns\ToModel;

class KodeBarangImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new KodeBarang([
            'kode_barang' => $row[0],
            'uraian' => $row[1],
            'kelompok' => $row[2],
            'kib' => $row[3]
        ]);
    }
}
