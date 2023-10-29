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
                <h6 class="m-0 font-weight-bold text-primary">Kelompok SH</h6>
                <a href="#" onclick="addKelompok('{{ route('kelompok.store') }}')" class="btn btn-primary btn-icon-split float-right">
                    <span class="icon text-white-50">
                        <i class="fas fa-plus-circle"></i>
                    </span>
                    <span class="text">{{ $page }}</span>
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataKelompok" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Kelompok</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
@includeIf('form.referensi.kelompok')
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
            table = $('#dataKelompok').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                autoWidth: false,
                ajax:{
                    url: '{{ route('kelompok.data') }}'
                },
                columns:[
                    {data:'DT_RowIndex', searchable:false, sortable:false},
                    {data:'kelompok'},
                    {data:'aksi', searchable:false, sortable:false},
                ]
            });

            $('#modalKelompok').validator().on('submit', function (e){
                if(! e.preventDefault()){
                    $.post($('#modalKelompok form').attr('action'), $('#modalKelompok form').serialize())
                    .done((response) => {
                        $(".alert" ).addClass( "alert-success" );
                        $(".alert").show();
                        $("#massages").append(response);
                        setTimeout(function(){
                            $(".alert" ).removeClass( "alert-success" );
                            $("#massages").empty();
                            $('#modalKelompok form')[0].reset();
                            $('#modalKelompok').modal('hide');
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
                                $('#modalKelompok form')[0].reset();
                                $('#modalKelompok').modal('hide');
                            }, 5000);
                        });
                    });
                }
            });

        });

        function addKelompok(url){
            $('#modalKelompok').modal('show');
            $('#modalKelompok .modal-title').text('Tambah Kelompok SSH');

            $('#modalKelompok form')[0].reset();
            $('#modalKelompok form').attr('action',url);
            $('#modalKelompok [name=_method]').val('post');
            $('#modalKelompok [name=kelompok]').val('');
            $('#modalKelompok').on('shown.bs.modal', function () {
                $('#kelompok').focus();
            })
        }

        function editKelompok(url, id){
            $('#modalKelompok').modal('show');
            $('#modalKelompok .modal-title').text('Ubah Kelompok SSH');

            $('#modalKelompok form')[0].reset();
            $('#modalKelompok form').attr('action',url);
            $('#modalKelompok [name=_method]').val('put');
            $('#modalKelompok [name=kelompok]').val('');
            $('#modalKelompok').on('shown.bs.modal', function () {
                $('#kelompok').focus();
            })

            let show = "{{route('kelompok.show', '')}}"+"/"+id;
            $.get(show)
            .done((response) => {
                $('#modalKelompok [name=kelompok]').val(response.kelompok);
            })
            .fail((errors) => {
                alert('Gagl tampil data');
                return;
            })
        }

        function hapusKelompok(url){
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
