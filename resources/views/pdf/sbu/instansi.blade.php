<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>SBU {{ $tahun }}</title>
    <link href="{{ asset('themes/css/sb-admin-2.min.css') }}" rel="stylesheet">
    <style>
        .table td, .table th {
            font-size: 10pt;
        }
    </style>
</head>
<body>
    <div class="text-center text-dark font-weight-light">
        <img src="{{ asset('logo/pbd.png') }}" class="rounded" alt="imms" width="100px" >
        <p class="font-weight-light">{{$instansi}} <br>{{$title}} {{ $tahun }} <br> {{strtoupper($opd)}}</p>
    </div>
    <table class="table table-sm table-bordered mt-4 text-dark" width="100%" cellspacing="0">
        <tr>
            <th>No</th>
            <th>Kode Barang</th>
            <th>Nama Barang</th>
            <th>Uraian</th>
            <th>Spesfikasi</th>
            <th>Satuan</th>
            <th>Harga</th>
            <th>TKDN</th>
            <th>Rek 1</th>
            <th>Rek 2</th>
            <th>Rek 3</th>
            <th>Rek 4</th>
            <th>Rek 5</th>
            <th>Rek 6</th>
            <th>Rek 7</th>
            <th>Rek 8</th>
            <th>Rek 9</th>
            <th>Rek 10</th>
        </tr>
        <tbody>
            <?php $i=0;?>
            @foreach ($sbu as $value )
            <?php $i++?>
            <tr>
                <td>{{ $i }}</td>
                <td>{{ getValue("kode_barang","referensi_kode_barang","id = ".$value->id_kode); }}</td>
                <td>{{ getValue("uraian","referensi_kode_barang","id = ".$value->id_kode); }}</td>
                <td>{{ $value->uraian }}</td>
                <td>{{ $value->spesifikasi }}</td>
                <td>{{ getValue("nm_satuan","data_satuan","id = ".$value->id_satuan); }}</td>
                <td>{{ number_format($value->harga, 2, ",", ".") }}</td>
                <td>{{ (!is_null($value->tkdn)) ? $value->tkdn : "" }}</td>
                <td>{{ (!is_null($value->rek_1)) ? getValue("kode_akun","referensi_rekening_belanja"," id = ".$value->rek_1) :""; }}</td>
                <td>{{ (!is_null($value->rek_2)) ? getValue("kode_akun","referensi_rekening_belanja"," id = ".$value->rek_2) : ""; }}</td>
                <td>{{ (!is_null($value->rek_3)) ? getValue("kode_akun","referensi_rekening_belanja"," id = ".$value->rek_3) : ""; }}</td>
                <td>{{ (!is_null($value->rek_4)) ? getValue("kode_akun","referensi_rekening_belanja"," id = ".$value->rek_4) : ""; }}</td>
                <td>{{ (!is_null($value->rek_5)) ? getValue("kode_akun","referensi_rekening_belanja"," id = ".$value->rek_5) : ""; }}</td>
                <td>{{ (!is_null($value->rek_6)) ? getValue("kode_akun","referensi_rekening_belanja"," id = ".$value->rek_6) : ""; }}</td>
                <td>{{ (!is_null($value->rek_7)) ? getValue("kode_akun","referensi_rekening_belanja"," id = ".$value->rek_7) : ""; }}</td>
                <td>{{ (!is_null($value->rek_8)) ? getValue("kode_akun","referensi_rekening_belanja"," id = ".$value->rek_8) : ""; }}</td>
                <td>{{ (!is_null($value->rek_9)) ? getValue("kode_akun","referensi_rekening_belanja"," id = ".$value->rek_9) : ""; }}</td>
                <td>{{ (!is_null($value->rek_10)) ? getValue("kode_akun","referensi_rekening_belanja"," id = ".$value->rek_10): ""; }}</td>
            </tr>

            @endforeach
        </tbody>
    </table>
    <div class="float-right" style="width: 350px">
        <div class="text-center text-dark font-weight-light">
            <h6 class="mt-2">Kepala {{ $opd }}</h6><br><br><br>
            <h6><u>{{ $ttd->nm_pimp }}</u><br> NIP {{ $ttd->nip }}</h6>
        </div>
    </div>
</body>
</html>
