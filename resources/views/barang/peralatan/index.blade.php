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
                <h6 class="m-0 font-weight-bold text-primary">Data Peralatan</h6>
                @if (Auth::user()->level != 'aset')
                <a href="#" onclick="addForm('{{ route('peralatan.store') }}')" class="btn btn-primary btn-icon-split float-right">
                    <span class="icon text-white-50">
                        <i class="fas fa-plus-circle"></i>
                    </span>
                    <span class="text">{{ $page }}</span>
                </a>
                @endif
            </div>
            <div id="form-peralatan" style="display: none">
                <div class="card-body">
                    <form id="frm-peralatan" action="">
                    @csrf
                    @method('post')
                        <div class="row mb-4">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="kode_barang">Nama / Jenis Peralatan</label>
                                    <select class="form-control" id="kode_barang" name="kode_barang">
                                        <option value="">Pilih Peralatan</option>
                                        @foreach ($drops['kode_barang'] as $value)
                                        <option value="{{ $value->kode_barang }}">{{ $value->uraian }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="id_kontrak">Nomor Kontrak</label>
                                    <select class="form-control" id="id_kontrak" name="id_kontrak">
                                        <option value="">Pilih Kontrak</option>
                                        @foreach ($drops['kontrak'] as $value)
                                        <option value="{{ $value->id }}">{{ $value->no_kontrak }} | {{ $value->nm_kontrak }} | {{ $value->tahun }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="merek">Merek</label>
                                    <input type="text" class="form-control" id="merek" name="merek">
                                </div>
                                <div class="form-group">
                                    <label for="harga">Harga</label>
                                    <input type="text" class="form-control" id="harga" name="harga" required>
                                </div>
                                <div class="form-group">
                                    <label for="keterangan">Keterangan</label>
                                    <textarea class="form-control" id="keterangan" name="keterangan" rows="3"></textarea>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="kode">Kode</label>
                                            <input type="text" class="form-control" id="kode" name="kode" readonly>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="register">Register</label>
                                            <input type="text" class="form-control" id="register" name="register" readonly >
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="no_bpkb">Nomor BPKB</label>
                                            <input type="text" class="form-control" id="no_bpkb" name="no_bpkb" >
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="pabrik">Pabrik</label>
                                            <input type="text" class="form-control" id="pabrik" name="pabrik" >
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="no_rangka">Nomor Rangka</label>
                                            <input type="text" class="form-control" id="no_rangka" name="no_rangka" >
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="nopol">Nomor Polisi</label>
                                            <input type="text" class="form-control" id="nopol" name="nopol" >
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="no_mesin">Nomor Mesin</label>
                                            <input type="text" class="form-control" id="no_mesin" name="no_mesin">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="spek">Ukuran / CC</label>
                                            <input type="text" class="form-control" id="spek" name="spek">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="asal">Asal</label>
                                            <input type="text" class="form-control" id="asal" name="asal" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="bahan">Bahan</label>
                                            <input type="text" class="form-control" id="bahan" name="bahan" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="bahan">Tahun</label>
                                    <input type="number" class="form-control" id="tahun" name="tahun" maxlength="4" minlength="4" max="{{ date('Y') }}" required>
                                </div>
                                <div class="form-group form-check">
                                    <input type="checkbox" class="form-check-input" id="checkHuman" name="checkHuman" required>
                                    <label class="form-check-label" for="checkHuman">Yakin?</label>
                                </div>
                                <button type="button" onclick="batal()" class="btn btn-secondary">Batal</button>
                                <button type="submit" class="btn btn-primary float-right">Simpan</button>

                            </div>

                        </div>
                        <div class="alert" role="alert" style="display: none" >
                            <p id="massages"></p>
                        </div>
                    </form>
                </div>
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
                    <table class="table table-sm table-bordered" id="dataPeralatan" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th rowspan="2" style="text-align: center;vertical-align: middle;">No</th>
                                <th rowspan="2" style="text-align: center;vertical-align: middle;">OPD</th>
                                <th rowspan="2" style="text-align: center;vertical-align: middle;">Kode</th>
                                <th rowspan="2" style="text-align: center;vertical-align: middle;">Nama Barang</th>
                                <th rowspan="2" style="text-align: center;vertical-align: middle;">Register</th>
                                <th rowspan="2" style="text-align: center;vertical-align: middle;">Merek / Tipe</th>
                                <th rowspan="2" style="text-align: center;vertical-align: middle;">Ukuran / CC</th>
                                <th rowspan="2" style="text-align: center;vertical-align: middle;">Bahan</th>
                                <th rowspan="2" style="text-align: center;vertical-align: middle;">Tahun</th>
                                <th colspan="5" style="text-align: center;vertical-align: middle;">Nomor</th>
                                <th rowspan="2" style="text-align: center;vertical-align: middle;">Asal Usul</th>
                                <th rowspan="2" style="text-align: center;vertical-align: middle;">Harga (ribuan Rp)</th>
                                <th rowspan="2" style="text-align: center;vertical-align: middle;">Keterangan</th>
                                <th rowspan="2" style="text-align: center;vertical-align: middle;">Aksi</th>
                            </tr>
                            <tr>
                                <th style="text-align: center;">Pabrik</th>
                                <th style="text-align: center;">Rangka</th>
                                <th style="text-align: center;">Mesin</th>
                                <th style="text-align: center;">Polisi</th>
                                <th style="text-align: center;">Bpkb</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('css')
<link href="{{ asset('themes/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css">
<link href="https://unpkg.com/gijgo@1.9.14/css/gijgo.min.css" rel="stylesheet" type="text/css" />
<style>
    .table td, .table th {
        font-size: 8pt;
    }
</style>
@endpush

@push('scripits')
<script src="{{ asset('themes/vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('themes/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.full.min.js"></script>
<script src="https://unpkg.com/gijgo@1.9.14/js/gijgo.min.js" type="text/javascript"></script>
<script src="{{ asset('js/validator.min.js') }}"></script>

<script>

    $('#opd').select2({
            theme: 'bootstrap4',
        });
    let table;
    let id_opd = '{{encrypt(Auth::user()->id_opd)}}';
    let user_akses = "{{ Auth::user()->level }}";
    $(document).ready(function () {
        $('#kode_barang').select2({
            theme: 'bootstrap4',
        });
        $('#id_kontrak').select2({
            theme: 'bootstrap4',
        }).prop("required", true);
        table = $('#dataPeralatan').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: {
                url: '{{ route('peralatan.data') }}'
            },
            columns: [
                {data: 'DT_RowIndex',searchable: false,sortable: false},
                {data: 'q_opd'},
                {data: 'kode'},
                {data: 'nm_peralatan'},
                {data: 'register'},
                {data: 'merek'},
                {data: 'spek'},
                {data: 'bahan'},
                {data: 'tahun'},
                {data: 'pabrik'},
                {data: 'no_rangka'},
                {data: 'no_mesin'},
                {data: 'nopol'},
                {data: 'no_bpkb'},
                {data: 'asal'},
                {data: 'harga'},
                {data: 'keterangan'},
                {data: 'aksi',searchable: false,sortable: false},
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

        $('#frm-peralatan').on('submit', function(e){
            e.preventDefault();
            $.post($('#frm-peralatan').attr('action'), $('#frm-peralatan').serialize())
            .done((response) => {
                $(".alert" ).addClass( "alert-success" );
                        $(".alert").show();
                        $("#massages").append(response);
                        setTimeout(function(){
                            $(".alert" ).removeClass( "alert-success" );
                            $("#massages").empty();
                            $('#frm-peralatan [name=id_kontrak]').val('').trigger('change');
                            $('#register').empty();
                            $('#frm-peralatan')[0].reset();
                            $('#form-peralatan').hide();
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
                    }, 3000);
                });
            });
        });
    });

    function addForm(url) {
        $('#form-peralatan').slideToggle('fast', function () {
            $('#frm-peralatan').attr('action', url);
            $('#frm-peralatan [name=_method]').val('post');
            $('#frm-peralatan')[0].reset();
            $('#nm_peralatan').focus();
            $('#kode_barang').change(function() {
                let kode = $("#kode_barang").val();
                if(kode == ''){
                    $('#kode').val('');
                    $('#register').val('');
                }else{
                    $('#kode').val(kode);
                    let getRegis = "{{route('peralatan.getRegister', ['',''])}}"+"/"+kode+"/"+id_opd;
                    $.get(getRegis)
                    .done((response) => {
                        $('#register').val(response);
                    })
                }
            });
        });
    }

    function editPeralatan(url,id) {
        $('#form-peralatan').show();

        $('#frm-peralatan').attr('action', url);

            $('#frm-peralatan [name=_method]').val('put');
            let show = "{{route('peralatan.show', '')}}"+"/"+id;
            $.get(show)
            .done((response) => {
                $('#frm-peralatan [name=kode_barang').off('change');
                // $('#frm-tanah [name=id_jenis]').val(response.id_jenis).trigger('change');
                $('#frm-peralatan [name=kode_barang]').val(response.kode).trigger('change').select2({disabled:'readonly'});

                $('#frm-peralatan [name=kode]').val(response.kode);
                $('#frm-peralatan [name=harga]').val(response.harga);
                $('#frm-peralatan [name=tahun]').val(response.tahun);
                $('#frm-peralatan [name=register]').val(response.register);
                $('#frm-peralatan [name=asal]').val(response.asal);
                $('#frm-peralatan [name=keterangan]').val(response.keterangan);
                $('#frm-peralatan [name=id_kontrak]').val(response.id_kontrak).trigger('change');
                $('#frm-peralatan [name=asal]').val(response.asal);
                $('#frm-peralatan [name=bahan]').val(response.bahan);
                $('#frm-peralatan [name=merek]').val(response.merek);
                $('#frm-peralatan [name=no_mesin]').val(response.no_mesin);
                $('#frm-peralatan [name=no_rangka]').val(response.no_rangka);
                $('#frm-peralatan [name=pabrik]').val(response.pabrik);
                $('#frm-peralatan [name=no_bpkb]').val(response.no_bpkb);
                $('#frm-peralatan [name=spek]').val(response.spek);
            })
            .fail((errors) => {
                alert('Gagl tampil data');
                return;
            })
    }

    function hapusPeralatan(url){
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

    function verifPeralatan(url){
        let cnfrm;
        if(user_akses == 'operator'){
            cnfrm = 'Yakin? data akan dikirimkan untuk validasi.';
        }else if(user_akses == 'aset'){
            cnfrm = 'yakin data akan dicatat dalam KIB B';
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

    function tolakPeralatan(url){

        if (confirm('Yakin? Data akan ditolak akan dikembalikan.')) {
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

    function batal(){
        $('#form-peralatan').hide();
        $('#frm-peralatan')[0].reset();
    }
</script>
@endpush
