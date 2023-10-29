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
                <h6 class="m-0 font-weight-bold text-primary">Data Kontrak</h6>
                <a href="#" onclick="addKontrak('{{ route('kontrak.store') }}')" class="btn btn-primary btn-icon-split float-right">
                    <span class="icon text-white-50">
                        <i class="fas fa-plus-circle"></i>
                    </span>
                    <span class="text">{{ $page }}</span>
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataKontrak" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nomor</th>
                                <th>Judul</th>
                                <th>Tahun</th>
                                <th>Tanggal</th>
                                <th>Opd</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
@includeIf('form.kontrak')
@endsection

@push('css')
    <link href="{{ asset('themes/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css">
    <link href="https://unpkg.com/gijgo@1.9.14/css/gijgo.min.css" rel="stylesheet" type="text/css" />

@endpush

@push('scripits')
    <script src="{{ asset('themes/vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('themes/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.full.min.js"></script>
    <script src="https://unpkg.com/gijgo@1.9.14/js/gijgo.min.js" type="text/javascript"></script>
    <script src="{{ asset('js/validator.min.js') }}"></script>

    <script>
        let table;
        $(document).ready(function() {
            $('#opd').select2({
                theme: 'bootstrap4',
            });
            $('#t_kontrak').datepicker({
                uiLibrary: 'bootstrap4',
                format: 'yyyy-mm-dd'
            });
            table = $('#dataKontrak').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                autoWidth: false,
                ajax:{
                    url: '{{ route('kontrak.data') }}'
                },
                columns:[
                    {data:'DT_RowIndex', searchable:false, sortable:false},
                    {data:'no_kontrak'},
                    {data:'nm_kontrak'},
                    {data:'tahun'},
                    {data:'tanggal'},
                    {data:'instansi'},
                    {data:'aksi', searchable:false, sortable:false},
                ]
            });

            $('#modalKontrak').validator().on('submit', function (e){
                if(! e.preventDefault()){
                    $.post($('#modalKontrak form').attr('action'), $('#modalKontrak form').serialize())
                    .done((response) => {
                        $(".alert" ).addClass( "alert-success" );
                        $(".alert").show();
                        $("#massages").append(response);
                        setTimeout(function(){
                            $(".alert" ).removeClass( "alert-success" );
                            $("#massages").empty();
                            $('#modalKontrak form')[0].reset();
                            $('#modalKontrak').modal('hide');
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
                                $('#modalKontrak form')[0].reset();
                                $('#modalKontrak').modal('hide');
                            }, 5000);
                        });
                    });
                }
            });

        });

        function addKontrak(url){
            $('#modalKontrak').modal('show');
            $('#modalKontrak .modal-title').text('Tambah Kontrak');

            $('#modalKontrak form')[0].reset();
            $('#modalKontrak form').attr('action',url);
            $('#modalKontrak [name=_method]').val('post');
            $('#modalKontrak').on('shown.bs.modal', function () {
                $('#no_kontrak').focus();
            })
        }

        function editKontrak(url, id){
            $('#modalKontrak').modal('show');
            $('#modalKontrak .modal-title').text('Ubah Kontrak');

            $('#modalKontrak form')[0].reset();
            $('#modalKontrak form').attr('action',url);
            $('#modalKontrak [name=_method]').val('put');
            $('#modalKontrak').on('shown.bs.modal', function () {
                $('#no_kontrak').focus();
            })

            let show = "{{route('kontrak.show', '')}}"+"/"+id;
            $.get(show)
            .done((response) => {
                $('#modalKontrak [name=no_kontrak]').val(response.no_kontrak);
                $('#modalKontrak [name=nm_kontrak]').val(response.nm_kontrak);
                $('#modalKontrak [name=tahun]').val(response.tahun);
                $('#modalKontrak [name=opd]').val(response.opd).trigger('change');
                $('#modalKontrak [name=t_kontrak]').val(response.t_kontrak);
            })
            .fail((errors) => {
                alert('Gagl tampil data');
                return;
            })
        }

        function hapusKontrak(url){
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
