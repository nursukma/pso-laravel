 <!-- ======= Sidebar ======= -->
 <aside id="sidebar" class="sidebar">

     <ul class="sidebar-nav" id="sidebar-nav">

         <li class="nav-heading">Dashboard</li>

         <li class="nav-item">
             <a class="nav-link {{ request()->is('/') ? 'active' : 'collapsed' }}" href="/">
                 <i class="bi bi-grid"></i>
                 <span>Dashboard</span>
             </a>
         </li><!-- End Dashboard Nav -->

         {{-- <li class="nav-item">
             <a class="nav-link {{ request()->is('/chart') ? 'active' : 'collapsed' }}" href="#">
                 <i class="bi bi-bar-chart-fill"></i>
                 <span>Chart</span>
             </a>
         </li><!-- End Dashboard Nav --> --}}

         <li class="nav-heading">Menu</li>

         <li class="nav-item">
             <a class="nav-link {{ request()->is('form-data') ? 'active' : 'collapsed' }}" href="/form-data">
                 <i class="bi bi-card-heading"></i><span>@lang('layouts.sidebar_form')</span>
             </a>
         </li><!-- End Form Nav -->

         <li class="nav-item">
             <a class="nav-link {{ request()->is('table-data') ? 'active' : 'collapsed' }}" href="/table-data">
                 <i class="bi bi-table"></i><span>@lang('layouts.sidebar_table')</span>
             </a>
         </li><!-- End Form Nav -->

         <li class="nav-item">
             <a class="nav-link {{ request()->is('variabel') ? 'active' : 'collapsed' }}" href="/variabel">
                 <i class="bi bi-collection"></i><span>@lang('layouts.sidebar_var')</span>
             </a>
         </li><!-- End Form Nav -->

         <li class="nav-heading">Log</li>

         <li class="nav-item">
             <a class="nav-link {{ request()->is('activity-log') ? 'active' : 'collapsed' }}" href="/activity-log">
                 <i class="bi bi-info-circle"></i><span>@lang('layouts.sidebar_log')</span>
             </a>
         </li><!-- End Form Nav -->

     </ul>

 </aside><!-- End Sidebar-->
