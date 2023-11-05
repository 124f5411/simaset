<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>SSH {{ $tahun }}</title>
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
            <th>Rekening 1</th>
            <th>Rekening 2</th>
            <th>Rekening 3</th>
            <th>Rekening 4</th>
            <th>Rekening 5</th>
            <th>Rekening 6</th>
            <th>Rekening 7</th>
            <th>Rekening 8</th>
            <th>Rekening 9</th>
            <th>Rekening 10</th>
            <th>T  K D N</th>
        </tr>
        <tbody>
            <?php $i=0;?>
            @foreach ($ssh as $value )
            <?php $i++?>
            <tr>
                <td>{{ $i }}</td>
                <td>{{ getValue("kode_barang","referensi_kode_barang","id = ".$value->id_kode); }}</td>
                <td>{{ getValue("uraian","referensi_kode_barang","id = ".$value->id_kode); }}</td>
                <td>{{ $value->uraian }}</td>
                <td>{{ $value->spesifikasi }}</td>
                <td>{{ getValue("nm_satuan","data_satuan","id = ".$value->id_satuan); }}</td>
                <td>{{ number_format($value->harga, 2, ",", ".") }}</td>
                <td>{{ $rek1 }}</td>
                <td>{{ $rek2 }}</td>
                <td>{{ $rek3 }}</td>
                <td>{{ $rek4 }}</td>
                <td>{{ $rek5 }}</td>
                <td>{{ $rek6 }}</td>
                <td>{{ $rek7 }}</td>
                <td>{{ $rek8 }}</td>
                <td>{{ $rek9 }}</td>
                <td>{{ $rek10 }}</td>
                <td>{{ (!is_null($value->tkdn)) ? $value->tkdn : "" }}</td>
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
