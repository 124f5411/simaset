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
                <h6 class="m-0 font-weight-bold text-primary">Status Tanah</h6>
                <a href="#" onclick="addStatus('{{ route('status_tanah.store') }}')" class="btn btn-primary btn-icon-split float-right">
                    <span class="icon text-white-50">
                        <i class="fas fa-plus-circle"></i>
                    </span>
                    <span class="text">{{ $page }}</span>
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataStatus" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
@includeIf('form.referensi.status')
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
            table = $('#dataStatus').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                autoWidth: false,
                ajax:{
                    url: '{{ route('status_tanah.data') }}'
                },
                columns:[
                    {data:'DT_RowIndex', searchable:false, sortable:false},
                    {data:'status'},
                    {data:'aksi', searchable:false, sortable:false},
                ]
            });

            $('#modalStatus').validator().on('submit', function (e){
                if(! e.preventDefault()){
                    $.post($('#modalStatus form').attr('action'), $('#modalStatus form').serialize())
                    .done((response) => {
                        $(".alert" ).addClass( "alert-success" );
                        $(".alert").show();
                        $("#massages").append(response);
                        setTimeout(function(){
                            $(".alert" ).removeClass( "alert-success" );
                            $("#massages").empty();
                            $('#modalStatus form')[0].reset();
                            $('#modalStatus').modal('hide');
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
                                $('#modalStatus form')[0].reset();
                                $('#modalStatus').modal('hide');
                            }, 5000);
                        });
                    });
                }
            });

        });

        function addStatus(url){
            $('#modalStatus').modal('show');
            $('#modalStatus .modal-title').text('Tambah Status Tanah');

            $('#modalStatus form')[0].reset();
            $('#modalStatus form').attr('action',url);
            $('#modalStatus [name=_method]').val('post');
            $('#modalStatus [name=jenis]').val('');
            $('#modalStatus').on('shown.bs.modal', function () {
                $('#status').focus();
            })
        }

        function editStatus(url, id){
            $('#modalStatus').modal('show');
            $('#modalStatus .modal-title').text('Ubah Status Tanah');

            $('#modalStatus form')[0].reset();
            $('#modalStatus form').attr('action',url);
            $('#modalStatus [name=_method]').val('put');
            $('#modalStatus [name=jenis]').val('');
            $('#modalStatus').on('shown.bs.modal', function () {
                $('#status').focus();
            })

            let show = "{{route('status_tanah.show', '')}}"+"/"+id;
            $.get(show)
            .done((response) => {
                $('#modalStatus [name=status]').val(response.status);
            })
            .fail((errors) => {
                alert('Gagl tampil data');
                return;
            })
        }

        function hapusStatus(url){
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
