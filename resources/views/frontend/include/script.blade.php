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

<div id="flash-messages" data-success="{{ session('success') }}" data-error="{{ session('error') }}"
    data-info="{{ session('info') }}" data-warning="{{ session('warning') }}" style="display: none;">
</div>

<script>
    const formatPrice = (price) => {
        let val = parseFloat(price);
        return isNaN(val) ? "0.00" : val.toFixed(2);
    };

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

        // Global Quick View Trigger
        $(document).on('click', '.btn-quick-view', function (e) {
            e.preventDefault();
            let productId = $(this).data('id');
            let modal = $('#quickViewModal');
            let content = $('#quick-view-content');

            // Show loading state
            content.html(`
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            `);
            modal.modal('show');

            $.ajax({
                url: `/product/quick-view/${productId}`,
                method: 'GET',
                success: function (response) {
                    if (response.success) {
                        content.html(response.html);
                    } else {
                        toastr.error('Failed to load product details');
                        modal.modal('hide');
                    }
                },
                error: function () {
                    toastr.error('An error occurred while fetching product details');
                    modal.modal('hide');
                }
            });
        });
    });
</script>

{{--cart script--}}
<script>
    function DisplayCountOfCheckedItems() {
        let totalCartItems = $('.cart_item_wrapper .cart_item').length;
        $('.item_for_checkout').text(totalCartItems);

        let subtotal = 0;
        let tax = 0;
        let shipping = 0;
        let freeShipping = true;

        // Loop through each item in the main cart table
        $('.cart_item_wrapper .cart_item').each(function () {
            const itemTotal = parseFloat($(this).find('.item-total').text().replace(/,/g, '')) || 0;
            const itemTax = parseFloat($(this).find('.tax').val()) || 0;
            const itemShipping = parseFloat($(this).find('.shipping-cost').val()) || 0;
            const isFreeShipping = parseInt($(this).find('.free-shipping').val()) === 1;

            subtotal += itemTotal;
            tax += itemTax;
            if (!isFreeShipping) {
                shipping += itemShipping;
                freeShipping = false;
            }
        });

        // Values are generally overridden by updateCartUI's data-driven approach
    }

    function updateCartUI(data) {
        let items = data.items;
        let totalQuantity = data.total_quantity;
        let subtotal = data.subtotal;
        let tax = data.tax;
        let currencySymbol = '{{ $currency["symbol"] ?? "৳" }}';
        let assetBaseUrl = '{{ asset("/") }}';
        let defaultImage = assetBaseUrl + 'backend/assets/img/default-150x150.png';

        // Update Off-Canvas Cart
        let offCanvasHtml = '';
        if (!items || Object.keys(items).length === 0) {
            offCanvasHtml = '<div class="text-center py-5"><h5>Your cart is empty</h5></div>';
        } else {
            $.each(items, function (key, product) {
                let productName = product.name;
                let image = product.attributes.image ? assetBaseUrl + product.attributes.image : defaultImage;

                offCanvasHtml += `
                    <div data-sku="${product.id}" class="d-flex gap-3 align-items-center justify-content-start cart_item cart-item-${product.id}">
                        <div class="cart_product_image">
                            <picture>
                                <img src="${image}" alt="${productName}" style="width: 50px; height: 50px; object-fit: cover;">
                            </picture>
                        </div>
                        <div class="cart_product_info w-100">
                            <div class="cart_item_info">
                                <h6 class="product-name" style="font-size: 14px; margin: 0;">${productName.substr(0, 30)}${productName.length > 30 ? '...' : ''}</h6>
                                <p class="d-flex justify-content-between" style="font-size: 12px; margin: 0;">
                                    <span>Price: <span class="quan_price">(${product.quantity} X ${formatPrice(product.price)})</span></span>
                                    <span> ${currencySymbol}<span class="item-total">${(product.quantity * parseFloat(product.price)).toFixed(2)}</span></span>
                                </p>
                                ${product.attributes.variant ? `<p style="font-size: 11px; color: #888; margin: 0;">${product.attributes.variant}</p>` : ''}
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-1">
                                <div class="quantity_controls d-flex">
                                    <button class="btn btn-sm btn-outline-secondary btn_decrease py-0 px-2">-</button>
                                    <input type="text" class="quantity form-control form-control-sm mx-1 text-center" value="${product.quantity}" readonly style="width: 40px; height: 25px;">
                                    <button class="btn btn-sm btn-outline-secondary btn_increase py-0 px-2">+</button>
                                </div>
                                <button class="btn btn-sm text-danger btn_remove remove-from-cart p-0" data-sku="${product.id}"><i class="fa fa-trash"></i></button>
                            </div>
                        </div>
                    </div>
                    <hr class="my-2">`;
            });
        }
        $('#offCanvasCartContainer').html(offCanvasHtml);

        // Update Header Cart
        let headerHtml = '';
        if (!items || Object.keys(items).length === 0) {
            headerHtml = '<tr><td colspan="3" class="text-center py-3">Your cart is empty</td></tr>';
        } else {
            $.each(items, function (key, product) {
                let productName = product.name;
                let image = product.attributes.image ? assetBaseUrl + product.attributes.image : defaultImage;

                headerHtml += `
                    <tr class="cart_item cart-item-${product.id}" data-sku="${product.id}">
                        <td class="si-pic">
                            <img src="${image}" alt="${productName}" style="width: 70px; height: 70px; object-fit: cover;">
                        </td>
                        <td class="si-text">
                            <div class="product-selected">
                                <p>${currencySymbol}${formatPrice(product.price)} x ${product.quantity}</p>
                                <h6>${productName}</h6>
                                ${product.attributes.variant ? `<p><small>${product.attributes.variant}</small></p>` : ''}
                            </div>
                        </td>
                        <td class="si-close">
                            <i class="ti-close remove-from-cart" data-sku="${product.id}"></i>
                        </td>
                    </tr>`;
            });
        }
        $('#headerCartItems').html(headerHtml);

        // Update Totals
        $('#cartTotalQuantity').text(totalQuantity || 0);
        $('.headerSubTotal').text(formatPrice(subtotal || 0));
        $('.navbarTotalPrice').text(formatPrice(subtotal || 0));

        // Update Main Cart Page Totals if present
        $('#subtotal').text(formatPrice(subtotal || 0));
        $('#tax').text(formatPrice(tax || 0));
        $('#shippingCost').text(formatPrice(data.shipping_cost || 0));
        $('#couponDiscount').text(formatPrice(data.coupon_discount || 0));
        $('#grandTotal').text(formatPrice(data.grand_total || 0));

        // Toggle Coupon Line Visibility
        if (data.coupon_discount > 0) {
            $('#couponDiscountRow').show();
        } else {
            $('#couponDiscountRow').hide();
        }

        // Call any page-specific update functions
        if (typeof updateCartTotal === "function") updateCartTotal();
        if (typeof updateTotalItems === "function") updateTotalItems();
        DisplayCountOfCheckedItems();
    }

    function addToCart(slug, variant, quantity) {
        $.ajax({
            method: 'GET',
            url: '{{route("cart.add")}}',
            data: {
                slug: slug,
                variant: variant,
                quantity: quantity ?? 1
            },
            success: function (data) {
                if (data.error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: data.error,
                    });
                } else {
                    updateCartUI(data);

                    Swal.fire({
                        position: "top-end",
                        icon: "success",
                        title: "Product added to your cart",
                        showConfirmButton: false,
                        timer: 1500,
                        toast: true,
                    });
                }
            },
        });
    }

    function cartUpdate(sku, quantity, quantityInput, cartItem) {
        $.ajax({
            method: 'POST',
            url: '{{route("cart.update")}}',
            data: {
                sku: sku,
                quantity: quantity,
                _token: '{{ csrf_token() }}'
            },
            success: function (data) {
                if (data.success) {
                    $('.cart-item-' + sku + ' .quantity').val(quantity);
                    $('.cart-item-' + sku + ' .tax').val(data.tax);
                    $('.cart-item-' + sku + ' .shipping-cost').val(data.shipping_cost);

                    updateItemTotal(cartItem);
                    updateCartUI(data);

                    // Specific logic for Checkout page (if on it)
                    if (typeof calculateGrandTotal === "function") {
                        calculateGrandTotal();
                    }
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: data.error,
                    });
                }
            },
            error: function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'There was an issue updating the cart.',
                });
            }
        });
    }

    function updateItemTotal(cartItem) {
        let $cartItem = $(cartItem);
        if (!$cartItem.hasClass('cart_item')) {
            $cartItem = $cartItem.closest('.cart_item');
        }

        let $quantityInput = $cartItem.find('.quantity');
        let price = parseFloat($cartItem.find('.price').val()) || parseFloat($cartItem.find('.item-price').text()) || 0;
        let quantity = parseInt($quantityInput.val()) || 1;
        let itemTotal = price * quantity;

        $cartItem.find('.quan_price').text(` (${quantity} X ${price.toFixed(2)})`);
        $cartItem.find('.item-total').text(itemTotal.toFixed(2));
    }

    $(document).ready(function () {
        $(document).on('click', '.add-to-cart, .buy_it_btn', function () {
            let slug = $(this).data('slug');
            let container = $(this).closest('.product-details');
            if (container.length === 0) {
                // Try to find if it's in a quick view modal or similar
                container = $(this).parent().parent();
            }
            let variant = container.find('#variant_name').val() || null;
            let quantity = container.find('#product_qty').val() || container.find('.qty').val() || container.find('.qv-qty').val() || 1;

            addToCart(slug, variant, quantity);

            if ($(this).hasClass('buy_it_btn')) {
                setTimeout(() => {
                    window.location.href = '{{ route("checkout.index") }}';
                }, 1000);
            }
        });

        $(document).on('click', '.btn_increase', function (e) {
            e.preventDefault();
            let cartItem = $(this).closest('.cart_item');
            let sku = cartItem.data('sku');
            let quantityInput = $(this).siblings('.quantity');
            if (quantityInput.length === 0) quantityInput = $(this).parent().find('.quantity');
            let quantity = parseInt(quantityInput.val()) || 0;
            quantity += 1;

            cartUpdate(sku, quantity, quantityInput, cartItem);
        });

        $(document).on('click', '.btn_decrease', function (e) {
            e.preventDefault();
            let cartItem = $(this).closest('.cart_item');
            let sku = cartItem.data('sku');
            let quantityInput = $(this).siblings('.quantity');
            if (quantityInput.length === 0) quantityInput = $(this).parent().find('.quantity');
            let quantity = parseInt(quantityInput.val()) || 0;
            if (quantity > 1) {
                quantity -= 1;
                cartUpdate(sku, quantity, quantityInput, cartItem);
            }
        });

        $(document).on('click', '.remove-from-cart', function (e) {
            e.preventDefault();
            let sku = $(this).data('sku');
            let cartItem = $(this).closest('.cart_item');
            if (!sku) sku = cartItem.data('sku');

            $.ajax({
                method: 'GET',
                url: '{{route("cart.remove")}}',
                data: { sku: sku },
                success: function (data) {
                    if (data.success) {
                        $('.cart-item-' + sku).remove();
                        updateCartUI(data);

                        if ($('.cart_item').length === 0 && window.location.pathname.includes('checkout')) {
                            window.location.href = '{{ route("home") }}';
                        }

                        Swal.fire({
                            position: "top-end",
                            icon: "success",
                            title: "Product removed from your cart",
                            showConfirmButton: false,
                            timer: 1500,
                            toast: true,
                        });
                    } else {
                        Swal.fire({ icon: 'error', title: 'Error!', text: data.error });
                    }
                },
                error: function () {
                    Swal.fire({ icon: 'error', title: 'Error!', text: 'There was an issue in removing the product.' });
                }
            });
        });

        // Back to top button logic
        $(window).scroll(function () {
            if ($(this).scrollTop() > 300) {
                $('.back-to-top-btn').fadeIn();
            } else {
                $('.back-to-top-btn').fadeOut();
            }
        });

        $('#back-to-top').click(function (e) {
            e.preventDefault();
            $('html, body').animate({ scrollTop: 0 }, 800);
        });

        // Wishlist logic
        $(document).on('click', '.add-to-wishlist', function (e) {
            e.preventDefault();
            let productId = $(this).data('id');
            let icon = $(this).find('i');

            @auth
                $.ajax({
                    method: 'POST',
                    url: '{{ route("customer.wishlist.store") }}',
                    data: {
                        product_id: productId,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        if (response.success) {
                            $('#wishlistCount').text(response.count);
                            icon.removeClass('icon_heart_alt').addClass('icon_heart');
                            toastr.success(response.message);
                        } else {
                            toastr.info(response.message);
                        }
                    },
                    error: function () {
                        toastr.error('Something went wrong!');
                    }
                });
            @else
                window.location.href = '{{ route("login") }}';
            @endauth
        });

        // Smart Sticky Header Logic
        var lastScrollTop = 0;
        var stickyTarget = $('.sticky-wrapper');

        if (stickyTarget.length) {
            var stickyTop = stickyTarget.offset().top;
            var stickyHeight = stickyTarget.outerHeight();

            $(window).resize(function () {
                if (!stickyTarget.hasClass('sticky-nav')) {
                    stickyTop = stickyTarget.offset().top;
                }
            });

            $(window).scroll(function (event) {
                var st = $(this).scrollTop();

                // Apply Sticky
                if (st >= stickyTop) {
                    if (!stickyTarget.hasClass('sticky-nav')) {
                        stickyTarget.addClass('sticky-nav');
                        stickyTarget.after('<div class="nav-placeholder" style="height:' + stickyTarget.outerHeight() + 'px"></div>');
                    }
                } else {
                    if (stickyTarget.hasClass('sticky-nav')) {
                        stickyTarget.removeClass('sticky-nav');
                        $('.nav-placeholder').remove();
                    }
                }

                // Hide/Show Logic
                if (stickyTarget.hasClass('sticky-nav')) {
                    if (st > lastScrollTop && st > stickyTop + stickyHeight) {
                        stickyTarget.addClass('nav-hidden');
                    } else {
                        stickyTarget.removeClass('nav-hidden');
                    }
                } else {
                    stickyTarget.removeClass('nav-hidden');
                }

                lastScrollTop = st;
            });
        }
    });
</script>
@stack('js')
