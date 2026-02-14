let paymentRows = [];

function handleCalc(b) {
    let display = $('#calcDisplay');
    let cur = display.val();
    if (b === 'C') display.val('0');
    else if (b === 'Del') display.val(cur.length > 1 ? cur.slice(0, -1) : '0');
    else if (b === '=') {
        try {
            let expr = cur.replace(/([0-9\.]+)\s*([\+\-\*\/])\s*([0-9\.]+)%/g, function (match, base, op, perc) {
                let bVal = parseFloat(base);
                let pVal = (bVal * (parseFloat(perc) / 100));
                return bVal + op + pVal;
            });
            expr = expr.replace(/([0-9\.]+)%/g, function (match, perc) {
                return (parseFloat(perc) / 100);
            });
            display.val(eval(expr.replace('x', '*')).toString());
        } catch (e) { display.val('Error'); }
    }
    else {
        if (cur === '0' && (/[0-9]/.test(b))) display.val(b);
        else display.val(cur + b);
    }
}

$(document).ready(function () {
    initCustomerSelect();
    fetchProducts();
    fetchCart();

    $('#cartToggleBtn, #cartToggle').click(function () {
        $('#posSidebar').toggleClass('active');
    });

    $(document).on('click', '#productGrid .pagination a', function (e) {
        e.preventDefault();
        const page = $(this).attr('href').split('page=')[1];
        fetchProducts(page);
    });

    $(document).on('click', function (e) {
        if ($(window).width() < 992) {
            if (!$(e.target).closest('#posSidebar, #cartToggleBtn, #cartToggle').length) {
                $('#posSidebar').removeClass('active');
            }
        }
    });

    $('#walkInToggle').on('change', function () {
        if ($(this).is(':checked')) {
            $('#customerSelectionWrap').addClass('d-none');
            $('#walkInLabel').removeClass('d-none');
            POS.customer = { id: null, name: 'Walk-in Customer' };
            $('#customerSelect').val(null).trigger('change');
        } else {
            $('#customerSelectionWrap').removeClass('d-none');
            $('#walkInLabel').addClass('d-none');
            updateCustomerFromSelect();
        }
    });

    $('#customerSelect').on('change', function () {
        if (!$('#walkInToggle').is(':checked')) {
            updateCustomerFromSelect();
        }
    });

    $('input[name="order_type"]').on('change', function () {
        POS.order_type = $(this).val();
        if (POS.order_type === 'delivery') {
            $('#shippingInfoWrap').removeClass('d-none');
        } else {
            $('#shippingInfoWrap').addClass('d-none');
            POS.shipping = 0;
        }
        fetchCart();
    });

    $('#shipCountry').on('change', function () {
        const countryId = $(this).val();
        POS.shipping_info.country_id = countryId;
        if (!countryId) return $('#shipState, #shipCity').html('<option value="">Select</option>');
        $.get(`${POS_CONFIG.routes.get_states}/${countryId}`, function (states) {
            let h = '<option value="">State</option>';
            states.forEach(s => h += `<option value="${s.id}">${s.name}</option>`);
            $('#shipState').html(h).trigger('change');
        });
    });

    $('#shipState').on('change', function () {
        const stateId = $(this).val();
        POS.shipping_info.state_id = stateId;
        if (!stateId) return $('#shipCity').html('<option value="">City</option>');
        $.get(`${POS_CONFIG.routes.get_cities}/${stateId}`, function (cities) {
            let h = '<option value="">City</option>';
            cities.forEach(c => h += `<option value="${c.id}">${c.name}</option>`);
            $('#shipCity').html(h);
            if (POS.order_type === 'delivery' && !POS.shipping_manual) fetchCart();
        });
    });

    $('#shipCity').on('change', function () {
        POS.shipping_info.city_id = $(this).val();
        if (POS.order_type === 'delivery' && !POS.shipping_manual) fetchCart();
    });

    $('#shipAddress').on('keyup', function () { POS.shipping_info.address = $(this).val(); });

    $(document).on('keydown', function (e) {
        if ($('#calculatorModal').is(':visible')) {
            const key = e.key;
            if (/[0-9\+\-\*\/\.\%\=]/.test(key)) handleCalc(key);
            else if (key === 'Enter') handleCalc('=');
            else if (key === 'Backspace') handleCalc('Del');
            else if (key === 'Escape') $('#calculatorModal').modal('hide');
        }
    });

    $('#productSearch').on('keyup', _.debounce(() => fetchProducts(1), 400));
    $('#categoryFilter, #brandFilter').on('change', () => fetchProducts(1));
    $('#refreshBtn').click(() => fetchProducts(1));

    $(document).on('click', '.product-card', function () { addToCart($(this).data('json')); });

    $('#calcBtn').click(() => $('#calculatorModal').modal('show'));
    $('#historyBtn').click(() => $('#holdOrdersModal').modal('show'));

    $(document).on('click', '.qty-btn', function (e) {
        e.stopPropagation();
        updateQty($(this).data('rowid'), $(this).data('qty'), $(this).data('action'));
    });

    $(document).on('click', '.remove-item', function (e) {
        e.stopPropagation();
        removeItem($(this).data('rowid'));
    });

    $('#clearCartBtn').click(() => {
        Swal.fire({
            title: 'Clear Cart?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, clear it!'
        }).then((result) => { if (result.isConfirmed) clearCart(); });
    });

    $('#checkoutBtn').click(() => {
        if (POS.cart.length === 0) return toastr.error('Cart is empty');
        $('#paymentModal').modal('show');
        updatePaymentSummary();
    });

    $('#applyCouponBtn').click(function () {
        const code = $('#couponCode').val().trim();
        if (!code) return toastr.error('Please enter a code');
        $.post(POS_CONFIG.routes.apply_coupon, { code }, function (res) {
            if (res.success && res.coupon) {
                POS.coupon = res.coupon;
                $('#couponStatus').removeClass('d-none');
                $('#couponMessage').text(`✓ ${res.coupon.code} applied!`);
                $('#couponCode').val('');
                toastr.success('Coupon applied');
                renderCart();
            } else {
                toastr.error('Invalid coupon');
            }
        });
    });

    $('#removeCouponBtn').click(function () {
        POS.coupon = null;
        $('#couponStatus').addClass('d-none');
        toastr.info('Coupon removed');
        renderCart();
    });

    $('#utilityModal').on('show.bs.modal', function (e) {
        const type = $(e.relatedTarget).data('type');
        $('#utilityTitle').text(type === 'discount' ? 'POS Discount' : 'Shipping Charge');
        $('#uLabel').text(type === 'discount' ? 'Value' : 'Amount');
        $('#discTypeGroup').toggle(type === 'discount');
        $('#uValue').val(type === 'discount' ? POS.discount.value : POS.shipping);
        if (type === 'discount') $('#uType').val(POS.discount.type);
        $('#uSubmit').off('click').on('click', () => {
            if (type === 'discount') {
                POS.discount.type = $('#uType').val();
                POS.discount.value = parseFloat($('#uValue').val()) || 0;
            } else {
                POS.shipping = parseFloat($('#uValue').val()) || 0;
                POS.shipping_manual = true;
                $('#shippingBadge').text('Manual').removeClass('bg-info').addClass('bg-warning');
            }
            $('#utilityModal').modal('hide'); renderCart();
        });
    });

    $('#addPaymentRowBtn').click(addPaymentRow);

    $('#pConfirmBtn').click(function () {
        const totalPayable = parseFloat($('#pTotal').text().replace('$', ''));
        const totalPaid = paymentRows.reduce((s, r) => s + parseFloat(r.amount || 0), 0);
        if (totalPaid < totalPayable) return Swal.fire('Error', 'Insufficient amount', 'error');

        const isWalkIn = $('#walkInToggle').is(':checked');
        const subtotal = parseFloat($('#pSub').text().replace('$', ''));
        const manualDisc = POS.discount.type === 'fixed' ? POS.discount.value : (subtotal * (POS.discount.value / 100));
        let couponDisc = 0;
        if (POS.coupon) {
            couponDisc = POS.coupon.discount_type == 1 ? parseFloat(POS.coupon.discount) : (subtotal * (parseFloat(POS.coupon.discount) / 100));
        }

        const data = {
            customer_id: isWalkIn ? null : $('#customerSelect').val(),
            customer_name: isWalkIn ? POS.walk_in_name : (POS.customer.name || 'N/A'),
            customer_phone: isWalkIn ? POS.walk_in_phone : (POS.customer.phone || 'N/A'),
            customer_email: isWalkIn ? POS.walk_in_email : (POS.customer.email || 'N/A'),
            customer_address: isWalkIn ? POS.walk_in_address : (POS.customer.address || ''),
            items: POS.cart,
            subtotal: subtotal,
            discount: manualDisc,
            coupon_id: POS.coupon ? POS.coupon.id : null,
            coupon_discount: couponDisc,
            shipping_cost: POS.shipping,
            tax: POS.tax,
            grand_total: totalPayable,
            payments: paymentRows,
            order_type: POS.order_type,
            shipping_info: POS.shipping_info
        };

        $(this).prop('disabled', true).text('Processing...');

        const url = POS.editing_order_id ? `${POS_CONFIG.routes.hold_orders_update}/${POS.editing_order_id}` : POS_CONFIG.routes.place_order;
        const form = $('<form>', { action: url, method: 'POST' })
            .append($('<input>', { type: 'hidden', name: '_token', value: $('meta[name="csrf-token"]').attr('content') }));

        const appendData = (baseKey, obj) => {
            for (const key in obj) {
                const fullKey = baseKey ? `${baseKey}[${key}]` : key;
                if (typeof obj[key] === 'object' && obj[key] !== null) appendData(fullKey, obj[key]);
                else form.append($('<input>', { type: 'hidden', name: fullKey, value: obj[key] }));
            }
        };

        appendData('', data);
        $('body').append(form);
        form.submit();
    });

    $('#holdBtn').click(function () {
        if (POS.cart.length === 0) return toastr.error('Cart is empty');

        const subtotal = POS.cart.reduce((s, i) => s + (i.price * i.quantity), 0);
        const disc = POS.discount.type === 'fixed' ? POS.discount.value : (subtotal * (POS.discount.value / 100));
        const total = subtotal + POS.shipping - disc;

        const isWalkIn = $('#walkInToggle').is(':checked');
        const manualDisc = POS.discount.type === 'fixed' ? POS.discount.value : (subtotal * (POS.discount.value / 100));
        let couponDisc = 0;
        if (POS.coupon) {
            couponDisc = POS.coupon.discount_type == 1 ? parseFloat(POS.coupon.discount) : (subtotal * (parseFloat(POS.coupon.discount) / 100));
        }

        const data = {
            is_hold: true,
            customer_id: isWalkIn ? null : $('#customerSelect').val(),
            customer_name: isWalkIn ? POS.walk_in_name : (POS.customer.name || 'N/A'),
            customer_phone: isWalkIn ? POS.walk_in_phone : (POS.customer.phone || 'N/A'),
            customer_email: isWalkIn ? POS.walk_in_email : (POS.customer.email || 'N/A'),
            customer_address: isWalkIn ? POS.walk_in_address : (POS.customer.address || ''),
            items: POS.cart,
            subtotal: subtotal,
            discount: manualDisc,
            coupon_id: POS.coupon ? POS.coupon.id : null,
            coupon_discount: couponDisc,
            shipping_cost: POS.shipping,
            tax: POS.tax,
            grand_total: total,
            order_type: POS.order_type,
            shipping_info: POS.shipping_info
        };

        $(this).prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Holding...');

        const url = POS.editing_order_id ? `${POS_CONFIG.routes.hold_orders_update}/${POS.editing_order_id}` : POS_CONFIG.routes.hold_order;
        const form = $('<form>', { action: url, method: 'POST' })
            .append($('<input>', { type: 'hidden', name: '_token', value: $('meta[name="csrf-token"]').attr('content') }));

        const appendData = (baseKey, obj) => {
            for (const key in obj) {
                const fullKey = baseKey ? `${baseKey}[${key}]` : key;
                if (typeof obj[key] === 'object' && obj[key] !== null) appendData(fullKey, obj[key]);
                else form.append($('<input>', { type: 'hidden', name: fullKey, value: obj[key] }));
            }
        };

        appendData('', data);
        $('body').append(form);
        form.submit();
    });

    $('#addCustomerForm').submit(function (e) {
        e.preventDefault();
        $.post(POS_CONFIG.routes.customer_store, $(this).serialize(), (res) => {
            if (res.success) {
                const opt = new Option(res.customer.name, res.customer.id, true, true);
                $('#customerSelect').append(opt).trigger('change');
                $('#addCustomerModal').modal('hide'); toastr.success('Customer saved');
            }
        });
    });
});

function initCustomerSelect() {
    $('#customerSelect').select2({
        width: '100%',
        ajax: {
            url: POS_CONFIG.routes.customers,
            data: (p) => ({ search: p.term }),
            processResults: (data) => ({
                results: data.map(c => ({
                    id: c.id,
                    text: `${c.user.name} (${c.phone})`,
                    phone: c.phone,
                    name: c.user.name,
                    email: c.email || c.user.email,
                    address: c.address,
                    country_id: c.country_id,
                    state_id: c.state_id,
                    city_id: c.city_id
                }))
            })
        }
    });
}
function fetchProducts(page = 1) {
    const params = { page, search: $('#productSearch').val(), category_id: $('#categoryFilter').val(), brand_id: $('#brandFilter').val() };
    $.get(POS_CONFIG.routes.products, params, (html) => $('#productGrid').html(html));
}

function fetchCart() {
    $.get(POS_CONFIG.routes.cart_get, { state_id: POS.shipping_info.state_id, order_type: POS.order_type }, (res) => {
        if (res.success) {
            POS.cart = res.items;
            POS.tax = res.tax || 0;
            if (!POS.shipping_manual) {
                POS.shipping = res.auto_shipping || 0;
                $('#shippingBadge').text('Auto').removeClass('bg-warning').addClass('bg-info');
            }
            renderCart();
        }
    });
}

function addToCart(product) {
    if (product.variants && product.variants.length > 0) return showVariantModal(product);
    $.post(POS_CONFIG.routes.cart_add, { product_id: product.id, quantity: 1 }, (res) => {
        if (res.success) {
            POS.cart = res.cart.items;
            renderCart();
            toastr.success('Added');
        } else {
            toastr.error(res.message || 'Error adding product');
        }
    });
}

function updateQty(rowId, qty, action) {
    const newQty = action === 'plus' ? parseInt(qty) + 1 : parseInt(qty) - 1;
    if (newQty <= 0) return removeItem(rowId);
    $.post(POS_CONFIG.routes.cart_update, { row_id: rowId, quantity: newQty }, (res) => {
        if (res.success) {
            POS.cart = res.cart.items;
            renderCart();
        } else {
            toastr.error(res.message || 'Error updating quantity');
        }
    });
}

function removeItem(rowId) {
    $.ajax({
        url: `${POS_CONFIG.routes.cart_remove}/${rowId}`,
        type: 'DELETE',
        success: (res) => { if (res.success) { POS.cart = res.cart.items; renderCart(); } }
    });
}

function clearCart() {
    $.post(POS_CONFIG.routes.cart_clear, (res) => {
        if (res.success) {
            POS.cart = [];
            POS.editing_order_id = null;
            POS.coupon = null;
            POS.discount = { type: 'fixed', value: 0 };
            $('#couponStatus').addClass('d-none');
            $('#checkoutBtn').html('Payment <i class="fa fa-arrow-right ms-1"></i>');
            $('#holdBtn').html('Hold <i class="fa fa-pause-circle"></i>');
            renderCart();
        }
    });
}

function renderCart() {
    let html = '';
    let subtotal = 0;
    if (POS.cart.length === 0) {
        html = '<div class="text-center py-5 mt-5 opacity-25"><i class="fa fa-shopping-basket fa-4x mb-3"></i><p>Empty</p></div>';
    } else {
        POS.cart.forEach((i) => {
            subtotal += i.price * i.quantity;
            html += `<div class="cart-item align-items-center">
                <img src="${i.image_url}" class="cart-item-img border">
                <div class="flex-grow-1 min-w-0 d-flex align-items-center gap-1">
                    <div class="flex-grow-1 min-w-0">
                        <p class="mb-0 fw-bold text-truncate" style="font-size: 11px;">${i.name}</p>
                        <p class="text-muted extra-small mb-0" style="font-size: 9px;">$${i.price.toFixed(2)}</p>
                    </div>
                    <div class="qty-controls">
                        <button class="btn qty-btn" data-rowid="${i.row_id}" data-qty="${i.quantity}" data-action="minus"><i class="fa fa-minus"></i></button>
                        <span>${i.quantity}</span>
                        <button class="btn qty-btn" data-rowid="${i.row_id}" data-qty="${i.quantity}" data-action="plus"><i class="fa fa-plus"></i></button>
                    </div>
                    <div class="text-end" style="min-width: 45px;">
                        <span class="fw-bold text-dark" style="font-size: 11px;">$${(i.price * i.quantity).toFixed(2)}</span>
                    </div>
                    <button class="btn btn-link btn-sm text-danger p-0 ms-1 remove-item" data-rowid="${i.row_id}"><i class="fa fa-times-circle"></i></button>
                </div>
            </div>`;
        });
    }
    $('#cartContent').html(html);
    $('#cartBadge').text(POS.cart.reduce((s, i) => s + parseInt(i.quantity), 0));
    updateSummary(subtotal);
}

function updateSummary(subtotal) {
    let couponDisc = 0;
    if (POS.coupon) {
        couponDisc = POS.coupon.discount_type == 1 ? parseFloat(POS.coupon.discount) : (subtotal * (parseFloat(POS.coupon.discount) / 100));
    }
    
    const manualDisc = POS.discount.type === 'fixed' ? POS.discount.value : (subtotal * (POS.discount.value / 100));
    const totalDisc = couponDisc + manualDisc;
    
    const total = Math.ceil(subtotal + POS.shipping + POS.tax - totalDisc);
    $('#subtotalVal').text('$' + subtotal.toFixed(2));
    $('#discountVal').text('-$' + totalDisc.toFixed(2));
    $('#shippingVal').text('$' + POS.shipping.toFixed(2));
    $('#taxVal').text('$' + POS.tax.toFixed(2));
    $('#grandTotalVal').text('$' + Math.max(0, total).toFixed(2));
}
function updateCustomerFromSelect() {
    const data = $('#customerSelect').select2('data')[0];
    if (data) {
        POS.customer = {
            id: data.id,
            name: data.name,
            phone: data.phone,
            email: data.email,
            address: data.address,
            country_id: data.country_id,
            state_id: data.state_id,
            city_id: data.city_id
        };
        if (POS.order_type === 'delivery') {
            $('#shipCountry').val(data.country_id).trigger('change');
            $('#shipAddress').val(data.address).keyup();
            // Note: States and Cities load via AJAX triggered by country change
        }
    }
}

function showVariantModal(product) {
    $('#vProdName').text(product.name);
    $('#vList').html('<div class="text-center"><div class="spinner-border text-primary"></div></div>');
    $('#variantModal').modal('show');
    $.get(`${POS_CONFIG.routes.product_variants}/${product.id}`, (variants) => {
        let h = '<div class="row g-3">';
        variants.forEach(v => {
            const fPrice = parseFloat(v.final_price || v.selling_price || product.price);
            const rPrice = parseFloat(v.regular_price || product.price);
            const hasDiscount = fPrice < rPrice;

            h += `<div class="col-6">
                <button class="btn btn-light border p-3 w-100 text-start shadow-sm hover-lift" onclick="addVToCart(${JSON.stringify(product).replace(/"/g, '&quot;')}, ${JSON.stringify(v).replace(/"/g, '&quot;')})">
                    <span class="d-block fw-bold text-dark mb-1">${v.name || v.variant_name || 'Variant'}</span>
                    <div class="d-flex align-items-center gap-2">
                        <span class="h6 mb-0 text-primary fw-black">$${fPrice.toFixed(2)}</span>
                        ${hasDiscount ? `<span class="text-muted extra-small text-decoration-line-through">$${rPrice.toFixed(2)}</span>` : ''}
                    </div>
                    <span class="badge ${v.stock_qty > 0 ? 'bg-success-soft text-success' : 'bg-danger-soft text-danger'} extra-small mt-2">
                        Stock: ${v.stock_qty || 0}
                    </span>
                </button>
            </div>`;
        });
        $('#vList').html(h + '</div>');
    });
}

function addVToCart(p, v) {
    $.post(POS_CONFIG.routes.cart_add, { product_id: p.id, variant_id: v.id, quantity: 1 }, (res) => {
        if (res.success) {
            POS.cart = res.cart.items;
            $('#variantModal').modal('hide');
            renderCart();
            toastr.success('Variant added');
        } else {
            toastr.error(res.message || 'Error adding variant');
        }
    });
}

function renderPaymentRows() {
    let h = '';
    paymentRows.forEach((row, idx) => {
        h += `<div class="payment-row mb-3 p-3 bg-light rounded-4 border animate__animated animate__fadeIn">
            <div class="row g-2 align-items-center">
                <div class="col-md-5">
                    <label class="form-label extra-small fw-bold text-muted text-uppercase mb-1">Method</label>
                    <select class="form-select border-0 shadow-sm" onchange="updatePaymentRow(${idx}, 'method_id', this.value)">
                        ${POS_CONFIG.data.payment_methods.map(m => `<option value="${m.id}" ${row.method_id == m.id ? 'selected' : ''}>${m.name}</option>`).join('')}
                    </select>
                </div>
                <div class="col-md-5">
                    <label class="form-label extra-small fw-bold text-muted text-uppercase mb-1">Amount</label>
                    <div class="input-group shadow-sm">
                        <span class="input-group-text bg-white border-0">$</span>
                        <input type="number" class="form-control border-0" value="${row.amount}" onkeyup="updatePaymentRow(${idx}, 'amount', this.value)" step="0.01">
                    </div>
                </div>
                <div class="col-md-2 mt-4 text-end">
                    <button class="btn btn-icon btn-danger btn-round shadow-sm" onclick="removePaymentRow(${idx})" ${paymentRows.length === 1 ? 'disabled' : ''}>
                        <i class="fa fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>`;
    });
    $('#paymentRows').html(h);
    calculateFinalBalances();
}

function addPaymentRow() {
    const total = parseFloat($('#pTotal').text().replace('$', ''));
    const paid = paymentRows.reduce((s, r) => s + parseFloat(r.amount || 0), 0);
    paymentRows.push({ method_id: POS_CONFIG.data.payment_methods[0].id, amount: Math.max(0, total - paid).toFixed(2) });
    renderPaymentRows();
}

function removePaymentRow(idx) { paymentRows.splice(idx, 1); renderPaymentRows(); }
function updatePaymentRow(idx, key, val) { paymentRows[idx][key] = val; calculateFinalBalances(); }

function calculateFinalBalances() {
    const total = parseFloat($('#pTotal').text().replace('$', ''));
    const paid = paymentRows.reduce((s, r) => s + parseFloat(r.amount || 0), 0);
    $('#pTotalPaidDisplay').text('$' + paid.toFixed(2));
    const balance = paid - total;
    $('#pBalanceDisplay').text('$' + balance.toFixed(2)).toggleClass('text-success', balance >= 0).toggleClass('text-danger', balance < 0);
}

function updatePaymentSummary() {
    const subtotal = POS.cart.reduce((s, i) => s + (i.price * i.quantity), 0);
    const disc = POS.discount.type === 'fixed' ? POS.discount.value : (subtotal * (POS.discount.value / 100));
    const total = Math.ceil(subtotal + POS.shipping + POS.tax - disc);

    $('#pItemCount').text(POS.cart.length + ' Items');
    $('#pSub').text('$' + subtotal.toFixed(2));
    $('#pDisc').text('-$' + disc.toFixed(2));
    $('#pShip').text('$' + POS.shipping.toFixed(2));
    $('#pTax').text('$' + POS.tax.toFixed(2));
    $('#pTotal').text('$' + total.toFixed(2));

    $('#pSummaryList').html(POS.cart.map(i => `
        <div class="d-flex align-items-center mb-3 bg-white p-2 rounded-3 border-light border">
            <img src="${i.image_url}" class="rounded-2 me-3" style="width: 40px; height: 40px; object-fit: cover;">
            <div class="flex-grow-1 min-w-0">
                <p class="mb-0 small fw-bold text-dark text-truncate">${i.name}</p>
                <span class="text-muted extra-small">${i.quantity} x $${i.price.toFixed(2)}</span>
            </div>
            <span class="fw-bold text-dark ms-2">$${(i.price * i.quantity).toFixed(2)}</span>
        </div>
    `).join(''));

    paymentRows = [{ method_id: POS_CONFIG.data.payment_methods[0].id, amount: total.toFixed(2) }];
    renderPaymentRows();
}

function saveWalkInInfo() {
    POS.walk_in_name = $('#walkInName').val() || 'Walk-in Customer';
    POS.walk_in_phone = $('#walkInPhone').val() || 'N/A';
    POS.walk_in_email = $('#walkInEmail').val() || 'N/A';
    POS.walk_in_address = $('#walkInAddress').val() || '';
    toastr.success('Saved');
}

function restoreOrder(id) {
    Swal.fire({
        title: 'Restore Order?',
        text: "This will clear your current cart!",
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes, restore it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.get(`${POS_CONFIG.routes.hold_orders_edit}/${id}`, function (order) {
                // Clear state
                POS.cart = [];
                POS.editing_order_id = order.id;
                POS.discount = { type: 'fixed', value: parseFloat(order.discount) };
                POS.shipping = parseFloat(order.shipping_cost);
                POS.tax = parseFloat(order.tax);
                
                // Handle Coupon
                if (order.coupon_id) {
                    // We need coupon details if we want to restore it fully in POS state
                    // For now, let's assume the order record has enough, or we might need another call.
                    // But if it's already a processed hold order, we might just want to show the discount.
                    // Let's set a dummy coupon object for UI if needed, OR better: the backend should return it.
                    // Since editHoldOrder includes customer, let's check if it includes coupon.
                    if (order.coupon) {
                        POS.coupon = order.coupon;
                        $('#couponStatus').removeClass('d-none');
                        $('#couponMessage').text(`✓ ${order.coupon.code} restored!`);
                    } else {
                        // If coupon details not joined, at least we have the discount amount.
                        // But let's check if PosService.php joins it.
                    }
                } else {
                    POS.coupon = null;
                    $('#couponStatus').addClass('d-none');
                }
                if (order.customer_id) {
                    $('#walkInToggle').prop('checked', false).trigger('change');
                    const opt = new Option(order.customer.name, order.customer.id, true, true);
                    $('#customerSelect').append(opt).trigger('change');
                } else {
                    $('#walkInToggle').prop('checked', true).trigger('change');
                    $('#walkInName').val(order.name);
                    $('#walkInPhone').val(order.phone);
                    $('#walkInEmail').val(order.email);
                    $('#walkInAddress').val(order.address);
                    saveWalkInInfo();
                }

                // Add Items to Cart via Batch Add
                const itemsToBatch = order.items.map(item => ({
                    product_id: item.product_id,
                    variant_id: item.variant_id,
                    quantity: item.quantity
                }));

                $.post(POS_CONFIG.routes.cart_batch_add, { items: itemsToBatch }, function (res) {
                    if (res.success) {
                        POS.cart = res.cart.items;
                        renderCart();
                        $('#holdOrdersModal').modal('hide');
                        $('#checkoutBtn').html('Update & Pay <i class="fa fa-arrow-right ms-1"></i>');
                        $('#holdBtn').html('Update Hold <i class="fa fa-pause-circle"></i>');
                        toastr.success('Order restored');
                    } else {
                        toastr.error(res.message || 'Error restoring items');
                    }
                });
            });
        }
    });
}

function deleteOrder(id) {
    Swal.fire({
        title: 'Delete Order?',
        text: "Are you sure you want to delete this hold order?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `${POS_CONFIG.routes.hold_orders_delete}/${id}`,
                type: 'DELETE',
                data: { _token: $('meta[name="csrf-token"]').attr('content') },
                success: function (res) {
                    if (res.success) {
                        toastr.success(res.message);
                        if (typeof fetchHoldOrders === 'function') fetchHoldOrders();
                    } else {
                        toastr.error(res.message);
                    }
                }
            });
        }
    });
}
