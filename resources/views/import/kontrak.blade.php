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
                <h6 class="m-0 font-weight-bold text-primary">Import Referensi Kode Barang Kontrak</h6>
            </div>
            <div class="card-body">
                <form class="form-import" enctype="multipart/form-data" action="{{ route('import.kontrak') }}" method="POST">
                    @csrf
                    @method('post')
                    <div class="input-group">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="dok_kode" name="dok_kode" aria-describedby="dok_kode">
                            <label class="custom-file-label" for="dok_kode">Choose file</label>
                        </div>
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="button" id="dok_kode">Button</button>
                        </div>
                    </div>
                    <div class="form-group form-check mt-4">
                        <input type="checkbox" class="form-check-input" id="exampleCheck1" required>
                        <label class="form-check-label" for="exampleCheck1">Yakin ?</label>
                    </div>

                    @if (session('message'))
                        <div class="alert alert-info"  role="alert">{{ session('message') }}</div>
                    @endif
                    <button type="submit" class="btn btn-sm btn-primary">Import</button>
                </form>
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

@endpush

@push('scripits')
    <script src="{{ asset('themes/vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('themes/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.full.min.js"></script>
    <script src="https://unpkg.com/gijgo@1.9.14/js/gijgo.min.js" type="text/javascript"></script>
    <script src="{{ asset('js/validator.min.js') }}"></script>

    <script>

    </script>
@endpush
