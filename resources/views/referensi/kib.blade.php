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
                <h6 class="m-0 font-weight-bold text-primary">Master Kib</h6>
                <a href="#" onclick="addKib('{{ route('kib.store') }}')" class="btn btn-primary btn-icon-split float-right">
                    <span class="icon text-white-50">
                        <i class="fas fa-plus-circle"></i>
                    </span>
                    <span class="text">{{ $page }}</span>
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataKib" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Tipe</th>
                                <th>Kode</th>
                                <th>Kib</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
@includeIf('form.referensi.kib')
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
            table = $('#dataKib').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                autoWidth: false,
                ajax:{
                    url: '{{ route('kib.data') }}'
                },
                columns:[
                    {data:'DT_RowIndex', searchable:false, sortable:false},
                    {data:'type'},
                    {data:'kode'},
                    {data:'kib'},
                    {data:'aksi', searchable:false, sortable:false},
                ]
            });

            $('#modalKib').validator().on('submit', function (e){
                if(! e.preventDefault()){
                    $.post($('#modalKib form').attr('action'), $('#modalKib form').serialize())
                    .done((response) => {
                        $(".alert" ).addClass( "alert-success" );
                        $(".alert").show();
                        $("#massages").append(response);
                        setTimeout(function(){
                            $(".alert" ).removeClass( "alert-success" );
                            $("#massages").empty();
                            $('#modalKib form')[0].reset();
                            $('#modalKib').modal('hide');
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
                                $('#modalKib form')[0].reset();
                                $('#modalKib').modal('hide');
                            }, 5000);
                        });
                    });
                }
            });

        });

        function addKib(url){
            $('#modalKib').modal('show');
            $('#modalKib .modal-title').text('Tambah Master KIB');

            $('#modalKib form')[0].reset();
            $('#modalKib form').attr('action',url);
            $('#modalKib [name=_method]').val('post');
            $('#modalKib [name=type]').val('');
            $('#modalKib').on('shown.bs.modal', function () {
                $('#type').focus();
            })
        }

        function editKib(url, id){
            $('#modalKib').modal('show');
            $('#modalKib .modal-title').text('Ubah Master KIB');

            $('#modalKib form')[0].reset();
            $('#modalKib form').attr('action',url);
            $('#modalKib [name=_method]').val('put');
            $('#modalKib [name=type]').val('');
            $('#modalKib').on('shown.bs.modal', function () {
                $('#type').focus();
            })

            let show = "{{route('kib.show', '')}}"+"/"+id;
            $.get(show)
            .done((response) => {
                $('#modalKib [name=type]').val(response.type);
                $('#modalKib [name=kode]').val(response.kode);
                $('#modalKib [name=kib]').val(response.kib);
                $('#modalKib [name=form]').val(response.form);
            })
            .fail((errors) => {
                alert('Gagl tampil data');
                return;
            })
        }

        function hapusKib(url){
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
