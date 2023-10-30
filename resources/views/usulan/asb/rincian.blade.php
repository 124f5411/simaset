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
                <h6 class="m-0 font-weight-bold text-primary">RINCIAN ANALISIS STANDA BELANJA (ASB)</h6>
                @if (Auth::user()->level == 'operator' || Auth::user()->level == 'bendahara')
                    <a href="#" onclick="window.open('{{ route('asb.export',Request::segment(4)) }}','Title','directories=no,titlebar=no,toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=1024,height = 720');" class="btn btn-danger btn-icon-split mt-2">
                        <span class="icon text-white-50">
                            <i class="fas fa-file-pdf"></i>
                        </span>
                        <span class="text">EXPORT</span>
                    </a>
                    @if ($usulan_status == '0')
                        <a href="#" onclick="addAsb('{{ route('asb.rincianStore',decrypt(Request::segment(4))) }}')" class="btn btn-primary btn-icon-split float-right mt-2">
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
                    <table class="table table-bordered" id="dataAsb" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Kode Barang</th>
                                <th>Nama Barang</th>
                                <th>Spesfikasi</th>
                                <th>Satuan</th>
                                <th>Harga</th>
                                <th>Rekening Belanja</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
@includeIf('form.usulan.asb.rincian')
@endsection

@push('css')
    <link href="{{ asset('themes/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css">

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

            table = $('#dataAsb').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                autoWidth: false,
                ajax:{
                    url: '{{ route('asb.data_rincian','') }}'+'/'+id_usulan,
                },
                columns:[
                    {data:'DT_RowIndex', searchable:false, sortable:false},
                    {data:'kode_barang'},
                    {data:'uraian'},
                    {data:'spesifikasi'},
                    {data:'satuan'},
                    {data:'harga'},
                    {data:'rekening_belanja'},
                    {data:'aksi', searchable:false, sortable:false},
                ]
            });

            $('#modalAsb').validator().on('submit', function (e){
                if(! e.preventDefault()){
                    $.post($('#modalAsb form').attr('action'), $('#modalAsb form').serialize())
                    .done((response) => {
                        $(".alert" ).addClass( "alert-success" );
                        $(".alert").show();
                        $("#massages").append(response);
                        setTimeout(function(){
                            $(".alert" ).removeClass( "alert-success" );
                            $("#massages").empty();
                            $('#modalAsb [name=id_kode]').val('').trigger('change');
                            $('#modalAsb [name=id_satuan]').val('').trigger('change');
                            $('#modalAsb [name=id_rekening]').val('').trigger('change');
                            $('#modalAsb form')[0].reset();
                            $('#modalAsb').modal('hide');
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
                                // $('#modalAsb [name=id_kontrak]').val('').trigger('change');
                                // $('#modalAsb form')[0].reset();
                                // $('#modalAsb').modal('hide');
                            }, 3000);
                        });
                    });
                }
            });

        });

        function addAsb(url){
            $('#modalAsb').modal('show');
            $('#modalAsb .modal-title').text('Tambah Usulan ASB');

            $('#modalAsb form')[0].reset();
            $('#modalAsb form').attr('action',url);
            $('#modalAsb [name=_method]').val('post');
            $('#modalAsb [name=jenis]').val('');
            $('#modalAsb').on('shown.bs.modal', function () {
                $('#id_kode').focus();
            })
        }

        function editAsb(url, id){
            $('#modalAsb').modal('show');
            $('#modalAsb .modal-title').text('Ubah Usulan ASB');

            $('#modalAsb form')[0].reset();
            $('#modalAsb form').attr('action',url);
            $('#modalAsb [name=_method]').val('put');
            $('#modalAsb [name=jenis]').val('');
            $('#modalAsb').on('shown.bs.modal', function () {
                $('#id_kode').focus();
            })

            let show = "{{route('asb.rincianShow', '')}}"+"/"+id;
            $.get(show)
            .done((response) => {
                $('#modalAsb [name=id_kode]').val(response.id_kode).trigger('change');
                $('#modalAsb [name=id_rekening]').val(response.id_rekening).trigger('change');
                $('#modalAsb [name=spesifikasi]').val(response.spesifikasi);
                $('#modalAsb [name=id_satuan]').val(response.id_satuan).trigger('change');
                $('#modalAsb [name=harga]').val(response.harga);
            })
            .fail((errors) => {
                alert('Gagl tampil data');
                return;
            })
        }

        function hapusAsb(url){
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

        function verifAsb(url){
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

    function tolakAsb(url){

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
