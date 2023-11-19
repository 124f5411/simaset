<?php

namespace App\Imports;

use App\Models\KodeBarangKontrak;
use Maatwebsite\Excel\Concerns\ToModel;

class KodeBarangKontrakImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new KodeBarangKontrak([
            'kode' => $row[0],
            'nama' => $row[1],
            'masa' => $row[2],
            'batas' => $row[3],
            'kib' => $row[4]
        ]);
    }
}
