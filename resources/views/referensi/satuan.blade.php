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
                <h6 class="m-0 font-weight-bold text-primary">Satuan</h6>
                <a href="#" onclick="addSatuan('{{ route('satuan.store') }}')" class="btn btn-primary btn-icon-split float-right">
                    <span class="icon text-white-50">
                        <i class="fas fa-plus-circle"></i>
                    </span>
                    <span class="text">{{ $page }}</span>
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataSatuan" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Satuan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
@includeIf('form.referensi.satuan')
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
            table = $('#dataSatuan').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                autoWidth: false,
                ajax:{
                    url: '{{ route('satuan.data') }}'
                },
                columns:[
                    {data:'DT_RowIndex', searchable:false, sortable:false},
                    {data:'nm_satuan'},
                    {data:'aksi', searchable:false, sortable:false},
                ]
            });

            $('#modalSatuan').validator().on('submit', function (e){
                if(! e.preventDefault()){
                    $.post($('#modalSatuan form').attr('action'), $('#modalSatuan form').serialize())
                    .done((response) => {
                        $(".alert" ).addClass( "alert-success" );
                        $(".alert").show();
                        $("#massages").append(response);
                        setTimeout(function(){
                            $(".alert" ).removeClass( "alert-success" );
                            $("#massages").empty();
                            $('#modalSatuan form')[0].reset();
                            $('#modalSatuan').modal('hide');
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
                                $('#modalSatuan form')[0].reset();
                                $('#modalSatuan').modal('hide');
                            }, 3000);
                        });
                    });
                }
            });

        });

        function addSatuan(url){
            $('#modalSatuan').modal('show');
            $('#modalSatuan .modal-title').text('Tambah data satuan');

            $('#modalSatuan form')[0].reset();
            $('#modalSatuan form').attr('action',url);
            $('#modalSatuan [name=_method]').val('post');
            $('#modalSatuan [name=nm_satuan]').val('');
            $('#modalSatuan').on('shown.bs.modal', function () {
                $('#nm_satuan').focus();
            })
        }

        function editSatuan(url, id){
            $('#modalSatuan').modal('show');
            $('#modalSatuan .modal-title').text('Ubah data satuan');

            $('#modalSatuan form')[0].reset();
            $('#modalSatuan form').attr('action',url);
            $('#modalSatuan [name=_method]').val('put');
            $('#modalSatuan [name=nm_satuan]').val('');
            $('#modalSatuan').on('shown.bs.modal', function () {
                $('#nm_satuan').focus();
            })

            let show = "{{route('satuan.show', '')}}"+"/"+id;
            $.get(show)
            .done((response) => {
                $('#modalSatuan [name=nm_satuan]').val(response.nm_satuan);
            })
            .fail((errors) => {
                alert('Gagl tampil data');
                return;
            })
        }

        function hapusSatuan(url){
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
