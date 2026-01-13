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
                        height="70"
                    />
                @else
                    <img
                        src="{{asset('backend')}}/assets/img/kaiadmin/logo_light.svg"
                        alt="navbar brand"
                        class="navbar-brand"
                        height="70"
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

                <li class="nav-section">
                <span class="sidebar-mini-icon">
                  <i class="fa fa-"></i>
                </span>
                    <h4 class="text-section">Menu</h4>
                </li>
                @canany(['Settings Add', 'Settings View', 'Settings Site', 'Settings Social Media', 'Settings Logo & Favicon', 'Settings Contact', 'Settings Store', 'Settings Activation'])
                    <li class="nav-item {{Str::contains($route, 'setting') ? 'active':''}}">
                        <a data-bs-toggle="collapse" href="#setting">
                            <i class="fas fa-cogs"></i>
                            <p>Settings</p>
                            <span class="caret"></span>
                        </a>
                        <div class="collapse {{Str::contains($route, 'setting') ? 'show':''}}" id="setting">
                            <ul class="nav nav-collapse">
                                @can('Settings Add')
                                    <li class="{{$route =='admin.setting.index' ? 'active':''}}">
                                        <a href="{{route('admin.setting.index')}}">
                                            <span class="sub-item">View Setting Key</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('Settings View')
                                    <li class="{{$route =='admin.setting.create' ? 'active':''}}">
                                        <a href="{{route('admin.setting.create')}}">
                                            <span class="sub-item">Add Setting Key</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('Settings Site')
                                    <li class="{{Str::contains($url, 'site') ? 'active':''}}">
                                        <a href="{{route('admin.setting.edit', 'site')}}">
                                            <span class="sub-item">Site Setting</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('Settings Social Media')
                                    <li class="{{Str::contains($url, 'social-media') ? 'active':''}}">
                                        <a href="{{route('admin.setting.edit', 'social-media')}}">
                                            <span class="sub-item">Social Media Setting</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('Settings Logo & Favicon')
                                    <li class="{{Str::contains($url, 'logos-favicon') ? 'active':''}}">
                                        <a href="{{route('admin.setting.edit', 'logos-favicon')}}">
                                            <span class="sub-item">Logos & Favicon Setting</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('Settings Contact')
                                    <li class="{{Str::contains($url, 'contact') ? 'active':''}}">
                                        <a href="{{route('admin.setting.edit', 'contact')}}">
                                            <span class="sub-item">Contact Information Setting</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('Settings Store')
                                    <li class="{{Str::contains($url, 'store') ? 'active':''}}">
                                        <a href="{{route('admin.setting.edit', 'store')}}">
                                            <span class="sub-item">Store Setting</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('Settings Activation')
                                    <li class="{{Str::contains($url, 'activation') ? 'active':''}}">
                                        <a href="{{route('admin.setting.edit', 'activation')}}">
                                            <span class="sub-item">Activation Settings</span>
                                        </a>
                                    </li>
                                @endcan
                            </ul>
                        </div>
                    </li>
                @endcanany
                @canany(['Role Add', 'Role View', 'Permission Add', 'Permission View'])
                    <li class="nav-item {{Str::contains($route, 'role') || Str::contains($route, 'permission') ? 'active':''}}">
                        <a data-bs-toggle="collapse" href="#role-permission">
                            <i class="fas fa-users-cog"></i>
                            <p>Roles & Permissions</p>
                            <span class="caret"></span>
                        </a>
                        <div class="collapse {{Str::contains($route, 'role') || Str::contains($route, 'permission') ? 'show':''}}" id="role-permission">
                            <ul class="nav nav-collapse">
                                @canany(['Role Add', 'Role View'])
                                    <li>
                                        <a data-bs-toggle="collapse" href="#role">
                                            <span class="sub-item">Roles</span>
                                            <span class="caret"></span>
                                        </a>
                                        <div class="collapse {{Str::contains($route, 'role') ? 'show':''}}" id="role">
                                            <ul class="nav nav-collapse subnav">
                                                @can('Role View')
                                                    <li class="{{$route =='admin.role.index' ? 'active':''}}">
                                                        <a href="{{route('admin.role.index')}}">
                                                            <span class="sub-item">View Roles</span>
                                                        </a>
                                                    </li>
                                                @endcan
                                                @can('Role Add')
                                                    <li class="{{$route =='admin.role.create' ? 'active':''}}">
                                                        <a href="{{route('admin.role.create')}}">
                                                            <span class="sub-item">Add Role</span>
                                                        </a>
                                                    </li>
                                                @endcan
                                            </ul>
                                        </div>
                                    </li>
                                @endcanany
                                @canany(['Permission Add', 'Permission View'])
                                    <li>
                                        <a data-bs-toggle="collapse" href="#permission">
                                            <span class="sub-item">Permission</span>
                                            <span class="caret"></span>
                                        </a>
                                        <div class="collapse {{Str::contains($route, 'permission') ? 'show':''}}" id="permission">
                                            <ul class="nav nav-collapse subnav">
                                                @can('Permission View')
                                                    <li class="{{$route =='admin.permssion.index' ? 'active':''}}">
                                                        <a href="{{route('admin.permission.index')}}">
                                                            <span class="sub-item">View Permissions</span>
                                                        </a>
                                                    </li>
                                                @endcan
                                                @can('Permission Add')
                                                    <li class="{{$route =='admin.permission.create' ? 'active':''}}">
                                                        <a href="{{route('admin.permission.create')}}">
                                                            <span class="sub-item">Add Permission</span>
                                                        </a>
                                                    </li>
                                                @endcan
                                            </ul>
                                        </div>
                                    </li>
                                @endcanany
                            </ul>
                        </div>
                    </li>
                @endcanany
                @canany(['Staff Create', 'Staff View'])
                    <li class="nav-item {{Str::contains($route, 'staff') ? 'active':''}}">
                        <a data-bs-toggle="collapse" href="#staff">
                            <i class="fas fa-user-friends"></i>
                            <p>Staffs</p>
                            <span class="caret"></span>
                        </a>
                        <div class="collapse {{Str::contains($route, 'staff') ? 'show':''}}" id="staff">
                            <ul class="nav nav-collapse">
                                @can('Staff View')
                                    <li class="{{$route =='admin.staff.index' ? 'active':''}}">
                                        <a href="{{route('admin.staff.index')}}">
                                            <span class="sub-item">View Staffs</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('Staff Create')
                                    <li class="{{$route =='admin.staff.create' ? 'active':''}}">
                                        <a href="{{route('admin.staff.create')}}">
                                            <span class="sub-item">Add Staff</span>
                                        </a>
                                    </li>
                                @endcan
                            </ul>
                        </div>
                    </li>
                @endcanany
                @canany(['Category Add', 'Category View'])
                    <li class="nav-item {{Str::contains($route, 'category') ? 'active':''}}">
                        <a data-bs-toggle="collapse" href="#category">
                            <i class="fas fa-layer-group"></i>
                            <p>Categories</p>
                            <span class="caret"></span>
                        </a>
                        <div class="collapse {{Str::contains($route, 'category') ? 'show':''}}" id="category">
                            <ul class="nav nav-collapse">
                                @can('Category View')
                                    <li class="{{$route =='admin.category.index' ? 'active':''}}">
                                        <a href="{{route('admin.category.index')}}">
                                            <span class="sub-item">View Categories</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('Category Add')
                                    <li class="{{$route =='admin.category.create' ? 'active':''}}">
                                        <a href="{{route('admin.category.create')}}">
                                            <span class="sub-item">Add Category</span>
                                        </a>
                                    </li>
                                @endcan
                            </ul>
                        </div>
                    </li>
                @endcanany
                @canany(['Brand Add', 'Brand View'])
                    <li class="nav-item {{Str::contains($route, 'brand') ? 'active':''}}">
                        <a data-bs-toggle="collapse" href="#brand">
                            <i class="fas fa-star"></i>
                            <p>Brands</p>
                            <span class="caret"></span>
                        </a>
                        <div class="collapse {{Str::contains($route, 'brand') ? 'show':''}}" id="brand">
                            <ul class="nav nav-collapse">
                                @can('Brand View')
                                    <li class="{{$route =='admin.brand.index' ? 'active':''}}">
                                        <a href="{{route('admin.brand.index')}}">
                                            <span class="sub-item">View Brands</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('Brand Add')
                                    <li class="{{$route =='admin.brand.create' ? 'active':''}}">
                                        <a href="{{route('admin.brand.create')}}">
                                            <span class="sub-item">Add Brand</span>
                                        </a>
                                    </li>
                                @endcan
                            </ul>
                        </div>
                    </li>
                @endcanany
                @canany(['Unit Add', 'Unit View'])
                    <li class="nav-item {{Str::contains($route, 'unit') ? 'active':''}}">
                        <a data-bs-toggle="collapse" href="#unit">
                            <i class="fas fa-scale-balanced"></i>
                            <p>Units</p>
                            <span class="caret"></span>
                        </a>
                        <div class="collapse {{Str::contains($route, 'unit') ? 'show':''}}" id="unit">
                            <ul class="nav nav-collapse">
                                @can('Unit View')
                                    <li class="{{$route =='admin.unit.index' ? 'active':''}}">
                                        <a href="{{route('admin.unit.index')}}">
                                            <span class="sub-item">View Units</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('Unit Add')
                                    <li class="{{$route =='admin.unit.create' ? 'active':''}}">
                                        <a href="{{route('admin.unit.create')}}">
                                            <span class="sub-item">Add Unit</span>
                                        </a>
                                    </li>
                                @endcan
                            </ul>
                        </div>
                    </li>
                @endcanany
                @canany(['Supplier Add', 'Supplier View'])
                    <li class="nav-item {{Str::contains($route, 'supplier') ? 'active':''}}">
                        <a data-bs-toggle="collapse" href="#supplier">
                            <i class="fa-solid fa-truck-field"></i>
                            <p>Suppliers</p>
                            <span class="caret"></span>
                        </a>
                        <div class="collapse {{Str::contains($route, 'supplier') ? 'show':''}}" id="supplier">
                            <ul class="nav nav-collapse">
                                @can('Supplier View')
                                    <li class="{{$route =='admin.supplier.index' ? 'active':''}}">
                                        <a href="{{route('admin.supplier.index')}}">
                                            <span class="sub-item">View Suppliers</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('Supplier Add')
                                    <li class="{{$route =='admin.supplier.create' ? 'active':''}}">
                                        <a href="{{route('admin.supplier.create')}}">
                                            <span class="sub-item">Add Supplier</span>
                                        </a>
                                    </li>
                                @endcan
                            </ul>
                        </div>
                    </li>
                @endcanany
                @canany(['Color Add', 'Color View'])
                    <li class="nav-item {{Str::contains($route, 'color ') ? 'active':''}}">
                        <a data-bs-toggle="collapse" href="#color ">
                            <i class="fas fa-eye-dropper"></i>
                            <p>Colors</p>
                            <span class="caret"></span>
                        </a>
                        <div class="collapse {{Str::contains($route, 'color') ? 'show':''}}" id="color">
                            <ul class="nav nav-collapse">
                                @can('Color View')
                                    <li class="{{$route =='admin.color.index' ? 'active':''}}">
                                        <a href="{{route('admin.color.index')}}">
                                            <span class="sub-item">View Colors</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('Color Add')
                                    <li class="{{$route =='admin.color.create' ? 'active':''}}">
                                        <a href="{{route('admin.color.create')}}">
                                            <span class="sub-item">Add Color </span>
                                        </a>
                                    </li>
                                @endcan
                            </ul>
                        </div>
                    </li>
                @endcanany
                @canany(['Attribute Add', 'Attribute View'])
                    <li class="nav-item {{Str::contains($route, 'attribute') ? 'active':''}}">
                        <a data-bs-toggle="collapse" href="#attribute">
                            <i class="fa-solid fa-network-wired"></i>
                            <p>Attributes</p>
                            <span class="caret"></span>
                        </a>
                        <div class="collapse {{Str::contains($route, 'category') ? 'show':''}}" id="attribute">
                            <ul class="nav nav-collapse">
                                @can('Attribute Add')
                                    <li class="{{$route =='admin.attribute.index' ? 'active':''}}">
                                        <a href="{{route('admin.attribute.index')}}">
                                            <span class="sub-item">View Attributes</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('Attribute View')
                                    <li class="{{$route =='admin.attribute.create' ? 'active':''}}">
                                        <a href="{{route('admin.attribute.create')}}">
                                            <span class="sub-item">Add Attribute</span>
                                        </a>
                                    </li>
                                @endcan
                            </ul>
                        </div>
                    </li>
                @endcanany
                @canany(['Vendor Add', 'Vendor View'])
                    <li class="nav-item {{Str::contains($route, 'vendor') ? 'active':''}}">
                        <a data-bs-toggle="collapse" href="#vendor">
                            <i class="fas fa-shop"></i>
                            <p>Vendors</p>
                            <span class="caret"></span>
                        </a>
                        <div class="collapse {{Str::contains($route, 'vendor') ? 'show':''}}" id="vendor">
                            <ul class="nav nav-collapse">
                                @can('Vendor View')
                                    <li class="{{$route =='admin.vendor.index' ? 'active':''}}">
                                        <a href="{{route('admin.vendor.index')}}">
                                            <span class="sub-item">View Vendors</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('Vendor Add')
                                    <li class="{{$route =='admin.vendor.create' ? 'active':''}}">
                                        <a href="{{route('admin.vendor.create')}}">
                                            <span class="sub-item">Add Vendor </span>
                                        </a>
                                    </li>
                                @endcan
                            </ul>
                        </div>
                    </li>
                @endcanany
                @canany(['Banner Add', 'Banner View'])
                    <li class="nav-item {{Str::contains($route, 'banner') ? 'active':''}}">
                        <a data-bs-toggle="collapse" href="#banner">
                            <i class="fas fa-image"></i>
                            <p>Banners</p>
                            <span class="caret"></span>
                        </a>
                        <div class="collapse {{Str::contains($route, 'banner') ? 'show':''}}" id="banner">
                            <ul class="nav nav-collapse">
                                @can('Banner View')
                                    <li class="{{$route =='admin.banner.index' ? 'active':''}}">
                                        <a href="{{route('admin.banner.index')}}">
                                            <span class="sub-item">View Banners</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('Banner Add')
                                    <li class="{{$route =='admin.banner.create' ? 'active':''}}">
                                        <a href="{{route('admin.banner.create')}}">
                                            <span class="sub-item">Add Banner </span>
                                        </a>
                                    </li>
                                @endcan
                            </ul>
                        </div>
                    </li>
                @endcanany
                @canany(['Slider Add', 'Slider View'])
                    <li class="nav-item {{Str::contains($route, 'slider') ? 'active':''}}">
                        <a data-bs-toggle="collapse" href="#slider">
                            <i class="fas fa-sliders"></i>
                            <p>Sliders</p>
                            <span class="caret"></span>
                        </a>
                        <div class="collapse {{Str::contains($route, 'slider') ? 'show':''}}" id="slider">
                            <ul class="nav nav-collapse">
                                @can('Slider View')
                                    <li class="{{$route =='admin.slider.index' ? 'active':''}}">
                                        <a href="{{route('admin.slider.index')}}">
                                            <span class="sub-item">View Sliders</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('Slider Add')
                                    <li class="{{$route =='admin.slider.create' ? 'active':''}}">
                                        <a href="{{route('admin.slider.create')}}">
                                            <span class="sub-item">Add Slider </span>
                                        </a>
                                    </li>
                                @endcan
                            </ul>
                        </div>
                    </li>
                @endcanany

            </ul>
        </div>
    </div>
</div>
<!-- End Sidebar -->



