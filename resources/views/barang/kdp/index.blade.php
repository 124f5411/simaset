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
                <h6 class="m-0 font-weight-bold text-primary">Data Konstruksi Dalam Pengerjaan</h6>
                @if (Auth::user()->level != 'aset')
                <a href="#" onclick="addForm('{{ route('kdp.store') }}')" class="btn btn-primary btn-icon-split float-right">
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
                                    <label for="kode_tanah">Kode Tanah</label>
                                    <input type="text" class="form-control" id="kode_tanah" name="kode_tanah">
                                </div>
                                <div class="form-group">
                                    <label for="harga">Nilai Kontrak</label>
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
                                            <label for="tingkat">Berlantai</label>
                                            <select class="form-control" id="tingkat" name="tingkat">
                                                <option value="">Pilih berlantai</option>
                                                <option value="0">Ya</option>
                                                <option value="1">Tidak</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="beton">Beton</label>
                                            <select class="form-control" id="beton" name="beton">
                                                <option value="">Pilih Beton</option>
                                                <option value="0">Ya</option>
                                                <option value="1">Tidak</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="luas">Luas</label>
                                            <input type="text" class="form-control" id="luas" name="luas" >
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="alamat">Lokasi</label>
                                            <input type="text" class="form-control" id="alamat" name="alamat" >
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="id_status_tanah">Status Tanah</label>
                                            <select class="form-control" id="id_status_tanah" name="id_status_tanah">
                                                <option value="">Pilih Status Tanah</option>
                                                @foreach ($drops['status_tanah'] as $value)
                                                    <option value="{{ $value->id }}">{{ $value->status }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="bangunan">Bangunan</label>
                                            <select class="form-control" id="bangunan" name="bangunan">
                                                <option value="">Pilih Jenis Bangunan</option>
                                                <option value="P">Permanen</option>
                                                <option value="SP">Semi Permanen</option>
                                                <option value="D">Darurat</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="t_mulai">Tanggal Mulai</label>
                                            <input type="text" class="form-control" id="t_mulai" name="t_mulai" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="asal">Asal</label>
                                            <input type="text" class="form-control" id="asal" name="asal" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="tgl_dokumen">Tanggal Dokumen</label>
                                            <input type="text" class="form-control" id="tgl_dokumen" name="tgl_dokumen" readonly required>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="no_dokumen">Nomor Dokumen</label>
                                            <input type="text" class="form-control" id="no_dokumen" name="no_dokumen" readonly required>
                                        </div>
                                    </div>
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
                    <table class="table table-sm table-bordered" id="dataKdp" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th rowspan="2" style="text-align: center;vertical-align: middle;">No</th>
                                <th rowspan="2" style="text-align: center;vertical-align: middle;">OPD</th>
                                <th rowspan="2" style="text-align: center;vertical-align: middle;">Kode</th>
                                <th rowspan="2" style="text-align: center;vertical-align: middle;">Nama / Jenis Barang</th>
                                <th rowspan="2" style="text-align: center;vertical-align: middle;">Register</th>
                                <th colspan="2" style="text-align: center;vertical-align: middle;">Konstruksi Bangunan</th>
                                <th rowspan="2" style="text-align: center;vertical-align: middle;">Luas</th>
                                <th rowspan="2" style="text-align: center;vertical-align: middle;">Letak</th>
                                <th colspan="2" style="text-align: center;vertical-align: middle;">Dokumen</th>
                                <th rowspan="2" style="text-align: center;vertical-align: middle;">Tanggal</th>
                                <th rowspan="2" style="text-align: center;vertical-align: middle;">Status Tanah</th>
                                <th rowspan="2" style="text-align: center;vertical-align: middle;">Kode Tanah</th>
                                <th rowspan="2" style="text-align: center;vertical-align: middle;">Asal Usul</th>
                                <th rowspan="2" style="text-align: center;vertical-align: middle;">Nilai Kontrak (ribuan Rp)</th>
                                <th rowspan="2" style="text-align: center;vertical-align: middle;">Keterangan</th>
                                <th rowspan="2" style="text-align: center;vertical-align: middle;">Aksi</th>
                            </tr>
                            <tr>
                                <th style="text-align: center;">Bertingkat</th>
                                <th style="text-align: center;">Beton</th>
                                <th style="text-align: center;">Tanggal</th>
                                <th style="text-align: center;">Nomor</th>
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

    // $('#tgl_dokumen').datepicker({
    //     uiLibrary: 'bootstrap4',
    //     format: 'yyyy-mm-dd'
    // });
    $('#t_mulai').datepicker({
        uiLibrary: 'bootstrap4',
        format: 'yyyy-mm-dd'
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
        table = $('#dataKdp').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: {
                url: '{{ route('kdp.data') }}'
            },
            columns: [
                {data: 'DT_RowIndex',searchable: false,sortable: false},
                {data: 'q_opd'},
                {data: 'kode'},
                {data: 'nm_barang'},
                {data: 'register'},
                {data: 'beton'},
                {data: 'tingkat'},
                {data: 'luas'},
                {data: 'alamat'},
                {data: 'tgl_dokumen'},
                {data: 'no_dokumen'},
                {data: 't_mulai'},
                {data: 'status_tanah'},
                {data: 'kode_tanah'},
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
            $('#kode_barang').focus();
            $('#kode_barang').change(function() {
                let kode = $("#kode_barang").val();
                if(kode == ''){
                    $('#kode').val('');
                    $('#register').val('');
                }else{
                    $('#kode').val(kode);
                    let getRegis = "{{route('kdp.getRegister', ['',''])}}"+"/"+kode+"/"+id_opd;
                    $.get(getRegis)
                    .done((response) => {
                        $('#register').val(response);
                    })
                }
            });
            $('#id_kontrak').change(function() {
                id_kontrak = $('#id_kontrak').val();
                let getKontrak = "{{route('kdp.getKontrak', ['',''])}}"+"/"+id_kontrak+"/"+id_opd;
                $.get(getKontrak)
                    .done((response) => {
                        console.log(response[0]);
                        $('#tgl_dokumen').val(response[0].t_kontrak);
                        $('#no_dokumen').val(response[0].no_kontrak);
                    })
            });
        });
    }

    function editKdp(url,id) {
        $('#form-peralatan').show();

        $('#frm-peralatan').attr('action', url);
            $('#frm-peralatan [name=_method]').val('put');
            let show = "{{route('kdp.show', '')}}"+"/"+id;
            $.get(show)
            .done((response) => {
                $('#frm-peralatan [name=kode_barang').off('change');
                // $('#frm-tanah [name=id_jenis]').val(response.id_jenis).trigger('change');
                $('#frm-peralatan [name=kode_barang]').val(response.kode).trigger('change').select2({disabled:'readonly'});
                $('#frm-peralatan [name=kode]').val(response.kode);
                $('#frm-peralatan [name=harga]').val(response.harga);
                $('#frm-peralatan [name=t_mulai]').val(response.t_mulai);
                $('#frm-peralatan [name=register]').val(response.register);
                $('#frm-peralatan [name=asal]').val(response.asal);
                $('#frm-peralatan [name=keterangan]').val(response.keterangan);
                $('#frm-peralatan [name=id_kontrak]').val(response.id_kontrak).trigger('change');
                $('#frm-peralatan [name=beton]').val(response.beton);
                $('#frm-peralatan [name=tingkat]').val(response.tingkat);
                $('#frm-peralatan [name=luas]').val(response.luas);
                $('#frm-peralatan [name=alamat]').val(response.alamat);
                $('#frm-peralatan [name=tgl_dokumen]').val(response.tgl_dokumen);
                $('#frm-peralatan [name=no_dokumen]').val(response.no_dokumen);
                $('#frm-peralatan [name=status_tanah]').val(response.status_tanah);
                $('#frm-peralatan [name=kode_tanah]').val(response.kode_tanah);
            })
            .fail((errors) => {
                alert('Gagl tampil data');
                return;
            })
    }

    function hapusKdp(url){
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

    function verifKdp(url){
        let cnfrm;
        if(user_akses == 'operator'){
            cnfrm = 'Yakin? data akan dikirimka untuk validasi.';
        }else if(user_akses == 'aset'){
            cnfrm = 'yakin data akan dicatat dalam KIB F';
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

    function tolakKdp(url){

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
