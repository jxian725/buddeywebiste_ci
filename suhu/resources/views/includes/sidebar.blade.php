 <!-- Main Sidebar Container -->
 <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="#" class="brand-link">
      <img src="{{ asset('dist/img/AdminLTELogo.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">{{ config('app.name', 'Laravel') }}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      
      <!-- SidebarSearch Form -->
      <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
          <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-sidebar">
              <i class="fas fa-search fa-fw"></i>
            </button>
          </div>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          @can('manage dashboard')
          <li class="nav-item">
            <a href="{{ route('dashboard') }}" class="nav-link {{ (Request::routeIs('dashboard*')) ? 'active' : '' }}">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                {{ __('Dashboard') }}
              </p>
            </a>
          </li>
          @endcan
          @can('manage admin user')
          <li class="nav-item {{ (Request::routeIs('user*') || Request::routeIs('role*')) ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ (Request::routeIs('user*') || Request::routeIs('role*')) ? 'active' : '' }}">
              <i class="nav-icon fas fa-users"></i>
              <p>
                {{ __('Manage Users') }}
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ route('user.index') }}" class="nav-link {{ Request::routeIs('user*') ? 'active' : '' }}">
                  <i class="far fas fa-user nav-icon"></i>
                  <p>{{ __('Users') }}</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('role.index') }}" class="nav-link {{ Request::routeIs('role*') ? 'active' : '' }}">
                  <i class="far fas fa-user-tag nav-icon"></i>
                  <p>{{ __('Roles') }}</p>
                </a>
              </li>
            </ul>
          </li>
          @endcan
          @can('manage merchant')
          <li class="nav-item {{ (Request::routeIs('merchant*')) ? 'active' : '' }}">
            <a href="{{ route('merchant.index') }}" class="nav-link {{ Request::routeIs('merchant*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-user-tie"></i>
              <p>
                {{ __('Merchant') }}
              </p>
            </a>
          </li>
          @endcan
          @can('manage driver')
          <li class="nav-item {{ (Request::routeIs('driver*')) ? 'active' : '' }}">
            <a href="{{ route('driver.index') }}" class="nav-link {{ (Request::routeIs('driver*')) ? 'active' : '' }}">
              <i class="nav-icon fas fa-taxi"></i>
              <p>
                {{ __('Driver') }}
              </p>
            </a>
          </li>
          @endcan
          @can('manage customer')
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-user"></i>
              <p>
                {{ __('Customer') }}
              </p>
            </a>
          </li>
          @endcan
          @can('manage order')
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-sort"></i>
              <p>
                {{ __('Order') }}
              </p>
            </a>
          </li>
          @endcan
          @can('manage package setting')
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-money-bill"></i>
              <p>
                {{ __('Package Setting') }}
              </p>
            </a>
          </li>
          @endcan
          @can('manage payment record')
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-money-check-alt"></i>
              <p>
                {{ __('Payment Record') }}
              </p>
            </a>
          </li>
          @endcan
          @can('manage topup')
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-coins"></i>
              <p>
                {{ __('Topup') }}
              </p>
            </a>
          </li>
          @endcan
          @can('manage product category')
          <li class="nav-item {{ (Request::routeIs('driver*')) ? 'active' : '' }}">
            <a href="{{ route('product-category.index') }}" class="nav-link {{ Request::routeIs('product-category*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-sort-alpha-up"></i>
              <p>
                {{ __('Category') }}
              </p>
            </a>
          </li>
          @endcan
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>