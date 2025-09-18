<div class="left-menu">
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
