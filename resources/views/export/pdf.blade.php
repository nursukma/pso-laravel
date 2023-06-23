<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    {{-- <title>{{ $site->nama_situs }}</title> --}}
    <title>SKRIPSI</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    {{-- <link href="{{ asset('build/assets/img/favicon.png') }}" rel="icon"> --}}
    {{-- <link href="{{ asset('storage/' . $site->image) }}" rel="apple-touch-icon"> --}}
    {{-- <link href="{{ asset('build/assets/img/apple-touch-icon.png') }}" rel="apple-touch-icon"> --}}

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">

    <!-- Vendor CSS Files -->
    {{-- <link href="{{ asset('build/assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('build/assets/vendor/simple-datatables/style.css') }}" rel="stylesheet"> --}}

    <!-- Template Main CSS File -->
    {{-- <link href="{{ asset('build/assets/css/style.css') }}" rel="stylesheet"> --}}
    {{-- <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
    <link type="text/css" href="css/style.css" rel="stylesheet" /> --}}
    <style>
        /* PDF Styles */
        body {
            font-family: Arial, sans-serif;
        }

        h3 {
            color: #337ab7;
        }

        .text-primary {
            color: #337ab7;
        }

        .text-warning {
            color: #ffc107;
        }

        .text-success {
            color: #38e268;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table td {
            padding: 8px;
            border: 1px solid #ccc;
        }

        .table tr.table-primary td {
            background-color: #f0f8ff;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        {{-- @include('partials.sidebar') --}}

        <div class="main">

            <main class="content">
                <div class="container-fluid">
                    <main class="main" id="main">
                        <div class="pagetitle">
                            <h1>Bobot Hasil Perhitungan</h1>
                        </div>

                        <div class="row">
                            <!-- Tables -->
                            <div class="col-xl-12 col-lg-12">
                                <div class="card shadow mb-4">
                                    <!-- Card Body -->
                                    <div class="card-body">
                                        <div class="row mx-auto mt-1">
                                            <div class="col-12">
                                                <p>Berdasarkan perhitungan menggunakan Algoritma Particle Swarm
                                                    Optimization
                                                    didapatkan hasil yang ditampilkan pada table di bawah dengan nilai
                                                    gBest:</p>
                                                <h3 class="text-primary">{{ $data['GBest'] }}</h3>
                                                <p>Dengan iterasi sebanyak
                                                    <span class="text-warning">{{ $data['iterasi'] }}</span>
                                                    <br><br>
                                                    Waktu proses <span
                                                        class="text-success">{{ session()->get('exeTime') }}</span>
                                                    detik
                                                </p>
                                            </div>
                                            <div class="col-12">
                                                <h5 class="card-title"><span>Tabel Solusi</span></h5>
                                                <table class="table table-bordered" id="table-hasil">
                                                    <tbody>
                                                        <tr class="table-primary">

                                                            <td>BDV</td>
                                                            <td>Acid</td>
                                                            <td>Water</td>
                                                            <td>IFT</td>
                                                            <td>Color</td>
                                                        </tr>
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
                            </div>
                        </div>
                    </main>
                </div>
            </main>
        </div>
    </div>


    <!-- Vendor JS Files -->

    <script src="{{ asset('build/assets/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('build/assets/vendor/simple-datatables/simple-datatables.js') }}"></script>


    <!-- Template Main JS File -->
    <script src="{{ asset('build/assets/js/main.js') }}"></script>

</body>

</html>
