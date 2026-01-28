<!-- Js Plugins -->
<script src="{{asset('frontend')}}/assets/js/jquery-3.3.1.min.js"></script>
<script src="{{asset('frontend')}}/assets/js/bootstrap.min.js"></script>
<script src="{{asset('frontend')}}/assets/js/jquery-ui.min.js"></script>
<script src="{{asset('frontend')}}/assets/js/jquery.countdown.min.js"></script>
<script src="{{asset('frontend')}}/assets/js/jquery.nice-select.min.js"></script>
<script src="{{asset('frontend')}}/assets/js/jquery.zoom.min.js"></script>
<script src="{{asset('frontend')}}/assets/js/jquery.dd.min.js"></script>
<script src="{{asset('frontend')}}/assets/js/jquery.slicknav.js"></script>
<script src="{{asset('frontend')}}/assets/js/owl.carousel.min.js"></script>
<script src="{{asset('frontend')}}/assets/js/main.js"></script>

{{--//laravel script--}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"
    integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "timeOut": "5000",
    }
</script>

{{-- Flash Messages Data --}}
<div id="flash-messages" data-success="{{ session('success') }}" data-error="{{ session('error') }}"
    data-info="{{ session('info') }}" data-warning="{{ session('warning') }}" style="display: none;">
</div>

<script>
    $(document).ready(function () {
        const flashMessages = $('#flash-messages');
        const success = flashMessages.data('success');
        const error = flashMessages.data('error');
        const info = flashMessages.data('info');
        const warning = flashMessages.data('warning');

        if (success) toastr.success(success);
        if (error) toastr.error(error);
        if (info) toastr.info(info);
        if (warning) toastr.warning(warning);
    });
</script>
@stack('js')
