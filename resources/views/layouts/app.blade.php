<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">

    <title>ICCN - Indonesia Creative Cities Network</title>
    @yield('page-style')
</head>

<body>
    <div class="container-fluid">
        <nav class="navbar navbar-expand-md navbar-light bg-white topbar static-top shadow">
            <div class="container">
                <a class="navbar-brand" href="">
                    Logo ICCN
                </a>
            </div>

            <button class="navbar-toggler shadow-none" type="button" data-bs-toggle="offcanvas"
                data-bs-target="#navigation" aria-controls="navigation" aria-expanded="false"
                aria-label="toggle navigation">
                <span class="navbar-toggler-icon">
                    <span class="navbar-toggler-bar bar1"></span>
                    <span class="navbar-toggler-bar bar2"></span>
                    <span class="navbar-toggler-bar bar3"></span>
                </span>
            </button>

            <div id="navigation" class="offcanvas offcanvas-end">
                <div class="offcanvas-header">
                    <a id="offcanvas-header" href="" class="offcanvas-title">Logo ICCN</a>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body justify-content-end">
                    <ul class="navbar-nav">
                        <li class="navbar-item">
                            <a class="nav-link" aria-current="page" href="#">Beranda</a>
                        </li>
                        <li class="navbar-item">
                            <a class="nav-link" aria-current="page" href="#">Organisasi</a>
                        </li>
                        <li class="navbar-item">
                            <a class="nav-link" aria-current="page" href="#">Program</a>
                        </li>
                        <li class="navbar-item">
                            <a class="nav-link" aria-current="page" href="#">Berita</a>
                        </li>
                        <li class="navbar-item">
                            <a class="nav-link" aria-current="page" href="#">Kontak</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <main id="app" class="container">
            @yield('content')
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous">
    </script>
    @yield('page-script')
</body>

</html>