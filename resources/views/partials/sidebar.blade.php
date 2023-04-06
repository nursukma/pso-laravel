 <!-- ======= Sidebar ======= -->
 <aside id="sidebar" class="sidebar">

     <ul class="sidebar-nav" id="sidebar-nav">



         <li class="nav-item">
             <a class="nav-link {{ request()->is('/') ? 'active' : 'collapsed' }}" href="/">
                 <i class="bi bi-grid"></i>
                 <span>Dashboard</span>
             </a>
         </li><!-- End Dashboard Nav -->

         <li class="nav-heading">Beranda</li>

         <li class="nav-item">
             <a class="nav-link {{ request()->is('sliders') ? 'active' : 'collapsed' }}" href="/sliders">
                 <i class="bi bi-images"></i><span>Sliders</span>
             </a>
         </li><!-- End Data Ibu Nav -->

         <li class="nav-item">
             <a class="nav-link {{ request()->is('about*') ? 'active' : 'collapsed' }}" href="/about">
                 <i class="bi bi-info-circle"></i><span>About</span>
             </a>
         </li><!-- End Data Anak Nav -->

         <li class="nav-item">
             <a class="nav-link {{ request()->is('aksi-bersama') ? 'active' : 'collapsed' }} " href="/aksi-bersama">
                 <i class="bi bi-people-fill"></i><span>Aksi Bersama</span>
             </a>
         </li><!-- End Data Ibu Nav -->

         <li class="nav-item">
             <a class="nav-link {{ request()->is('media') ? 'active' : 'collapsed' }}" href="/media">
                 <i class="bi bi-camera-fill"></i><span>Media ICCN</span>
             </a>
         </li><!-- End Data Ibu Nav -->

         <li class="nav-item">
             <a class="nav-link {{ request()->is('materi*') ? 'active' : 'collapsed' }} " href="/materi">
                 <i class="bi bi-file-earmark-fill"></i><span>Materi ICCN</span>
             </a>
         </li><!-- End Data Ibu Nav -->

         <li class="nav-item">
             <a class="nav-link {{ request()->is('prinsip-kota*') ? 'active' : 'collapsed' }} " href="/prinsip-kota">
                 <i class="bi bi-award-fill"></i><span>Prinsip Kota</span>
             </a>
         </li><!-- End Data Ibu Nav -->

         <li class="nav-item">
             <a class="nav-link {{ request()->is('program') ? 'active' : 'collapsed' }} " href="/program">
                 <i class="bi bi-card-list"></i><span>Program</span>
             </a>
         </li><!-- End Data Ibu Nav -->

         <li class="nav-item">
             <a class="nav-link {{ request()->is('timeline') ? 'active' : 'collapsed' }}" href="/timeline">
                 <i class="bi bi-calendar-check-fill"></i><span>Linimasa</span>
             </a>
         </li><!-- End Data Ibu Nav -->

         <li class="nav-heading">Organisasi</li>

         <li class="nav-item">
             <a class="nav-link {{ request()->is('struktur*') ? 'active' : 'collapsed' }} " href="/struktur">
                 <i class="bi bi-diagram-3-fill"></i><span>Struktur Organisasi</span>
             </a>
         </li><!-- End Data Ibu Nav -->

         <li class="nav-item">
             <a class="nav-link {{ request()->is('pengurus*') ? 'active' : 'collapsed' }} " href="/pengurus">
                 <i class="bi bi-person-fill"></i><span>Pengurus Organisasi</span>
             </a>
         </li><!-- End Data Ibu Nav -->

         <li class="nav-item">
             <a class="nav-link {{ request()->is('koordinator*') ? 'active' : 'collapsed' }} " href="/koordinator">
                 <i class="bi bi-person-lines-fill"></i><span>Koordinator Organisasi</span>
             </a>
         </li><!-- End Data Ibu Nav -->

         <li class="nav-heading">Berita</li>

         <li class="nav-item">
             <a class="nav-link {{ request()->is('news*') ? 'active' : 'collapsed' }}" href="/news">
                 <i class="bi bi-newspaper"></i><span>Berita dan Blog</span>
             </a>
         </li><!-- End Data Ibu Nav -->

         <li class="nav-heading">Pengaturan</li>

         <li class="nav-item">
             <a class="nav-link {{ request()->is('setting') ? 'active' : 'collapsed' }}" href="/setting">
                 <i class="bi bi-gear-fill"></i><span>Situs</span>
             </a>
         </li><!-- End Data Anak Nav -->

     </ul>

 </aside><!-- End Sidebar-->
