@extends('layouts.default')

@section('page-style')
@endsection

@section('content')
    <main class="main" id="main">
        <div class="pagetitle">
            <h1>Bobot Hasil Perhitungan</h1>
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
                        <h5 class="card-title">Nilai gBest <span>| {{ $data['iterasi'] }} iterasi</span> </h5>
                        <div class="row mx-auto mt-1">
                            <div class="col-12">
                                <table class="table table-hover" id="table-fitness">
                                    <thead>
                                        <tr>
                                            <th scope="col">Keterangan</th>
                                            <th scope="col">Nilai</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach (session()->get('g_best') as $value)
                                            <tr>
                                                <td>Iterasi ke-{{ $loop->iteration }}</td>
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
                                <p>Berdasarkan perhitungan menggunakan Algoritma Particle Swarm Optimization
                                    didapatkan hasil yang ditampilkan pada table di samping dengan nilai gBest:</p>
                                <h3 class="text-primary">{{ $data['GBest'] }}</h3>
                                <p>Dengan iterasi sebanyak
                                    <span class="text-warning">{{ $data['iterasi'] }}</span>
                                </p>
                                <p>waktu eksekusi
                                    <span class="text-success">{{ session()->get('exeTime') }}</span> detik
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer mx-3">
                        <form action="{{ route('view-pdf') }}" method="post">
                            @csrf
                            <button type="submit" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"
                                title="Cetak PDF">
                                Cetak PDF
                            </button>
                            <a href="/table-data" class="d-none d-sm-inline-block btn btn-sm btn-warning shadow-sm">
                                Kembali
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
                        text: 'Download Excel'
                    }],
                    dom: {
                        button: {
                            className: 'btn'
                        }
                    }
                },
            });
        });
    </script>
@endsection
