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
                    <a href="javascript:void(0)" onclick="window.open('{{ route('rincian.export',Request::segment(2)) }}','Title','directories=no,titlebar=no,toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=1024,height = 720');"
                    class="btn btn-sm btn-danger btn-icon-split mt-2">
                        <span class="icon text-white-50">
                            <i class="fas fa-file-pdf"></i>
                        </span>
                        <span class="text">EXPORT</span>
                    </a>
                    @if ($usulan_status == '0')
                        <a href="javascript:void(0)" onclick="addSsh('{{ route('rincian.store',decrypt(Request::segment(2))) }}')"
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
                                <th>Spesfikasi</th>
                                <th>Satuan</th>
                                <th>Harga</th>
                                <th>Rek 1</th>
                                <th>Rek 2</th>
                                <th>Rek 3</th>
                                <th>Rek 4</th>
                                <th>Rek 5</th>
                                <th>Rek 6</th>
                                <th>Rek 7</th>
                                <th>Rek 8</th>
                                <th>Rek 9</th>
                                <th>Rek 10</th>
                                <th>TKDN</th>
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
{{-- @includeIf('form.input.rekening') --}}
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

    .select2-selection--multiple{
        overflow: hidden !important;
        height: auto !important;
    }
</style>
@endpush

@push('scripits')
    <script src="{{ asset('themes/vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('themes/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.full.min.js"></script>
    <script src="{{ asset('js/validator.min.js') }}"></script>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        let id_usulan = '{{decrypt(Request::segment(2))}}';
        let table;
        $(document).ready(function() {
            $('#id_kode').select2({
                theme: 'bootstrap4',
            });
            $('#rek_1').select2({
                theme: 'bootstrap4',
            });
            $('#rek_2').select2({
                theme: 'bootstrap4',
            });
            $('#rek_3').select2({
                theme: 'bootstrap4',
            });
            $('#rek_4').select2({
                theme: 'bootstrap4',
            });
            $('#rek_5').select2({
                theme: 'bootstrap4',
            });
            $('#rek_6').select2({
                theme: 'bootstrap4',
            });
            $('#rek_7').select2({
                theme: 'bootstrap4',
            });
            $('#rek_8').select2({
                theme: 'bootstrap4',
            });
            $('#rek_9').select2({
                theme: 'bootstrap4',
            });
            $('#rek_10').select2({
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
                    type: 'POST',
                },
                columns:[
                    {data:'DT_RowIndex', searchable:false, sortable:false},
                    {data:'kode_barang'},
                    {data:'uraian_id'},
                    {data:'uraian'},
                    {data:'spesifikasi'},
                    {data:'satuan'},
                    {data:'harga'},
                    {data:'rek_1'},
                    {data:'rek_2'},
                    {data:'rek_3'},
                    {data:'rek_4'},
                    {data:'rek_5'},
                    {data:'rek_6'},
                    {data:'rek_7'},
                    {data:'rek_8'},
                    {data:'rek_9'},
                    {data:'rek_10'},
                    {data:'tkdn'},
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
                            $('#modalSsh [name=rek_1]').val('').trigger('change');
                            $('#modalSsh [name=rek_2]').val('').trigger('change');
                            $('#modalSsh [name=rek_3]').val('').trigger('change');
                            $('#modalSsh [name=rek_4]').val('').trigger('change');
                            $('#modalSsh [name=rek_5]').val('').trigger('change');
                            $('#modalSsh [name=rek_6]').val('').trigger('change');
                            $('#modalSsh [name=rek_7]').val('').trigger('change');
                            $('#modalSsh [name=rek_8]').val('').trigger('change');
                            $('#modalSsh [name=rek_9]').val('').trigger('change');
                            $('#modalSsh [name=rek_10]').val('').trigger('change');
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

            function dropRekening(selector){
            let target = $('#' + selector);
                target.focus();
                $.ajax({
                    url: "{{ route('rekening_belanja.dropdown') }}",
                    type: 'GET',
                    success: function(data) {
                        target.empty()
                        target.attr('placeholder', target.data('placeholder'))
                        target.append(`<option> ${target.data('placeholder')} </option>`)
                        $.each(data, function(key, value) {
                            target.append(`<option value="${value.id}">${value.kode_akun} - ${value.nm_akun}</option>`)
                        });
                    }
                });
        }

            $('#modalSsh #rek_1').on('change', function() {
                $('#modalSsh .rek_2').show();
                dropRekening('modalSsh #rek_2');
                $('#modalSsh .rek_3').hide();
                $('#modalSsh .rek_4').hide();
                $('#modalSsh .rek_5').hide();
                $('#modalSsh .rek_6').hide();
                $('#modalSsh .rek_7').hide();
                $('#modalSsh .rek_8').hide();
                $('#modalSsh .rek_9').hide();
                $('#modalSsh .rek_10').hide();
            });

            $('#modalSsh #rek_2').on('change', function() {
                $('#modalSsh .rek_3').show();
                dropRekening('modalSsh #rek_3');
                $('#modalSsh .rek_4').hide();
                $('#modalSsh .rek_5').hide();
                $('#modalSsh .rek_6').hide();
                $('#modalSsh .rek_7').hide();
                $('#modalSsh .rek_8').hide();
                $('#modalSsh .rek_9').hide();
                $('#modalSsh .rek_10').hide();
            });

            $('#modalSsh #rek_3').on('change', function() {
                $('#modalSsh .rek_4').show();
                dropRekening('modalSsh #rek_4');
                $('#modalSsh .rek_5').hide();
                $('#modalSsh .rek_6').hide();
                $('#modalSsh .rek_7').hide();
                $('#modalSsh .rek_8').hide();
                $('#modalSsh .rek_9').hide();
                $('#modalSsh .rek_10').hide();
            });

            $('#modalSsh #rek_4').on('change', function() {
                $('#modalSsh .rek_5').show();
                dropRekening('modalSsh #rek_5');
                $('#modalSsh .rek_6').hide();
                $('#modalSsh .rek_7').hide();
                $('#modalSsh .rek_8').hide();
                $('#modalSsh .rek_9').hide();
                $('#modalSsh .rek_10').hide();
            });

            $('#modalSsh #rek_5').on('change', function() {
                $('#modalSsh .rek_6').show();
                dropRekening('modalSsh #rek_6');
                $('#modalSsh .rek_7').hide();
                $('#modalSsh .rek_8').hide();
                $('#modalSsh .rek_9').hide();
                $('#modalSsh .rek_10').hide();
            });

            $('#modalSsh #rek_6').on('change', function() {
                $('#modalSsh .rek_7').show();
                dropRekening('modalSsh #rek_7');
                $('#modalSsh .rek_8').hide();
                $('#modalSsh .rek_9').hide();
                $('#modalSsh .rek_10').hide();
            });

            $('#modalSsh #rek_7').on('change', function() {
                $('#modalSsh .rek_8').show();
                dropRekening('modalSsh #rek_8');
                $('#modalSsh .rek_9').hide();
                $('#modalSsh .rek_10').hide();
            });

            $('#modalSsh #rek_8').on('change', function() {
                $('#modalSsh .rek_9').show();
                dropRekening('modalSsh #rek_9');
                $('#modalSsh .rek_10').hide();
            });

            $('#modalSsh #rek_9').on('change', function() {
                $('#modalSsh .rek_10').show();
                dropRekening('modalSsh #rek_10');
            });


        });


        function dropRekening(selector){
            let target = $('#' + selector);
                target.focus();
                $.ajax({
                    url: "{{ route('rekening_belanja.dropdown') }}",
                    type: 'GET',
                    success: function(data) {
                        target.empty()
                        target.attr('placeholder', target.data('placeholder'))
                        target.append(`<option> ${target.data('placeholder')} </option>`)
                        $.each(data, function(key, value) {
                            target.append(`<option value="${value.id}">${value.kode_akun} - ${value.nm_akun}</option>`)
                        });
                    }
                });
        }

        function addSsh(url){
            $('#modalSsh').modal('show');
            $('#modalSsh .modal-title').text('Tambah Rincian Usulan');

            $('#modalSsh form')[0].reset();
            $('#modalSsh form').attr('action',url);
            $('#modalSsh [name=_method]').val('post');
            $('#modalSsh [name=jenis]').val('');
            $('#modalSsh').on('shown.bs.modal', function () {
                dropRekening('modalSsh #rek_1');
                $('#modalSsh .rek_2').hide();
                $('#modalSsh .rek_3').hide();
                $('#modalSsh .rek_4').hide();
                $('#modalSsh .rek_5').hide();
                $('#modalSsh .rek_6').hide();
                $('#modalSsh .rek_7').hide();
                $('#modalSsh .rek_8').hide();
                $('#modalSsh .rek_9').hide();
                $('#modalSsh .rek_10').hide();

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
                $('#modalSsh #id_kode').focus();
                dropRekening('modalSsh #rek_1');
                $('#modalSsh .rek_2').hide();
                $('#modalSsh .rek_3').hide();
                $('#modalSsh .rek_4').hide();
                $('#modalSsh .rek_5').hide();
                $('#modalSsh .rek_6').hide();
                $('#modalSsh .rek_7').hide();
                $('#modalSsh .rek_8').hide();
                $('#modalSsh .rek_9').hide();
                $('#modalSsh .rek_10').hide();
            })

            let show = "{{route('rincian.show', '')}}"+"/"+id;
            $.get(show)
            .done((response) => {
                $('#modalSsh [name=id_kode]').val(response.id_kode).trigger('change');
                $('#modalSsh [name=spesifikasi]').val(response.spesifikasi);
                $('#modalSsh [name=uraian]').val(response.uraian);
                $('#modalSsh [name=id_satuan]').val(response.id_satuan).trigger('change');
                $('#modalSsh [name=harga]').val(response.harga);
                $('#modalSsh [name=tkdn]').val(response.tkdn);
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

        function hapusRekening(url){
            if (confirm('Yakin ingin menghapus rekening terpilih?')) {
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
