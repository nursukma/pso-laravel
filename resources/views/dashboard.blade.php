@extends('layouts.default')

@section('content')
    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Dashboard</h1>
        </div><!-- End Page Title -->

        <div class="section dashboard">
            <div class="row">
                <div class="col-xxl-8 col-xl-8 col-md-8 col-sm-8">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><span>@lang('dashboard.trafo_title')</span></h5>
                            <p>@lang('dashboard.trafo_info')</p>

                            <h5 class="card-title">Particle Swarm Optimization </span></h5>
                            <p>@lang('dashboard.pso_info')</p>

                            <h5 class="card-title">@lang('dashboard.info_title')</span></h5>
                            <ol>
                                <ul>
                                    <li>@lang('dashboard.info_s')</li>
                                    <li>@lang('dashboard.info_d')</li>
                                    <li>@lang('dashboard.info_t')</li>
                                    <li>@lang('dashboard.info_e')</li>
                                    {{-- <li>@dd(session()->get('history'))</li> --}}
                                </ul>
                            </ol>
                        </div>
                    </div>

                </div>
                <div class="col-xxl-4 col-xl-4 col-md-4 col-sm-4">
                    <div class="card">
                        <div class="filter">
                            <a class="icon" href="#" data-bs-toggle="dropdown"><i
                                    class="bi bi-question-circle"></i></a>
                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                <li class="dropdown-header text-start">
                                    <h6>Contoh Format Excel</h6>
                                </li>

                                <li><a class="dropdown-item" href="{{ route('download-contoh') }}">Unduh</a></li>
                            </ul>
                        </div>
                        <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">
                            {{-- <img src="{{ asset('storage/logo.png') }}" class="img-preview"
                                style="width: 120px; height: 150px; object-fit: cover" /> --}}

                            <img src="{{ asset('storage/polinema.png') }}" class="img-preview"
                                style="width: 200px; height: 250px; object-fit: contain" />

                            <label for="img" id="img">AW Automation</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@section('page-script')
    {{-- <script type="text/javascript">
        document.addEventListener("DOMContentLoaded", () => {
            var data = '{{ Session::get('g_best') }}'
            var index = [];
            if (data.length !== 0) {
                data.forEach(key, el => {
                    index.push(key)
                });
            }

            new ApexCharts(document.querySelector("#chart"), {
                series: [{
                    name: "Desktops",
                    data: data
                }],
                chart: {
                    height: 350,
                    type: 'line',
                    zoom: {
                        enabled: false
                    }
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: 'straight'
                },
                grid: {
                    row: {
                        colors: ['#f3f3f3',
                            'transparent'
                        ], // takes an array which will be repeated on columns
                        opacity: 0.5
                    },
                },
                xaxis: {
                    // categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep'],
                    categories: index,
                }
            }).render();
        });
    </script> --}}
@endsection
