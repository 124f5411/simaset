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
                <h6 class="m-0 font-weight-bold text-primary">Rincian Kontrak</h6>

                <div class="card mt-2 mb-2" style="width: auto;">
                    <div class="card-header">
                        <?= getValue("no_kontrak","data_kontrak" ," id = ".decrypt(Request::segment(3))) ?>
                    </div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">Tahun : <?= getValue("tahun","data_kontrak" ," id = ".decrypt(Request::segment(3))) ?></li>
                        <li class="list-group-item">Kegiatan : <?= getValue("nm_kontrak","data_kontrak" ," id = ".decrypt(Request::segment(3))) ?></li>
                    </ul>
                </div>
                <a href="#" onclick="addRincian('{{ route('kontrak.rincian.store',Request::segment(3)) }}')" class="btn btn-primary btn-icon-split float-right">
                    <span class="icon text-white-50">
                        <i class="fas fa-plus-circle"></i>
                    </span>
                    <span class="text">{{ $page }}</span>
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm table-bordered" id="dataRincian" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th style="width: 200px">Jenis Aset</th>
                                <th>Kode Aset</th>
                                <th>No Register</th>
                                <th>Asal Usul Dana</th>
                                <th>Harga</th>
                                <th>KIB</th>
                                <th>Detail</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
@includeIf('form.kontrak.opd.rincian')
@includeIf('form.kontrak.opd.edit')
@includeIf('form.kontrak.opd.detail.kiba')
@includeIf('form.kontrak.opd.detail.kibb')
@includeIf('form.kontrak.opd.detail.kibc')
@includeIf('form.kontrak.opd.detail.kibd')
@includeIf('form.kontrak.opd.detail.kibe')
@includeIf('form.kontrak.opd.detail.kibf')
@endsection

@push('css')
    <link href="{{ asset('themes/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css">
    <link href="https://unpkg.com/gijgo@1.9.14/css/gijgo.min.css" rel="stylesheet" type="text/css" />
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
    <script src="https://unpkg.com/gijgo@1.9.14/js/gijgo.min.js" type="text/javascript"></script>
    <script src="{{ asset('js/validator.min.js') }}"></script>

    <script>
        let table;
        let id_kontrak = '{{Request::segment(3)}}';
        $(document).ready(function() {
            $('#tgl_sertifikat').datepicker({
                uiLibrary: 'bootstrap4',
                format: 'yyyy-mm-dd'
            });
            $('#tgl_dokumen').datepicker({
                uiLibrary: 'bootstrap4',
                format: 'yyyy-mm-dd'
            });
            $('#modalKibD #tgl_dokumen').datepicker({
                uiLibrary: 'bootstrap4',
                format: 'yyyy-mm-dd'
            });
            $('#t_mulai').datepicker({
                uiLibrary: 'bootstrap4',
                format: 'yyyy-mm-dd'
            });
            table = $('#dataRincian').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                autoWidth: false,
                ajax:{
                    url: '{{ route('kontrak.rincian.data','') }}'+'/'+id_kontrak,
                },
                columns:[
                    {data:'DT_RowIndex', searchable:false, sortable:false},
                    {data:'jenis_aset'},
                    {data:'kode'},
                    {data:'register'},
                    {data:'asal'},
                    {data:'harga'},
                    {data:'kib'},
                    {data:'details',searchable:false, sortable:false},
                    {data:'aksi', searchable:false, sortable:false},
                ]
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
                        table.ajax.reload();
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

            $('#modalEditRincian').validator().on('submit', function (e){
                if(! e.preventDefault()){
                    $.post($('#modalEditRincian form').attr('action'), $('#modalEditRincian form').serialize())
                    .done((response) => {
                        $("#modalEditRincian .alert" ).addClass( "alert-success" );
                        $("#modalEditRincian .alert").show();
                        $("#modalEditRincian #massages").append(response);
                        setTimeout(function(){
                            $("#modalEditRincian .alert" ).removeClass( "alert-success" );
                            $("#modalEditRincian #massages").empty();
                            $('#modalEditRincian form')[0].reset();
                            $('#modalEditRincian').modal('hide');
                        }, 1000);
                        table.ajax.reload();
                    })
                    .fail((errors) => {
                        let err = errors.responseJSON.errors;
                        $("#modalEditRincian .alert" ).addClass( "alert-danger" );
                        $("#modalEditRincian .alert").show();
                        $.each(err, function(key, val) {
                            $("#modalEditRincian #massages").append(val);
                            setTimeout(function(){
                                $("#modalEditRincian .alert").hide();
                                $("#modalEditRincian .alert" ).removeClass( "alert-danger" );
                                $("#modalEditRincian #massages").empty();
                            }, 3000);
                        });
                    });
                }
            });

            $('#modalKibA').validator().on('submit', function (event){
                if(! event.preventDefault()){
                    $.post($('#modalKibA form').attr('action'), $('#modalKibA form').serialize())
                    .done((response) => {
                        $("#modalKibA .alert" ).addClass( "alert-success" );
                        $("#modalKibA .alert").show();
                        $("#modalKibA #massages").append(response);
                        setTimeout(function(){
                            $("#modalKibA .alert" ).removeClass( "alert-success" );
                            $("#modalKibA #massages").empty();
                            $('#modalKibA form')[0].reset();
                            $('#modalKibA').modal('hide');
                        }, 1000);
                        table.ajax.reload();
                    })
                    .fail((errors) => {
                        let err = errors.responseJSON.errors;
                        $("#modalKibA .alert" ).addClass( "alert-danger" );
                        $("#modalKibA .alert").show();
                        $.each(err, function(key, val) {
                            $("#modalKibA #massages").append(val);
                            setTimeout(function(){
                                $("#modalKibA .alert").hide();
                                $("#modalKibA .alert" ).removeClass( "alert-danger" );
                                $("#modalKibA #massages").empty();
                            }, 3000);
                        });
                    });
                }
            });

            $('#modalKibB').validator().on('submit', function (event){
                if(! event.preventDefault()){
                    $.post($('#modalKibB form').attr('action'), $('#modalKibB form').serialize())
                    .done((response) => {
                        $("#modalKibB .alert" ).addClass( "alert-success" );
                        $("#modalKibB .alert").show();
                        $("#modalKibB #massages").append(response);
                        setTimeout(function(){
                            $("#modalKibB .alert" ).removeClass( "alert-success" );
                            $("#modalKibB #massages").empty();
                            $('#modalKibB form')[0].reset();
                            $('#modalKibB').modal('hide');
                        }, 1000);
                        table.ajax.reload();
                    })
                    .fail((errors) => {
                        let err = errors.responseJSON.errors;
                        $("#modalKibB .alert" ).addClass( "alert-danger" );
                        $("#modalKibB .alert").show();
                        $.each(err, function(key, val) {
                            $("#modalKibB #massages").append(val);
                            setTimeout(function(){
                                $("#modalKibB .alert").hide();
                                $("#modalKibB .alert" ).removeClass( "alert-danger" );
                                $("#modalKibB #massages").empty();
                            }, 3000);
                        });
                    });
                }
            });

            $('#modalKibC').validator().on('submit', function (event){
                if(! event.preventDefault()){
                    $.post($('#modalKibC form').attr('action'), $('#modalKibC form').serialize())
                    .done((response) => {
                        $("#modalKibC .alert" ).addClass( "alert-success" );
                        $("#modalKibC .alert").show();
                        $("#modalKibC #massages").append(response);
                        setTimeout(function(){
                            $("#modalKibC .alert" ).removeClass( "alert-success" );
                            $("#modalKibC #massages").empty();
                            $('#modalKibC form')[0].reset();
                            $('#modalKibC').modal('hide');
                        }, 1000);
                        table.ajax.reload();
                    })
                    .fail((errors) => {
                        let err = errors.responseJSON.errors;
                        $("#modalKibC .alert" ).addClass( "alert-danger" );
                        $("#modalKibC .alert").show();
                        $.each(err, function(key, val) {
                            $("#modalKibC #massages").append(val);
                            setTimeout(function(){
                                $("#modalKibC .alert").hide();
                                $("#modalKibC .alert" ).removeClass( "alert-danger" );
                                $("#modalKibC #massages").empty();
                            }, 3000);
                        });
                    });
                }
            });

            $('modalKibD').validator().on('submit', function (event){
                if(! event.preventDefault()){
                    $.post($('#modalKibD form').attr('action'), $('#modalKibD form').serialize())
                    .done((response) => {
                        $("#modalKibD .alert" ).addClass( "alert-success" );
                        $("#modalKibD .alert").show();
                        $("#modalKibD #massages").append(response);
                        setTimeout(function(){
                            $("#modalKibD .alert" ).removeClass( "alert-success" );
                            $("#modalKibD #massages").empty();
                            $('#modalKibD form')[0].reset();
                            $('#modalKibD').modal('hide');
                        }, 1000);
                        table.ajax.reload();
                    })
                    .fail((errors) => {
                        let err = errors.responseJSON.errors;
                        $("#modalKibD .alert" ).addClass( "alert-danger" );
                        $("#modalKibD .alert").show();
                        $.each(err, function(key, val) {
                            $("#modalKibD #massages").append(val);
                            setTimeout(function(){
                                $("#modalKibD .alert").hide();
                                $("#modalKibD .alert" ).removeClass( "alert-danger" );
                                $("#modalKibD #massages").empty();
                            }, 3000);
                        });
                    });
                }
            });

            $('#modalKibE').validator().on('submit', function (event){
                if(! event.preventDefault()){
                    $.post($('#modalKibE form').attr('action'), $('#modalKibE form').serialize())
                    .done((response) => {
                        $("#modalKibE .alert" ).addClass( "alert-success" );
                        $("#modalKibE .alert").show();
                        $("#modalKibE #massages").append(response);
                        setTimeout(function(){
                            $("#modalKibE .alert" ).removeClass( "alert-success" );
                            $("#modalKibE #massages").empty();
                            $('#modalKibE form')[0].reset();
                            $('#modalKibE').modal('hide');
                        }, 1000);
                        table.ajax.reload();
                    })
                    .fail((errors) => {
                        let err = errors.responseJSON.errors;
                        $("#modalKibE .alert" ).addClass( "alert-danger" );
                        $("#modalKibE .alert").show();
                        $.each(err, function(key, val) {
                            $("#modalKibE #massages").append(val);
                            setTimeout(function(){
                                $("#modalKibE .alert").hide();
                                $("#modalKibE .alert" ).removeClass( "alert-danger" );
                                $("#modalKibE #massages").empty();
                            }, 3000);
                        });
                    });
                }
            });

            $('#modalKibF').validator().on('submit', function (event){
                if(! event.preventDefault()){
                    $.post($('#modalKibF form').attr('action'), $('#modalKibF form').serialize())
                    .done((response) => {
                        $("#modalKibF .alert" ).addClass( "alert-success" );
                        $("#modalKibF .alert").show();
                        $("#modalKibF #massages").append(response);
                        setTimeout(function(){
                            $("#modalKibF .alert" ).removeClass( "alert-success" );
                            $("#modalKibF #massages").empty();
                            $('#modalKibF form')[0].reset();
                            $('#modalKibF').modal('hide');
                        }, 1000);
                        table.ajax.reload();
                    })
                    .fail((errors) => {
                        let err = errors.responseJSON.errors;
                        $("#modalKibF .alert" ).addClass( "alert-danger" );
                        $("#modalKibF .alert").show();
                        $.each(err, function(key, val) {
                            $("#modalKibF #massages").append(val);
                            setTimeout(function(){
                                $("#modalKibF .alert").hide();
                                $("#modalKibF .alert" ).removeClass( "alert-danger" );
                                $("#modalKibF #massages").empty();
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

            $('#modalRincian #kelompok').on('change', function() {
                var id = $(this).val();
                var url = `{{ URL::to('kodeJenis') }}`;
                $('#modalRincian #jenis').empty().prop('disabled', false);
                $('#modalRincian #objek').empty().prop('disabled', true);
                $('#modalRincian #rincian').empty().prop('disabled', true);
                $('#modalRincian #subrincian').empty().prop('disabled', true);
                $('#modalRincian #uraian').empty().prop('disabled', true);
                onChangeSelect(url, id, 'modalRincian #jenis');
            });

            $('#modalRincian #jenis').on('change', function() {
                var id = $(this).val();
                var url = `{{ URL::to('kodeObjek') }}`;
                $('#modalRincian #objek').empty().prop('disabled', false);
                $('#modalRincian #rincian').empty().prop('disabled', true);
                $('#modalRincian #subrincian').empty().prop('disabled', true);
                $('#modalRincian #uraian').empty().prop('disabled', true);
                onChangeSelect(url, id, 'modalRincian #objek');
            });

            $('#modalRincian #objek').on('change', function() {
                var id = $(this).val();
                var url = `{{ URL::to('kodeRincian') }}`;
                $('#modalRincian #rincian').empty().prop('disabled', false);
                $('#modalRincian #subrincian').empty().prop('disabled', true);
                $('#modalRincian #uraian').empty().prop('disabled', true);
                onChangeSelect(url, id, 'modalRincian #rincian');
            });

            $('#modalRincian #rincian').on('change', function() {
                var id = $(this).val();
                var url = `{{ URL::to('kodeSubRincian') }}`;
                $('#modalRincian #subrincian').empty().prop('disabled', false);
                $('#modalRincian #uraian').empty().prop('disabled', true);
                onChangeSelect(url, id, 'modalRincian #subrincian');
            });
            $('#modalRincian #subrincian').on('change', function() {
                var id = $(this).val();
                var url = `{{ URL::to('kodeBarang') }}`;
                $('#modalRincian #uraian').empty().prop('disabled', false);
                onChangeSelect(url, id, 'modalRincian #uraian');
            });

            $('#modalRincian #uraian').on('change', function() {
                var id = $(this).val();
                $('#modalRincian #kode').val(id);
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

        function addRincian(url){
            $('#kelompok').select2({
                theme: 'bootstrap4',
            });
            $('#jenis').select2({
                theme: 'bootstrap4',
            });
            $('#objek').select2({
                theme: 'bootstrap4',
            });
            $('#rincian').select2({
                theme: 'bootstrap4',
            });
            $('#subrincian').select2({
                theme: 'bootstrap4',
            });
            $('#uraian').select2({
                theme: 'bootstrap4',
            });
            $('#modalRincian').modal('show');
            $('#modalRincian .modal-title').text('Tambah Rincian Kontrak');

            $('#modalRincian form')[0].reset();
            $('#modalRincian form').attr('action',url);
            $('#modalRincian [name=_method]').val('post');
            $('#modalRincian').on('shown.bs.modal', function () {
                dropKelompok('modalRincian #kelompok');
                $('#kelompok').focus();
                $('#modalRincian #jenis').empty().prop('disabled', true);

                $('#modalRincian #objek').empty().prop('disabled', true);
                $('#modalRincian #rincian').empty().prop('disabled', true);
                $('#modalRincian #subrincian').empty().prop('disabled', true);
                $('#modalRincian #uraian').empty().prop('disabled', true);

                $('#modalRincian #kelompok').prop('required','required');
                $('#modalRincian #objek').prop('required','required');
                $('#modalRincian #jenis').prop('required','required');
                $('#modalRincian #rincian').prop('required','required');
                $('#modalRincian #subrincian').prop('required','required');
                $('#modalRincian #uraian').prop('required','required');
            })

        }

        function editRincian(url, id){
            $('#modalEditRincian').modal('show');
            $('#modalEditRincian .modal-title').text('Ubah Rincian Kontrak');

            $('#modalEditRincian form')[0].reset();
            $('#modalEditRincian form').attr('action',url);
            $('#modalEditRincian [name=_method]').val('put');
            $('#modalEditRincian').on('shown.bs.modal', function () {
                $('#modalEditRincian #asal').focus();
            })
            let show = "{{route('kontrak.rincian.show', '')}}"+"/"+id;
                $.get(show)
                .done((response) => {
                    $('#modalEditRincian [name=asal]').val(response.asal);
                    $('#modalEditRincian [name=harga]').val(response.harga);
                })
                .fail((errors) => {
                    alert('Gagl tampil data');
                    return;
                })
        }

        function addDetails(url,form,detail_id){
            $('#modalKib'+form).modal('show');
            $('#modalKib'+form+' .modal-title').text('Isi / Ubah Detail Rincian');
            $('#modalKib'+form+' form')[0].reset();
            $('#modalKib'+form+' form').attr('action',url);
            $('#modalKib'+form+' [name=_method]').val('put');
            showDetails(form,detail_id);
        }

        function showDetails(kib,detail_id){
            let show = "{{route('kontrak.rincian.show', '')}}"+"/"+detail_id;
            $.get(show)
                .done((response) => {
                    $('#modalKib'+kib+' [name=kode]').val(response.kode);
                    $('#modalKib'+kib+' [name=tahun]').val(response.tahun);
                    $('#modalKib'+kib+' [name=keterangan]').val(response.keterangan);
                    if(kib == 'A'){
                        $('#modalKib'+kib+' [name=tgl_sertifikat]').val(response.tgl_sertifikat);
                        $('#modalKib'+kib+' [name=no_sertifikat]').val(response.no_sertifikat);
                        $('#modalKib'+kib+' [name=luas_tanah]').val(response.luas_tanah);
                        $('#modalKib'+kib+' [name=id_hak]').val(response.id_hak);
                        $('#modalKib'+kib+' [name=penggunaan]').val(response.penggunaan);
                        $('#modalKib'+kib+' [name=alamat]').val(response.alamat);
                    }else if(kib == 'B'){
                        $('#modalKib'+kib+' [name=bahan]').val(response.bahan);
                        $('#modalKib'+kib+' [name=merek]').val(response.merek);
                        $('#modalKib'+kib+' [name=no_mesin]').val(response.no_mesin);
                        $('#modalKib'+kib+' [name=no_rangka]').val(response.no_rangka);
                        $('#modalKib'+kib+' [name=pabrik]').val(response.pabrik);
                        $('#modalKib'+kib+' [name=no_bpkb]').val(response.no_bpkb);
                        $('#modalKib'+kib+' [name=spesifikasi]').val(response.spesifikasi);
                    }else if(kib == 'C'){
                        $('#modalKib'+kib+' [name=tgl_dokumen]').val(response.tgl_dokumen);
                        $('#modalKib'+kib+' [name=no_dokumen]').val(response.no_dokumen);
                        $('#modalKib'+kib+' [name=luas_lantai]').val(response.luas_lantai);
                        $('#modalKib'+kib+' [name=luas_tanah]').val(response.luas_tanah);
                        $('#modalKib'+kib+' [name=tingkat]').val(response.tingkat);
                        $('#modalKib'+kib+' [name=beton]').val(response.beton);
                        $('#modalKib'+kib+' [name=id_status_tanah]').val(response.id_status_tanah);
                        $('#modalKib'+kib+' [name=kode_tanah]').val(response.kode_tanah);
                        $('#modalKib'+kib+' [name=kondisi]').val(response.kondisi);
                    }else if(kib == 'D'){
                        $('#modalKib'+kib+' [name=tgl_dokumen]').val(response.tgl_dokumen);
                        $('#modalKib'+kib+' [name=no_dokumen]').val(response.no_dokumen);
                        $('#modalKib'+kib+' [name=panjang]').val(response.panjang);
                        $('#modalKib'+kib+' [name=luas_tanah]').val(response.luas_tanah);
                        $('#modalKib'+kib+' [name=lebar]').val(response.lebar);
                        $('#modalKib'+kib+' [name=konstruksi]').val(response.konstruksi);
                        $('#modalKib'+kib+' [name=alamat]').val(response.alamat);
                        $('#modalKib'+kib+' [name=id_status_tanah]').val(response.id_status_tanah);
                        $('#modalKib'+kib+' [name=kode_tanah]').val(response.kode_tanah);
                        $('#modalKib'+kib+' [name=kondisi]').val(response.kondisi);
                    }else if(kib == 'E'){
                        $('#modalKib'+kib+' [name=nm_aset]').val(response.nm_aset);
                        $('#modalKib'+kib+' [name=asal_daerah]').val(response.asal_daerah);
                        $('#modalKib'+kib+' [name=pencipta]').val(response.pencipta);
                        $('#modalKib'+kib+' [name=jenis]').val(response.jenis);
                        $('#modalKib'+kib+' [name=ukuran]').val(response.ukuran);
                        $('#modalKib'+kib+' [name=jumlah]').val(response.jumlah);
                    }else if(kib == 'F'){
                        $('#modalKib'+kib+' [name=tgl_dokumen]').val(response.tgl_dokumen);
                        $('#modalKib'+kib+' [name=no_dokumen]').val(response.no_dokumen);
                        $('#modalKib'+kib+' [name=bangunan]').val(response.bangunan);
                        $('#modalKib'+kib+' [name=luas_tanah]').val(response.luas_tanah);
                        $('#modalKib'+kib+' [name=bangunan]').val(response.bangunan);
                        $('#modalKib'+kib+' [name=tingkat]').val(response.tingkat);
                        $('#modalKib'+kib+' [name=beton]').val(response.beton);
                        $('#modalKib'+kib+' [name=id_status_tanah]').val(response.id_status_tanah);
                        $('#modalKib'+kib+' [name=kode_tanah]').val(response.kode_tanah);
                        $('#modalKib'+kib+' [name=kondisi]').val(response.kondisi);
                        $('#modalKib'+kib+' [name=t_mulai]').val(response.t_mulai);
                    }
                })
        }

        function hapusRincian(url){
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
