<?php

namespace App\Imports;

use App\Models\RekeningBelanja;
use Maatwebsite\Excel\Concerns\ToModel;

class RekeningBelanjaImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new RekeningBelanja([
            'kode_akun' => $row[0],
            'nm_akun' => $row[1]
        ]);
    }
}
