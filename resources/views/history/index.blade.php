@extends('layouts.default')

@section('page-style')
    <style>
        /* Style the loading spinner overlay */
        .spinner-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }

        /* Style the loading spinner */
        .spinner-border {
            color: #ffffff;
            /* Adjust the color as needed */
        }
    </style>
@endsection

@section('content')
    <main id="main" class="main">
        <div id="loading-spinner" class="spinner-overlay">
            <div class="spinner-border" style="width: 50px; height: 50px;" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>

        <div class="section dashboard">
            <div class="row">
                <div class="col-xxl-8 col-xl-8 col-md-12 col-sm-12 mx-auto">
                    <div class="card">
                        <div class="card-body">
                            @if (app()->getLocale() == 'id')
                                <h5 class="card-title">Log Aktivitas <span>| Hari ini</span></h5>
                            @else
                                <h5 class="card-title">Activity Log <span>| Today</span></h5>
                            @endif
                            <div class="activity mt-3">
                                <table id="activity-table" class="table table-hover datatable">
                                    <tbody>
                                        @foreach ($data['waktu'] as $index => $waktu)
                                            <tr>
                                                <td class="text-center">{{ $waktu }}</td>
                                                <td class="text-start">
                                                    @if ($data['aktivitas'][$index] === 'Login')
                                                        <i
                                                            class='bi bi-circle-fill activity-badge text-success align-self-start'></i>
                                                        @if (app()->getLocale() == 'id')
                                                            <a href="#" class="fw-bold text-success">Masuk</a> ke
                                                            sistem
                                                        @else
                                                            <a href="#" class="fw-bold text-success">Login</a> to
                                                            system
                                                        @endif
                                                    @elseif ($data['aktivitas'][$index] === 'Upload')
                                                        <i
                                                            class='bi bi-circle-fill activity-badge text-warning align-self-start'></i>
                                                        @if (app()->getLocale() == 'id')
                                                            <a href="#" class="fw-bold text-warning">Unggah</a> berkas
                                                            ke sistem
                                                        @else
                                                            <a href="#" class="fw-bold text-warning">Uploade</a> file
                                                            to
                                                            system
                                                        @endif
                                                    @elseif ($data['aktivitas'][$index] === 'Input')
                                                        <i
                                                            class='bi bi-circle-fill activity-badge text-info align-self-start'></i>
                                                        @if (app()->getLocale() == 'id')
                                                            <a href="#" class="fw-bold text-info">Memasukkan</a> data
                                                            ke sistem
                                                        @else
                                                            <a href="#" class="fw-bold text-info">Save Data</a> to
                                                            system
                                                        @endif
                                                    @elseif ($data['aktivitas'][$index] === 'Hitung')
                                                        <i
                                                            class='bi bi-circle-fill activity-badge text-primary align-self-start'></i>
                                                        @if (app()->getLocale() == 'id')
                                                            Melakukan proses <a href="#"
                                                                class="fw-bold text-primary">perhitungan</a>
                                                        @else
                                                            Using <a href="#"
                                                                class="fw-bold text-primary">calculation</a>
                                                        @endif
                                                    @elseif ($data['aktivitas'][$index] === 'Hapus')
                                                        <i
                                                            class='bi bi-circle-fill activity-badge text-danger align-self-start'></i>
                                                        @if (app()->getLocale() == 'id')
                                                            <a href="#" class="fw-bold text-danger">Menghapus</a> data
                                                        @else
                                                            <a href="#" class="fw-bold text-danger">Delete</a> data
                                                        @endif
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            {{-- {{ dd(session()->all()) }} --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@section('page-script')
    <script>
        window.onload = function() {
            $('#loading-spinner').hide();
            $('body').css('pointer-events', 'auto');
        }
    </script>
@endsection
