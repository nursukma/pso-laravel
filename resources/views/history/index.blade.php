@extends('layouts.default')

@section('content')
    <main id="main" class="main">

        <div class="section dashboard">
            <div class="row">
                <div class="col-xxl-8 col-xl-8 col-md-12 col-sm-12 mx-auto">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Activity Log <span>| Today</span></h5>
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
                                                        <a href="#" class="fw-bold text-success">Login</a> ke sistem
                                                    @elseif ($data['aktivitas'][$index] === 'Upload')
                                                        <i
                                                            class='bi bi-circle-fill activity-badge text-warning align-self-start'></i>
                                                        <a href="#" class="fw-bold text-warning">Upload</a> berkas ke
                                                        sistem
                                                    @elseif ($data['aktivitas'][$index] === 'Input')
                                                        <i
                                                            class='bi bi-circle-fill activity-badge text-info align-self-start'></i>
                                                        <a href="#" class="fw-bold text-info">Memasukkan</a> data ke
                                                        sistem
                                                    @elseif ($data['aktivitas'][$index] === 'Hitung')
                                                        <i
                                                            class='bi bi-circle-fill activity-badge text-primary align-self-start'></i>
                                                        Melakukan proses <a href="#"
                                                            class="fw-bold text-primary">perhitungan</a>
                                                    @elseif ($data['aktivitas'][$index] === 'Hapus')
                                                        <i
                                                            class='bi bi-circle-fill activity-badge text-danger align-self-start'></i>
                                                        <a href="#" class="fw-bold text-danger">Menghapus</a> data
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
