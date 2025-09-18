@extends('layouts.sleeky')

@section('title', 'Create Account')

@section('content')
<section class="section">
    <div class="section-container">
        <div class="section-header">
            <h2 class="section-title">Create Your Account</h2>
        </div>
        <div class="contact-content">
            <div class="contact-form">
                <form method="POST" action="{{ route('register') }}" id="registerForm">
                    @csrf
                    <div class="form-row">
                        <div class="form-column">
                            <div class="form-group">
                                <label for="name">Full Name *</label>
                                <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" placeholder="Enter your full name" required autocomplete="name">
                                @error('name') <div class="error">{{ $message }}</div> @enderror
                            </div>

                            <div class="form-group">
                                <label for="email">Email Address *</label>
                                <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="Enter your email address" required autocomplete="email">
                                @error('email') <div class="error">{{ $message }}</div> @enderror
                            </div>

                            <div class="form-group">
                                <label for="password">Password *</label>
                                <input type="password" id="password" name="password" class="form-control" placeholder="Create a strong password" required autocomplete="new-password">
                                @error('password') <div class="error">{{ $message }}</div> @enderror
                            </div>

                            <div class="form-group">
                                <label for="password_confirmation">Confirm Password *</label>
                                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="Confirm your password" required autocomplete="new-password">
                                @error('password_confirmation') <div class="error">{{ $message }}</div> @enderror
                            </div>
                            
                            <div class="form-group">
                                <label for="phone">Phone Number *</label>
                                <input type="tel" id="phone" name="phone" class="form-control" value="{{ old('phone') }}" placeholder="Enter your phone number" required autocomplete="tel">
                                @error('phone') <div class="error">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="form-column">
                            <div class="form-group">
                                <label for="country">Country *</label>
                                <select name="country" id="country" class="form-control" required>
                                    <option value="">Select your country</option>
                                    @foreach ($countries as $country)
                                        <option value="{{ $country->country_id }}" {{ old('country') == $country->country_id ? 'selected' : '' }}>
                                            {{ $country->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('country') <div class="error">{{ $message }}</div> @enderror
                            </div>

                            <div class="form-group">
                                <label for="state">State *</label>
                                <select name="state" id="state" class="form-control" required>
                                    <option value="">Select your state</option>
                                </select>
                                @error('state') <div class="error">{{ $message }}</div> @enderror
                            </div>

                            <div class="form-group">
                                <label for="city">City *</label>
                                <input type="text" id="city" name="city" class="form-control" value="{{ old('city') }}" placeholder="Enter your city" required autocomplete="address-level2">
                                @error('city') <div class="error">{{ $message }}</div> @enderror
                            </div>

                            <div class="form-group">
                                <label for="pincode">Pincode *</label>
                                <input type="text" id="pincode" name="pincode" class="form-control" value="{{ old('pincode') }}" placeholder="Enter your pincode" required pattern="[0-9]{5,10}" autocomplete="postal-code">
                                @error('pincode') <div class="error">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="submit-btn" id="submitBtn">
                        <span id="btnText">Create Account</span>
                        <span id="btnSpinner" class="spinner hidden"></span>
                    </button>
                </form>

                <div class="text-center mt-4" style="line-height: 4;">
                    <p style="color: var(--text-secondary);">
                        Already have an account? 
                        <a href="{{ route('login') }}" style="color: var(--primary-color); text-decoration: none; font-weight: 600;">
                            Sign in here
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    function fetchStates() {
        const countryId = $('#country').val();
        const stateSelect = $('#state');
        const submitBtn = $('#submitBtn');
        
        stateSelect.html('<option value="">Select your state</option>').prop('disabled', true);
        
        if (countryId) {
            submitBtn.addClass('loading').find('#btnText').text('Loading states...');
            submitBtn.find('#btnSpinner').removeClass('hidden');

            $.ajax({
                url: `/api/states/${countryId}`,
                type: 'GET',
                dataType: 'json',
                success: function(states) {
                    states.forEach(state => {
                        stateSelect.append(`<option value="${state.stateid}">${state.state_name}</option>`);
                    });
                    const oldState = '{{ old("state") }}';
                    if (oldState) {
                        stateSelect.val(oldState);
                    }
                },
                error: function(error) {
                    console.error('Error fetching states:', error);
                    stateSelect.html('<option value="">Error loading states</option>');
                },
                complete: function() {
                    stateSelect.prop('disabled', false);
                    submitBtn.removeClass('loading').find('#btnText').text('Create Account');
                    submitBtn.find('#btnSpinner').addClass('hidden');
                }
            });
        }
    }

    $('#country').on('change', fetchStates);

    if ($('#country').val()) {
        fetchStates();
    }

    $('#registerForm').on('submit', function() {
        const submitBtn = $('#submitBtn');
        submitBtn.addClass('loading').find('#btnText').text('Creating Account...');
        submitBtn.find('#btnSpinner').removeClass('hidden');
    });
});
</script>
@endpush