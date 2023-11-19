@extends('themes.dashboard.master')
@section('content')

<div id="content">
    @includeIf('themes.dashboard.navbar')
    <div class="container-fluid">

        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">{{ $title }}</h1>
        </div>


        <div class="row">
            <div class="col-lg-6">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Data Urusan</h6>
                        <div class="btn-group float-right">
                            <a href="javascript:void(0)" onclick="addUrusan('')" class="btn btn-sm btn-primary  btn-icon-split">
                                <span class="icon text-white-50">
                                    <i class="fas fa-plus-circle"></i>
                                </span>
                                <span class="text">Kode Urusan</span>
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered" id="dataUrusan" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode</th>
                                        <th>Urusan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Data Sub Urusan</h6>
                        <div class="btn-group float-right">
                            <a href="javascript:void(0)" onclick="addSub('')" class="btn btn-sm btn-primary  btn-icon-split">
                                <span class="icon text-white-50">
                                    <i class="fas fa-plus-circle"></i>
                                </span>
                                <span class="text">Kode Sub Urusan </span>
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered" id="dataSubUrusan" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode</th>
                                        <th>Sub Urusan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>
</div>
@includeIf('form.referensi.kodeUrusan')
@endsection

@push('css')
    <link href="{{ asset('themes/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css">
    <style>
        .table td, .table th {
            font-size: 10pt;
        }
    </style>

@endpush

@push('scripits')
    <script src="{{ asset('themes/vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('themes/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.full.min.js"></script>
    <script src="{{ asset('js/validator.min.js') }}"></script>

    <script>
        let tblUrusan;
        let tblSubUrusan;
        $(document).ready(function() {

            tblUrusan = $('#dataUrusan').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                autoWidth: false,
                stateSave: true,
                scrollY: 200,
                scroller: {
                    loadingIndicator: true
                },
                ajax:{
                    url: '{{ route('urusan.dataurusan') }}'
                },
                columns:[
                    {data:'DT_RowIndex', searchable:false, sortable:false},
                    {data:'kode'},
                    {data:'urusan'},
                    {data:'aksi', searchable:false, sortable:false},
                ]
            });

            tblSubUrusan = $('#dataSubUrusan').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                autoWidth: false,
                stateSave: true,
                scrollY: 200,
                scroller: {
                    loadingIndicator: true
                },
                ajax:{
                    url: '{{ route('urusan.datasuburusan') }}'
                },
                columns:[
                    {data:'DT_RowIndex', searchable:false, sortable:false},
                    {data:'kode'},
                    {data:'urusan'},
                    {data:'aksi', searchable:false, sortable:false},
                ]
            });
        });

    </script>
@endpush
