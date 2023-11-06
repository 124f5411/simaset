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
                <h6 class="m-0 font-weight-bold text-primary">STANDAR BIAYA UMUM (SBU)</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="form-group mr-4">
                        <label><strong>Tahun :</strong></label>
                        <select id='tahun' class="form-control" style="width:auto">
                            <option value=" ">Semua</option>
                            @foreach ($drops['tahun'] as $value)
                                <option value="{{$value->tahun}}">{{$value->tahun}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label><strong>Usulan :</strong></label>
                        <select id='usulan' class="form-control" style="width:auto">
                            <option value=" ">Semua</option>
                            <option value="1">Induk</option>
                            <option value="2">Perubahan</option>
                        </select>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered" id="dataSsh" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>OPD</th>
                                <th>Tahun</th>
                                <th>Usulan</th>
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
                                <th>Status</th>
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
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css">
    <link href='https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css' rel='stylesheet' type='text/css'>
<style>
    .table td, .table th {
        font-size: 10pt;
    }
</style>
@endpush

@push('scripits')
    <script src="{{ asset('themes/vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('themes/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.full.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.print.min.js"></script>
    <script src="{{ asset('js/validator.min.js') }}"></script>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        let table;
        $(document).ready(function() {
            table = $('#dataSsh').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                autoWidth: false,
                ajax:{
                    url: '{{ route('monitor.data','') }}'+'/sbu',
                    type: 'POST',
                    data: function (d) {
                        d.tahuh = $('#tahun').val(),
                        d.usulan = $('#usulan').val(),
                        d.search = $('input[type="search"]').val()
                    }
                },
                dom: 'Blfrtip',
                buttons: [{
                    extend: 'excel',
                    title: 'SBU',
                }],
                lengthMenu:[[10, 25, 50, 100,-1], [10, 25, 50, 100, "All"]],
                columns:[
                    {data:'DT_RowIndex', searchable:false, sortable:false},
                    {data:'opd'},
                    {data:'tahun'},
                    {data:'usulan'},
                    {data:'kode_barang'},
                    {data:'nama_barang'},
                    {data:'uraian'},
                    {data:'spesifikasi'},
                    {data:'satuan'},
                    {data:'harga'},
                    {data:'tkdn'},
                    {data:'rek_1'},
                    {data:'rek_2'},
                    {data:'rek_3'},
                    {data:'rek_4'},
                    {data:'rek_5'},
                    {data:'rek_6'},
                    {data:'rek_7'},
                    {data:'rek_8'},
                    {data:'rek_9'},
                    {data:'rek_10'},
                    {data:'status_ssh'},
                ]
            });

            $('#tahun').change(function(){
                table.draw();
            });

            $('#usulan').change(function(){
                table.draw();
            });

        });
    </script>
@endpush
