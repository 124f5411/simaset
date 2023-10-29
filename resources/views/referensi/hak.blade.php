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
                <h6 class="m-0 font-weight-bold text-primary">Hak Tanah</h6>
                <a href="#" onclick="addHak('{{ route('hak_tanah.store') }}')" class="btn btn-primary btn-icon-split float-right">
                    <span class="icon text-white-50">
                        <i class="fas fa-plus-circle"></i>
                    </span>
                    <span class="text">{{ $page }}</span>
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataHak" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Hak</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
@includeIf('form.referensi.hak')
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
            table = $('#dataHak').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                autoWidth: false,
                ajax:{
                    url: '{{ route('hak_tanah.data') }}'
                },
                columns:[
                    {data:'DT_RowIndex', searchable:false, sortable:false},
                    {data:'hak'},
                    {data:'aksi', searchable:false, sortable:false},
                ]
            });

            $('#modalHak').validator().on('submit', function (e){
                if(! e.preventDefault()){
                    $.post($('#modalHak form').attr('action'), $('#modalHak form').serialize())
                    .done((response) => {
                        $(".alert" ).addClass( "alert-success" );
                        $(".alert").show();
                        $("#massages").append(response);
                        setTimeout(function(){
                            $(".alert" ).removeClass( "alert-success" );
                            $("#massages").empty();
                            $('#modalHak form')[0].reset();
                            $('#modalHak').modal('hide');
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
                                $('#modalHak form')[0].reset();
                                $('#modalHak').modal('hide');
                            }, 5000);
                        });
                    });
                }
            });

        });

        function addHak(url){
            $('#modalHak').modal('show');
            $('#modalHak .modal-title').text('Tambah Hak Tanah');

            $('#modalHak form')[0].reset();
            $('#modalHak form').attr('action',url);
            $('#modalHak [name=_method]').val('post');
            $('#modalHak [name=jenis]').val('');
            $('#modalHak').on('shown.bs.modal', function () {
                $('#hak').focus();
            })
        }

        function editHak(url, id){
            $('#modalHak').modal('show');
            $('#modalHak .modal-title').text('Ubah Hak Tanah');

            $('#modalHak form')[0].reset();
            $('#modalHak form').attr('action',url);
            $('#modalHak [name=_method]').val('put');
            $('#modalHak [name=jenis]').val('');
            $('#modalHak').on('shown.bs.modal', function () {
                $('#hak').focus();
            })

            let show = "{{route('hak_tanah.show', '')}}"+"/"+id;
            $.get(show)
            .done((response) => {
                $('#modalHak [name=hak]').val(response.hak);
            })
            .fail((errors) => {
                alert('Gagl tampil data');
                return;
            })
        }

        function hapusHak(url){
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
