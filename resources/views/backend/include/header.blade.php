<div class="main-header">
    <div class="main-header-logo">
        <!-- Logo Header -->
        <div class="logo-header" data-background-color="dark">
            <a href="{{route('admin.dashboard')}}" class="logo">
                @if(getSetting('site_logo') != null)
                    <img src="{{asset(getSetting('site_logo'))}}" alt="navbar brand" class="navbar-brand" height="20" />
                @else
                    <img src="{{asset('backend')}}/assets/img/kaiadmin/logo_light.svg" alt="navbar brand"
                        class="navbar-brand" height="20" />
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
    <!-- Navbar Header -->
    <nav class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom">
        <div class="container-fluid">
            <!-- Mobile Search Toggle -->
            <nav class="navbar navbar-header-left navbar-expand-lg navbar-form nav-search p-0 d-flex d-lg-none">
                <div class="input-group sidebar-search-inner" style="margin-left: 10px; width: calc(100vw - 180px);">
                    <i class="fas fa-search"></i>
                    <input type="text" class="mobileSidebarSearch" placeholder="Search menu..." autocomplete="off"
                        style="color: #666;">
                </div>
            </nav>

            <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">
                <li class="nav-item topbar-icon dropdown hidden-caret">
                    <a class="nav-link" data-bs-toggle="dropdown" href="#" aria-expanded="false">
                        <i class="fas fa-layer-group"></i>
                    </a>
                    <div class="dropdown-menu quick-actions animated fadeIn">
                        <div class="quick-actions-header">
                            <span class="title mb-1">Quick Actions</span>
                            <span class="subtitle op-7">Shortcuts</span>
                        </div>
                        <div class="quick-actions-scroll scrollbar-outer">
                            <div class="quick-actions-items">
                                <div class="row m-0">
                                    <a class="col-6 col-md-4 p-0" href="{{ route('home') }}" target="_blank">
                                        <div class="quick-actions-item">
                                            <div class="avatar-item bg-primary rounded-circle">
                                                <i class="fas fa-globe"></i>
                                            </div>
                                            <span class="text">{{getSetting('site_name')}}</span>
                                        </div>
                                    </a>
                                    <a class="col-6 col-md-4 p-0" href="{{route('admin.pos.index')}}">
                                        <div class="quick-actions-item">
                                            <div class="avatar-item bg-danger rounded-circle">
                                                <i class="fa-solid fa-cart-shopping"></i>
                                            </div>
                                            <span class="text">POS</span>
                                        </div>
                                    </a>
                                    <a class="col-6 col-md-4 p-0" href="{{route('admin.cache.clear')}}">
                                        <div class="quick-actions-item">
                                            <div class="avatar-item bg-warning rounded-circle">
                                                <i class="fas fa-eraser"></i>
                                            </div>
                                            <span class="text">Clear Cache</span>
                                        </div>
                                    </a>
                                    <a class="col-6 col-md-4 p-0" href="{{route('admin.account-report.sales-report')}}">
                                        <div class="quick-actions-item">
                                            <div class="avatar-item bg-info rounded-circle">
                                                <i class="fas fa-file-excel"></i>
                                            </div>
                                            <span class="text">Reports</span>
                                        </div>
                                    </a>
                                    <a class="col-6 col-md-4 p-0" href="{{route('admin.subscriber.index')}}">
                                        <div class="quick-actions-item">
                                            <div class="avatar-item bg-success rounded-circle">
                                                <i class="fas fa-envelope"></i>
                                            </div>
                                            <span class="text">Subscribers</span>
                                        </div>
                                    </a>

                                    <a class="col-6 col-md-4 p-0"
                                        href="{{route('admin.account-report.balance-sheet')}}">
                                        <div class="quick-actions-item">
                                            <div class="avatar-item bg-secondary rounded-circle">
                                                <i class="fas fa-credit-card"></i>
                                            </div>
                                            <span class="text">Accounts</span>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>

                <li class="nav-item topbar-user dropdown hidden-caret">
                    <a class="dropdown-toggle profile-pic" data-bs-toggle="dropdown" href="#" aria-expanded="false">
                        <div class="avatar-sm">
                            @if(Auth::user()->image != null)
                                <img src="{{asset(Auth::user()->image)}}" alt="..." class="avatar-img rounded-circle" />
                            @else
                                <img src="{{asset('backend')}}/assets/img/profile.jpg" alt="..."
                                    class="avatar-img rounded-circle" />
                            @endif
                        </div>
                        <span class="profile-username">
                            <span class="op-7">Hi,</span>
                            <span class="fw-bold">{{Auth::user()->name}}</span>
                        </span>
                    </a>
                    <ul class="dropdown-menu dropdown-user animated fadeIn">
                        <div class="dropdown-user-scroll scrollbar-outer">
                            <li>
                                <div class="user-box">
                                    <div class="avatar-lg">
                                        @if(Auth::user()->image != null)
                                            <img src="{{asset(Auth::user()->image)}}" alt="..."
                                                class="avatar-img rounded-circle" />
                                        @else
                                            <img src="{{asset('backend')}}/assets/img/profile.jpg" alt="..."
                                                class="avatar-img rounded-circle" />
                                        @endif
                                    </div>
                                    <div class="u-text">
                                        <h4>{{Auth::user()->name}}</h4>
                                        <p class="text-muted">{{Auth::user()->email}}</p>
                                        <a href="{{route('admin.profile.edit')}}"
                                            class="btn btn-xs btn-secondary btn-sm">View Profile</a>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="dropdown-divider"></div>
                                {{-- <a class="dropdown-item" href="#">My Profile</a>--}}
                                {{-- <a class="dropdown-item" href="#">My Balance</a>--}}
                                {{-- <a class="dropdown-item" href="#">Inbox</a>--}}
                                {{-- <div class="dropdown-divider"></div>--}}
                                {{-- <a class="dropdown-item" href="#">Account Setting</a>--}}
                                {{-- <div class="dropdown-divider"></div>--}}
                                <a class="dropdown-item" href="#"
                                    onclick="event.preventDefault(); $('#logoutForm').submit()">Logout</a>
                                <form action="{{route('logout')}}" id="logoutForm" method="post">
                                    @csrf
                                </form>
                            </li>
                        </div>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
    <!-- End Navbar -->
</div>