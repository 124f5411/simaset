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
                <h6 class="m-0 font-weight-bold text-primary">RINCIAN HARGA SATUAN POKOK KEGIATAN (HSPK)</h6>
                @if (Auth::user()->level == 'operator' || Auth::user()->level == 'bendahara')
                    <a href="#" onclick="window.open('{{ route('hspk.export',Request::segment(4)) }}','Title','directories=no,titlebar=no,toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=1024,height = 720');" class="btn btn-danger btn-icon-split mt-2">
                        <span class="icon text-white-50">
                            <i class="fas fa-file-pdf"></i>
                        </span>
                        <span class="text">EXPORT</span>
                    </a>
                    @if ($usulan_status == '0')
                        <a href="#" onclick="addHspk('{{ route('hspk.rincianStore',decrypt(Request::segment(4))) }}')" class="btn btn-primary btn-icon-split float-right mt-2">
                            <span class="icon text-white-50">
                                <i class="fas fa-plus-circle"></i>
                            </span>
                            <span class="text">RINCIAN</span>
                        </a>
                    @endif
                @endif
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm table-bordered" id="dataHspk" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Kode Barang</th>
                                <th>Nama Barang</th>
                                <th>Uraian</th>
                                <th style="width: 100px">Spesfikasi</th>
                                <th>Satuan</th>
                                <th style="width: 150px">Harga</th>
                                <th>Rekening Belanja</th>
                                <th style="width: 100px">Keterangan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
@includeIf('form.usulan.hspk.rincian')
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
        let user_akses = "{{ Auth::user()->level }}";
        let id_usulan = '{{decrypt(Request::segment(4))}}';
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
            $('#opd').select2({
                theme: 'bootstrap4',
            });

            table = $('#dataHspk').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                autoWidth: false,
                ajax:{
                    url: '{{ route('hspk.data_rincian','') }}'+'/'+id_usulan,
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
                ]
            });

            $('#modalHspk').validator().on('submit', function (e){
                if(! e.preventDefault()){
                    $.post($('#modalHspk form').attr('action'), $('#modalHspk form').serialize())
                    .done((response) => {
                        $(".alert" ).addClass( "alert-success" );
                        $(".alert").show();
                        $("#massages").append(response);
                        setTimeout(function(){
                            $(".alert" ).removeClass( "alert-success" );
                            $("#massages").empty();
                            $('#modalHspk [name=id_kode]').val('').trigger('change');
                            $('#modalHspk [name=id_satuan]').val('').trigger('change');
                            $('#modalHspk [name=id_rekening]').val('').trigger('change');
                            $('#modalHspk form')[0].reset();
                            $('#modalHspk').modal('hide');
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
                                // $('#modalHspk [name=id_kontrak]').val('').trigger('change');
                                // $('#modalHspk form')[0].reset();
                                // $('#modalHspk').modal('hide');
                            }, 3000);
                        });
                    });
                }
            });

        });

        function addHspk(url){
            $('#modalHspk').modal('show');
            $('#modalHspk .modal-title').text('Tambah Usulan HSPK');

            $('#modalHspk form')[0].reset();
            $('#modalHspk form').attr('action',url);
            $('#modalHspk [name=_method]').val('post');
            $('#modalHspk [name=jenis]').val('');
            $('#modalHspk').on('shown.bs.modal', function () {
                $('#id_kode').focus();
            })
        }

        function editHspk(url, id){
            $('#modalHspk').modal('show');
            $('#modalHspk .modal-title').text('Ubah Usulan HSPK');

            $('#modalHspk form')[0].reset();
            $('#modalHspk form').attr('action',url);
            $('#modalHspk [name=_method]').val('put');
            $('#modalHspk [name=jenis]').val('');
            $('#modalHspk').on('shown.bs.modal', function () {
                $('#id_kode').focus();
            })

            let show = "{{route('ssh.rincianShow', '')}}"+"/"+id;
            $.get(show)
            .done((response) => {
                $('#modalHspk [name=id_kode]').val(response.id_kode).trigger('change');
                $('#modalHspk [name=id_rekening]').val(response.id_rekening).trigger('change');
                $('#modalHspk [name=spesifikasi]').val(response.spesifikasi);
                $('#modalHspk [name=uraian]').val(response.uraian);
                $('#modalHspk [name=id_satuan]').val(response.id_satuan).trigger('change');
                $('#modalHspk [name=harga]').val(response.harga);
            })
            .fail((errors) => {
                alert('Gagl tampil data');
                return;
            })
        }

        function hapusHspk(url){
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

        function verifHspk(url){
        let cnfrm;
        if(user_akses == 'operator'){
            cnfrm = 'Yakin? data akan dikirimka untuk validasi.';
        }else if(user_akses == 'aset'){
            cnfrm = 'yakin data usulan telah benar?';
        }
        if (confirm(cnfrm)) {
                $.post(url, {
                        '_token': $('[name=csrf-token]').attr('content'),
                        '_method': 'put'
                    })
                    .done((response) => {
                        table.ajax.reload();
                    })
                    .fail((errors) => {
                        alert('Gagal kirim data');
                        return;
                    });
            }
    }

    function tolakHspk(url){

        if (confirm('Yakin? Data akan ditolak atau dikembalikan.')) {
                $.post(url, {
                        '_token': $('[name=csrf-token]').attr('content'),
                        '_method': 'put'
                    })
                    .done((response) => {
                        table.ajax.reload();
                    })
                    .fail((errors) => {
                        alert('Gagal tolak data');
                        return;
                    });
            }
    }
    </script>
@endpush
