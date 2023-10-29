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
                <h6 class="m-0 font-weight-bold text-primary">ANALISIS STANDA BELANJA (ASB)</h6>
                @if (Auth::user()->level == 'operator' || Auth::user()->level == 'bendahara')
                <a href="#" onclick="addAsb('{{ route('asb.store') }}')" class="btn btn-primary btn-icon-split float-right">
                    <span class="icon text-white-50">
                        <i class="fas fa-plus-circle"></i>
                    </span>
                    <span class="text">{{ $page }}</span>
                </a>
                @endif
            </div>
            <div class="card-body">
                @if (Auth::user()->level == 'aset')
                <div class="form-group">
                    <label><strong>Filter :</strong></label>
                    <select id='opd' class="form-control" style="width:auto">
                        <option value=" ">Semua</option>
                        @foreach ($drops['instansi'] as $value)
                            <option value="{{$value->opd}}">{{$value->opd}}</option>
                        @endforeach
                    </select>
                </div>
                @endif
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataAsb" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>OPD</th>
                                <th>Nama Barang</th>
                                <th>Spesfikasi</th>
                                <th>Satuan</th>
                                <th>Harga</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
@includeIf('form.usulan.asb')
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
        let table;
        $(document).ready(function() {
            $('#id_kode').select2({
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
                    url: '{{ route('asb.data') }}'
                },
                columns:[
                    {data:'DT_RowIndex', searchable:false, sortable:false},
                    {data:'q_opd'},
                    {data:'uraian'},
                    {data:'spesifikasi'},
                    {data:'satuan'},
                    {data:'harga'},
                    {data:'aksi', searchable:false, sortable:false},
                ]
            }).column(1).visible(false);

            $('#opd').change(function() {
                // if($(this).val() != ''){
                //     table.column(1).search($(this).val()).draw();
                // }else{
                //     table.ajax.reload();
                // }
                if($(this).val() != ''){
                    table.column(1).search($(this).val()).draw();
                }
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

            let show = "{{route('asb.show', '')}}"+"/"+id;
            $.get(show)
            .done((response) => {
                $('#modalAsb [name=id_kode]').val(response.id_kode).trigger('change');
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
