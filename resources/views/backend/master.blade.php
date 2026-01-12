<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>{{getSetting('business_name')}} - @yield('title')</title>
    <meta
        content="width=device-width, initial-scale=1.0, shrink-to-fit=no"
        name="viewport"
    />
    @if(getSetting('site_favicon') != null)
        <link
            rel="icon"
            href="{{asset(getSetting('site_favicon'))}}"
            type="image/x-icon"
        />
    @else
        <link
            rel="icon"
            href="{{asset('backend')}}/assets/img/kaiadmin/favicon.ico"
            type="image/x-icon"
        />
    @endif

    @include('backend.include.style')
</head>
<body>
<div class="wrapper">
    @include('backend.include.sidebar')

    <div class="main-panel">
        @include('backend.include.header')

        @yield('content')
       @include('backend.include.footer')
    </div>

    <!-- Custom template | don't include it in your project! -->
    <div class="custom-template">
        <div class="title">Settings</div>
        <div class="custom-content">
            <div class="switcher">
                <div class="switch-block">
                    <h4>Logo Header</h4>
                    <div class="btnSwitch">
                        <button
                            type="button"
                            class="selected changeLogoHeaderColor"
                            data-color="dark"
                        ></button>
                        <button
                            type="button"
                            class="changeLogoHeaderColor"
                            data-color="blue"
                        ></button>
                        <button
                            type="button"
                            class="changeLogoHeaderColor"
                            data-color="purple"
                        ></button>
                        <button
                            type="button"
                            class="changeLogoHeaderColor"
                            data-color="light-blue"
                        ></button>
                        <button
                            type="button"
                            class="changeLogoHeaderColor"
                            data-color="green"
                        ></button>
                        <button
                            type="button"
                            class="changeLogoHeaderColor"
                            data-color="orange"
                        ></button>
                        <button
                            type="button"
                            class="changeLogoHeaderColor"
                            data-color="red"
                        ></button>
                        <button
                            type="button"
                            class="changeLogoHeaderColor"
                            data-color="white"
                        ></button>
                        <br />
                        <button
                            type="button"
                            class="changeLogoHeaderColor"
                            data-color="dark2"
                        ></button>
                        <button
                            type="button"
                            class="changeLogoHeaderColor"
                            data-color="blue2"
                        ></button>
                        <button
                            type="button"
                            class="changeLogoHeaderColor"
                            data-color="purple2"
                        ></button>
                        <button
                            type="button"
                            class="changeLogoHeaderColor"
                            data-color="light-blue2"
                        ></button>
                        <button
                            type="button"
                            class="changeLogoHeaderColor"
                            data-color="green2"
                        ></button>
                        <button
                            type="button"
                            class="changeLogoHeaderColor"
                            data-color="orange2"
                        ></button>
                        <button
                            type="button"
                            class="changeLogoHeaderColor"
                            data-color="red2"
                        ></button>
                    </div>
                </div>
                <div class="switch-block">
                    <h4>Navbar Header</h4>
                    <div class="btnSwitch">
                        <button
                            type="button"
                            class="changeTopBarColor"
                            data-color="dark"
                        ></button>
                        <button
                            type="button"
                            class="changeTopBarColor"
                            data-color="blue"
                        ></button>
                        <button
                            type="button"
                            class="changeTopBarColor"
                            data-color="purple"
                        ></button>
                        <button
                            type="button"
                            class="changeTopBarColor"
                            data-color="light-blue"
                        ></button>
                        <button
                            type="button"
                            class="changeTopBarColor"
                            data-color="green"
                        ></button>
                        <button
                            type="button"
                            class="changeTopBarColor"
                            data-color="orange"
                        ></button>
                        <button
                            type="button"
                            class="changeTopBarColor"
                            data-color="red"
                        ></button>
                        <button
                            type="button"
                            class="selected changeTopBarColor"
                            data-color="white"
                        ></button>
                        <br />
                        <button
                            type="button"
                            class="changeTopBarColor"
                            data-color="dark2"
                        ></button>
                        <button
                            type="button"
                            class="changeTopBarColor"
                            data-color="blue2"
                        ></button>
                        <button
                            type="button"
                            class="changeTopBarColor"
                            data-color="purple2"
                        ></button>
                        <button
                            type="button"
                            class="changeTopBarColor"
                            data-color="light-blue2"
                        ></button>
                        <button
                            type="button"
                            class="changeTopBarColor"
                            data-color="green2"
                        ></button>
                        <button
                            type="button"
                            class="changeTopBarColor"
                            data-color="orange2"
                        ></button>
                        <button
                            type="button"
                            class="changeTopBarColor"
                            data-color="red2"
                        ></button>
                    </div>
                </div>
                <div class="switch-block">
                    <h4>Sidebar</h4>
                    <div class="btnSwitch">
                        <button
                            type="button"
                            class="changeSideBarColor"
                            data-color="white"
                        ></button>
                        <button
                            type="button"
                            class="selected changeSideBarColor"
                            data-color="dark"
                        ></button>
                        <button
                            type="button"
                            class="changeSideBarColor"
                            data-color="dark2"
                        ></button>
                    </div>
                </div>
            </div>
        </div>
        <div class="custom-toggle">
            <i class="icon-settings"></i>
        </div>
    </div>
    <!-- End Custom template -->
</div>
@include('backend.include.script')
</body>
</html>
