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
            <div class="card-header">
                <h4 class="text-center">SKRIPSI</h4>
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
                {{-- @dd(session()->get('login')) --}}
                <form class="row g-3" action="{{ route('pso.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="col-md-2">
                        <label for="bdv" class="form-label">BDV</label>
                        <input type="text" class="form-control" id="bdv"
                            onkeypress="return isNumberKey(this, event);" name="bdv">
                    </div>
                    <div class="col-md-2">
                        <label for="water" class="form-label">Water</label>
                        <input type="text" class="form-control" id="water"
                            onkeypress="return isNumberKey(this, event);" name="water">
                    </div>
                    <div class="col-md-2">
                        <label for="acidity" class="form-label">Acidity</label>
                        <input type="text" class="form-control" id="acidity"
                            onkeypress="return isNumberKey(this, event);" name="acidity">
                    </div>
                    <div class="col-md-2">
                        <label for="ift" class="form-label">IFT</label>
                        <input type="text" class="form-control" id="ift"
                            onkeypress="return isNumberKey(this, event);" name="ift">
                    </div>
                    <div class="col-md-2">
                        <label for="color" class="form-label">Color</label>
                        <input type="text" class="form-control" id="color"
                            onkeypress="return isNumberKey(this, event);" name="color">
                    </div>
                    <div class="col-md-2">
                        <label for="color" class="form-label">Target</label>
                        <input type="text" class="form-control" id="target"
                            onkeypress="return isNumberKey(this, event);" name="target">
                    </div>
                    <div class="col-md-12">
                        <label for="inputEmail4" class="form-label">Berkas Excel</label>
                        <input type="file" name="file" class="form-control">
                    </div>
                    <div class="col-md-12"></div>
                    <div class="col-12">
                        <button class="btn btn-primary" id="import">Tambah Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


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
