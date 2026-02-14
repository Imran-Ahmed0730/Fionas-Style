<!-- Customer Modal -->
<div class="modal fade" id="addCustomerModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0 pb-0">
                <h5 class="fw-bold"><i class="fa fa-user-plus text-primary me-2"></i> New Customer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addCustomerForm">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Full Name *</label>
                        <input type="text" name="name" class="form-control rounded-3" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Phone Number *</label>
                        <input type="text" name="phone" class="form-control rounded-3" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Email Address</label>
                        <input type="email" name="email" class="form-control rounded-3">
                    </div>
                    <div class="mb-0">
                        <label class="form-label small fw-bold">Address</label>
                        <textarea name="address" class="form-control rounded-3" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="submit" class="btn btn-primary w-100 py-2 fw-bold">Save Customer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Walk-in Customer Info Modal -->
<div class="modal fade" id="walkInInfoModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0 pb-0">
                <h5 class="fw-bold"><i class="fa fa-user text-primary me-2"></i> Walk-in Customer Info</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="walkInInfoForm">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Name</label>
                        <input type="text" name="walk_in_name" id="walkInName" class="form-control rounded-3"
                            placeholder="Customer Name">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Phone Number</label>
                        <input type="text" name="walk_in_phone" id="walkInPhone" class="form-control rounded-3"
                            placeholder="Phone Number">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Email</label>
                        <input type="email" name="walk_in_email" id="walkInEmail" class="form-control rounded-3"
                            placeholder="Email Address">
                    </div>
                    <div class="mb-0">
                        <label class="form-label small fw-bold">Address</label>
                        <textarea name="walk_in_address" id="walkInAddress" class="form-control rounded-3"
                            placeholder="Full Address" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-primary w-100 py-2 fw-bold" data-bs-dismiss="modal"
                        onclick="saveWalkInInfo()">Save Info</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Utility Modal (Discount/Shipping) -->
<div class="modal fade" id="utilityModal" tabindex="-1">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0 pb-0">
                <h6 class="fw-bold" id="utilityTitle">Update Value</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3" id="discTypeGroup">
                    <label class="form-label small mb-1">Type</label>
                    <select id="uType" class="form-select rounded-3">
                        <option value="fixed">Fixed ($)</option>
                        <option value="percent">Percent (%)</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label small mb-1" id="uLabel">Value</label>
                    <input type="number" id="uValue" class="form-control rounded-3 shadow-none">
                </div>
                <button id="uSubmit" class="btn btn-primary w-100 fw-bold">Apply Changes</button>
            </div>
        </div>
    </div>
</div>

<!-- Variant Modal -->
<div class="modal fade" id="variantModal" tabindex="-2">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0 pb-0">
                <h5 class="fw-bold" id="vProdName">Select Variant</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4" id="vList">
            </div>
        </div>
    </div>
</div>

<!-- Calculator Modal -->
<div class="modal fade" id="calculatorModal" tabindex="-1">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content border-0 bg-dark text-white rounded-5 shadow-2xl p-4">
            <div class="p-4 mb-4 bg-black rounded-4 text-right">
                <input type="text" id="calcDisplay" class="form-control-plaintext text-white text-5xl fw-black text-end"
                    value="0" readonly>
            </div>
            <div class="row g-2">
                @foreach(['C', '%', '/', '*', '-', '7', '8', '9', '+', '4', '5', '6', '=', '1', '2', '3', '0', '.', 'Del'] as $key)
                    <div class="{{ $key == '0' ? 'col-6' : ($key == '=' ? 'col-3 h-auto' : 'col-3') }}">
                        <button onclick="handleCalc('{{$key}}')"
                            class="btn {{ in_array($key, ['+', '-', '*', '/', '%', '=']) ? 'btn-primary' : (in_array($key, ['C', 'Del']) ? 'btn-danger' : 'btn-dark') }} w-100 py-3 rounded-4 fw-bold fs-5">
                            {{ $key }}
                        </button>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Payment Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-5 overflow-hidden">
            <div class="row g-0" style="min-height: 70vh;">
                <!-- Left: Order Summary -->
                <div class="col-md-5 bg-light p-4 border-end overflow-y-auto custom-scroll" style="max-height: 85vh;">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h6 class="fw-bold mb-0 text-uppercase tracking-wider">Order Summary</h6>
                        <span class="badge bg-primary rounded-pill extra-small" id="pItemCount">0 Items</span>
                    </div>

                    <div id="pSummaryList" class="mb-4"></div>

                    <div class="p-3 bg-white rounded-4 border shadow-sm mt-4">
                        <div class="d-flex justify-content-between mb-2 extra-small text-muted text-uppercase fw-bold">
                            <span>Subtotal</span>
                            <span id="pSub">$0.00</span>
                        </div>
                        <div
                            class="d-flex justify-content-between mb-2 extra-small text-danger fw-black text-uppercase">
                            <span>Discount</span>
                            <span id="pDisc">-$0.00</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2 extra-small text-muted text-uppercase fw-bold">
                            <span>Shipping</span>
                            <span id="pShip">$0.00</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2 extra-small text-muted text-uppercase fw-bold">
                            <span>Tax</span>
                            <span id="pTax">$0.00</span>
                        </div>
                        <hr class="my-2 border-dashed opacity-25">
                        <div class="d-flex justify-content-between align-items-end pt-1">
                            <h6 class="mb-0 fw-black text-dark text-uppercase">Total Payable</h6>
                            <h3 class="mb-0 fw-black text-primary" id="pTotal">$0.00</h3>
                        </div>
                    </div>
                </div>

                <!-- Right: Multi-Payment Logic -->
                <div class="col-md-7 p-4 p-lg-5 bg-white overflow-y-auto custom-scroll position-relative"
                    style="max-height: 85vh;">
                    <button type="button" class="btn-close position-absolute top-0 end-0 m-4 shadow-none"
                        data-bs-dismiss="modal"></button>

                    <div class="mb-4">
                        <h5 class="fw-black text-dark mb-1 text-uppercase tracking-tight">Multi-Payment System</h5>
                        <p class="text-muted extra-small">Add and combine multiple payment methods for this order.</p>
                    </div>

                    <div id="paymentRows" class="mb-4">
                        <!-- Dynamic Payment Rows -->
                    </div>

                    <button class="btn btn-outline-primary btn-sm rounded-3 px-3 mb-4 fw-bold extra-small"
                        id="addPaymentRowBtn">
                        <i class="fa fa-plus-circle me-1"></i> Add Payment Method
                    </button>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded-4 border text-center">
                                <span
                                    class="extra-small fw-bold text-muted text-uppercase mb-1 d-block tracking-wider">Total
                                    Paid</span>
                                <h4 class="mb-0 fw-black text-dark" id="pTotalPaidDisplay">$0.00</h4>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 bg-dark rounded-4 text-center">
                                <span
                                    class="extra-small fw-bold text-white-50 text-uppercase mb-1 d-block tracking-wider">Balance
                                    / Change</span>
                                <h4 class="mb-0 fw-black text-success" id="pBalanceDisplay">$0.00</h4>
                            </div>
                        </div>
                    </div>

                    <div class="d-grid">
                        <button id="pConfirmBtn"
                            class="btn btn-primary py-3 rounded-4 fw-black shadow-lg text-uppercase tracking-widest">
                            Process Order & Print Invoice <i class="fa fa-arrow-right ms-2"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>