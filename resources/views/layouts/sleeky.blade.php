<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Digital Footprint') - Digital Footprint</title>
    <link rel="stylesheet" href="{{ asset('css/templatemo-sleeky-pro.css') }}">
    
    @stack('styles')
</head>
<body>
    <!-- Header -->
    <header class="header" id="header">
        <nav class="nav-container">
            <a href="{{ route('home') }}" class="logo-container">
                
                <img src="{{ asset('images/logo.svg') }}"  width="30" alt="The Digital Footprint Logo">
                <span class="logo-text">The Digital Footprint</span>
            </a>
            <ul class="nav-menu" id="navMenu">
                <li><a href="{{ route('home') }}" class="nav-link active">Home</a></li>
                <li><a href="{{ route('qr.create') }}" class="nav-link">Create QR</a></li>
                <li><a href="{{ route('qr.index') }}" class="nav-link">QR Codes</a></li>
                <li><a href="{{ route('categories.index') }}" class="nav-link">Categories</a></li>
                @auth
                    <li><a href="{{ route('profile') }}" class="nav-link">Profile</a></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <a href="{{ route('logout') }}" class="nav-link" onclick="event.preventDefault(); this.closest('form').submit();">
                                Logout
                            </a>
                        </form>
                    </li>
                @else
                    <li><a href="{{ route('login') }}" class="nav-link">Login</a></li>
                    <li><a href="{{ route('register') }}" class="nav-link">Register</a></li>
                @endauth
            </ul>
            <button class="hamburger" id="hamburger">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </nav>
    </header>

    <main class="main-content">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">
            <div class="footer-main">
                <div class="footer-brand">
                    <div class="footer-logo">
                                        <img src="{{ asset('images/logo.svg') }}"  width="30" alt="The Digital Footprint Logo">

                        <span class="footer-logo-text">The Digital Footprint</span>
                    </div>
                    <p class="footer-description">Join the easiest QR Code generator and start creating dynamic QR Codes that are fully editable and trackable.</p>
                    <div class="footer-social">
                        <a href="#" class="social-link">📘</a>
                        <a href="#" class="social-link">📷</a>
                        <a href="#" class="social-link">🐦</a>
                        <a href="#" class="social-link">💼</a>
                    </div>
                </div>
                
                <div class="footer-section">
                    <h4>Services</h4>
                    <div class="footer-links">
                        <a href="#" class="footer-link">Landscape Photography</a>
                        <a href="#" class="footer-link">Alpine Expeditions</a>
                        <a href="#" class="footer-link">Fine Art Prints</a>
                        <a href="#" class="footer-link">Custom Commissions</a>
                        <a href="#" class="footer-link">Workshops</a>
                    </div>
                </div>
                
                <div class="footer-section">
                    <h4>Company</h4>
                    <div class="footer-links">
                        <a href="#" class="footer-link">About Us</a>
                        <a href="#" class="footer-link">Our Story</a>
                        <a href="#" class="footer-link">Careers</a>
                        <a href="#" class="footer-link">Press</a>
                        <a href="#" class="footer-link">Blog</a>
                    </div>
                </div>
                
                <div class="footer-section">
                    <h4>Support</h4>
                    <div class="footer-links">
                        <a href="#" class="footer-link">Help Center</a>
                        <a href="#" class="footer-link">Privacy Policy</a>
                        <a href="#" class="footer-link">Terms of Service</a>
                        <a href="#" class="footer-link">Shipping Info</a>
                        <a href="#" class="footer-link">Returns</a>
                    </div>
                </div>
            </div>
            
            <div class="footer-bottom">
                <div class="footer-copyright">
                    © {{ date('Y') }}  The Digital Footprint. All rights reserved.
                </div>
                <div class="footer-credits">
                    
                </div>
            </div>
        </div>
    </footer>

    <script src="{{ asset('js/templatemo-sleeky-scripts.js') }}"></script>
    @stack('scripts')
</body>
</html>