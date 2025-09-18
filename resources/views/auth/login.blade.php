@extends('layouts.sleeky')

@section('title', 'Sign In')

@section('content')
<section class="section">
    <div class="section-container">
        <div class="section-header">
            <h2 class="section-title">Sign In</h2>
        </div>
        <div class="contact-content">
            <div class="contact-form">
                <form method="POST" action="{{ route('login') }}" id="loginForm">
                    @csrf
                    
                    <div class="form-group">
                        <label for="email">Email Address *</label>
                        <input 
                            type="email" 
                            id="email"
                            name="email" 
                            class="form-control"
                            value="{{ old('email') }}" 
                            placeholder="Enter your email address"
                            required
                            autocomplete="email"
                            autofocus
                        >
                        @error('email') 
                            <div class="error">{{ $message }}</div> 
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password">Password *</label>
                        <input 
                            type="password" 
                            id="password"
                            name="password" 
                            class="form-control"
                            placeholder="Enter your password"
                            required
                            autocomplete="current-password"
                        >
                        @error('password') 
                            <div class="error">{{ $message }}</div> 
                        @enderror
                    </div>

                    <div class="form-group">
                        <label style="display: flex; align-items: center; font-weight: normal; cursor: pointer;">
                            <input 
                                type="checkbox" 
                                name="remember" 
                                style="margin-right: 0.5rem; width: auto;"
                            >
                            Remember me
                        </label>
                    </div>

                    <button type="submit" class="submit-btn" id="submitBtn">
                        <span id="btnText">Sign In</span>
                        <span id="btnSpinner" class="spinner hidden"></span>
                    </button>
                </form>

                <div class="text-center mt-4" style="line-height: 4;">
                    <p style="color: var(--text-secondary);">
                        Don't have an account? 
                        <a href="{{ route('register') }}" style="color: var(--primary-color); text-decoration: none; font-weight: 600;">
                            Create one here
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection