<!DOCTYPE html>
<html>

<head>
    <title>SKRIPSI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
</head>

<body>

    <div class="container">
        <div class="card mt-3 mb-3">
            <div class="card-header text-center">
                <h4>SKRIPSI</h4>
            </div>
            <div class="card-body">

                <table class="table table-bordered mt-3">
                    <tr>
                        <th colspan="5">
                            Bobot
                        </th>
                    </tr>
                    <tr>
                        <th>BDV</th>
                        <th>Water</th>
                        <th>Acid</th>
                        <th>IFT</th>
                        <th>Color</th>
                        <th>Gbest</th>
                    </tr>
                    <tr>
                        @foreach ($data['Solusi'] as $value)
                            <td>{{ $value }}</td>
                        @endforeach
                        <td>{{ $data['GBest'] }}</td>
                    </tr>
                </table>
                <a href="{{ route('pso.hapus') }}" class="btn btn-warning" title="Bersihkan Data" id='hapus'
                    name='hapus'>
                    Kembali
                </a>
            </div>
        </div>

    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.0.2/js/bootstrap.min.js"
        integrity="sha512-a6ctI6w1kg3J4dSjknHj3aWLEbjitAXAjLDRUxo2wyYmDFRcz2RJuQr5M3Kt8O/TtUSp8n2rAyaXYy1sjoKmrQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
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
