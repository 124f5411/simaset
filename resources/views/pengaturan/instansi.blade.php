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
                <h6 class="m-0 font-weight-bold text-primary">Data Instansi</h6>
                <a href="#" onclick="addInstansi('{{ route('instansi.store') }}')" class="btn btn-primary btn-icon-split float-right">
                    <span class="icon text-white-50">
                        <i class="fas fa-plus-circle"></i>
                    </span>
                    <span class="text">{{ $page }}</span>
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataInstansi" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Instansi</th>
                                <th>parent</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
@includeIf('form.pengaturan.instansi')
@endsection

@push('css')
    <link href="{{ asset('themes/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css">

@endpush

@push('scripits')
    <script src="{{ asset('themes/vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('themes/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.full.min.js"></script>
    <script src="{{ asset('js/validator.min.js') }}"></script>

    <script>
        let table;
        $(document).ready(function() {
            $('#parent').select2({
                theme: 'bootstrap4',
            });
            table = $('#dataInstansi').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                autoWidth: false,
                ajax:{
                    url: '{{ route('instansi.data') }}'
                },
                columns:[
                    {data:'DT_RowIndex', searchable:false, sortable:false},
                    {data:'opd'},
                    {data:'parent'},
                    {data:'aksi', searchable:false, sortable:false},
                ]
            });

            $('#modalInstansi').validator().on('submit', function (e){
                if(! e.preventDefault()){
                    $.post($('#modalInstansi form').attr('action'), $('#modalInstansi form').serialize())
                    .done((response) => {
                        $(".alert" ).addClass( "alert-success" );
                        $(".alert").show();
                        $("#massages").append(response);
                        setTimeout(function(){
                            $(".alert" ).removeClass( "alert-success" );
                            $("#massages").empty();
                            $('#modalInstansi form')[0].reset();
                            $('#modalInstansi').modal('hide');
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
                                $('#modalInstansi form')[0].reset();
                                $('#modalInstansi').modal('hide');
                            }, 5000);
                        });
                    });
                }
            });

        });

        function addInstansi(url){
            $('#modalInstansi').modal('show');
            $('#modalInstansi .modal-title').text('Tambah instansi');

            $('#modalInstansi form')[0].reset();
            $('#modalInstansi form').attr('action',url);
            $('#modalInstansi [name=_method]').val('post');
            $('#modalInstansi [name=opd]').val('');
            $('#modalInstansi').on('shown.bs.modal', function () {
                $('#opd').focus();
            })
        }

        function editInstansi(url, id){
            $('#modalInstansi').modal('show');
            $('#modalInstansi .modal-title').text('Ubah instansi');

            $('#modalInstansi form')[0].reset();
            $('#modalInstansi form').attr('action',url);
            $('#modalInstansi [name=_method]').val('put');
            $('#modalInstansi [name=opd]').val('');
            $('#modalInstansi').on('shown.bs.modal', function () {
                $('#opd').focus();
            })

            let show = "{{route('instansi.show', '')}}"+"/"+id;
            $.get(show)
            .done((response) => {
                $('#modalInstansi [name=opd]').val(response.opd);
                $('#modalInstansi [name=parent]').val(response.parent).trigger('change');
            })
            .fail((errors) => {
                alert('Gagl tampil data');
                return;
            })
        }

        function hapusInstansi(url){
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
