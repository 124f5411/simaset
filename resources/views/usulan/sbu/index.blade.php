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
                @if (Auth::user()->level == 'operator' || Auth::user()->level == 'bendahara')
                <a href="{{ asset('download/dokumen/SURAT-PERNYATAAN-USULAN.docx') }}" target="_blank" class="btn btn-primary btn-icon-split mt-2">
                    <span class="icon text-white-50">
                        <i class="fas fa-file-word"></i>
                    </span>
                    <span class="text">Pakta Integritas</span>
                </a>
                <a href="#" onclick="addSbu('{{ route('sbu.store') }}')" class="btn btn-primary btn-icon-split float-right">
                    <span class="icon text-white-50">
                        <i class="fas fa-plus-circle"></i>
                    </span>
                    <span class="text">{{ $page }}</span>
                </a>
                @endif
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataSbu" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>OPD</th>
                                <th>Tahun</th>
                                <th>Usulan</th>
                                <th style="width: 200px">Upload Pakta</th>
                                <th style="width: 100px">Rincian</th>
                                <th style="width: 150px">Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
@includeIf('form.usulan.sbu.index')
@includeIf('form.usulan.sbu.upload')
@endsection

@push('css')
    <link href="{{ asset('themes/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css">

@endpush

@push('scripits')
    <script src="{{ asset('themes/vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('themes/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.full.min.js"></script>
    <script src="{{ asset('js/validator.min.js') }}"></script>

    <script>
        let user_akses = "{{ Auth::user()->level }}";
        let table;
        $(document).ready(function() {
            $('#id_kode').select2({
                theme: 'bootstrap4',
            });
            $('#id_satuan').select2({
                theme: 'bootstrap4',
            });
            $('#opd').select2({
                theme: 'bootstrap4',
            });

            table = $('#dataSbu').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                autoWidth: false,
                ajax:{
                    url: '{{ route('sbu.data') }}'
                },
                columns:[
                    {data:'DT_RowIndex', searchable:false, sortable:false},
                    {data:'q_opd'},
                    {data:'tahun'},
                    {data:'usulan'},
                    {data:'dokumen'},
                    {data:'rincian', searchable:false, sortable:false},
                    {data:'aksi', searchable:false, sortable:false},
                ]
            }).column(1).visible(false);

            $('#opd').change(function() {
                // if($(this).val() != ''){
                //     table.column(1).search($(this).val()).draw();
                // }else{
                //     table.ajax.reload();
                // }
                if($(this).val() != ''){
                    table.column(1).search($(this).val()).draw();
                }
            });

            $('#modalSbu').validator().on('submit', function (e){
                if(! e.preventDefault()){
                    $.post($('#modalSbu form').attr('action'), $('#modalSbu form').serialize())
                    .done((response) => {
                        $(".alert" ).addClass( "alert-success" );
                        $(".alert").show();
                        $("#massages").append(response);
                        setTimeout(function(){
                            $(".alert" ).removeClass( "alert-success" );
                            $("#massages").empty();
                            $('#modalSbu form')[0].reset();
                            $('#modalSbu').modal('hide');
                        }, 1000);
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
                                // $('#modalSbu [name=id_kontrak]').val('').trigger('change');
                                // $('#modalSbu form')[0].reset();
                                // $('#modalSbu').modal('hide');
                            }, 3000);
                        });
                    });
                }
            });

            $('#modalUpload').validator().on('submit', function (e){
                if(! e.preventDefault()){
                    let url_upload = $('#modalUpload form').attr('action');
                    $.ajax({
                                url : url_upload,
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
                            $('#modalUpload form')[0].reset();
                            $('#modalUpload').modal('hide');
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
                                $('#modalUpload form')[0].reset();
                                $('#modalUpload').modal('hide');
                            }, 5000);
                        });
                    });
                }
            });

        });

        function addSbu(url){
            $('#modalSbu').modal('show');
            $('#modalSbu .modal-title').text('Tambah SBU');

            $('#modalSbu form')[0].reset();
            $('#modalSbu form').attr('action',url);
            $('#modalSbu [name=_method]').val('post');
            $('#modalSbu [name=jenis]').val('');
            $('#modalSbu').on('shown.bs.modal', function () {
                $('#tahun').focus();
            })
        }

        function sbuUpload(url){
            $('#modalUpload').modal('show');
            $('#modalUpload .modal-title').text('Upload dokume SBU');

            $('#modalUpload form')[0].reset();
            $('#modalUpload form').attr('action',url);
            $('#modalUpload [name=_method]').val('put');
            $('#modalUpload [name=ssd_dokumen]').val('');
            $('#modalUpload').on('shown.bs.modal', function () {
                $('#ssd_dokumen').focus();
            })
        }

        function editSbu(url, id){
            $('#modalSbu').modal('show');
            $('#modalSbu .modal-title').text('Ubah Usulan SBU');

            $('#modalSbu form')[0].reset();
            $('#modalSbu form').attr('action',url);
            $('#modalSbu [name=_method]').val('put');
            $('#modalSbu [name=tahun]').val('');
            $('#modalSbu').on('shown.bs.modal', function () {
                $('#tahun').focus();
            })

            let show = "{{route('ssh.show', '')}}"+"/"+id;
            $.get(show)
            .done((response) => {
                $('#modalSbu [name=tahun]').val(response.tahun);
                $('#modalSbu [name=induk_perubahan]').val(response.induk_perubahan);
            })
            .fail((errors) => {
                alert('Gagl tampil data');
                return;
            })
        }

        function hapusSbu(url){
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

        function verifSbu(url){
            let cnfrm;
            if(user_akses == 'operator' || user_akses == 'bendahara'){
                cnfrm = 'Yakin? data akan dikirimkan untuk validasi.';
            }else if(user_akses == 'aset'){
                cnfrm = 'yakin data usulan telah benar?';
            }
            if (confirm(cnfrm)) {
                    $.post(url, {
                            '_token': $('[name=csrf-token]').attr('content'),
                            '_method': 'put'
                        })
                        .done((response) => {
                            table.ajax.reload();
                        })
                        .fail((errors) => {
                            alert('Gagal kirim data');
                            return;
                        });
                }
        }
    </script>
@endpush
