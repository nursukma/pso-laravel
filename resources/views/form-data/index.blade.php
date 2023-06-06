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
    </style>
@endsection

@section('content')
    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Form Input</h1>
        </div><!-- End Page Title -->

        <section class="section profile">
            <div class="row">
                <div class="col-xxl-4 col-xl-4 col-md-4 col-sm-4">

                    <div class="card">
                        <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">

                            <img src="{{ asset('build/assets/img/file.png') }}" class="img-preview"
                                style="width: 120px; height: 150px; object-fit: cover">

                            <label for="file" id="excel_name">Nama Berkas</label>

                            <label class="btn btn-primary mt-2">
                                Upload Berkas
                                <input type="file" name="file" class="upload__inputfile" id="up_file"
                                    accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
                                    onchange="previewFile()">
                            </label>
                        </div>
                    </div>

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
                        <div class="card-body pt-3">
                            <!-- Input Form Awal -->
                            <form action="{{ route('pso.import') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="row mb-3">
                                    <label for="bdv" class="col-md-4 col-lg-3 col-form-label">BDV</label>
                                    <div class="col-md-8 col-lg-9">
                                        <input name="bdv" type="text" class="form-control range-data" id="bdv"
                                            onkeypress="return isNumberKey(this, event);"
                                            placeholder="Masukkan nilai rentang 1-4">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="water" class="col-md-4 col-lg-3 col-form-label">Water</label>
                                    <div class="col-md-8 col-lg-9">
                                        <input name="water" type="text" class="form-control range-data" id="water"
                                            onkeypress="return isNumberKey(this, event);"
                                            placeholder="Masukkan nilai rentang 1-4">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="acidity" class="col-md-4 col-lg-3 col-form-label">Acidity</label>
                                    <div class="col-md-8 col-lg-9">
                                        <input name="acidity" type="text" class="form-control range-data" id="acidity"
                                            onkeypress="return isNumberKey(this, event);"
                                            placeholder="Masukkan nilai rentang 1-4">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="ift" class="col-md-4 col-lg-3 col-form-label">IFT</label>
                                    <div class="col-md-8 col-lg-9">
                                        <input name="ift" type="text" class="form-control range-data" id="ift"
                                            onkeypress="return isNumberKey(this, event);"
                                            placeholder="Masukkan nilai rentang 1-4">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="color" class="col-md-4 col-lg-3 col-form-label">Color</label>
                                    <div class="col-md-8 col-lg-9">
                                        <input name="color" type="text" class="form-control range-data" id="color"
                                            onkeypress="return isNumberKey(this, event);"
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
                    </div>

                </div>

                <div class="col-xl-12">
                    <br>
                    <br>
                    <br>
                </div>
            </div>
        </section>

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
            formData.append('file', excel[0])

            $.ajax({
                type: 'POST',
                url: "{{ route('pso.import') }}",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: (data) => {
                    toastr.options = {
                        "closeButton": true,
                        "progressBar": true,
                        "positionClass": "toast-top-right"
                    };
                    toastr.success("Berhasil mengunggah berkas!");
                },
                error: function(data) {
                    toastr.error(data.message);
                    console.log(data)
                }
            });
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
@endsection
