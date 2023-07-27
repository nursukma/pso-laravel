@extends('layouts.default')

@section('page-style')
@endsection

@section('content')
    <main class="main" id="main">
        <div class="pagetitle">
            <h1>@lang('page_hasil.title')</h1>
        </div>

        <div class="row">
            <!-- Tables -->
            <div class="col-xl-7 col-lg-7">
                <div class="card shadow mb-4">
                    <!-- Card Body -->
                    <div class="card-body">
                        <div class="row mx-auto mt-1">
                            <div class="col-12">
                                <table class="table table-hover" id="table-hasil">
                                    <tbody>
                                        <tr>
                                            @foreach ($data['Solusi'] as $value)
                                                <td>{{ $value }}</td>
                                            @endforeach
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow mb-4">
                    <!-- Card Body -->
                    <div class="card-body">
                        <h5 class="card-title">@lang('page_hasil.gBest_t1') <span>| {{ $data['iterasi'] }} @lang('page_hasil.gBest_t2')</span>
                        </h5>
                        <div class="row mx-auto mt-1">
                            <div class="col-12">
                                <table class="table table-hover" id="table-fitness">
                                    <thead>
                                        <tr>
                                            <th scope="col">@lang('page_hasil.dt_ket')</th>
                                            <th scope="col">@lang('page_hasil.dt_nilai')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach (session()->get('g_best') as $value)
                                            <tr>
                                                <td>@lang('page_hasil.iter_ke'){{ $loop->iteration }}</td>
                                                <td>{{ $value }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    {{-- <div class="card-footer">
                        <button type="button" class="btn btn-primary" id="btn-excel">Ekspor tabel ke excel</button>
                    </div> --}}
                </div>
            </div>

            <div class="col-xl-5 col-lg-5">
                <div class="card shadow mb-4">
                    <!-- Card Body -->
                    <div class="card-body">
                        <div class="row mx-auto mt-1">
                            <div class="col-12">
                                <p>@lang('page_hasil.label_cs')</p>
                                <h3 class="text-primary">{{ $data['GBest'] }}</h3>
                                <p>@lang('page_hasil.label_cs2')
                                    <span class="text-warning"> {{ $data['iterasi'] }} </span> @lang('page_hasil.label_cs21')

                                </p>
                                <p>@lang('page_hasil.label_cs3')
                                    <span class="text-success">{{ session()->get('exeTime') }}</span> @lang('page_hasil.label_waktu')
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer mx-3">
                        <form action="{{ route('view-pdf') }}" method="post">
                            @csrf
                            <button type="submit" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"
                                title="Cetak PDF">
                                @lang('page_hasil.btn_ex_pdf')
                            </button>
                            <a href="/table-data" class="d-none d-sm-inline-block btn btn-sm btn-warning shadow-sm">
                                @lang('page_hasil.btn_back')
                            </a>
                        </form>

                    </div>
                </div>

                <div class="col-12">
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                </div>
            </div>
    </main>
@endsection

@section('page-script')
    <script>
        $(document).ready(function() {
            var lang = "{{ app()->getLocale() }}";

            if (lang === 'id') {
                var table = $('#table-fitness').DataTable({
                    dom: 'Bfrtip',
                    "searching": false,
                    "ordering": false,
                    "language": {
                        "sEmptyTable": "Tidak ada data yang tersedia",
                        "sInfo": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                        "sInfoEmpty": "Menampilkan 0 sampai 0 dari 0 data",
                        "lengthMenu": "Menampilkan _MENU_ data",
                        "oPaginate": {
                            "sFirst": "<<",
                            "sPrevious": "<",
                            "sNext": ">",
                            "sLast": ">>"
                        },
                    },
                    buttons: {
                        buttons: [{
                            extend: 'excel',
                            className: 'btn-primary',
                            text: 'Unduh Excel'
                        }],
                        dom: {
                            button: {
                                className: 'btn'
                            }
                        }
                    },
                });
            } else {
                var table = $('#table-fitness').DataTable({
                    dom: 'Bfrtip',
                    "searching": false,
                    "ordering": false,
                    "language": {
                        "oPaginate": {
                            "sFirst": "<<",
                            "sPrevious": "<",
                            "sNext": ">",
                            "sLast": ">>"
                        },
                    },
                    buttons: {
                        buttons: [{
                            extend: 'excel',
                            className: 'btn-primary',
                            text: 'Download Excel'
                        }],
                        dom: {
                            button: {
                                className: 'btn'
                            }
                        }
                    },
                });
            }
        });
    </script>
@endsection
