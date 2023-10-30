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
                <h6 class="m-0 font-weight-bold text-primary">Rekening Belanja</h6>
                <div class="btn-group float-right">
                    <a href="#" onclick="importRekening('{{ route('rekening_belanja.import') }}')" class="btn btn-sm btn-danger btn-icon-split">
                        <span class="icon text-white-50">
                            <i class="fas fa-upload"></i>
                        </span>
                        <span class="text">Import Excel</span>
                    </a>
                    <a href="#" onclick="addRekening('{{ route('rekening_belanja.store') }}')" class="btn btn-sm btn-primary  btn-icon-split">
                        <span class="icon text-white-50">
                            <i class="fas fa-plus-circle"></i>
                        </span>
                        <span class="text">Rekening Belanja</span>
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataRekening" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Kode Akun</th>
                                <th>Nama Akun</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
@includeIf('form.referensi.rekeningBelanja')
@includeIf('form.referensi.importrekeningBelanja')
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
            table = $('#dataRekening').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                autoWidth: false,
                stateSave: true,
                ajax:{
                    url: '{{ route('rekening_belanja.data') }}'
                },
                columns:[
                    {data:'DT_RowIndex', searchable:false, sortable:false},
                    {data:'kode_akun'},
                    {data:'nm_akun'},
                    {data:'aksi', searchable:false, sortable:false},
                ]
            });

            $('#modalRekening').validator().on('submit', function (e){
                if(! e.preventDefault()){
                    $.post($('#modalRekening form').attr('action'), $('#modalRekening form').serialize())
                    .done((response) => {
                        $(".alert" ).addClass( "alert-success" );
                        $(".alert").show();
                        $("#massages").append(response);
                        setTimeout(function(){
                            $(".alert" ).removeClass( "alert-success" );
                            $("#massages").empty();
                            $('#modalRekening form')[0].reset();
                            $('#modalRekening').modal('hide');
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
                                $('#modalRekening form')[0].reset();
                                $('#modalRekening').modal('hide');
                            }, 5000);
                        });
                    });
                }
            });

            $('#modalRekeningImport').validator().on('submit', function (e){
                if(! e.preventDefault()){
                    $.ajax({
                                url : '{{ route('rekening_belanja.import')}}',
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
                            $('#modalRekeningImport form')[0].reset();
                            $('#modalRekeningImport').modal('hide');
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
                                $('#modalRekeningImport form')[0].reset();
                                $('#modalRekeningImport').modal('hide');
                            }, 5000);
                        });
                    });
                }
            });

        });

        function addRekening(url){
            $('#modalRekening').modal('show');
            $('#modalRekening .modal-title').text('Tambah Rekening Belanja');

            $('#modalRekening form')[0].reset();
            $('#modalRekening form').attr('action',url);
            $('#modalRekening [name=_method]').val('post');
            $('#modalRekening [name=kode_barang]').val('');
            $('#modalRekening').on('shown.bs.modal', function () {
                $('#kode_akun').focus();
            })
        }

        function importRekening(url){
            $('#modalRekeningImport').modal('show');
            $('#modalRekeningImport .modal-title').text('Import Rekening Belanja');

            $('#modalRekeningImport form')[0].reset();
            $('#modalRekeningImport form').attr('action',url);
            $('#modalRekeningImport [name=_method]').val('post');
            $('#modalRekeningImport [name=files]').val('');
            $('#modalRekeningImport').on('shown.bs.modal', function () {
                $('#dok_rekening').focus();
            })
        }

        function editRekening(url, id){
            $('#modalRekening').modal('show');
            $('#modalRekening .modal-title').text('Ubah Rekening Belanja');

            $('#modalRekening form')[0].reset();
            $('#modalRekening form').attr('action',url);
            $('#modalRekening [name=_method').val('put');
            $('#modalRekening [name=kode_barang]').val('');
            $('#modalRekening').on('shown.bs.modal', function () {
                $('#kode_barang').focus();
            })

            let show = "{{route('rekening_belanja.show', '')}}"+"/"+id;
            $.get(show)
            .done((response) => {
                $('#modalRekening [name=kode_akun]').val(response.kode_akun);
                $('#modalRekening [name=nm_akun]').val(response.nm_akun);
            })
            .fail((errors) => {
                alert('Gagl tampil data');
                return;
            })
        }

        function hapusRekening(url){
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
