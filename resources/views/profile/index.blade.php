@extends('layouts.sleeky')

@section('title', 'Profile')

@section('content')

<div class="with-left-panel">
    <div class="left-panel">
        @include('layouts.left-menu')
    </div>
    <div class="main-panel">
        <section class="section">
            <div class="section-container">
                <div class="section-header">
                    <h2 class="section-title">Profile Page</h2>
                </div>
                <div class="contact-content">
                    <div class="contact-form">
                        <p>
                            <strong>Name:</strong> 
                            <span id="name-display">{{ $user->name }}</span>
                            <button id="edit-name-btn" class="btn btn-sm btn-primary">Edit</button>
                        </p>
                        <div id="edit-name-form" style="display: none;">
                            <input type="text" id="name-input" value="{{ $user->name }}">
                            <button id="save-name-btn" class="btn btn-sm btn-success">Save</button>
                            <button id="cancel-edit-btn" class="btn btn-sm btn-secondary">Cancel</button>
                        </div>
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
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#edit-name-btn').on('click', function() {
        $('#name-display').hide();
        $('#edit-name-btn').hide();
        $('#edit-name-form').show();
    });

    $('#cancel-edit-btn').on('click', function() {
        $('#edit-name-form').hide();
        $('#name-display').show();
        $('#edit-name-btn').show();
    });

    $('#save-name-btn').on('click', function() {
        var newName = $('#name-input').val();
        $.ajax({
            url: '{{ route("profile.updateName") }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                name: newName
            },
            success: function(response) {
                $('#name-display').text(newName).show();
                $('#edit-name-form').hide();
                $('#edit-name-btn').show();
            },
            error: function(response) {
                // Handle error
                alert('Error updating name');
            }
        });
    });
});
</script>
@endpush