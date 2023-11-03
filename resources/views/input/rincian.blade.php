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
                <h6 class="m-0 font-weight-bold text-primary">RINCIAN USULAN</h6>
                    <a href="#" onclick="window.open('{{ route('rincian.export',Request::segment(2)) }}','Title','directories=no,titlebar=no,toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=1024,height = 720');"
                    class="btn btn-sm btn-danger btn-icon-split mt-2">
                        <span class="icon text-white-50">
                            <i class="fas fa-file-pdf"></i>
                        </span>
                        <span class="text">EXPORT</span>
                    </a>
                    @if ($usulan_status == '0')
                        <a href="#" onclick="addSsh('{{ route('rincian.store',decrypt(Request::segment(2))) }}')"
                        class="btn btn-sm btn-primary btn-icon-split float-right mt-2">
                            <span class="icon text-white-50">
                                <i class="fas fa-plus-circle"></i>
                            </span>
                            <span class="text">RINCIAN</span>
                        </a>
                    @endif
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm table-bordered" id="dataSsh" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode Barang</th>
                                <th>Nama Barang</th>
                                <th>Uraian</th>
                                <th style="width: 200px">Spesfikasi</th>
                                <th>Satuan</th>
                                <th style="width: 150px">Harga</th>
                                <th>Rekening Belanja</th>
                                <th style="width: 100px">Keterangan</th>
                                <th>Aksi</th>
                                <th>Jenis</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
@includeIf('form.input.rincian')
@endsection

@push('css')
    <link href="{{ asset('themes/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
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
        let id_usulan = '{{decrypt(Request::segment(2))}}';
        let table;
        $(document).ready(function() {
            $('#id_kode').select2({
                theme: 'bootstrap4',
            });
            $('#id_rekening').select2({
                theme: 'bootstrap4',
            });
            $('#id_satuan').select2({
                theme: 'bootstrap4',
            });

            table = $('#dataSsh').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                autoWidth: false,
                ajax:{
                    url: '{{ route('rincian.data','') }}'+'/'+id_usulan,
                },
                columns:[
                    {data:'DT_RowIndex', searchable:false, sortable:false},
                    {data:'kode_barang'},
                    {data:'uraian_id'},
                    {data:'uraian'},
                    {data:'spesifikasi'},
                    {data:'satuan'},
                    {data:'harga'},
                    {data:'rekening_belanja'},
                    {data:'keterangan'},
                    {data:'aksi', searchable:false, sortable:false},
                    {data:'jenis'},
                ]
            });

            $('#modalSsh').validator().on('submit', function (e){
                if(! e.preventDefault()){
                    $.post($('#modalSsh form').attr('action'), $('#modalSsh form').serialize())
                    .done((response) => {
                        $(".alert" ).addClass( "alert-success" );
                        $(".alert").show();
                        $("#massages").append(response);
                        setTimeout(function(){
                            $(".alert" ).removeClass( "alert-success" );
                            $("#massages").empty();
                            $('#modalSsh [name=id_kode]').val('').trigger('change');
                            $('#modalSsh [name=id_satuan]').val('').trigger('change');
                            $('#modalSsh [name=id_rekening]').val('').trigger('change');
                            $('#modalSsh form')[0].reset();
                            $('#modalSsh').modal('hide');
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
                            }, 3000);
                        });
                    });
                }
            });

        });

        function addSsh(url){
            $('#modalSsh').modal('show');
            $('#modalSsh .modal-title').text('Tambah Rincian Usulan');

            $('#modalSsh form')[0].reset();
            $('#modalSsh form').attr('action',url);
            $('#modalSsh [name=_method]').val('post');
            $('#modalSsh [name=jenis]').val('');
            $('#modalSsh').on('shown.bs.modal', function () {
                $('#id_kode').focus();
            })
        }

        function editSsh(url, id){
            $('#modalSsh').modal('show');
            $('#modalSsh .modal-title').text('Ubah Rincian Usulan');

            $('#modalSsh form')[0].reset();
            $('#modalSsh form').attr('action',url);
            $('#modalSsh [name=_method]').val('put');
            $('#modalSsh [name=jenis]').val('');
            $('#modalSsh').on('shown.bs.modal', function () {
                $('#id_kode').focus();
            })

            let show = "{{route('rincian.show', '')}}"+"/"+id;
            $.get(show)
            .done((response) => {
                $('#modalSsh [name=id_kode]').val(response.id_kode).trigger('change');
                $('#modalSsh [name=id_rekening]').val(response.id_rekening).trigger('change');
                $('#modalSsh [name=spesifikasi]').val(response.spesifikasi);
                $('#modalSsh [name=uraian]').val(response.uraian);
                $('#modalSsh [name=id_satuan]').val(response.id_satuan).trigger('change');
                $('#modalSsh [name=harga]').val(response.harga);
            })
            .fail((errors) => {
                alert('Gagl tampil data');
                return;
            })
        }

        function hapusSsh(url){
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
