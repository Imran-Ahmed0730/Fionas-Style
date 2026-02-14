<div class="modal fade" id="holdOrdersModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0 pb-0">
                <nav class="w-100">
                    <div class="nav nav-tabs border-0" id="order-tabs" role="tablist">
                        <button class="nav-link active fw-bold border-0 bg-transparent text-primary py-3 px-4"
                            id="hold-tab" data-bs-toggle="tab" data-bs-target="#hold-pane" type="button">
                            <i class="fa fa-pause-circle me-1"></i> Hold Orders
                        </button>
                        <button class="nav-link fw-bold border-0 bg-transparent text-muted py-3 px-4" id="recent-tab"
                            data-bs-toggle="tab" data-bs-target="#recent-pane" type="button">
                            <i class="fa fa-history me-1"></i> Recent Orders
                        </button>
                    </div>
                </nav>
                <button type="button" class="btn-close position-absolute top-0 end-0 m-3"
                    data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4 pt-0">
                <div class="tab-content pt-3">
                    <div class="tab-pane fade show active" id="hold-pane">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="border-0">Invoice</th>
                                        <th class="border-0">Customer</th>
                                        <th class="border-0 text-end">Total</th>
                                        <th class="border-0 text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="holdOrdersTable">
                                    <tr>
                                        <td colspan="4" class="text-center py-4">Loading hold orders...</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="recent-pane">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="border-0">Invoice</th>
                                        <th class="border-0">Customer</th>
                                        <th class="border-0 text-end">Total</th>
                                        <th class="border-0 text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="recentOrdersTable">
                                    <tr>
                                        <td colspan="4" class="text-center py-4">Loading recent orders...</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $('#holdOrdersModal').on('show.bs.modal', function () {
        fetchHistory('hold');
        fetchHistory('recent');
    });

    function fetchHistory(type) {
        const url = type === 'hold' ? POS_CONFIG.routes.hold_orders : POS_CONFIG.routes.recent_orders;
        $.get(url, (orders) => {
            let h = '';
            orders.forEach(o => {
                h += `<tr>
                        <td class="fw-bold text-primary">${o.invoice_no}</td>
                        <td>${o.name || 'Walk-in'}</td>
                        <td class="text-end fw-bold">$${parseFloat(o.grand_total).toFixed(2)}</td>
                        <td class="text-center">
                            ${type === 'hold' ?
                        `<button class="btn btn-sm btn-icon btn-round btn-primary me-1" onclick="restoreOrder(${o.id})" title="Restore Order"><i class="fa fa-reply"></i></button>
                         <button class="btn btn-sm btn-icon btn-round btn-danger" onclick="deleteOrder(${o.id})" title="Delete Order"><i class="fa fa-trash"></i></button>` :
                        `<button class="btn btn-sm btn-icon btn-round btn-info" onclick="printInvoice(${o.id})" title="Print Invoice"><i class="fa fa-print"></i></button>`
                    }
                        </td>
                    </tr>`;
            });
            $(`#${type}OrdersTable`).html(h || `<tr><td colspan="4" class="text-center py-4 opacity-50">No orders found</td></tr>`);
        });
    }

    window.fetchHoldOrders = () => fetchHistory('hold');

    function printInvoice(id) {
        window.open(`{{ url('admin/order') }}/${id}/invoice`, '_blank');
    }
</script>