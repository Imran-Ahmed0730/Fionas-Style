<!-- Fonts and icons -->
<script src="{{asset('backend')}}/assets/js/plugin/webfont/webfont.min.js"></script>
<script>
    WebFont.load({
        google: { families: ["Public Sans:300,400,500,600,700"] },
        custom: {
            families: [
                "Font Awesome 5 Solid",
                "Font Awesome 5 Regular",
                "Font Awesome 5 Brands",
                "simple-line-icons",
            ],
            urls: ["{{asset('backend')}}/assets/css/fonts.min.css"],
        },
        active: function () {
            sessionStorage.fonts = true;
        },
    });
</script>
<!--   Core JS Files   -->
<script src="{{asset('backend')}}/assets/js/core/jquery-3.7.1.min.js"></script>
<script src="{{asset('backend')}}/assets/js/core/popper.min.js"></script>
<script src="{{asset('backend')}}/assets/js/core/bootstrap.min.js"></script>

<!-- jQuery Scrollbar -->
<script src="{{asset('backend')}}/assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>

<!-- Datatables -->
<script src="{{asset('backend')}}/assets/js/plugin/datatables/datatables.min.js"></script>

<!-- Bootstrap Notify -->
<script src="{{asset('backend')}}/assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js"></script>

<!-- jQuery Vector Maps -->
<script src="{{asset('backend')}}/assets/js/plugin/jsvectormap/jsvectormap.min.js"></script>
<script src="{{asset('backend')}}/assets/js/plugin/jsvectormap/world.js"></script>

<!-- Sweet Alert -->
<script src="{{asset('backend')}}/assets/js/plugin/sweetalert/sweetalert.min.js"></script>

<!-- Kaiadmin JS -->
<script src="{{asset('backend')}}/assets/js/kaiadmin.min.js"></script>

{{--//laravel script--}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function() {
        $('#sidebar-search').on('keyup', function() {
            var searchTerm = $(this).val().toLowerCase();
            $('#sidebar-menu li').each(function() {
                var menuItem = $(this).text().toLowerCase();
                if (menuItem.indexOf(searchTerm) !== -1) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });
    });
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "timeOut": "5000",
    }
</script>

{{-- Flash Messages Data --}}
<div id="flash-messages"
     data-success="{{ session('success') }}"
     data-error="{{ session('error') }}"
     data-info="{{ session('info') }}"
     data-warning="{{ session('warning') }}"
     style="display: none;">
</div>

<script>
    $(document).ready(function() {
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
<!-- SweetAlert2 CDN -->
<script>
    $(document).on('click', '.btn-delete', function(e) {
        e.preventDefault();  // Prevent default action (form submission)

        var form = $(this).closest('div').find('form');  // Select the form using the ID

        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                // Submit the form if the user confirms
                form.submit();
            }
        });
    });
</script>
<script>
    $(document).ready(function () {
        // Bind the click event to the toggle switches
        $('.toggle-switch').on('change', function (e) {
            e.preventDefault();

            let switchElement = $(this); // The clicked switch
            let id = switchElement.data('id'); // Get the item ID from data-id
            let newStatus = switchElement.prop('checked') ? 1 : 0; // Determine new status
            let module = switchElement.data('module'); // Get the module from data-module

            // SweetAlert confirmation
            Swal.fire({
                title: 'Are you sure?',
                text: `You are about to ${newStatus ? 'activate' : 'deactivate'} this ${module}.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, change it!',
                cancelButtonText: 'No, cancel!',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    let route = `/admin/${module}/status/change/${id}`

                    // Send AJAX request to update the status
                    $.ajax({
                        url: route, // Use the dynamically constructed route
                        type: 'GET',
                        success: function (response) {
                            if (response.success) {
                                toastr.success(`The ${module} status has been successfully updated.`);
                            } else {
                                toastr.error(`Failed to update the ${module} status.`);
                                // Optionally, revert the switch back to the original state
                                switchElement.prop('checked', !newStatus);
                            }
                        },
                        error: function (error) {
                            toastr.error('An error occurred while updating the status.');
                            switchElement.prop('checked', !newStatus); // Revert the switch
                        }
                    });
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    switchElement.prop('checked', !newStatus); // Revert the switch on cancel
                }
            });
        });
    });
</script>

@stack('js')
