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
                        <h6 class="m-0 font-weight-bold text-primary">Data Kelompok</h6>
                        <div class="btn-group float-right">
                            <a href="javascript:void(0)" onclick="addKelompok('{{ route('kode.barang.kontrak.store') }}')" class="btn btn-sm btn-primary  btn-icon-split">
                                <span class="icon text-white-50">
                                    <i class="fas fa-plus-circle"></i>
                                </span>
                                <span class="text">Kode Kelompok</span>
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered" id="dataKelompok" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode Kelompok</th>
                                        <th>Uraian</th>
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
                        <h6 class="m-0 font-weight-bold text-primary">Data Jenis</h6>
                        <div class="btn-group float-right">
                            <a href="javascript:void(0)" onclick="addJenis('{{ route('kode.barang.kontrak.store') }}')" class="btn btn-sm btn-primary  btn-icon-split">
                                <span class="icon text-white-50">
                                    <i class="fas fa-plus-circle"></i>
                                </span>
                                <span class="text">Kode Jenis</span>
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered" id="dataJenis" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode Jenis</th>
                                        <th>Uraian</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Data Objek</h6>
                        <div class="btn-group float-right">
                            <a href="javascript:void(0)" onclick="addObjek('{{ route('kode.barang.kontrak.store') }}')" class="btn btn-sm btn-primary  btn-icon-split">
                                <span class="icon text-white-50">
                                    <i class="fas fa-plus-circle"></i>
                                </span>
                                <span class="text">Kode Objek</span>
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered" id="dataObjek" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode Objek</th>
                                        <th>Uraian</th>
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
                        <h6 class="m-0 font-weight-bold text-primary">Data Rincian</h6>
                        <div class="btn-group float-right">
                            <a href="javascript:void(0)" onclick="addRincian('{{ route('kode.barang.kontrak.store') }}')" class="btn btn-sm btn-primary  btn-icon-split">
                                <span class="icon text-white-50">
                                    <i class="fas fa-plus-circle"></i>
                                </span>
                                <span class="text">Kode Rincian</span>
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered" id="dataRincian" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode Rincian</th>
                                        <th>Uraian</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-lg-6">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Data Sub Rincian</h6>
                        <div class="btn-group float-right">
                            <a href="javascript:void(0)" onclick="addSubRincian('{{ route('kode.barang.kontrak.store') }}')" class="btn btn-sm btn-primary  btn-icon-split">
                                <span class="icon text-white-50">
                                    <i class="fas fa-plus-circle"></i>
                                </span>
                                <span class="text">Kode Sub Rincian</span>
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered" id="dataSubRincian" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode Sub Rincian</th>
                                        <th>Uraian</th>
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
                        <h6 class="m-0 font-weight-bold text-primary">Kode Barang Kontrak</h6>
                        <div class="btn-group float-right">
                            <a href="javascript:void(0)" onclick="addKode('{{ route('kode.barang.kontrak.store') }}')" class="btn btn-sm btn-primary  btn-icon-split">
                                <span class="icon text-white-50">
                                    <i class="fas fa-plus-circle"></i>
                                </span>
                                <span class="text">Kode Barang</span>
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered" id="dataKode" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode Barang</th>
                                        <th>Uraian</th>
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
@includeIf('form.referensi.kodeBarangKontrak')
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

        let table;
        let tblKelompok;
        let tblJenis;
        let tblObjek;
        let tblRincian;
        let tblSubRincian
        $(document).ready(function() {
            $.fn.DataTable.ext.pager.numbers_length = 5;

            table = $('#dataKode').DataTable({
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
                    url: '{{ route('kode.barang.kontrak.data') }}'
                },
                columns:[
                    {data:'DT_RowIndex', searchable:false, sortable:false},
                    {data:'kode'},
                    {data:'nama'},
                    {data:'aksi', searchable:false, sortable:false},
                ]
            });

            tblKelompok = $('#dataKelompok').DataTable({
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
                    url: '{{ route('kode.barang.kontrak.kelompok') }}'
                },
                columns:[
                    {data:'DT_RowIndex', searchable:false, sortable:false},
                    {data:'kode'},
                    {data:'nama'},
                    {data:'aksi', searchable:false, sortable:false},
                ]
            });

            tblJenis = $('#dataJenis').DataTable({
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
                    url: '{{ route('kode.barang.kontrak.jenis') }}'
                },
                columns:[
                    {data:'DT_RowIndex', searchable:false, sortable:false},
                    {data:'kode'},
                    {data:'nama'},
                    {data:'aksi', searchable:false, sortable:false},
                ]
            });

            tblObjek = $('#dataObjek').DataTable({
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
                    url: '{{ route('kode.barang.kontrak.objek') }}'
                },
                columns:[
                    {data:'DT_RowIndex', searchable:false, sortable:false},
                    {data:'kode'},
                    {data:'nama'},
                    {data:'aksi', searchable:false, sortable:false},
                ]
            });

            tblRincian = $('#dataRincian').DataTable({
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
                    url: '{{ route('kode.barang.kontrak.rincian') }}'
                },
                columns:[
                    {data:'DT_RowIndex', searchable:false, sortable:false},
                    {data:'kode'},
                    {data:'nama'},
                    {data:'aksi', searchable:false, sortable:false},
                ]
            });

            tblSubRincian = $('#dataSubRincian').DataTable({
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
                    url: '{{ route('kode.barang.kontrak.subrincian') }}'
                },
                columns:[
                    {data:'DT_RowIndex', searchable:false, sortable:false},
                    {data:'kode'},
                    {data:'nama'},
                    {data:'aksi', searchable:false, sortable:false},
                ]
            });

            $('#modalKode').validator().on('submit', function (e){
                if(! e.preventDefault()){
                    $.post($('#modalKode form').attr('action'), $('#modalKode form').serialize())
                    .done((response) => {
                        $("#modalKode .alert" ).addClass( "alert-success" );
                        $("#modalKode .alert").show();
                        $("#modalKode #massages").append(response);
                        setTimeout(function(){
                            $("#modalKode .alert" ).removeClass( "alert-success" );
                            $("#modalKode #massages").empty();
                            $('#modalKode form')[0].reset();
                            $('#modalKode').modal('hide');
                        }, 1000);
                        table.ajax.reload();
                    })
                    .fail((errors) => {
                        let err = errors.responseJSON.errors;
                        $("#modalKode .alert" ).addClass( "alert-danger" );
                        $("#modalKode .alert").show();
                        $.each(err, function(key, val) {
                            $("#modalKode #massages").append(val);
                            setTimeout(function(){
                                $("#modalKode .alert").hide();
                                $("#modalKode .alert" ).removeClass( "alert-danger" );
                                $("#modalKode #massages").empty();
                            }, 3000);
                        });
                    });
                }
            });


            $('#modalObjek').validator().on('submit', function (e){
                if(! e.preventDefault()){
                    $.post($('#modalObjek form').attr('action'), $('#modalObjek form').serialize())
                    .done((response) => {
                        $("#modalObjek .alert" ).addClass( "alert-success" );
                        $("#modalObjek .alert").show();
                        $("#modalObjek #massages").append(response);
                        setTimeout(function(){
                            $("#modalObjek .alert" ).removeClass( "alert-success" );
                            $("#modalObjek #massages").empty();
                            $('#modalObjek form')[0].reset();
                            $('#modalObjek').modal('hide');
                        }, 1000);
                        tblObjek.ajax.reload();
                    })
                    .fail((errors) => {
                        let err = errors.responseJSON.errors;
                        $("#modalObjek .alert" ).addClass( "alert-danger" );
                        $("#modalObjek .alert").show();
                        $.each(err, function(key, val) {
                            $("#modalObjek #massages").append(val);
                            setTimeout(function(){
                                $("#modalObjek .alert").hide();
                                $("#modalObjek .alert" ).removeClass( "alert-danger" );
                                $("#modalObjek #massages").empty();
                            }, 3000);
                        });
                    });
                }
            });

            $('#modalRincian').validator().on('submit', function (e){
                if(! e.preventDefault()){
                    $.post($('#modalRincian form').attr('action'), $('#modalRincian form').serialize())
                    .done((response) => {
                        $("#modalRincian .alert" ).addClass( "alert-success" );
                        $("#modalRincian .alert").show();
                        $("#modalRincian #massages").append(response);
                        setTimeout(function(){
                            $("#modalRincian .alert" ).removeClass( "alert-success" );
                            $("#modalRincian #massages").empty();
                            $('#modalRincian form')[0].reset();
                            $('#modalRincian').modal('hide');
                        }, 1000);
                        tblRincian.ajax.reload();
                    })
                    .fail((errors) => {
                        let err = errors.responseJSON.errors;
                        $("#modalRincian .alert" ).addClass( "alert-danger" );
                        $("#modalRincian .alert").show();
                        $.each(err, function(key, val) {
                            $("#modalRincian #massages").append(val);
                            setTimeout(function(){
                                $("#modalRincian .alert").hide();
                                $("#modalRincian .alert" ).removeClass( "alert-danger" );
                                $("#modalRincian #massages").empty();
                            }, 3000);
                        });
                    });
                }
            });

            $('#modalSubRincian').validator().on('submit', function (e){
                if(! e.preventDefault()){
                    $.post($('#modalSubRincian form').attr('action'), $('#modalSubRincian form').serialize())
                    .done((response) => {
                        $("#modalSubRincian .alert" ).addClass( "alert-success" );
                        $("#modalSubRincian .alert").show();
                        $("#modalSubRincian #massages").append(response);
                        setTimeout(function(){
                            $("#modalSubRincian .alert" ).removeClass( "alert-success" );
                            $("#modalSubRincian #massages").empty();
                            $('#modalSubRincian form')[0].reset();
                            $('#modalSubRincian').modal('hide');
                        }, 1000);
                        tblSubRincian.ajax.reload();
                    })
                    .fail((errors) => {
                        let err = errors.responseJSON.errors;
                        $("#modalSubRincian .alert" ).addClass( "alert-danger" );
                        $("#modalSubRincian .alert").show();
                        $.each(err, function(key, val) {
                            $("#modalSubRincian #massages").append(val);
                            setTimeout(function(){
                                $("#modalSubRincian .alert").hide();
                                $("#modalSubRincian .alert" ).removeClass( "alert-danger" );
                                $("#modalSubRincian #massages").empty();
                            }, 3000);
                        });
                    });
                }
            });

            $('#modalKelompok').validator().on('submit', function (e){
                if(! e.preventDefault()){
                    $.post($('#modalKelompok form').attr('action'), $('#modalKelompok form').serialize())
                    .done((response) => {
                        $("#modalKelompok .alert" ).addClass( "alert-success" );
                        $("#modalKelompok .alert").show();
                        $("#modalKelompok #massages").append(response);
                        setTimeout(function(){
                            $("#modalKelompok .alert" ).removeClass( "alert-success" );
                            $("#modalKelompok #massages").empty();
                            $('#modalKelompok form')[0].reset();
                            $('#modalKelompok').modal('hide');
                        }, 1000);
                        tblKelompok.ajax.reload();
                    })
                    .fail((errors) => {
                        let err = errors.responseJSON.errors;
                        $("#modalKelompok .alert" ).addClass( "alert-danger" );
                        $("#modalKelompok .alert").show();
                        $.each(err, function(key, val) {
                            $("#modalKelompok #massages").append(val);
                            setTimeout(function(){
                                $("#modalKelompok .alert").hide();
                                $("#modalKelompok .alert" ).removeClass( "alert-danger" );
                                $("#modalKelompok #massages").empty();
                            }, 3000);
                        });
                    });
                }
            });

            $('#modalJenis').validator().on('submit', function (e){
                if(! e.preventDefault()){
                    $.post($('#modalJenis form').attr('action'), $('#modalJenis form').serialize())
                    .done((response) => {
                        $("#modalJenis .alert" ).addClass( "alert-success" );
                        $("#modalJenis .alert").show();
                        $("#modalJenis #massages").append(response);
                        setTimeout(function(){
                            $("#modalJenis .alert" ).removeClass( "alert-success" );
                            $("#modalJenis #massages").empty();
                            $('#modalJenis form')[0].reset();
                            $('#modalJenis').modal('hide');
                        }, 1000);
                        tblJenis.ajax.reload();
                    })
                    .fail((errors) => {
                        let err = errors.responseJSON.errors;
                        $("#modalJenis .alert" ).addClass( "alert-danger" );
                        $("#modalJenis .alert").show();
                        $.each(err, function(key, val) {
                            $("#modalJenis #massages").append(val);
                            setTimeout(function(){
                                $("#modalJenis .alert").hide();
                                $("#modalJenis .alert" ).removeClass( "alert-danger" );
                                $("#modalJenis #massages").empty();
                            }, 3000);
                        });
                    });
                }
            });

            $('#modalEdit').validator().on('submit', function (e){
                if(! e.preventDefault()){
                    $.post($('#modalEdit form').attr('action'), $('#modalEdit form').serialize())
                    .done((response) => {
                        $("#modalEdit .alert" ).addClass( "alert-success" );
                        $("#modalEdit .alert").show();
                        $("#modalEdit #massages").append(response);
                        setTimeout(function(){
                            $("#modalEdit .alert" ).removeClass( "alert-success" );
                            $("#modalEdit #massages").empty();
                            $('#modalEdit form')[0].reset();
                            $('#modalEdit').modal('hide');
                        }, 1000);
                        var tbl = $('#modalEdit #table').val();
                        if(tbl == 'semua'){
                            table.ajax.reload();
                        }

                        if(tbl == 'kelompok'){
                            tblKelompok.ajax.reload();
                        }

                        if(tbl == 'jenis'){
                            tblJenis.ajax.reload();
                        }

                        if(tbl == 'objek'){
                            tblObjek.ajax.reload();
                        }

                        if(tbl == 'rincian'){
                            tblRincian.ajax.reload();
                        }

                        if(tbl == 'subRincian'){
                            tblSubRincian.ajax.reload();
                        }
                    })
                    .fail((errors) => {
                        let err = errors.responseJSON.errors;
                        $("#modalEdit .alert" ).addClass( "alert-danger" );
                        $("#modalEdit .alert").show();
                        $.each(err, function(key, val) {
                            $("#modalEdit #massages").append(val);
                            setTimeout(function(){
                                $("#modalEdit .alert").hide();
                                $("#modalEdit .alert" ).removeClass( "alert-danger" );
                                $("#modalEdit #massages").empty();
                            }, 3000);
                        });
                    });
                }
            });

            function onChangeSelect(url, id, name) {
                $.ajax({
                    url: url + '/' + id,
                    type: 'GET',
                    success: function(data) {
                        let target = $('#' + name);
                        target.attr('disabled', false);
                        target.empty()
                        target.attr('placeholder', target.data('placeholder'))
                        target.append(`<option> ${target.data('placeholder')} </option>`)
                        $.each(data, function(key, value) {
                            target.append(`<option value="${value.kode}">${value.kode} - ${value.nama}</option>`)
                        });
                    }
                });
            }

            $('#modalJenis #kelompok').on('change', function() {
                var id = $(this).val();
                var url = "{{ route('ambilkode.jenis','') }}"+"/"+id;
                $.get(url)
                .done((response) => {
                    $('#modalJenis #kode').val(response);
                });
            });

            $('#modalObjek #kelompok').on('change', function() {
                var id = $(this).val();
                var url = `{{ URL::to('kodeJenis') }}`;
                $('#modalObjek #jenis').empty().prop('disabled', false);
                $('#modalObjek #objek').empty().prop('disabled', true);
                onChangeSelect(url, id, 'modalObjek #jenis');
            });

            $('#modalObjek #jenis').on('change', function() {
                var id = $(this).val();
                var url = "{{ route('ambilkode.objek','') }}"+"/"+id;
                $.get(url)
                .done((response) => {
                    $('#modalObjek #kode').val(response);
                });
            });

            $('#modalRincian #kelompok').on('change', function() {
                var id = $(this).val();
                var url = `{{ URL::to('kodeJenis') }}`;
                $('#modalRincian #jenis').empty().prop('disabled', false);
                $('#modalRincian #objek').empty().prop('disabled', true);
                onChangeSelect(url, id, 'modalRincian #jenis');
            });

            $('#modalRincian #jenis').on('change', function() {
                var id = $(this).val();
                var url = `{{ URL::to('kodeObjek') }}`;
                $('#modalRincian #objek').empty().prop('disabled', false);
                onChangeSelect(url, id, 'modalRincian #objek');
            });

            $('#modalRincian #objek').on('change', function() {
                var id = $(this).val();
                var url = "{{ route('ambilkode.rincian','') }}"+"/"+id;
                $.get(url)
                .done((response) => {
                    $('#modalRincian #kode').val(response);
                });
            });

            $('#modalSubRincian #kelompok').on('change', function() {
                var id = $(this).val();
                var url = `{{ URL::to('kodeJenis') }}`;
                $('#modalSubRincian #jenis').empty().prop('disabled', false);
                $('#modalSubRincian #objek').empty().prop('disabled', true);
                $('#modalSubRincian #rincian').empty().prop('disabled', true);
                onChangeSelect(url, id, 'modalSubRincian #jenis');
            });

            $('#modalSubRincian #jenis').on('change', function() {
                var id = $(this).val();
                var url = `{{ URL::to('kodeObjek') }}`;
                $('#modalSubRincian #objek').empty().prop('disabled', false);
                $('#modalSubRincian #rincian').empty().prop('disabled', true);
                onChangeSelect(url, id, 'modalSubRincian #objek');
            });

            $('#modalSubRincian #objek').on('change', function() {
                var id = $(this).val();
                var url = `{{ URL::to('kodeRincian') }}`;
                $('#modalSubRincian #rincian').empty().prop('disabled', false);
                onChangeSelect(url, id, 'modalSubRincian #rincian');
            });

            $('#modalSubRincian #rincian').on('change', function() {
                var id = $(this).val();
                var url = "{{ route('ambilkode.subrincian','') }}"+"/"+id;
                $.get(url)
                .done((response) => {
                    $('#modalSubRincian #kode').val(response);
                });
            });

            $('#modalKode #kelompok').on('change', function() {
                var id = $(this).val();
                var url = `{{ URL::to('kodeJenis') }}`;
                $('#modalKode #jenis').empty().prop('disabled', false);
                $('#modalKode #objek').empty().prop('disabled', true);
                $('#modalKode #rincian').empty().prop('disabled', true);
                $('#modalKode #subrincian').empty().prop('disabled', true);
                onChangeSelect(url, id, 'modalKode #jenis');
            });

            $('#modalKode #jenis').on('change', function() {
                var id = $(this).val();
                var url = `{{ URL::to('kodeObjek') }}`;
                $('#modalKode #objek').empty().prop('disabled', false);
                $('#modalKode #rincian').empty().prop('disabled', true);
                $('#modalKode #subrincian').empty().prop('disabled', true);
                onChangeSelect(url, id, 'modalKode #objek');
            });

            $('#modalKode #objek').on('change', function() {
                var id = $(this).val();
                var url = `{{ URL::to('kodeRincian') }}`;
                $('#modalKode #rincian').empty().prop('disabled', false);
                $('#modalKode #subrincian').empty().prop('disabled', true);
                onChangeSelect(url, id, 'modalKode #rincian');
            });

            $('#modalKode #rincian').on('change', function() {
                var id = $(this).val();
                var url = `{{ URL::to('kodeSubRincian') }}`;
                $('#modalKode #subrincian').empty().prop('disabled', false);
                onChangeSelect(url, id, 'modalKode #subrincian');
            });

            $('#modalKode #subrincian').on('change', function() {
                var id = $(this).val();
                var url = "{{ route('ambilkode.barang','') }}"+"/"+id;
                $.get(url)
                .done((response) => {
                    $('#modalKode #kode').val(response);
                });
            });


        });

        function dropKelompok(selector){
            let target = $('#' + selector);
                target.focus();
                $.ajax({
                    url: "{{ route('kode.barang.kontrak.kelompokdropdown') }}",
                    type: 'GET',
                    success: function(data) {
                        target.empty()
                        target.attr('placeholder', target.data('placeholder'))
                        target.append(`<option> ${target.data('placeholder')} </option>`)
                        $.each(data, function(key, value) {
                            target.append(`<option value="${value.kode}">${value.kode} - ${value.nama}</option>`)
                        });
                    }
                });
        }

        function addKode(url){
            $('#modalKode #kelompok ').select2({
                theme: 'bootstrap4',
            });

            $('#modalKode #jenis').select2({
                theme: 'bootstrap4',
            });

            $('#modalKode #objek').select2({
                theme: 'bootstrap4',
            });

            $('#modalKode #rincian').select2({
                theme: 'bootstrap4',
            });

            $('#modalKode #subrincian').select2({
                theme: 'bootstrap4',
            });
            $('#modalKode').modal('show');
            $('#modalKode .modal-title').text('Tambah Data Kode Barang');

            $('#modalKode form')[0].reset();
            $('#modalKode form').attr('action',url);
            $('#modalKode [name=_method]').val('post');
            $('#modalKode [name=kode]').val('');
            $('#modalKode').on('shown.bs.modal', function () {
                dropKelompok('modalKode #kelompok');
                $('#modalKode #jenis').empty().prop('disabled', true);
                $('#modalKode #objek').empty().prop('disabled', true);
                $('#modalKode #rincian').empty().prop('disabled', true);
                $('#modalKode #subrincian').empty().prop('disabled', true);
            })
        }

        function addKelompok(url){
            $('#modalKelompok').modal('show');
            $('#modalKelompok .modal-title').text('Tambah Data Kode Kelompok');

            $('#modalKelompok form')[0].reset();
            $('#modalKelompok form').attr('action',url);
            $('#modalKelompok [name=_method]').val('post');
            $('#modalKelompok [name=nama]').val('');
            $('#modalKelompok').on('shown.bs.modal', function () {
                var url = "{{ route('ambilkode.kelompok') }}";
                $.get(url)
                .done((response) => {
                    $('#modalKelompok #kode').val(response);
                });
                $('#modalKelompok #nama').focus();
            })

        }

        function addJenis(url){
            $('#modalJenis #kelompok').select2({
                theme: 'bootstrap4',
            });
            $('#modalJenis').modal('show');
            $('#modalJenis .modal-title').text('Tambah Data Kode Ojek');

            $('#modalJenis form')[0].reset();
            $('#modalJenis form').attr('action',url);
            $('#modalJenis [name=_method]').val('post');
            $('#modalJenis [name=kode]').val('');
            $('#modalJenis').on('shown.bs.modal', function () {
                dropKelompok('modalJenis #kelompok');
            })

        }

        function addObjek(url){
            $('#modalObjek #kelompok').select2({
                theme: 'bootstrap4',
            });
            $('#modalObjek #jenis').select2({
                theme: 'bootstrap4',
            });
            $('#modalObjek').modal('show');
            $('#modalObjek .modal-title').text('Tambah Data Kode Ojek');

            $('#modalObjek form')[0].reset();
            $('#modalObjek form').attr('action',url);
            $('#modalObjek [name=_method]').val('post');
            $('#modalObjek [name=kode]').val('');
            $('#modalObjek').on('shown.bs.modal', function () {
                dropKelompok('modalObjek #kelompok');
                $('#modalObjek #jenis').empty().prop('disabled', true);
            })

        }

        function addRincian(url){
            $('#modalRincian #kelompok').select2({
                theme: 'bootstrap4',
            });

            $('#modalRincian #jenis').select2({
                theme: 'bootstrap4',
            });

            $('#modalRincian #objek').select2({
                theme: 'bootstrap4',
            });
            $('#modalRincian').modal('show');
            $('#modalRincian .modal-title').text('Tambah Data Kode Rincian');

            $('#modalRincian form')[0].reset();
            $('#modalRincian form').attr('action',url);
            $('#modalRincian [name=_method]').val('post');
            $('#modalRincian [name=kode]').val('');
            $('#modalRincian').on('shown.bs.modal', function () {
                dropKelompok('modalRincian #kelompok');
                $('#modalRincian #jenis').empty().prop('disabled', true);
                $('#modalRincian #objek').empty().prop('disabled', true);
            })

        }

        function addSubRincian(url){
            $('#modalSubRincian #kelompok').select2({
                theme: 'bootstrap4',
            });

            $('#modalSubRincian #jenis').select2({
                theme: 'bootstrap4',
            });

            $('#modalSubRincian #objek').select2({
                theme: 'bootstrap4',
            });

            $('#modalSubRincian #rincian').select2({
                theme: 'bootstrap4',
            });
            $('#modalSubRincian').modal('show');
            $('#modalSubRincian .modal-title').text('Tambah Data Kode Sub Rincian');

            $('#modalSubRincian form')[0].reset();
            $('#modalSubRincian form').attr('action',url);
            $('#modalSubRincian [name=_method]').val('post');
            $('#modalSubRincian [name=kode]').val('');
            $('#modalSubRincian').on('shown.bs.modal', function () {
                dropKelompok('modalSubRincian #kelompok');
                $('#modalSubRincian #jenis').empty().prop('disabled', true);
                $('#modalSubRincian #objek').empty().prop('disabled', true);
                $('#modalSubRincian #rincian').empty().prop('disabled', true);
            })

        }

        function importKode(url){
            $('#modalKodeImport').modal('show');
            $('#modalKodeImport .modal-title').text('Import Kode Barang');

            $('#modalKodeImport form')[0].reset();
            $('#modalKodeImport form').attr('action',url);
            $('#modalKodeImport [name=_method]').val('post');
            $('#modalKodeImport [name=files]').val('');
            $('#modalKodeImport').on('shown.bs.modal', function () {
                $('#files').focus();
            })
        }

        function editKode(url, id,table){
            $('#modalEdit').modal('show');
            $('#modalEdit .modal-title').text('Ubah Kode Barang');

            $('#modalEdit form')[0].reset();
            $('#modalEdit form').attr('action',url);
            $('#modalEdit [name=_method').val('put');
            $('#modalEdit [name=nama]').val('');
            $('#modalEdit').on('shown.bs.modal', function () {
                $('#modalEdit #nama').focus();
            })

            let show = "{{route('kode.barang.kontrak.show', '')}}"+"/"+id;
            $.get(show)
            .done((response) => {
                $('#modalEdit [name=nama]').val(response.nama);
                $('#modalEdit [name=table]').val(table);
            })
            .fail((errors) => {
                alert('Gagl tampil data');
                return;
            })
        }

        function hapusObjek(url){
            if (confirm('Yakin ingin menghapus data terpilih?')) {
                    $.post(url, {
                            '_token': $('[name=csrf-token]').attr('content'),
                            '_method': 'delete'
                        })
                        .done((response) => {
                            tblObjek.ajax.reload();
                        })
                        .fail((errors) => {
                            alert('Gagal hapus data');
                            return;
                        });
                }
        }

        function hapusRincian(url){
            if (confirm('Yakin ingin menghapus data terpilih?')) {
                    $.post(url, {
                            '_token': $('[name=csrf-token]').attr('content'),
                            '_method': 'delete'
                        })
                        .done((response) => {
                            tblRincian.ajax.reload();
                        })
                        .fail((errors) => {
                            alert('Gagal hapus data');
                            return;
                        });
                }
        }

        function hapusSubRincian(url){
            if (confirm('Yakin ingin menghapus data terpilih?')) {
                    $.post(url, {
                            '_token': $('[name=csrf-token]').attr('content'),
                            '_method': 'delete'
                        })
                        .done((response) => {
                            tblSubRincian.ajax.reload();
                        })
                        .fail((errors) => {
                            alert('Gagal hapus data');
                            return;
                        });
                }
        }

        function hapusKode(url){
            if (confirm('Yakin ingin menghapus data terpilih?')) {
                    $.post(url, {
                            '_token': $('[name=csrf-token]').attr('content'),
                            '_method': 'delete'
                        })
                        .done((response) => {
                            table.ajax.reload();
                        })
                        .fail((errors) => {
                            alert('Gagal hapus data');
                            return;
                        });
                }
        }

        function hapusKelompok(url){
            if (confirm('Yakin ingin menghapus data terpilih?')) {
                    $.post(url, {
                            '_token': $('[name=csrf-token]').attr('content'),
                            '_method': 'delete'
                        })
                        .done((response) => {
                            tblKelompok.ajax.reload();
                        })
                        .fail((errors) => {
                            alert('Gagal hapus data');
                            return;
                        });
                }
        }

        function hapusJenis(url){
            if (confirm('Yakin ingin menghapus data terpilih?')) {
                    $.post(url, {
                            '_token': $('[name=csrf-token]').attr('content'),
                            '_method': 'delete'
                        })
                        .done((response) => {
                            tblJenis.ajax.reload();
                        })
                        .fail((errors) => {
                            alert('Gagal hapus data');
                            return;
                        });
                }
        }
    </script>
@endpush
