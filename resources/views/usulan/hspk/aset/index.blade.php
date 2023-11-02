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
                <h6 class="m-0 font-weight-bold text-primary">
                    RINCIAN HARGA SATUAN POKOK KEGIATAN (HSPK)
                    <a href="#" onclick="pilihTahun('{{ route('hspk.exportForm') }}')" class="btn btn-sm btn-danger btn-icon-split mt-2 float-right">
                        <span class="icon text-white-50">
                            <i class="fas fa-file-pdf"></i>
                        </span>
                        <span class="text">EXPORT</span>
                    </a>
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-3">
                        <div class="form-group">
                            <label for="opd">Cari Opd</label>
                            <input type="text" class="form-control" id="opd" aria-describedby="opdHelp">
                        </div>
                    </div>
                </div>

                <div id="results" class="row">
                    @foreach ($drops['instansi'] as $value)
                        <div id="result" class="col-xl-6 col-md-6 mb-4">
                            <a href="{{ route('hspk.instansi',encrypt($value->id)) }}" class="btn btn-block btn-outline-primary">
                                <div class="card border-left-primary shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">

                                                <div class="h5 mb-0 font-weight-bold text-gray-800 text-justify" style="font-size: 10pt"> {{ $value->opd }}</div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fas fa-folder fa-2x text-gray-300"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

    </div>
</div>

@includeIf('form.usulan.export')
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
        $(document).ready(function() {
            $('#modalExport').validator().on('submit', function (e){
                if(! e.preventDefault()){
                    $.post($('#modalExport form').attr('action'), $('#modalExport form').serialize())
                    .done((response) => {
                        console.log(response);
                        window.open("{{route('hspk.exportAset', ['',''])}}"+"/"+response.tahun+"/"+response.jenis,'Title','directories=no,titlebar=no,toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=1024,height = 720');
                        setTimeout(function(){
                            $('#modalExport').modal('hide');
                        },1000);
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
        $("#opd").keyup(function() {
            var filter = $(this).val(),
            count = 0;

            $('#results div').each(function() {
                if ($(this).text().search(new RegExp(filter, "i")) < 0) {
                    $(this).hide();  // MY CHANGE

                    // Show the list item if the phrase matches and increase the count by 1
                    } else {
                    $(this).show(); // MY CHANGE
                    count++;
                }
            });
        });

        function pilihTahun(url){
            $('#modalExport').modal('show');
            $('#modalExport .modal-title').text('Pilih tahun');

            $('#modalExport form')[0].reset();
            $('#modalExport form').attr('action',url);
            $('#modalExport [name=_method]').val('post');
            $('#modalExport [name=tahun]').val('');
            $('#modalExport').on('shown.bs.modal', function () {
                $('#tahun').focus();
            })
        }
    </script>
@endpush
