 <!-- ======= Header ======= -->
 <header id="header" class="header fixed-top d-flex align-items-center">

     <div class="d-flex align-items-center justify-content-between">
         <a href="#" class="logo d-flex align-items-center">
             {{-- <img src="{{ asset('build/assets/img/logo.png') }}" alt=""> --}}
             <span class="d-none d-lg-block">e-SIM</span>
         </a>
         <i class="bi bi-list toggle-sidebar-btn"></i>
     </div><!-- End Logo -->

     <nav class="header-nav ms-auto">
         <ul class="d-flex align-items-center">

             <li class="nav-item dropdown pe-3">

                 <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#"
                     data-bs-toggle="dropdown">
                     <img src="{{ asset('build/assets/img/user.png') }}" alt="Profile" class="rounded-circle">
                     <span class="d-none d-md-block dropdown-toggle ps-2">Admin</span>
                     {{-- <span class="d-none d-md-block dropdown-toggle ps-2">TES AKUN</span> --}}
                 </a><!-- End Profile Iamge Icon -->

                 <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                     <li>
                         <form action="/logout" method="post">
                             @csrf
                             <button type="submit" class="dropdown-item d-flex align-items-center">
                                 <i class="bi bi-box-arrow-right"></i>
                                 <span>Keluar</span>
                             </button>
                         </form>
                     </li>

                 </ul><!-- End Profile Dropdown Items -->
             </li><!-- End Profile Nav -->

         </ul>
     </nav><!-- End Icons Navigation -->

 </header><!-- End Header -->
