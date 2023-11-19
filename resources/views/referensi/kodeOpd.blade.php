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
                <h6 class="m-0 font-weight-bold text-primary">Data OPD</h6>
                <div class="btn-group float-right">
                    <a href="javascript:void(0)" onclick="addOpd('{{ route('opd.store') }}')" class="btn btn-sm btn-primary  btn-icon-split">
                        <span class="icon text-white-50">
                            <i class="fas fa-plus-circle"></i>
                        </span>
                        <span class="text">OPD</span>
                    </a>
                </div>
                <div class="btn-group float-right mr-2">
                    <a href="javascript:void(0)" onclick="addBiro('{{ route('opd.store') }}')" class="btn btn-sm btn-primary  btn-icon-split">
                        <span class="icon text-white-50">
                            <i class="fas fa-plus-circle"></i>
                        </span>
                        <span class="text">BIRO</span>
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm table-bordered" id="kodeOpd" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode</th>
                                <th>OPD</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>


    </div>
</div>
@includeIf('form.referensi.kodeOpd')
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
        $(document).ready(function() {

            table = $('#kodeOpd').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                autoWidth: false,
                ajax:{
                    url: '{{ route('opd.data') }}'
                },
                columns:[
                    {data:'DT_RowIndex', searchable:false, sortable:false},
                    {data:'kd_opd'},
                    {data:'nm_opd'},
                    {data:'aksi', searchable:false, sortable:false},
                ]
            });

            $('#modalOpd').validator().on('submit', function (e){
                if(! e.preventDefault()){
                    $.post($('#modalOpd form').attr('action'), $('#modalOpd form').serialize())
                    .done((response) => {
                        $("#modalOpd .alert" ).addClass( "alert-success" );
                        $("#modalOpd .alert").show();
                        $("#modalOpd #massages").append(response);
                        setTimeout(function(){
                            $("#modalOpd .alert" ).removeClass( "alert-success" );
                            $("#modalOpd #massages").empty();
                            $('#modalOpd form')[0].reset();
                            $('#modalOpd').modal('hide');
                        }, 1000);
                        table.ajax.reload();
                    })
                    .fail((errors) => {
                        let err = errors.responseJSON.errors;
                        $("#modalOpd .alert" ).addClass( "alert-danger" );
                        $("#modalOpd .alert").show();
                        $.each(err, function(key, val) {
                            $("#modalOpd #massages").append(val);
                            setTimeout(function(){
                                $("#modalOpd .alert").hide();
                                $("#modalOpd .alert" ).removeClass( "alert-danger" );
                                $("#modalOpd #massages").empty();
                            }, 3000);
                        });
                    });
                }
            });

            $('#modalBiro').validator().on('submit', function (e){
                if(! e.preventDefault()){
                    $.post($('#modalBiro form').attr('action'), $('#modalBiro form').serialize())
                    .done((response) => {
                        $("#modalBiro .alert" ).addClass( "alert-success" );
                        $("#modalBiro .alert").show();
                        $("#modalBiro #massages").append(response);
                        setTimeout(function(){
                            $("#modalBiro .alert" ).removeClass( "alert-success" );
                            $("#modalBiro #massages").empty();
                            $('#modalBiro form')[0].reset();
                            $('#modalBiro').modal('hide');
                        }, 1000);
                        table.ajax.reload();
                    })
                    .fail((errors) => {
                        let err = errors.responseJSON.errors;
                        $("#modalBiro .alert" ).addClass( "alert-danger" );
                        $("#modalBiro .alert").show();
                        $.each(err, function(key, val) {
                            $("#modalBiro #massages").append(val);
                            setTimeout(function(){
                                $("#modalBiro .alert").hide();
                                $("#modalBiro .alert" ).removeClass( "alert-danger" );
                                $("#modalBiro #massages").empty();
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
                            console.log(value);
                            target.append(`<option value="${value.kode}">${value.kode} - ${value.urusan}</option>`)
                        });
                    }
                });
            }

            function dropSubUrusan(selector){
            let target = $('#' + selector);
                target.focus();
                $.ajax({
                    url: "{{ route('urusan.suburusan') }}",
                    type: 'GET',
                    success: function(data) {
                        target.empty()
                        target.attr('placeholder', target.data('placeholder'))
                        target.append(`<option> ${target.data('placeholder')} </option>`)
                        $.each(data, function(key, value) {
                            target.append(`<option value="${value.kode}">${value.kode} - ${value.urusan}</option>`)
                        });
                    }
                });
        }

            $('#modalOpd #urusan').on('change', function() {
                var id = $(this).val();
                var url = `{{ URL::to('urusan/getKodeBidang') }}`;
                $('#modalOpd #bidang').empty().prop('disabled', false);

                $('#modalOpd #suburusan').prop('required',false);
                $('#modalOpd #subbidang').prop('required',false);
                $('#modalOpd #subsuburusan').prop('required',false);
                $('#modalOpd #subsubbidang').prop('required',false);
                $('.insuburusan').hide();
                $('#suburusan').empty();
                $('.insubbidang').hide();
                $('#subbidang').empty();
                $('.insubsuburusan').hide();
                $('#subsuburusan').empty();
                $('.insubsubbidang').hide();
                $('#subsubbidang').empty();
                onChangeSelect(url, id, 'modalOpd #bidang');
            });

            $('#modalOpd #bidang').on('change', function() {
                var id = $(this).val();
                var url = `{{ URL::to('urusan/subUrusan') }}`;
                $('#modalOpd #suburusan').empty();
                $('#modalOpd #subbidang').empty();
                $('#modalOpd .insuburusan').show();
                $('#modalOpd .insubbidang').show();

                $('.insubsuburusan').hide();
                $('#subsuburusan').empty();
                $('.insubsubbidang').hide();
                $('#subsubbidang').empty();
                var getKode = "{{ route('urusan.kodeUrusan','') }}"+"/"+id;
                $.get(getKode)
                .done((response) => {
                    $('#modalOpd #kd_opd').val(response);
                });

                dropSubUrusan('modalOpd #suburusan');
            });

            $('#modalOpd #suburusan').on('change', function() {
                var id = $(this).val();
                var url = `{{ URL::to('urusan/getKodeBidang') }}`;
                $('#modalOpd #suburusan').prop('required','required');
                $('#modalOpd #subbidang').prop('required','required');
                $('.insubsuburusan').hide();
                $('#subsuburusan').empty();
                $('.insubsubbidang').hide();
                $('#subsubbidang').empty();
                onChangeSelect(url, id,'modalOpd #subbidang');
            });

            $('#modalOpd #subbidang').on('change', function() {
                var id_bidang = $('#modalOpd #bidang').val();
                var id = $(this).val();
                let kode = id_bidang+'.'+id;
                var url = `{{ URL::to('urusan/subUrusan') }}`;
                $('#modalOpd .insubsuburusan').show();
                $('#modalOpd .insubsubbidang').show();

                var getKode = "{{ route('urusan.kodeUrusan','') }}"+"/"+kode;
                $.get(getKode)
                .done((response) => {
                    $('#modalOpd #kd_opd').val(response);
                });

                dropSubUrusan('modalOpd #subsuburusan');
            });

            $('#modalOpd #subsuburusan').on('change', function() {
                var id = $(this).val();
                var url = `{{ URL::to('urusan/getKodeBidang') }}`;
                $('#modalOpd #subsuburusan').prop('required','required');
                $('#modalOpd #subsubbidang').prop('required','required');
                onChangeSelect(url, id,'modalOpd #subsubbidang');
            });

            $('#modalOpd #subsubbidang').on('change', function() {
                var id = $(this).val();
                var id_bidang = $('#modalOpd #bidang').val();
                var id_subbidang = $('#modalOpd #subbidang').val();
                let kode = id_bidang+'.'+id_subbidang+'.'+id;
                var getKode = "{{ route('urusan.kodeUrusan','') }}"+"/"+kode;
                $.get(getKode)
                .done((response) => {
                    $('#modalOpd #kd_opd').val(response);
                });
            });


        });

        function dropUrusan(selector){
            let target = $('#' + selector);
                target.focus();
                $.ajax({
                    url: "{{ route('urusan.dropUrusan') }}",
                    type: 'GET',
                    success: function(data) {
                        target.attr('placeholder', target.data('placeholder'))
                        target.append(`<option> ${target.data('placeholder')} </option>`)
                        $.each(data, function(key, value) {
                            target.append(`<option value="${value.kode}">${value.kode} - ${value.urusan}</option>`)
                        });
                    }
                });
        }

        function addBiro(url){
            $('#modalBiro').modal('show');
            $('#modalBiro .modal-title').text('Tambah BIRO');

            $('#modalBiro form')[0].reset();
            $('#modalBiro form').attr('action',url);
            $('#modalBiro [name=_method]').val('post');
            $('#modalBiro [name=nm_opd]').val('');
            $('#modalBiro').on('shown.bs.modal', function () {
                var url = "{{ route('urusan.biro') }}";
                $.get(url)
                .done((response) => {
                    $('#modalBiro #kd_opd').val(response);
                });
            })
        }

        function addOpd(url){
            $('#urusan ').select2({
                theme: 'bootstrap4',
            });

            $('#bidang').select2({
                theme: 'bootstrap4',
            });

            $('#suburusan').select2({
                theme: 'bootstrap4',
            });

            $('#subbidang').select2({
                theme: 'bootstrap4',
            });

            $('#subsuburusan').select2({
                theme: 'bootstrap4',
            });

            $('#subsubbidang').select2({
                theme: 'bootstrap4',
            });
            $('#modalOpd').modal('show');
            $('#modalOpd .modal-title').text('Tambah OPD');

            $('#modalOpd form')[0].reset();
            $('#modalOpd form').attr('action',url);
            $('#modalOpd [name=_method]').val('post');
            $('#modalOpd [name=urusan]').val('');
            $('#modalOpd').on('shown.bs.modal', function () {
                dropUrusan('urusan');
                $('#modalOpd #urusan').empty();
                $('#modalOpd #bidang').empty().prop('disabled', true);
                $('.insuburusan').hide();
                $('.insubbidang').hide();
                $('.insubsuburusan').hide();
                $('.insubsubbidang').hide();
            })
        }

        function hapusOpd(url){
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


    </script>
@endpush
