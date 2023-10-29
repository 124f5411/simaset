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
                <h6 class="m-0 font-weight-bold text-primary">Kode Barang</h6>
                <div class="btn-group float-right">
                    <a href="#" onclick="importKode('{{ route('kode_barang.import') }}')" class="btn btn-sm btn-danger btn-icon-split">
                        <span class="icon text-white-50">
                            <i class="fas fa-upload"></i>
                        </span>
                        <span class="text">Import Excel</span>
                    </a>
                    <a href="#" onclick="addKode('{{ route('kode_barang.store') }}')" class="btn btn-sm btn-primary  btn-icon-split">
                        <span class="icon text-white-50">
                            <i class="fas fa-plus-circle"></i>
                        </span>
                        <span class="text">Kode Barang</span>
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataKode" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>#</th>
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
@includeIf('form.referensi.kodeBarang')
@includeIf('form.referensi.importkodeBarang')
@endsection

@push('css')
    <link href="{{ asset('themes/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

@endpush

@push('scripits')
    <script src="{{ asset('themes/vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('themes/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('js/validator.min.js') }}"></script>

    <script>
        let table;
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            table = $('#dataKode').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                autoWidth: false,
                stateSave: true,
                ajax:{
                    url: '{{ route('kode_barang.data') }}'
                },
                columns:[
                    {data:'DT_RowIndex', searchable:false, sortable:false},
                    {data:'kode_barang'},
                    {data:'uraian'},
                    {data:'aksi', searchable:false, sortable:false},
                ]
            });

            $('#modalKode').validator().on('submit', function (e){
                if(! e.preventDefault()){
                    $.post($('#modalKode form').attr('action'), $('#modalKode form').serialize())
                    .done((response) => {
                        $(".alert" ).addClass( "alert-success" );
                        $(".alert").show();
                        $("#massages").append(response);
                        setTimeout(function(){
                            $(".alert" ).removeClass( "alert-success" );
                            $("#massages").empty();
                            $('#modalKode form')[0].reset();
                            $('#modalKode').modal('hide');
                        }, 3000);
                        table.ajax.reload();
                    })
                    .fail((errors) => {
                        let err = errors.responseJSON.errors;
                        $(".alert" ).addClass( "alert-danger" );
                        $(".alert").show();
                        $.each(err, function(key, val) {
                            $("#massages").append(val);
                            setTimeout(function(){
                                $(".alert").hide();
                                $(".alert" ).removeClass( "alert-danger" );
                                $("#massages").empty();
                                $('#modalKode form')[0].reset();
                                $('#modalKode').modal('hide');
                            }, 5000);
                        });
                    });
                }
            });

            $('#modalKodeImport').validator().on('submit', function (e){
                if(! e.preventDefault()){
                    $.ajax({
                                url : '{{ route('kode_barang.import')}}',
                                type : 'POST',
                                data : new FormData($('.form-import')[0]),
                                async : false,
                                processData : false,
                                contentType : false,
                            })
                    .done((response) => {
                        $(".alert" ).addClass( "alert-success" );
                        $(".alert").show();
                        $("#massages-imp").append(response);
                        setTimeout(function(){
                            $(".alert" ).removeClass( "alert-success" );
                            $("#massages-imp").empty();
                            $('#modalKodeImport form')[0].reset();
                            $('#modalKodeImport').modal('hide');
                        }, 3000);
                        table.ajax.reload();
                    })
                    .fail((errors) => {
                        let err = errors.responseJSON.errors;
                        $(".alert" ).addClass( "alert-danger" );
                        $(".alert").show();
                        $.each(err, function(key, val) {
                            $("#massages-imp").append(val);
                            setTimeout(function(){
                                $(".alert").hide();
                                $(".alert" ).removeClass( "alert-danger" );
                                $("#massages-imp").empty();
                                $('#modalKodeImport form')[0].reset();
                                $('#modalKodeImport').modal('hide');
                            }, 5000);
                        });
                    });
                }
            });

        });

        function addKode(url){
            $('#modalKode').modal('show');
            $('#modalKode .modal-title').text('Tambah Kode Barang');

            $('#modalKode form')[0].reset();
            $('#modalKode form').attr('action',url);
            $('#modalKode [name=_method]').val('post');
            $('#modalKode [name=kode_barang]').val('');
            $('#modalKode').on('shown.bs.modal', function () {
                $('#kode_barang').focus();
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

        function editKode(url, id){
            $('#modalKode').modal('show');
            $('#modalKode .modal-title').text('Ubah Kode Barang');

            $('#modalKode form')[0].reset();
            $('#modalKode form').attr('action',url);
            $('#modalKode [name=_method').val('put');
            $('#modalKode [name=kode_barang]').val('');
            $('#modalKode').on('shown.bs.modal', function () {
                $('#kode_barang').focus();
            })

            let show = "{{route('kode_barang.show', '')}}"+"/"+id;
            $.get(show)
            .done((response) => {
                $('#modalKode [name=kode_barang]').val(response.kode_barang);
                $('#modalKode [name=uraian]').val(response.uraian);
                $('#modalKode [name=kib]').val(response.kib);
                $('#modalKode [name=kelompok]').val(response.kelompok);
            })
            .fail((errors) => {
                alert('Gagl tampil data');
                return;
            })
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
    </script>
@endpush
