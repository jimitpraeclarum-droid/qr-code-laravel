@extends('layouts.sleeky')

@section('title', 'Profile')

@section('content')
<section class="section">
    <div class="section-container">
        <div class="section-header">
            <h2 class="section-title">Profile Page</h2>
        </div>
        <div class="contact-content">
            <div class="contact-form">
                <p><strong>Name:</strong> {{ $user->name }}</p>
                <p><strong>Email:</strong> {{ $user->email }}</p>

                <div style="display: none">
                @foreach($qrData as $qr)
                    <p>{{ $qr->content }}</p>
                    @if($qr->qrcode_image)
                        <img src="{{ asset($qr->qrcode_image) }}" alt="QR Code" width="150">
                    @endif
                @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
@endsection