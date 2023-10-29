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
                <h6 class="m-0 font-weight-bold text-primary">Data Bendahara</h6>
                <a href="#" onclick="addAdmin('{{ route('bendahara.store') }}')" class="btn btn-primary btn-icon-split float-right">
                    <span class="icon text-white-50">
                        <i class="fas fa-plus-circle"></i>
                    </span>
                    <span class="text">{{ $page }}</span>
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataAdmin" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama</th>
                                <th>NIP</th>
                                <th>Email</th>
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
@includeIf('form.pengaturan.bendahara')
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
            $('#id_opd').select2({
                theme: 'bootstrap4',
                required : 'required'
            });
            table = $('#dataAdmin').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                autoWidth: false,
                ajax:{
                    url: '{{ route('bendahara.data') }}'
                },
                columns:[
                    {data:'DT_RowIndex', searchable:false, sortable:false},
                    {data:'name'},
                    {data:'nip'},
                    {data:'email'},
                    {data:'instansi'},
                    {data:'aksi', searchable:false, sortable:false},
                ]
            });

            $('#modalAdmin').validator().on('submit', function (e){
                if(! e.preventDefault()){
                    $.post($('#modalAdmin form').attr('action'), $('#modalAdmin form').serialize())
                    .done((response) => {
                        $(".alert" ).addClass( "alert-success" );
                        $(".alert").show();
                        $("#massages").append(response);
                        setTimeout(function(){
                            $(".alert" ).removeClass( "alert-success" );
                            $("#massages").empty();
                            $('#modalAdmin form')[0].reset();
                            $('#modalAdmin').modal('hide');
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
                            }, 5000);
                        });
                    });
                }
            });

        });

        function addAdmin(url){
            $('#modalAdmin').modal('show');
            $('#modalAdmin .modal-title').text('Tambah User Bendahara');

            $('#modalAdmin form')[0].reset();
            $('#modalAdmin form').attr('action',url);
            $('#modalAdmin [name=_method]').val('post');
            $('#modalAdmin [name=name]').val('');
            $('#modalAdmin').on('shown.bs.modal', function () {
                $('#name').focus();
            })
        }

        function editAdmin(url, id){
            $('#modalAdmin').modal('show');
            $('#modalAdmin .modal-title').text('Ubah User Bendahara');

            $('#modalAdmin form')[0].reset();
            $('#modalAdmin form').attr('action',url);
            $('#modalAdmin [name=_method]').val('put');
            $('#modalAdmin [name=name]').val('');
            $('#modalAdmin [name=password]').removeAttr('required');
            $('#modalAdmin').on('shown.bs.modal', function () {
                $('#name').focus();
            })

            let show = "{{route('bendahara.show', '')}}"+"/"+id;
            $.get(show)
            .done((response) => {
                $('#modalAdmin [name=name]').val(response.name);
                $('#modalAdmin [name=nip]').val(response.nip);
                $('#modalAdmin [name=email]').val(response.email);
                $('#modalAdmin [name=id_opd]').val(response.id_opd).trigger('change');
            })
            .fail((errors) => {
                alert('Gagl tampil data');
                return;
            })
        }

        function hapusAdmin(url){
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
