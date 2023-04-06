<!DOCTYPE html>
<html>

<head>
    <title>SKRIPSI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}" />


    {{-- Toastr js --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
</head>

<body>

    <div class="container">
        <div class="card mt-3 mb-3">
            <div class="card-header text-center">
                <h4>SKRIPSI</h4>
                <div class="justify-content-end">
                    <form action="/logout" method="post">
                        @csrf
                        <button type="submit" class="dropdown-item d-flex align-items-center">
                            <span>Keluar</span>
                        </button>
                    </form>
                </div>
            </div>
            <div class="card-body">

                <table class="table table-bordered mt-3">
                    <tr>
                        <th colspan="5">
                            List Of Data
                        </th>
                    </tr>
                    <tr>
                        <th>BDV</th>
                        <th>Water</th>
                        <th>ACID</th>
                        <th>IFT</th>
                        <th>Color</th>
                    </tr>
                    @if (session('data'))
                        @foreach (session('data') as $key => $var)
                            <tr>
                                <td>{{ $var[0] }}</td>
                                <td>{{ $var[1] }}</td>
                                <td>{{ $var[2] }}</td>
                                <td>{{ $var[3] }}</td>
                                <td>{{ $var[4] }}</td>
                            </tr>
                        @endforeach
                    @else
                        {{-- @foreach ($data as $key => $var) --}}
                        <tr>
                            {{-- <td>{{ $var[0] }}</td>
                                <td>{{ $var[1] }}</td>
                                <td>{{ $var[2] }}</td>
                                <td>{{ $var[3] }}</td>
                                <td>{{ $var[4] }}</td> --}}
                        </tr>
                        {{-- @endforeach --}}
                    @endif
                </table>

                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal"
                    title="Tambah Data Baru">
                    Tambah
                </button>
                <a href="{{ route('pso.hapus') }}" class="btn btn-warning" title="Bersihkan Data" id='hapus'
                    name='hapus'>
                    Bersihkan
                </a>
                </button>
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#hitungModal"
                    title="Tambah Data Baru">
                    Hitung
                </button>
            </div>
        </div>

        <!-- Modal Tambah -->
        <div class="modal fade" id="addModal" data-bs-backdrop="static" tabindex="-1" role="dialog"
            aria-labelledby="addModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Form Input</h5>
                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form class="row g-2 needs-validation" action="{{ route('pso.add') }}" method="post"
                        enctype="multipart/form-data" novalidate>
                        @csrf
                        <div class="modal-body">
                            <div class="col-md-12">
                                <label for="bdv" class="form-label">BDV</label>
                                <input type="text" class="form-control" id="bdv"
                                    onkeypress="return isNumberKey(this, event);" name="bdv">
                            </div>
                            <div class="col-md-12">
                                <label for="water" class="form-label">Water</label>
                                <input type="text" class="form-control" id="water"
                                    onkeypress="return isNumberKey(this, event);" name="water">
                            </div>
                            <div class="col-12">
                                <label for="acidity" class="form-label">Acidity</label>
                                <input type="text" class="form-control" id="acidity"
                                    onkeypress="return isNumberKey(this, event);" name="acidity">
                            </div>
                            <div class="col-12">
                                <label for="ift" class="form-label">IFT</label>
                                <input type="text" class="form-control" id="ift"
                                    onkeypress="return isNumberKey(this, event);" name="ift">
                            </div>
                            <div class="col-md-12">
                                <label for="color" class="form-label">Color</label>
                                <input type="text" class="form-control" id="color"
                                    onkeypress="return isNumberKey(this, event);" name="color">
                            </div>
                            <div class="col-md-12">
                                <label for="color" class="form-label">Target</label>
                                <input type="text" class="form-control" id="target"
                                    onkeypress="return isNumberKey(this, event);" name="target">
                            </div>
                            <div class="col-md-12">
                                <label for="inputEmail4" class="form-label">Berkas Excel</label>
                                <input type="file" name="file" class="form-control">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-success">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal hitung -->
        <div class="modal fade" id="hitungModal" data-bs-backdrop="static" tabindex="-1" role="dialog"
            aria-labelledby="addModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Form Hitung</h5>
                        <button class="btn-close" type="button" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <form class="row g-2 needs-validation" action="{{ route('pso.hitung') }}" method="get"
                        enctype="multipart/form-data" novalidate>
                        @csrf
                        <div class="modal-body">
                            <div class="col-md-12">
                                <label for="bdv" class="form-label">Jumlah Iterasi</label>
                                <input type="text" class="form-control" id="iter" name="iter">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-success">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.0.2/js/bootstrap.min.js"
        integrity="sha512-a6ctI6w1kg3J4dSjknHj3aWLEbjitAXAjLDRUxo2wyYmDFRcz2RJuQr5M3Kt8O/TtUSp8n2rAyaXYy1sjoKmrQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    {{-- Toastr js --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

    <script>
        @if (Session::has('message'))
            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                "positionClass": "toast-top-right"
            };
            toastr.success("{{ session('message') }}");
        @endif

        @if (Session::has('error'))
            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                "positionClass": "toast-top-right"
            };
            toastr.error("{{ session('error') }}");
        @endif

        @if (Session::has('info'))
            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                "positionClass": "toast-top-right"
            };
            toastr.info("{{ session('info') }}");
        @endif

        @if (Session::has('warning'))
            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                "positionClass": "toast-top-right"
            };
            toastr.warning("{{ session('warning') }}");
        @endif
    </script>

    <script type="text/javascript">
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
    </script>
</body>

</html>
