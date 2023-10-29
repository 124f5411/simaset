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
                    <h6 class="m-0 font-weight-bold text-primary">Pilih OPD</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered" id="dataOpd" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Organisasi Perangkat Daerah</th>
                                    <th></th>
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

    @endpush

    @push('scripits')
        <script src="{{ asset('themes/vendor/datatables/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('themes/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
        <script src="{{ asset('js/validator.min.js') }}"></script>

        <script>
            let table;
            $(document).ready(function() {
                table = $('#dataOpd').DataTable({
                    responsive: true,
                    processing: true,
                    serverSide: true,
                    autoWidth: false,
                    ajax:{
                        url: '{{ route('instansi.data') }}'
                    },
                    columns:[
                        {data:'DT_RowIndex', searchable:false, sortable:false},
                        {data:'opd'},
                        {data:'detail', searchable:false, sortable:false},
                    ]
                });
            });
        </script>
    @endpush
