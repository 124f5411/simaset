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
                <h6 class="m-0 font-weight-bold text-primary">Penyedia</h6>
                <a href="javascript:void(0)" onclick="addPenyedia('{{ route('penyedia.store') }}')" class="btn btn-primary btn-icon-split float-right">
                    <span class="icon text-white-50">
                        <i class="fas fa-plus-circle"></i>
                    </span>
                    <span class="text">Penyedia</span>
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataPenyedia" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Penyedia</th>
                                <th>Pimpinan</th>
                                <th>Alamat</th>
                                <th>No Telp</th>
                                <th>Email</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
@includeIf('form.penyedia.index')
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
            table = $('#dataPenyedia').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                autoWidth: false,
                ajax:{
                    url: '{{ route('penyedia.data') }}'
                },
                columns:[
                    {data:'DT_RowIndex', searchable:false, sortable:false},
                    {data:'nm_penyedia'},
                    {data:'pimpinan'},
                    {data:'telp'},
                    {data:'alamat'},
                    {data:'email'},
                    {data:'aksi', searchable:false, sortable:false},
                ]
            });

            $('#modalPenyedia').validator().on('submit', function (e){
                if(! e.preventDefault()){
                    $.post($('#modalPenyedia form').attr('action'), $('#modalPenyedia form').serialize())
                    .done((response) => {
                        $(".alert" ).addClass( "alert-success" );
                        $(".alert").show();
                        $("#massages").append(response);
                        setTimeout(function(){
                            $(".alert" ).removeClass( "alert-success" );
                            $("#massages").empty();
                            $('#modalPenyedia form')[0].reset();
                            $('#modalPenyedia').modal('hide');
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
                                $('#modalPenyedia form')[0].reset();
                                $('#modalPenyedia').modal('hide');
                            }, 3000);
                        });
                    });
                }
            });

        });

        function addPenyedia(url){
            $('#modalPenyedia').modal('show');
            $('#modalPenyedia .modal-title').text('Tambah Penyedia');

            $('#modalPenyedia form')[0].reset();
            $('#modalPenyedia form').attr('action',url);
            $('#modalPenyedia [name=_method]').val('post');
            $('#modalPenyedia [name=jenis]').val('');
            $('#modalPenyedia').on('shown.bs.modal', function () {
                $('#nm_penyedia').focus();
            })
        }

        function editPenyedia(url, id){
            $('#modalPenyedia').modal('show');
            $('#modalPenyedia .modal-title').text('Ubah Penyedia');

            $('#modalPenyedia form')[0].reset();
            $('#modalPenyedia form').attr('action',url);
            $('#modalPenyedia [name=_method]').val('put');
            $('#modalPenyedia [name=jenis]').val('');
            $('#modalPenyedia').on('shown.bs.modal', function () {
                $('#nm_penyedia').focus();
            })

            let show = "{{route('penyedia.show', '')}}"+"/"+id;
            $.get(show)
            .done((response) => {
                $('#modalPenyedia [name=nm_penyedia]').val(response.nm_penyedia);
                $('#modalPenyedia [name=pimpinan]').val(response.pimpinan);
                $('#modalPenyedia [name=telp]').val(response.telp);
                $('#modalPenyedia [name=alamat]').val(response.alamat);
                $('#modalPenyedia [name=email]').val(response.email);
            })
            .fail((errors) => {
                alert('Gagl tampil data');
                return;
            })
        }

        function hapusPenyedia(url){
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
