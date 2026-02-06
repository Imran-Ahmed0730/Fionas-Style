@extends('frontend.customer.layout')

@section('customer_content')
    <div class="profile-management">
        <h4 class="mb-4">Profile Management</h4>

        <form action="{{ route('customer.profile.update') }}" method="POST" enctype="multipart/form-data"
            class="profile-form">
            @csrf

            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        <label for="name">Full Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name', $user->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        <label for="username">Username <span class="text-danger">*</span></label>
                        <input type="text" name="username" id="username" class="form-control @error('username') is-invalid @enderror"
                            value="{{ old('username', $customer->username ?? '') }}" required>
                        @error('username')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        <label for="email">Email Address <small class="text-muted">(optional)</small></label>
                        <input type="email" name="email" id="email"
                            class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email', $user->email) }}">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        <label for="phone">Phone Number <span class="text-danger">*</span></label>
                        <input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror"
                            value="{{ old('phone', $customer->phone ?? '') }}" required>
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        <label for="image">Profile Image</label>
                        <input type="file" name="image" id="image"
                            class="form-control-file @error('image') is-invalid @enderror" accept="image/jpg,image/jpeg,image/png">
                        <div class="mt-2">
                            @php
                                $preview = $customer && $customer->image ? asset($customer->image) : asset('backend/assets/img/profile.jpg');
                            @endphp
                            <img id="imagePreview" src="{{ $preview }}" alt="Profile Preview" class="img-thumbnail" style="max-width:120px; max-height:120px; object-fit:cover;" />
                        </div>
                        @error('image')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        <label for="country_id">Country</label>
                        <select name="country_id" id="country_id" class="form-control @error('country_id') is-invalid @enderror">
                            <option value="">Select Country</option>
                            @foreach($countries as $country)
                                <option value="{{ $country->id }}" {{ old('country_id', $customer->country_id) == $country->id ? 'selected' : '' }}>
                                    {{ $country->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('country_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        <label for="state_id">State</label>
                        <select name="state_id" id="state_id" class="form-control @error('state_id') is-invalid @enderror">
                            <option value="">Select State</option>
                            @if($customer->state_id)
                                @php
                                    $states = \App\Models\Admin\State::where('country_id', $customer->country_id)->get();
                                @endphp
                                @foreach($states as $state)
                                    <option value="{{ $state->id }}" {{ old('state_id', $customer->state_id) == $state->id ? 'selected' : '' }}>
                                        {{ $state->name }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                        @error('state_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        <label for="city_id">City</label>
                        <select name="city_id" id="city_id" class="form-control @error('city_id') is-invalid @enderror">
                            <option value="">Select City</option>
                            @if($customer->city_id)
                                @php
                                    $cities = \App\Models\Admin\City::where('state_id', $customer->state_id)->get();
                                @endphp
                                @foreach($cities as $city)
                                    <option value="{{ $city->id }}" {{ old('city_id', $customer->city_id) == $city->id ? 'selected' : '' }}>
                                        {{ $city->name }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                        @error('city_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-12 mb-3">
                    <div class="form-group">
                        <label for="address">Address</label>
                        <textarea name="address" id="address" class="form-control @error('address') is-invalid @enderror"
                            rows="2">{{ old('address', $customer->address ?? '') }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <hr class="my-4">
            <h5 class="mb-3">Change Password (Leave blank to keep current)</h5>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        <label for="password">New Password</label>
                        <div class="input-group">
                            <input type="password" name="password" id="password"
                                class="form-control password-input @error('password') is-invalid @enderror">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary password-toggle" type="button" tabindex="-1"><i class="fa fa-eye"></i></button>
                            </div>
                        </div>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        <label for="password_confirmation">Confirm Password</label>
                        <div class="input-group">
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control password-input">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary password-toggle" type="button" tabindex="-1"><i class="fa fa-eye"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="site-btn">Update Profile</button>
            </div>
        </form>
    </div>
@endsection

@push('js')
    <script>
    $(document).ready(function() {
        $('#country_id').on('change', function() {
            var countryId = $(this).val();
            if (countryId) {
                $.ajax({
                    url: '/customer/get-states/' + countryId,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        $('#state_id').empty();
                        $('#state_id').append('<option value="">Select State</option>');
                        $.each(data, function(key, value) {
                            $('#state_id').append('<option value="' + value.id + '">' + value.name + '</option>');
                        });
                        $('#city_id').empty();
                        $('#city_id').append('<option value="">Select City</option>');
                    }
                });
            } else {
                $('#state_id').empty();
                $('#city_id').empty();
            }
        });

        $('#state_id').on('change', function() {
            var stateId = $(this).val();
            if (stateId) {
                $.ajax({
                    url: '/customer/get-cities/' + stateId,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        $('#city_id').empty();
                        $('#city_id').append('<option value="">Select City</option>');
                        $.each(data, function(key, value) {
                            $('#city_id').append('<option value="' + value.id + '">' + value.name + '</option>');
                        });
                    }
                });
            } else {
                $('#city_id').empty();
            }
        });

        // Image preview for profile image
        $('#image').on('change', function(e) {
            var file = this.files && this.files[0];
            if (!file) return;
            var reader = new FileReader();
            reader.onload = function(evt) {
                $('#imagePreview').attr('src', evt.target.result);
            };
            reader.readAsDataURL(file);
        });

        // Password visibility toggle
        $('.password-toggle').on('click', function() {
            var btn = $(this);
            var input = btn.closest('.input-group').find('.password-input');
            if (!input.length) return;
            if (input.attr('type') === 'password') {
                input.attr('type', 'text');
                btn.find('i').removeClass('fa-eye').addClass('fa-eye-slash');
            } else {
                input.attr('type', 'password');
                btn.find('i').removeClass('fa-eye-slash').addClass('fa-eye');
            }
        });
    });
</script>
@endpush
