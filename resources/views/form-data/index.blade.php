@extends('layouts.default')

@section('page-style')
    <style>
        .upload__inputfile {
            width: 0.1px;
            height: 0.1px;
            opacity: 0;
            overflow: hidden;
            position: absolute;
            z-index: -1;
        }

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

        <div class="pagetitle">
            <h1>Form Input
                <a class="icon" href="#askModal" data-bs-toggle="modal" id="openModal" data-bs-target="#askModal">
                    <i class="bi bi-question-circle"></i>
                </a>
            </h1>
        </div><!-- End Page Title -->

        <section class="section profile">
            <div class="row">
                <div class="col-xxl-4 col-xl-4 col-md-4 col-sm-4">

                    <div class="card">
                        {{-- <div class="card-body"> --}}

                        <!-- Bordered Tabs -->
                        {{-- <ul class="nav nav-tabs nav-tabs-bordered" id="borderedTab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="home-tab" data-bs-toggle="tab"
                                        data-bs-target="#nilai-klasifikasi" type="button" role="tab"
                                        aria-controls="home" aria-selected="true">Skor Kualitas Minyak</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="profile-tab" data-bs-toggle="tab"
                                        data-bs-target="#nilai-asli" type="button" role="tab" aria-controls="profile"
                                        aria-selected="false">Nilai Hasil Pengujian</button>
                                </li>
                            </ul> --}}
                        {{-- <div class="tab-content" id="borderedTabContent">
                                <div class="tab-pane fade show active d-flex flex-column align-items-center pt-2"
                                    id="nilai-klasifikasi" role="tabpanel" aria-labelledby="home-tab"> --}}
                        <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">
                            <img src="{{ asset('build/assets/img/file.png') }}" class="img-preview"
                                style="width: 120px; height: 150px; object-fit: cover;" id="img-element">

                            <label for="file" id="excel_name">Nama Berkas</label>

                            <label class="btn btn-primary mt-2" id="prev_file">
                                Upload Berkas
                                <input type="file" name="file" class="upload__inputfile" id="up_file"
                                    accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
                                    onchange="previewFile()">
                            </label>
                        </div>
                        {{-- <div class="tab-pane fade d-flex flex-column align-items-center pt-2" id="nilai-asli"
                                    role="tabpanel" aria-labelledby="profile-tab" style="display: none">
                                    <img src="{{ asset('build/assets/img/file.png') }}" class="img-preview"
                                        style="width: 120px; height: 150px; object-fit: cover;" id="img-asli">

                                    <label for="file" id="excel_asli">Nama Berkas Asli</label>

                                    <label class="btn btn-primary mt-2" id="prev_asli">
                                        Upload Berkas Asli
                                        <input type="file" name="file_asli" class="upload__inputfile" id="up_file_asli"
                                            accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
                                            onchange="previewFileAsli()">
                                    </label>
                                </div> --}}
                        {{-- </div><!-- End Bordered Tabs --> --}}

                    </div>
                    {{-- </div> --}}

                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"> Berkas terakhir yang diunggah</h5>

                            <div class="activity">
                                <div class="activity-item d-flex">
                                    <i class='bi bi-circle-fill activity-badge text-success align-self-start'></i>
                                    <div class="activity-content">
                                        <label for="file" id="nama_berkas">
                                            {{-- @if (session()->has('nama_berkas')) --}}
                                            {{ session()->get('nama_berkas') }}
                                            {{-- @endif --}}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="col-xl-8 col-xl-8 col-md-8 col-sm-8">

                    <div class="card">
                        <div class="card-body">
                            <!-- Bordered Tabs -->
                            <ul class="nav nav-tabs nav-tabs-bordered" id="borderedTab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="inputKlas-tab" data-bs-toggle="tab"
                                        data-bs-target="#input-klasifikasi" type="button" role="tab"
                                        aria-controls="home" aria-selected="true">Skor Kualitas Minyak</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="inputAsli-tab" data-bs-toggle="tab"
                                        data-bs-target="#input-asli" type="button" role="tab" aria-controls="profile"
                                        aria-selected="false">Nilai Hasil
                                        Pengujian</button>
                                </li>
                            </ul>
                            <div class="tab-content" id="borderedTabContent">
                                <div class="tab-pane fade show active pt-2" id="input-klasifikasi" role="tabpanel"
                                    aria-labelledby="home-tab">
                                    <!-- Input Form Awal -->
                                    <form action="{{ route('pso.import') }}" method="post" enctype="multipart/form-data">
                                        @csrf
                                        <div class="row mb-3">
                                            <label for="bdv" class="col-md-4 col-lg-3 col-form-label">BDV</label>
                                            <div class="col-md-8 col-lg-9">
                                                <input name="bdv" type="text" class="form-control range-data"
                                                    id="bdv" onkeypress="return isNumberKey(this, event);"
                                                    placeholder="Masukkan nilai rentang 1-4">
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="water" class="col-md-4 col-lg-3 col-form-label">Water</label>
                                            <div class="col-md-8 col-lg-9">
                                                <input name="water" type="text" class="form-control range-data"
                                                    id="water" onkeypress="return isNumberKey(this, event);"
                                                    placeholder="Masukkan nilai rentang 1-4">
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="acidity" class="col-md-4 col-lg-3 col-form-label">Acidity</label>
                                            <div class="col-md-8 col-lg-9">
                                                <input name="acidity" type="text" class="form-control range-data"
                                                    id="acidity" onkeypress="return isNumberKey(this, event);"
                                                    placeholder="Masukkan nilai rentang 1-4">
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="ift" class="col-md-4 col-lg-3 col-form-label">IFT</label>
                                            <div class="col-md-8 col-lg-9">
                                                <input name="ift" type="text" class="form-control range-data"
                                                    id="ift" onkeypress="return isNumberKey(this, event);"
                                                    placeholder="Masukkan nilai rentang 1-4">
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="color" class="col-md-4 col-lg-3 col-form-label">Color</label>
                                            <div class="col-md-8 col-lg-9">
                                                <input name="color" type="text" class="form-control range-data"
                                                    id="color" onkeypress="return isNumberKey(this, event);"
                                                    placeholder="Masukkan nilai rentang 1-4">
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="target" class="col-md-4 col-lg-3 col-form-label">Target</label>
                                            <div class="col-md-8 col-lg-9">
                                                <input name="target" type="text" class="form-control range-target"
                                                    id="target" onkeypress="return isNumberKey(this, event);"
                                                    placeholder="Masukkan nilai rentang 1-4">
                                            </div>
                                        </div>

                                        <div class="align-content-between">
                                            <button type="submit" class="btn btn-success">Simpan</button>
                                            <button type="reset" class="btn btn-warning">Bersihkan</button>
                                        </div>
                                    </form><!-- End Input Form  Awal -->
                                </div>
                                <div class="tab-pane fade pt-2" id="input-asli" role="tabpanel"
                                    aria-labelledby="profile-tab">
                                    <!-- Input Form Awal -->
                                    <form action="{{ route('pso.import_asli') }}" method="post"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <div class="row mb-3">
                                            <label for="bdv" class="col-md-4 col-lg-3 col-form-label">BDV</label>
                                            <div class="col-md-8 col-lg-9">
                                                <input name="bdv_asli" type="text" class="form-control"
                                                    id="bdv_asli" onkeypress="return isNumberKey(this, event);"
                                                    placeholder="Masukkan nilai asli (belum klasifikasi)">
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="water" class="col-md-4 col-lg-3 col-form-label">Water</label>
                                            <div class="col-md-8 col-lg-9">
                                                <input name="water_asli" type="text" class="form-control"
                                                    id="water_asli" onkeypress="return isNumberKey(this, event);"
                                                    placeholder="Masukkan nilai asli (belum klasifikasi)">
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="acidity" class="col-md-4 col-lg-3 col-form-label">Acidity</label>
                                            <div class="col-md-8 col-lg-9">
                                                <input name="acidity_asli" type="text" class="form-control"
                                                    id="acidity_asli" onkeypress="return isNumberKey(this, event);"
                                                    placeholder="Masukkan nilai asli (belum klasifikasi)">
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="ift" class="col-md-4 col-lg-3 col-form-label">IFT</label>
                                            <div class="col-md-8 col-lg-9">
                                                <input name="ift_asli" type="text" class="form-control"
                                                    id="ift_asli" onkeypress="return isNumberKey(this, event);"
                                                    placeholder="Masukkan nilai asli (belum klasifikasi)">
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="color" class="col-md-4 col-lg-3 col-form-label">Color</label>
                                            <div class="col-md-8 col-lg-9">
                                                <input name="color_asli" type="text" class="form-control"
                                                    id="color_asli" onkeypress="return isNumberKey(this, event);"
                                                    placeholder="Masukkan nilai asli (belum klasifikasi)">
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="target" class="col-md-4 col-lg-3 col-form-label">Target</label>
                                            <div class="col-md-8 col-lg-9">
                                                <input name="target_asli" type="text" class="form-control"
                                                    id="target_asli" onkeypress="return isNumberKey(this, event);"
                                                    placeholder="Masukkan nilai asli (belum klasifikasi)">
                                            </div>
                                        </div>

                                        <div class="align-content-between">
                                            <button type="submit" class="btn btn-success">Simpan</button>
                                            <button type="reset" class="btn btn-warning">Bersihkan</button>
                                        </div>
                                    </form><!-- End Input Form  Awal -->
                                </div>
                            </div><!-- End Bordered Tabs -->
                        </div>
                    </div>

                </div>

                <div class="col-xl-12">
                    <br>
                    <br>
                    <br>
                </div>
            </div>
        </section>

        <div id="loading-spinner" class="spinner-overlay">
            <div class="spinner-border" style="width: 50px; height: 50px;" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>

        <!-- Modal hitung -->
        <div class="modal fade" id="askModal" data-bs-backdrop="static" tabindex="-1" role="dialog"
            aria-labelledby="addModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Petunjuk</h5>
                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Nilai Hasil
                            Pengujian merupakan nilai murni hasil pengujian yang belum diklasifikasikan</p>
                        <div class="alert alert-warning alert-dismissible fade show text-left" role="alert">
                            <i class="bi bi-exclamation-octagon me-1"></i>
                            <span class=""> contoh: bdv = 51.30, water = 18.99, acidity = 0.22, ift = 20.90, color =
                                60.30</span>
                        </div>
                        <p>Skor Kualitas Minyak merupakan nilai hasil
                            pengujian yang sudah diklasifikasikan</p>
                        <div class="alert alert-primary alert-dismissible fade show text-left" role="alert">
                            <i class="bi bi-exclamation-octagon me-1"></i>
                            <span class="">contoh: bdv = 1, water = 1, acidity = 4, ift = 3, color = 4</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Modal konfirmasi --}}
        <div class="modal fade" id="confirmModal" tabindex="-1" data-bs-backdrop="static">
            <div class="modal-dialog" role="dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Konfirmasi</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form class="row g-3 needs-validation" id="deleteAll-form" action="/" method="post" novalidate>
                        @csrf
                        <div class="modal-body">
                            <div class="alert alert-primary alert-dismissible fade show text-center" role="alert">
                                <i class="bi bi-exclamation-octagon me-1"></i>
                                <span class=""> Apakah nilai di berkas sudah diklasifikasikan?</span>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-success btn-ya">Sudah</button>
                            <button type="button" class="btn btn-warning btn-tdk">Belum</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main><!-- End #main -->
@endsection

@section('page-script')
    <script>
        function isNumberKey(txt, evt) {
            var charCode = (evt.which) ? evt.which : evt.keyCode;
            if (charCode == 46) {
                //Check if the text already contains the . character
                if (txt.value.indexOf('.') === -1) {
                    return true;
                } else {
                    return false;
                }
            } else {
                if (charCode > 31 &&
                    (charCode < 48 || charCode > 57))
                    return false;
            }
            return true;
        }

        function previewFile() {
            const excel = document.querySelector('#up_file');
            const excel_name = document.querySelector('#excel_name');

            var nama_berkas = document.querySelector('#nama_berkas');

            const reader = new FileReader();
            reader.readAsDataURL(excel.files[0]);

            var fileName = document.getElementById("up_file").value;
            var idxDot = fileName.lastIndexOf(".") + 1;
            var extFile = fileName.substr(idxDot, fileName.length).toLowerCase();

            if (extFile == "xlsx") {
                const reader = new FileReader();
                reader.readAsDataURL(excel.files[0]);

                upFile();
                excel_name.innerHTML = excel.files[0].name;
                nama_berkas.innerHTML = excel.files[0].name;
            } else {
                toastr.options = {
                    "closeButton": true,
                    "progressBar": true,
                    "positionClass": "toast-top-right"
                };
                toastr.warning("Hanya boleh mengunggah berkas excel dengan ekstensi .xlsx!");
            }
        }

        function upFile() {
            var formData = new FormData();
            var excel = $('#up_file')[0].files;
            formData.append('file', excel[0]);
            var url = "";

            $('#confirmModal').modal('show');

            $('#confirmModal .btn-ya').on('click', function() {
                url = "{{ route('pso.import') }}";
                executeAjax(url, formData);
            });

            $('#confirmModal .btn-tdk').on('click', function() {
                url = "{{ route('pso.import_asli') }}";
                formData.append('file_asli', excel[0]);
                executeAjax(url, formData);
            });

            function executeAjax(url, formData) {
                // Close the modal dialog
                $('#confirmModal').modal('hide');

                $.ajax({
                    type: 'POST',
                    url: url,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $('#loading-spinner').show();
                        $('body').css('pointer-events', 'none');
                    },
                    success: function(data) {
                        $('#loading-spinner').hide();
                        $('body').css('pointer-events', 'auto');
                        showSuccessToast();
                    },
                    error: function(data) {
                        $('#loading-spinner').hide();
                        $('body').css('pointer-events', 'auto');
                        showErrorToast(data.statusText);
                    }
                });
            }

            function showSuccessToast() {
                toastr.options = {
                    "closeButton": true,
                    "progressBar": true,
                    "positionClass": "toast-top-right"
                };
                toastr.success("Berhasil mengunggah berkas!");
            }

            function showErrorToast(statusText) {
                toastr.options = {
                    "closeButton": true,
                    "progressBar": true,
                    "positionClass": "toast-top-right"
                };
                toastr.error(statusText);
                console.log(statusText);
            }
        }
    </script>

    <script>
        const inputs = document.querySelectorAll('.range-data');

        // Add an input event listener to each input element
        inputs.forEach(function(input) {
            input.addEventListener('input', function(event) {
                const value = parseFloat(event.target.value);

                if (isNaN(value) || value < 1 || value > 4) {
                    // Clear the input value if it's not a valid number between 0 and 1
                    event.target.value = '';
                }
            });
        });
    </script>
    <script>
        const target = document.querySelectorAll('.range-target');

        // Add an input event listener to each input element
        target.forEach(function(input) {
            input.addEventListener('input', function(event) {
                const value = parseFloat(event.target.value);

                if (isNaN(value) || value < 0 || value > 4) {
                    // Clear the input value if it's not a valid number between 0 and 1
                    event.target.value = '';
                }
            });
        });
    </script>
    <script>
        window.onload = function() {
            $('#loading-spinner').hide();
            $('body').css('pointer-events', 'auto');
        }
    </script>
@endsection
