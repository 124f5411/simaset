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
            </div>
            <div class="card-body">
                @if (Auth::user()->level == 'aset')
                    <div class="row">
                        <div class="form-group mr-4">
                            <label><strong>OPD :</strong></label>
                            <select id='opd' class="form-control" style="width:auto">
                                <option value=" ">Semua</option>
                                @foreach ($drops['instansi'] as $value)
                                    <option value="{{$value->opd}}">{{$value->opd}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label><strong>Tahun :</strong></label>
                            <select id='tahun' class="form-control" style="width:auto">
                                <option value=" ">Semua</option>
                                <option value="2020">2020</option>
                                <option value="2021">2021</option>
                                <option value="2022">2022</option>
                            </select>
                        </div>
                    </div>
                @endif
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataUsulan" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>OPD</th>
                                <th>Kode Barang</th>
                                <th>Nama Barang</th>
                                <th>Spesfikasi</th>
                                <th>Satuan</th>
                                <th>Harga</th>
                                <th>Rekening Belanja</th>
                                <th>Tahun</th>
                                <th>Dokumen</th>
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
            table = $('#dataUsulan').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                autoWidth: false,
                ajax:{
                    url: '{{ route('hspk.datas') }}',
                },
                columns:[
                    {data:'DT_RowIndex', searchable:false, sortable:false},
                    {data:'q_opd'},
                    {data:'kode_barang'},
                    {data:'uraian'},
                    {data:'spesifikasi'},
                    {data:'satuan'},
                    {data:'harga'},
                    {data:'rekening_belanja'},
                    {data:'tahun'},
                    {data:'dokumen', searchable:false, sortable:false},
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

            $('#tahun').change(function() {
                // if($(this).val() != ''){
                //     table.column(1).search($(this).val()).draw();
                // }else{
                //     table.ajax.reload();
                // }
                if($(this).val() != ''){
                    table.column(6).search($(this).val()).draw();
                }
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
                $('#modalHspk [name=id_satuan]').val(response.id_satuan).trigger('change');
                $('#modalHspk [name=harga]').val(response.harga);
            })
            .fail((errors) => {
                alert('Gagl tampil data');
                return;
            })
        }

        function verifHspk(url){
            let pesan;
            if(user_akses == 'operator'){
                pesan = 'Yakin? data akan dikirimkan untuk validasi.';
            }else if(user_akses == 'aset'){
                pesan = 'yakin data usulan telah benar?';
            }
            if (confirm(pesan)) {
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
