@extends('backend.master')
@section('title', 'Add Transaction')
@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="page-header">
                <h3 class="fw-bold mb-3">Add Ledger Transaction</h3>
                <ul class="breadcrumbs mb-3">
                    <li class="nav-home">
                        <a href="{{ route('admin.dashboard') }}">
                            <i class="icon-home"></i>
                        </a>
                    </li>
                    <li class="separator">
                        <i class="icon-arrow-right"></i>
                    </li>
                    <li class="nav-item">
                        <a href="#">Accounts</a>
                    </li>
                    <li class="separator">
                        <i class="icon-arrow-right"></i>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.account-report.balance-sheet') }}">Balance Sheet</a>
                    </li>
                    <li class="separator">
                        <i class="icon-arrow-right"></i>
                    </li>
                    <li class="nav-item">
                        <a href="#">Add</a>
                    </li>
                </ul>
            </div>
            <div class="row">
                <div class="col-md-12 mx-auto">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h3 class="card-title">New Transaction</h3>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.account-ledger.store') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Transaction Type</label>
                                    <select name="type" class="form-select @error('type') is-invalid @enderror" required>
                                        <option value="">Select Type</option>
                                        <option value="1" {{ old('type') == 1 ? 'selected' : '' }}>Income (Credit)</option>
                                        <option value="2" {{ old('type') == 2 ? 'selected' : '' }}>Expense (Debit)</option>
                                    </select>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Account Head</label>
                                    <select name="account_head_id" id="account_head_id"
                                        class="form-select @error('account_head_id') is-invalid @enderror" required>
                                        <option value="">Select Account Head</option>
                                        @foreach($accountHeads as $head)
                                            <option value="{{ $head->id }}" data-type="{{ $head->type }}" {{ old('account_head_id') == $head->id ? 'selected' : '' }}>{{ $head->title }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('account_head_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Amount</label>
                                    <input type="number" step="0.01" name="amount"
                                        class="form-control @error('amount') is-invalid @enderror"
                                        value="{{ old('amount') }}" placeholder="Enter amount" required>
                                    @error('amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Particular</label>
                                    <input type="text" name="particular"
                                        class="form-control @error('particular') is-invalid @enderror"
                                        value="{{ old('particular') }}" placeholder="Enter particular" required>
                                    @error('particular')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Comment <small>[optional]</small></label>
                                    <textarea name="comment" class="form-control @error('comment') is-invalid @enderror"
                                        rows="3" placeholder="Enter optional comment">{{ old('comment') }}</textarea>
                                    @error('comment')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary">Save Transaction</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function () {
            const $typeSelect = $('select[name="type"]');
            const $headSelect = $('#account_head_id');
            const $allOptions = $headSelect.find('option').clone();

            function filterHeads() {
                const selectedType = $typeSelect.val();
                $headSelect.empty().append($allOptions.first().clone()); // Keep "Select Account Head"

                if (selectedType) {
                    const $filtered = $allOptions.filter(function () {
                        return $(this).data('type') == selectedType;
                    });
                    $headSelect.append($filtered);
                }

                // Restore old value if possible, otherwise reset
                const oldValue = "{{ old('account_head_id') }}";
                if (oldValue && $headSelect.find(`option[value="${oldValue}"]`).length) {
                    $headSelect.val(oldValue);
                }
            }

            $typeSelect.on('change', filterHeads);

            // Initial filter on page load (for old input or if type is somehow pre-selected)
            if ($typeSelect.val()) {
                filterHeads();
            }
        });
    </script>
@endpush