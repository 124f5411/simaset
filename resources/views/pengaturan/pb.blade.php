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
                <h6 class="m-0 font-weight-bold text-primary">Data Pengguna Barang</h6>
                @php
                    if($data->get()->count() < 1){
                        $uri = route('ttd.store');
                    }else{
                        $uri = route('ttd.update',Auth::user()->id_opd);
                    }
                @endphp
            </div>
            <div class="card-body">
                <form id="ttd-seting" class="ttd-seting" enctype="multipart/form-data">
                    @csrf
                    @method(($data->get()->count() == 1) ? "put" : "post")
                        <div class="form-group">
                            <div class="alert " role="alert" style="display: none">
                                <p id="massages"></p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="nm_pimp">Nama</label>
                            <input type="text" class="form-control" id="nm_pimp" value="{{ ($data->get()->count() == 1) ? $data->first()->nm_pimp : ''  }}" name="nm_pimp" required>
                        </div>
                        <div class="form-group">
                            <label for="nm_pimp">NIP</label>
                            <input type="text" class="form-control" id="nip" name="nip" value="{{ ($data->get()->count() == 1) ? $data->first()->nip : ''  }}" required>
                        </div>
                        <div class="form-group">
                            <label for="pangkat">Pangkat</label>
                            <input type="text" class="form-control" id="pangkat" name="pangkat" value="{{ ($data->get()->count() == 1) ? $data->first()->pangkat : ''  }}" required>
                        </div>
                        <div class="form-group">
                            <label for="golongan">Golongan</label>
                            <input type="text" class="form-control" id="golongan" name="golongan" value="{{ ($data->get()->count() == 1) ? $data->first()->golongan : ''  }}" required>
                        </div>
                        <div class="form-group form-check">
                            <input type="checkbox" class="form-check-input" id="yakin" required>
                            <label class="form-check-label" for="yakin">Yakin</label>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan</button>

                </form>
            </div>
        </div>

    </div>
</div>

@endsection

@push('css')

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css">
    <style>
        .table td, .table th {
            font-size: 10pt;
        }
    </style>
@endpush

@push('scripits')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.full.min.js"></script>
    <script src="{{ asset('js/validator.min.js') }}"></script>

    <script>
        let uri = '{{ $uri }}';
        let table;
        $(document).ready(function() {
            $('#ttd-seting').on('submit', function(e){
                e.preventDefault();
                $.post(uri, $('#ttd-seting').serialize())
                .done((response) => {
                    $(".alert" ).addClass( "alert-success" );
                    $(".alert").show();
                    $("#massages").append(response);
                    location.reload();
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

    </script>
@endpush
