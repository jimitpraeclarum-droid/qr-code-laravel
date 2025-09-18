@extends('layouts.app')

@section('title', 'QR Code')

@section('content')
<div class="container qr-view-container">
    <div class="card">
        <div class="card-header">
            <h2>{{ $qrData->title ?? 'QR Code' }}</h2>
        </div>
        <div class="card-body text-center">
            <div class="qr-code-image-container">
                <img src="{{ asset($qrData->qrcode_image) }}" alt="QR Code">
            </div>

            <div class="qr-code-actions mt-4">
                <a href="{{ asset($qrData->qrcode_image) }}" download="{{ $qrData->title ?? 'qrcode' }}.png" class="btn btn-primary">
                    <i class="fas fa-download"></i> Download QR Code
                </a>
            </div>

            <div class="qr-code-content mt-4">
                @switch($qrData->category->category_key)
                    @case('url')
                        <p>Redirecting to: <a href="{{ $qrData->content }}">{{ $qrData->content }}</a></p>
                        <script>
                            setTimeout(function() {
                                window.location.href = "{{ $qrData->content }}";
                            }, 3000);
                        </script>
                        @break

                    @case('pdf')
                        <p>Your PDF is ready to be viewed or downloaded.</p>
                        <a href="{{ asset('storage/' . $qrData->content) }}" class="btn btn-secondary" target="_blank">View PDF</a>
                        @break

                    @case('file')
                        <p>Your file is ready to be downloaded.</p>
                        <a href="{{ asset('storage/' . $qrData->content) }}" class="btn btn-secondary" target="_blank">Download File</a>
                        @break

                    @case('contact')
                        @php $contact = json_decode($qrData->content, true); @endphp
                        <h3>Contact Details</h3>
                        <ul class="list-group">
                            @foreach($contact as $key => $value)
                                <li class="list-group-item"><strong>{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong> {{ $value }}</li>
                            @endforeach
                        </ul>
                        @break

                    @case('socials')
                        @php $socials = json_decode($qrData->content, true); @endphp
                        <h3>Social Media Links</h3>
                        <div class="social-links-show">
                            @foreach($socials as $platform => $url)
                                <a href="{{ $url }}" class="social-link-item-show" target="_blank">
                                    <i class="fab fa-{{ $platform }}"></i>
                                    <span>{{ ucfirst($platform) }}</span>
                                </a>
                            @endforeach
                        </div>
                        @break

                    @case('app')
                        @php $apps = json_decode($qrData->content, true); @endphp
                        <h3>App Download Links</h3>
                        <div class="app-links">
                            @foreach($apps as $platform => $url)
                                <a href="{{ $url }}" class="btn btn-primary" target="_blank">
                                    <i class="fab fa-{{ $platform }}"></i> Download for {{ ucfirst($platform) }}
                                </a>
                            @endforeach
                        </div>
                        @break

                    @case('location')
                        <h3>Location</h3>
                        <p>{{ $qrData->content }}</p>
                        <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($qrData->content) }}" class="btn btn-primary" target="_blank">Open in Google Maps</a>
                        @break

                    @case('email')
                        @php $email = json_decode($qrData->content, true); @endphp
                        <h3>Email</h3>
                        <p><strong>To:</strong> {{ $email['to'] }}</p>
                        <p><strong>Subject:</strong> {{ $email['subject'] }}</p>
                        <p><strong>Body:</strong></p>
                        <p class="content-text">{{ $email['body'] }}</p>
                        <a href="mailto:{{ $email['to'] }}?subject={{ urlencode($email['subject']) }}&body={{ urlencode($email['body']) }}" class="btn btn-primary">Send Email</a>
                        @break

                    @case('multi-url')
                        @php $urls = json_decode($qrData->content, true); @endphp
                        <h3>Multiple URLs</h3>
                        <ul class="list-group">
                            @foreach($urls as $item)
                                <li class="list-group-item"><a href="{{ $item['url'] }}" target="_blank">{{ $item['title'] }}</a></li>
                            @endforeach
                        </ul>
                        @break

                    @default
                        <p class="content-text">{{ $qrData->content }}</p>
                @endswitch
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.qr-view-container {
    max-width: 600px;
    margin-top: 2rem;
}
.qr-code-image-container {
    padding: 1rem;
    background: #f8fafc;
    border-radius: 8px;
    margin-bottom: 1.5rem;
    display: inline-block;
}
.qr-code-image-container img {
    max-width: 100%;
    height: auto;
    display: block;
}
.content-text {
    background: #f1f5f9;
    padding: 1rem;
    border-radius: 8px;
    white-space: pre-wrap;
    word-break: break-word;
    text-align: left;
}
.social-links-show {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    justify-content: center;
    margin-top: 1rem;
}
.social-link-item-show {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    text-decoration: none;
    color: var(--text-primary);
    transition: all 0.2s;
}
.social-link-item-show:hover {
    background: #f8fafc;
    border-color: #3b82f6;
}
.social-link-item-show i {
    font-size: 1.5rem;
}
.app-links {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    margin-top: 1rem;
}
</style>
@endpush
