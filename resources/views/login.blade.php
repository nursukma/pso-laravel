<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <link rel="stylesheet" href="{{ asset('/build/assets/css/app.css') }}">
    {{-- <link href="{{ asset('storage/logo.png') }}" rel="apple-touch-icon">
    <link href="{{ asset('storage/logo.png') }}" rel="icon"> --}}

    <link href="{{ asset('storage/polinema.png') }}" rel="apple-touch-icon">
    <link href="{{ asset('storage/polinema.png') }}" rel="icon">
    {{-- <link href="{{ asset('build/assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet"> --}}

    {{-- Toastr js --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">

    <title>Login</title>
</head>

<body>
    <div class="wrapper fadeInDown">
        <div id="formContent">

            <!-- Icon -->
            <div class="fadeIn first">
                <img src="{{ asset('/build/assets/img/user.png') }}" alt="User Icon" width="120px" height="120px" />
            </div>

            <!-- Login Form -->
            <form class="needs-validation" method="POST" action="{{ route('login.submit') }}" novalidate>
                @csrf
                <input type="text" name="username" id="username" class="fadeIn second" placeholder="username">
                <input type="password" name="password" id="password" class="fadeIn third" placeholder="password">
                <input type="submit" class="fadeIn fourth" value="Log In">
            </form>

            <!-- Remind Passowrd -->
            <div id="formFooter">
                Adaptive Weighting On Power Transformers
            </div>

        </div>
    </div>

    <script src="{{ asset('build/assets/vendor/jquery/jquery.min.js') }}"></script>
    <!-- Template Main JS File -->
    <script src="{{ asset('build/assets/js/main.js') }}"></script>

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

        @if (Session::has('warning'))
            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                "positionClass": "toast-top-right"
            };
            toastr.warning("{{ session('warning') }}");
        @endif
    </script>
</body>

</html>
