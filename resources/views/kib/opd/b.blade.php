@extends('themes.dashboard.master')
@section('content')

<div id="content">
    @includeIf('themes.dashboard.navbar')
    <div class="container-fluid">

        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">{{ $title }}</h1>
        </div>
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Data {{ $page }}</h6>
            </div>
            <div class="card-body">

                <div class="table-responsive">
                    <table class="table table-sm table-bordered" id="dataKibb" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th rowspan="2" style="text-align: center;vertical-align: middle;">No</th>
                                <th rowspan="2" style="text-align: center;vertical-align: middle;">Kode</th>
                                <th rowspan="2" style="text-align: center;vertical-align: middle;">Nama Barang</th>
                                <th rowspan="2" style="text-align: center;vertical-align: middle;">Register</th>
                                <th rowspan="2" style="text-align: center;vertical-align: middle;">Merek / Tipe</th>
                                <th rowspan="2" style="text-align: center;vertical-align: middle;">Ukuran / CC</th>
                                <th rowspan="2" style="text-align: center;vertical-align: middle;">Bahan</th>
                                <th rowspan="2" style="text-align: center;vertical-align: middle;">Tahun</th>
                                <th colspan="5" style="text-align: center;vertical-align: middle;">Nomor</th>
                                <th rowspan="2" style="text-align: center;vertical-align: middle;">Asal Usul</th>
                                <th rowspan="2" style="text-align: center;vertical-align: middle;">Harga (ribuan Rp)</th>
                                <th rowspan="2" style="text-align: center;vertical-align: middle;">Keterangan</th>
                                <th rowspan="2" style="text-align: center;vertical-align: middle;">Aksi</th>
                            </tr>
                            <tr>
                                <th style="text-align: center;">Pabrik</th>
                                <th style="text-align: center;">Rangka</th>
                                <th style="text-align: center;">Mesin</th>
                                <th style="text-align: center;">Polisi</th>
                                <th style="text-align: center;">Bpkb</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('css')
<link href="{{ asset('themes/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css">
<link href="https://unpkg.com/gijgo@1.9.14/css/gijgo.min.css" rel="stylesheet" type="text/css" />
<style>
    .table td, .table th {
        font-size: 8pt;
    }
</style>
@endpush

@push('scripits')
<script src="{{ asset('themes/vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('themes/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.full.min.js"></script>
<script src="https://unpkg.com/gijgo@1.9.14/js/gijgo.min.js" type="text/javascript"></script>
<script src="{{ asset('js/validator.min.js') }}"></script>

<script>
let table;
    $(document).ready(function () {
        table = $('#dataKibb').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: {
                url: '{{ route('kibb.data') }}'
            },
            columns: [
                {data: 'DT_RowIndex',searchable: false,sortable: false},
                {data: 'kode'},
                {data: 'uraian'},
                {data: 'register'},
                {data: 'merek'},
                {data: 'spesifikasi'},
                {data: 'bahan'},
                {data: 'tahun'},
                {data: 'pabrik'},
                {data: 'no_rangka'},
                {data: 'no_mesin'},
                {data: 'nopol'},
                {data: 'no_bpkb'},
                {data: 'asal'},
                {data: 'harga'},
                {data: 'keterangan'},
                {data: 'aksi',searchable: false,sortable: false},
            ]
        });
    });

</script>
@endpush
