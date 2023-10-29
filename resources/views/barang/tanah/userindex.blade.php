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
                <h6 class="m-0 font-weight-bold text-primary">Data Tanah</h6>
                @if (Auth::user()->level != 'aset')

                <a href="#" onclick="addForm('{{ route('tanah.store',encrypt(Auth::user()->id_opd)) }}')" class="btn btn-primary btn-icon-split float-right">
                    <span class="icon text-white-50">
                        <i class="fas fa-plus-circle"></i>
                    </span>
                    <span class="text">{{ $page }}</span>
                </a>
                @endif
            </div>
            <div id="form-tanah" style="display: none">
                <div class="card-body">
                    <form id="frm-tanah" action="">
                    @csrf
                    @method('post')
                        <div class="row mb-4">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="id_jenis">Nama / Jenis Tanah</label>
                                    <select class="form-control" id="id_jenis" name="id_jenis" required>
                                        <option value="">Pilih Jenis</option>
                                        @foreach ($drops['jenis'] as $value)
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
                                    <label for="tgl_sertifikat">Tanggal Sertifikat</label>
                                    <input type="text" class="form-control" id="tgl_sertifikat" name="tgl_sertifikat">
                                </div>
                                <div class="form-group">
                                    <label for="no_sertifikat">Nomor Sertifikat</label>
                                    <input type="text" class="form-control" id="no_sertifikat" name="no_sertifikat">
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
                                            <input type="text" class="form-control" id="register" name="register" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="luas">Luas</label>
                                            <input type="text" class="form-control" id="luas" name="luas" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="tahun">Tahun</label>
                                            <input type="text" class="form-control" id="tahun" name="tahun" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="id_hak">Hak Tanah</label>
                                    <select class="form-control" id="id_hak" name="id_hak" required>
                                        <option value="">Pilih Hak Tanah</option>
                                        @foreach ($drops['hak'] as $value)
                                        <option value="{{ $value->id }}">{{ $value->hak }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="penggunaan">Penggunaan</label>
                                    <input type="text" class="form-control" id="penggunaan" name="penggunaan" required>
                                </div>
                                <div class="form-group">
                                    <label for="asal">Asal</label>
                                    <input type="text" class="form-control" id="asal" name="asal" required>
                                </div>
                                <div class="form-group">
                                    <label for="alamat">Lokasi / Alamat</label>
                                    <textarea class="form-control" id="alamat" name="alamat" rows="3" required></textarea>
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
                    <table class="table table-sm table-bordered" id="dataTanah" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th rowspan="3" style="text-align: center;vertical-align: middle;">No</th>
                                <th rowspan="3" style="text-align: center;vertical-align: middle;">OPD</th>
                                <th rowspan="3" style="text-align: center;vertical-align: middle;">Nama barang</th>
                                <th colspan="2" style="text-align: center">Nomor</th>
                                <th rowspan="3" style="text-align: center;vertical-align: middle;">Luas</th>
                                <th rowspan="3" style="text-align: center;vertical-align: middle;">Tahun</th>
                                <th rowspan="3" style="text-align: center;vertical-align: middle;">Lokasi</th>
                                <th colspan="3" style="text-align: center;vertical-align: middle;">Status Tanah</th>
                                <th rowspan="3" style="text-align: center;vertical-align: middle;">Penggunaan</th>
                                <th rowspan="3" style="text-align: center;vertical-align: middle;">Asal Usul</th>
                                <th rowspan="3" style="text-align: center;vertical-align: middle;">Harga (ribuan Rp)
                                </th>
                                <th rowspan="3" style="text-align: center;vertical-align: middle;">Keterangan</th>
                                <th rowspan="3" style="text-align: center;vertical-align: middle;">Aksi</th>
                            </tr>
                            <tr>
                                <th rowspan="2" style="text-align: center;vertical-align: middle;">Kode barang</th>
                                <th rowspan="2" style="text-align: center;vertical-align: middle;">Register</th>
                                <th rowspan="2" style="text-align: center;vertical-align: middle;">Hak</th>
                                <th colspan="2" style="text-align: center;">Sertifikat</th>
                            </tr>
                            <tr>
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
    $('#opd').select2({
            theme: 'bootstrap4',
        });
    let table;
    let id_opd = '{{encrypt(Auth::user()->id_opd)}}';
    let user_akses = "{{ Auth::user()->level }}";
    $(document).ready(function () {
        // $('#id_jenis').select2({
        //     theme: 'bootstrap4',
        // });
        $('#id_kontrak').select2({
            theme: 'bootstrap4',
        });
        $('#id_hak').select2({
            theme: 'bootstrap4',
        });
        $('#tgl_sertifikat').datepicker({
                uiLibrary: 'bootstrap4',
                format: 'yyyy-mm-dd'
            });

        let show_url = "";
        if(user_akses == 'aset'){
            show_url = '{{ route('tanah.all') }}';
        }else{
            show_url = '{{ route('tanah.data','') }}' + '/' + id_opd;
        }
        table = $('#dataTanah').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: {
                url: show_url
            },
            columns: [
                {data: 'DT_RowIndex',searchable: false,sortable: false},
                {data: 'q_opd'},
                {data: 'jenis'},
                {data: 'kode'},
                {data: 'register'},
                {data: 'luas'},
                {data: 'tahun'},
                {data: 'alamat'},
                {data: 'hak'},
                {data: 'tgl_sertifikat'},
                {data: 'no_sertifikat'},
                {data: 'penggunaan'},
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

        $('#frm-tanah').on('submit', function(e){
            e.preventDefault();
            $.post($('#frm-tanah').attr('action'), $('#frm-tanah').serialize())
            .done((response) => {
                $(".alert" ).addClass( "alert-success" );
                        $(".alert").show();
                        $("#massages").append(response);
                        setTimeout(function(){
                            $(".alert" ).removeClass( "alert-success" );
                            $("#massages").empty();
                            $('#frm-tanah [name=id_jenis]').val('').trigger('change');
                            $('#frm-tanah [name=id_kontrak]').val('').trigger('change');
                            $('#frm-tanah [name=id_hak]').val('').trigger('change');
                            $('#register').empty();
                            $('#frm-tanah [name=id_jenis]').prop("disabled", false);
                            $('#id_jenis').select2({
                                theme: 'bootstrap4',
                            });
                            $('#frm-tanah')[0].reset();
                            if(user_akses == 'aset'){
                                $('#form-tanah').hide();
                            }
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
        $('#form-tanah').slideToggle('fast', function () {
            $('#id_jenis').select2({
                theme: 'bootstrap4',
            }).prop("disabled", false);
            $('#frm-tanah').attr('action', url);
            $('#frm-tanah [name=_method]').val('post');
            $('#frm-tanah')[0].reset();
            $('#id_jenis').focus();
            $('#id_jenis').change(function() {
                let id = $("#id_jenis").val();
                let kode = $("#id_jenis").val();
                if(id === ""){
                    $('#kode').val('');
                    $('#register').val('');
                }else{
                    $('#kode').val(kode);
                    let getRegis = "{{route('tanah.getRegister', ['',''])}}"+"/"+kode+"/"+id_opd;
                    $.get(getRegis)
                    .done((response) => {
                        $('#register').val(response);
                    })
                }

            });
        });
    }

    function editTanah(url,id) {
        $('#form-tanah').show();

        $('#frm-tanah').attr('action', url);
            $('#frm-tanah [name=_method]').val('put');
            let show = "{{route('tanah.show', '')}}"+"/"+id;
            $.get(show)
            .done((response) => {
                $('#frm-tanah [name=id_jenis').off('change');
                // $('#frm-tanah [name=id_jenis]').val(response.id_jenis).trigger('change');
                $('#frm-tanah [name=id_jenis]').val(response.kode).trigger('change').select2({disabled:'readonly'});

                $('#frm-tanah [name=id_kontrak]').val(response.id_kontrak).trigger('change');
                $('#frm-tanah [name=tgl_sertifikat]').val(response.tgl_sertifikat);
                $('#frm-tanah [name=no_sertifikat]').val(response.no_sertifikat);
                $('#frm-tanah [name=harga]').val(response.harga);
                $('#frm-tanah [name=id_hak]').val(response.id_hak).trigger('change');
                $('#frm-tanah [name=kode]').val(response.kode);
                $('#frm-tanah [name=tahun]').val(response.tahun);
                $('#frm-tanah [name=penggunaan]').val(response.penggunaan);
                $('#frm-tanah [name=luas]').val(response.luas);
                $('#frm-tanah [name=register]').val(response.register);
                $('#frm-tanah [name=asal]').val(response.asal);
                $('#frm-tanah [name=alamat]').val(response.alamat);
                $('#frm-tanah [name=keterangan]').val(response.keterangan);
            })
            .fail((errors) => {
                alert('Gagl tampil data');
                return;
            })
    }

    function hapusTanah(url){
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

    function verifTanah(url){
        let cnfrm;
        if(user_akses == 'operator'){
            cnfrm = 'Yakin? data akan dikirimka untuk validasi.';
        }else if(user_akses == 'aset'){
            cnfrm = 'yakin data akan dicatat dalam KIB A';
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

    function tolakTanah(url){

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
        $('#form-tanah').hide();
        $('#frm-tanah')[0].reset();
    }

</script>
@endpush
