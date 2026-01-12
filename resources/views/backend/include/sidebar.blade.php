@php
    $route = Route::currentRouteName();
    $url = URL::current();
@endphp

<!-- Sidebar -->
<div class="sidebar" data-background-color="dark">
    <div class="sidebar-logo">
        <!-- Logo Header -->
        <div class="logo-header" data-background-color="dark">
            <a href="{{route('admin.dashboard')}}" class="logo">
                @if(getSetting('site_logo') != null)
                    <img
                        src="{{asset(getSetting('site_logo'))}}"
                        alt="navbar brand"
                        class="navbar-brand"
                        height="20"
                    />
                @else
                    <img
                        src="{{asset('backend')}}/assets/img/kaiadmin/logo_light.svg"
                        alt="navbar brand"
                        class="navbar-brand"
                        height="20"
                    />
                @endif

            </a>
            <div class="nav-toggle">
                <button class="btn btn-toggle toggle-sidebar">
                    <i class="gg-menu-right"></i>
                </button>
                <button class="btn btn-toggle sidenav-toggler">
                    <i class="gg-menu-left"></i>
                </button>
            </div>
            <button class="topbar-toggler more">
                <i class="gg-more-vertical-alt"></i>
            </button>
        </div>
        <!-- End Logo Header -->
    </div>
    <div class="p-3">
        <input type="text" id="sidebar-search" class="form-control" placeholder="Search Menu">
    </div>
    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">

            <ul class="nav nav-secondary" id="sidebar-menu">
                <li class="nav-item {{$route == 'admin.dashboard' ? 'active':''}}">
                    <a href="{{route('admin.dashboard')}}"><i class="fas fa-home"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                @if(Auth::user()->role == 1)
                    @include('backend.include.sidebar.admin')
                @endif
            </ul>
        </div>
    </div>
</div>
<!-- End Sidebar -->



