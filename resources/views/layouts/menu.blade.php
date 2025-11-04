 <aside id="layout-menu" class="layout-menu-horizontal menu-horizontal menu bg-menu-theme flex-grow-0">
     <div class="container-xxl d-flex h-100">
         <ul class="menu-inner">
             <!-- Dashboards -->
             <li class="menu-item">
                 <a href="{{ URL::to('/') }}" class="menu-link">
                     <i class="menu-icon tf-icons mdi mdi-home-outline"></i>
                     <div data-i18n="Dashboard">Dashboard</div>
                 </a>
             </li>
             <li class="menu-item">
                 <a href="javascript:void(0)" class="menu-link menu-toggle">
                     <i class="menu-icon tf-icons mdi mdi-database-outline"></i>
                     <div data-i18n="Master Data">Master Data</div>
                 </a>
                 <ul class="menu-sub">
                     <li class="menu-item">
                         <a href="{{ URL::to('customers') }}" class="menu-link">
                             <i class="menu-icon tf-icons mdi mdi-account-outline"></i>
                             <div data-i18n="Customers">Customers</div>
                         </a>
                     </li>
                     <li class="menu-item">
                         <a href="{{ URL::to('billing-transactions') }}" class="menu-link">
                             <i class="menu-icon tf-icons mdi mdi-list-box-outline"></i>
                             <div data-i18n="Transaction">Transaction</div>
                         </a>
                     </li>                     
                 </ul>
             </li>
         </ul>
     </div>
 </aside>
