<div class="left-menu">
    <a href="{{ route('home') }}" class="logo">
        <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M4 4H12V12H4V4Z" fill="#3B82F6"/>
            <path d="M4 20H12V28H4V20Z" fill="#3B82F6"/>
            <path d="M20 4H28V12H20V4Z" fill="#3B82F6"/>
            <path d="M16 16H20V20H16V16Z" fill="#A3BFFA"/>
            <path d="M20 20H24V24H20V20Z" fill="#A3BFFA"/>
            <path d="M24 24H28V28H24V24Z" fill="#3B82F6"/>
            <path d="M12 12H16V16H12V12Z" fill="#A3BFFA"/>
        </svg>
        <span>Digital Footprint</span>
    </a>
    <ul>
        <li><a href="{{ route('profile') }}"><i class="fas fa-user"></i> Profile</a></li>
        <li><a href="{{ route('qr.index') }}"><i class="fas fa-qrcode"></i> QR List</a></li>
        <li><a href="{{ route('categories.index') }}"><i class="fas fa-grip-horizontal"></i> Categories</a></li>
        <li><a href="{{ route('qr.create') }}"><i class="fas fa-plus"></i> Create QR</a></li>
        <li>
            <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit" class="btn btn-link"><i class="fas fa-sign-out-alt"></i> Logout</button>
            </form>
        </li>
    </ul>
</div>
