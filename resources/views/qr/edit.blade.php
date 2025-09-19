@extends('layouts.app')
@section('body-class', 'create-qr edit-qr')

@section('title', 'Edit QR Code')

@section('content')
<div class="with-left-panel">
    <div class="left-panel">
        @include('layouts.left-menu')
    </div>
    <div class="main-panel">
        <section class="section">
            <div class="section-container">
                <div class="section-header">
                    <h2 class="section-title">Edit QR Code</h2>
                    <p>Modify the details and design of your QR code</p>
                </div>

                <div class="qr-creator-container">

            <form method="POST" action="{{ route('qr.update', $qrCode->qrcode_id) }}" enctype="multipart/form-data" id="qrForm">
                @csrf
                @method('PUT')
                <div class="qr-creator-layout-2-row">
                    <div class="qr-creator-panel">
                        <div class="design-section">
                            <h3><i class="fas fa-stream"></i> Content Type</h3>
                            <div class="content-type-tabs-scroll">
                                <div class="content-type-tabs">
                                    @php
                                        $icons = [
                                            'url' => 'fas fa-link',
                                            'pdf' => 'fas fa-file-pdf',
                                            'plain-text' => 'fas fa-font',
                                            'sms' => 'fas fa-sms',
                                            'phone' => 'fas fa-phone',
                                            'multi-url' => 'fas fa-globe',
                                            'file' => 'fas fa-file',
                                            'contact' => 'fas fa-address-card',
                                            'socials' => 'fas fa-share-alt',
                                            'app' => 'fas fa-mobile-alt',
                                            'location' => 'fas fa-map-marked-alt',
                                            'email' => 'fas fa-envelope-open-text',
                                        ];
                                        $activeCategoryId = old('category_id', $qrCode->category_id);
                                    @endphp
                                    @foreach($categories as $cat)
                                        <button type="button" class="content-tab {{ $cat->category_id == $activeCategoryId ? 'active' : '' }}" 
                                                data-category="{{ $cat->category_id }}" 
                                                data-name="{{ $cat->category_name }}"
                                                data-key="{{ $cat->category_key }}">
                                            <i class="{{ $icons[$cat->category_key] ?? 'fas fa-qrcode' }}"></i>
                                            <span>{{ ucfirst($cat->category_name) }}</span>
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="qr-creator-layout-3-col">
                        <!-- Column 1: Content -->
                        <div class="qr-creator-panel">
                            <input type="hidden" name="category_id" id="category_id" value="{{ $activeCategoryId }}">
                            
                            <div class="design-section">
                                <h3><i class="fas fa-info-circle"></i> Details</h3>
                                <div class="form-group">
                                    <label for="title">Title</label>
                                    <input type="text" name="title" id="title" class="form-control" placeholder="Enter a title for your QR code" value="{{ old('title', $qrCode->title) }}">
                                </div>
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select name="status" id="status" class="form-control" required>
                                <option value="enabled" {{ old('status', $qrCode->status) == 'enabled' ? 'selected' : '' }}>Enabled</option>
                                <option value="disabled" {{ old('status', $qrCode->status) == 'disabled' ? 'selected' : '' }}>Disabled</option>
                            </select>
                        </div>
                    </div>

                    <div class="design-section">
                        <h3><i class="fas fa-edit"></i> Content</h3>
                        <div id="content-inputs">
                            <!-- URL Input -->
                            <div class="content-input" id="input-url" style="display: {{ $qrCode->category_id == 1 ? 'block' : 'none' }};">
                                <label>Enter URL:</label>
                                <input type="url" name="content" placeholder="https://example.com" data-required="true" value="{{ old('content', is_string($qrCode->content) ? $qrCode->content : '') }}" class="form-control">
                            </div>
                            
                            <!-- PDF Input -->
                            <div class="content-input" id="input-pdf" style="display: {{ $qrCode->category_id == 2 ? 'block' : 'none' }};">
                                <label>Upload PDF:</label>
                                <div class="file-upload">
                                    <input type="file" name="content" accept="application/pdf" data-required="true">
                                    <div class="file-upload-text">
                                        <i class="fas fa-cloud-upload-alt"></i>
                                        <span>Choose PDF file or drag & drop</span>
                                    </div>
                                </div>
                                @if($qrCode->category_id == 2 && $qrCode->content)
                                    <p>Current file: {{ basename($qrCode->content) }}</p>
                                @endif
                            </div>
                            
                            <!-- Text Input -->
                            <div class="content-input" id="input-plain-text" style="display: {{ $qrCode->category_id == 3 ? 'block' : 'none' }};">
                                <label>Enter Text:</label>
                                <textarea name="content" placeholder="Enter your text here..." data-required="true" class="form-control">{{ old('content', is_string($qrCode->content) ? $qrCode->content : '') }}</textarea>
                            </div>
                            
                            <!-- SMS Input -->
                            <div class="content-input" id="input-sms" style="display: {{ $qrCode->category_id == 4 ? 'block' : 'none' }};">
                                @php
                                    // Parse SMS content: sms:1234567890?body=message
                                    $smsPhone = '';
                                    $smsMessage = '';
                                    if ($qrCode->category_id == 4 && is_string($qrCode->content)) {
                                        if (preg_match('/^sms:([^?]+)\?body=(.*)$/', $qrCode->content, $matches)) {
                                            $smsPhone = $matches[1];
                                            $smsMessage = urldecode($matches[2]);
                                        }
                                    }
                                @endphp
                                <label>Phone Number:</label>
                                <input type="tel" name="phone" placeholder="+1234567890" data-required="true" value="{{ old('phone', $smsPhone) }}" class="form-control">
                                <label>Message:</label>
                                <textarea name="message" placeholder="Enter your message..." data-required="true" class="form-control">{{ old('message', $smsMessage) }}</textarea>
                            </div>
                            
                            <!-- Phone Input -->
                            <div class="content-input" id="input-phone" style="display: {{ $qrCode->category_id == 5 ? 'block' : 'none' }};">
                                @php
                                    // Parse Phone content: tel:1234567890
                                    $phoneNumber = '';
                                    if ($qrCode->category_id == 5 && is_string($qrCode->content)) {
                                        if (preg_match('/^tel:(.+)$/', $qrCode->content, $matches)) {
                                            $phoneNumber = $matches[1];
                                        }
                                    }
                                @endphp
                                <label>Phone Number:</label>
                                <input type="tel" name="phone" placeholder="+1234567890" data-required="true" value="{{ old('phone', $phoneNumber) }}" class="form-control">
                            </div>

                            <!-- Email Input -->
                            <div class="content-input" id="input-email" style="display: {{ $qrCode->category_id == 14 ? 'block' : 'none' }};">
                                <label>Email To:</label>
                                <input type="email" name="email[to]" placeholder="recipient@example.com" data-required="true" value="{{ old('email.to', is_array($qrCode->content) ? ($qrCode->content['to'] ?? '') : '') }}" class="form-control">
                                <label>Subject:</label>
                                <input type="text" name="email[subject]" placeholder="Email Subject" value="{{ old('email.subject', is_array($qrCode->content) ? ($qrCode->content['subject'] ?? '') : '') }}" class="form-control">
                                <label>Body:</label>
                                <textarea name="email[body]" placeholder="Email body..." class="form-control">{{ old('email.body', is_array($qrCode->content) ? ($qrCode->content['body'] ?? '') : '') }}</textarea>
                            </div>


                            <!-- vCard Input -->
                            <div class="content-input" id="input-contact" style="display: {{ $qrCode->category_id == 8 ? 'block' : 'none' }};">
                                <div class="form-row">
                                    <div class="form-column">
                                        <label>First Name:</label>
                                        <input type="text" name="vcard[first_name]" data-required="true" value="{{ old('vcard.first_name', is_array($qrCode->content) ? ($qrCode->content['first_name'] ?? '') : '') }}" class="form-control">
                                        <label>Last Name:</label>
                                        <input type="text" name="vcard[last_name]" data-required="true" value="{{ old('vcard.last_name', is_array($qrCode->content) ? ($qrCode->content['last_name'] ?? '') : '') }}" class="form-control">
                                        <label>Prefix:</label>
                                        <input type="text" name="vcard[prefix]" value="{{ old('vcard.prefix', is_array($qrCode->content) ? ($qrCode->content['prefix'] ?? '') : '') }}" class="form-control">
                                        <label>Organization:</label>
                                        <input type="text" name="vcard[organization]" value="{{ old('vcard.organization', is_array($qrCode->content) ? ($qrCode->content['organization'] ?? '') : '') }}" class="form-control">
                                        <label>Job Title:</label>
                                        <input type="text" name="vcard[job_title]" value="{{ old('vcard.job_title', is_array($qrCode->content) ? ($qrCode->content['job_title'] ?? '') : '') }}" class="form-control">
                                    </div>
                                    <div class="form-column">
                                        <label>Email:</label>
                                        <input type="email" name="vcard[email]" value="{{ old('vcard.email', is_array($qrCode->content) ? ($qrCode->content['email'] ?? '') : '') }}" class="form-control">
                                        <label>Phone:</label>
                                        <input type="tel" name="vcard[phone]" value="{{ old('vcard.phone', is_array($qrCode->content) ? ($qrCode->content['phone'] ?? '') : '') }}" class="form-control">
                                        <label>Mobile Phone:</label>
                                        <input type="tel" name="vcard[mobile_phone]" value="{{ old('vcard.mobile_phone', is_array($qrCode->content) ? ($qrCode->content['mobile_phone'] ?? '') : '') }}" class="form-control">
                                        <label>Fax:</label>
                                        <input type="tel" name="vcard[fax]" value="{{ old('vcard.fax', is_array($qrCode->content) ? ($qrCode->content['fax'] ?? '') : '') }}" class="form-control">
                                        <label>URL/Website/Social:</label>
                                        <input type="url" name="vcard[url]" value="{{ old('vcard.url', is_array($qrCode->content) ? ($qrCode->content['url'] ?? '') : '') }}" class="form-control">
                                    </div>
                                </div>
                                <h4>Address</h4>
                                <div class="form-row">
                                    <div class="form-column">
                                        <label>Street:</label>
                                        <input type="text" name="vcard[street]" value="{{ old('vcard.street', is_array($qrCode->content) ? ($qrCode->content['street'] ?? '') : '') }}" class="form-control">
                                        <label>City:</label>
                                        <input type="text" name="vcard[city]" value="{{ old('vcard.city', is_array($qrCode->content) ? ($qrCode->content['city'] ?? '') : '') }}" class="form-control">
                                        <label>Region:</label>
                                        <input type="text" name="vcard[region]" value="{{ old('vcard.region', is_array($qrCode->content) ? ($qrCode->content['region'] ?? '') : '') }}" class="form-control">
                                    </div>
                                    <div class="form-column">
                                        <label>Postcode:</label>
                                        <input type="text" name="vcard[postcode]" value="{{ old('vcard.postcode', is_array($qrCode->content) ? ($qrCode->content['postcode'] ?? '') : '') }}" class="form-control">
                                        <label>Country:</label>
                                        <input type="text" name="vcard[country]" value="{{ old('vcard.country', is_array($qrCode->content) ? ($qrCode->content['country'] ?? '') : '') }}" class="form-control">
                                    </div>
                                </div>
                            </div>

                            <!-- Socials Input -->
                            <div class="content-input" id="input-socials" style="display: {{ $qrCode->category_id == 9 ? 'block' : 'none' }};">
                                <div class="social-link-creator">
                                    <div class="social-radios">
                                        <label><input type="radio" name="social_platform" value="facebook" checked> <i class="fab fa-facebook"></i></label>
                                        <label><input type="radio" name="social_platform" value="twitter"> <i class="fab fa-twitter"></i></label>
                                        <label><input type="radio" name="social_platform" value="instagram"> <i class="fab fa-instagram"></i></label>
                                        <label><input type="radio" name="social_platform" value="linkedin"> <i class="fab fa-linkedin"></i></label>
                                        <label><input type="radio" name="social_platform" value="youtube"> <i class="fab fa-youtube"></i></label>
                                        <label><input type="radio" name="social_platform" value="whatsapp"> <i class="fab fa-whatsapp"></i></label>
                                        <label><input type="radio" name="social_platform" value="snapchat"> <i class="fab fa-snapchat"></i></label>
                                        <label><input type="radio" name="social_platform" value="tiktok"> <i class="fab fa-tiktok"></i></label>
                                        <label><input type="radio" name="social_platform" value="spotify"> <i class="fab fa-spotify"></i></label>
                                    </div>
                                    <div class="social-input-group">
                                        <input type="text" id="social-url" class="form-control" placeholder="Enter URL or username">
                                        <button type="button" id="add-social-link" class="btn btn-secondary">Add</button>
                                    </div>
                                </div>
                                <div id="social-links-list">
                                    @if($qrCode->category_id == 9 && is_array($qrCode->content) && !empty($qrCode->content))
                                        @foreach($qrCode->content as $index => $social)
                                            <div class="social-link-item" id="social-item-{{ $index }}">
                                                <span><i class="fab fa-{{ $social['platform'] }}"></i> {{ $social['url'] }}</span>
                                                <div>
                                                    <input type="hidden" name="socials[{{ $index }}][platform]" value="{{ $social['platform'] }}">
                                                    <input type="hidden" name="socials[{{ $index }}][url]" value="{{ $social['url'] }}">
                                                    <button type="button" class="remove-social-link" data-id="social-item-{{ $index }}">Remove</button>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>

                            <!-- App Input -->
                            <div class="content-input" id="input-app" style="display: {{ $qrCode->category_id == 10 ? 'block' : 'none' }};">
                                <label>URL for Android:</label>
                                <input type="url" name="app[android]" placeholder="https://play.google.com/store/apps/details?id=..." value="{{ old('app.android', is_array($qrCode->content) ? ($qrCode->content['android'] ?? '') : '') }}">
                                <label>URL for iOS:</label>
                                <input type="url" name="app[ios]" placeholder="https://apps.apple.com/app/..." value="{{ old('app.ios', is_array($qrCode->content) ? ($qrCode->content['ios'] ?? '') : '') }}">
                                <label>URL for other devices:</label>
                                <input type="url" name="app[other]" placeholder="https://example.com/app" value="{{ old('app.other', is_array($qrCode->content) ? ($qrCode->content['other'] ?? '') : '') }}">
                            </div>

                            <!-- Location Input -->
                            <div class="content-input" id="input-location" style="display: {{ $qrCode->category_id == 11 ? 'block' : 'none' }};">
                                <label>Latitude:</label>
                                <input type="text" name="location[latitude]" data-required="true" value="{{ old('location.latitude', is_array($qrCode->content) ? ($qrCode->content['latitude'] ?? '') : '') }}" class="form-control">
                                <label>Longitude:</label>
                                <input type="text" name="location[longitude]" data-required="true" value="{{ old('location.longitude', is_array($qrCode->content) ? ($qrCode->content['longitude'] ?? '') : '') }}" class="form-control">
                            </div>

                            <!-- Multi-URL Input -->
                            <div class="content-input" id="input-multi-url" style="display: {{ $qrCode->category_id == 6 ? 'block' : 'none' }};">
                                <button type="button" id="addMultiUrlBtn" class="btn btn-secondary">Add URL</button>
                                <div id="multi-url-list">
                                    @if($qrCode->category_id == 6 && is_array($qrCode->content) && !empty($qrCode->content))
                                        @foreach($qrCode->content as $index => $url)
                                            <div class="multi-url-item" id="multi-url-item-{{ $index }}">
                                                <span>{{ $url['title'] }} - {{ $url['url'] }}</span>
                                                <div>
                                                    <input type="hidden" name="multi_url[{{ $index }}][title]" value="{{ $url['title'] }}">
                                                    <input type="hidden" name="multi_url[{{ $index }}][url]" value="{{ $url['url'] }}">
                                                    <button type="button" class="edit-multi-url" data-id="multi-url-item-{{ $index }}">Edit</button>
                                                    <button type="button" class="remove-multi-url">Remove</button>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>

                            <!-- File Input -->
                            <div class="content-input" id="input-file" style="display: {{ $qrCode->category_id == 7 ? 'block' : 'none' }};">
                                <label>Upload File (Max: 5MB):</label>
                                <div class="file-upload">
                                    <input type="file" name="content" data-required="true">
                                    <div class="file-upload-text">
                                        <i class="fas fa-cloud-upload-alt"></i>
                                        <span>Choose a document, image, video, or audio file</span>
                                    </div>
                                </div>
                                @if($qrCode->category_id == 7 && $qrCode->content)
                                    <p>Current file: {{ basename($qrCode->content) }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Column 2: Design -->
                <div class="qr-creator-panel">
                    <div class="design-section">
                        <h3><i class="fas fa-shield-alt"></i> Security</h3>
                        <div class="security-options">
                            <label class="checkbox-label">
                                <input type="checkbox" name="is_protected" value="Y" {{ old('is_protected', $qrCode->is_protected) == 'Y' ? 'checked' : '' }}>
                                <span class="checkmark"></span>
                                Password Protected
                            </label>
                            <div class="password-field" id="password-field" style="display: {{ old('is_protected', $qrCode->is_protected) == 'Y' ? 'block' : 'none' }};">
                                <label>Set New Password (optional):</label>
                                <input type="password" name="qrcode_password" placeholder="Enter new password" minlength="4">
                            </div>
                            <label>Expire Date:</label>
                            <input type="date" name="end_date" class="form-control" value="{{ old('end_date', $qrCode->end_date ? \Carbon\Carbon::parse($qrCode->end_date)->format('Y-m-d') : '') }}">
                        </div>
                    </div>
                </div>

                <!-- Column 3: Preview -->
                <div class="qr-creator-panel preview-panel">
                    <button type="submit" class="btn btn-primary create-qr-btn w-100">
                        <i class="fas fa-save"></i>
                        Save Changes
                    </button>
                </div>
            </div>
        </div>
    </form>

        </section>
</div>

<!-- Multi-URL Modal -->
<div id="multiUrlModal" class="modal">
    <div class="modal-content">
        <span class="close" id="closeMultiUrlModal">&times;</span>
        <h2 id="multiUrlModalTitle">Add URL</h2>
        <form id="multiUrlForm">
            <div class="form-group">
                <label for="multiUrlTitle">Title</label>
                <input type="text" id="multiUrlTitle" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="multiUrl">URL</label>
                <input type="url" id="multiUrl" class="form-control" required>
            </div>
            <input type="hidden" id="multiUrlEditId">
            <button type="submit" id="multiUrlSubmitButton" class="btn btn-primary">Add</button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    let qrCode = null;
    let previewTimeout = null;

    function initQRCode() {
        const previewContainer = $('#qr-preview');
        previewContainer.html(''); // Clear placeholder
        var options = {
            text: "{{ is_string($qrCode->content) ? $qrCode->content : 'Enter content to see preview' }}",
            width: 300,
            height: 300,
            colorDark: "{{ $qrCode->pixel_color ?? '#000000' }}",
            colorLight: "{{ $qrCode->bg_color ?? '#ffffff' }}",
            PO: "{{ $qrCode->square_color ?? '#000000' }}",
            PI: "{{ $qrCode->square_color ?? '#000000' }}",
            dotScale: {{ $qrCode->pattern_style == 'rounded' ? 0.7 : ($qrCode->pattern_style == 'dots' ? 0.4 : ($qrCode->pattern_style == 'smooth' ? 0.6 : ($qrCode->pattern_style == 'diamond' ? 0.5 : ($qrCode->pattern_style == 'star' ? 0.5 : ($qrCode->pattern_style == 'cross' ? 0.5 : 1))))) }},
            logo: "{{ $qrCode->logo_path ? asset('storage/' . $qrCode->logo_path) : '' }}",
            logoWidth: {{ $qrCode->logo_size ?? 30 }},
            logoHeight: {{ $qrCode->logo_size ?? 30 }},
            logoMargin: {{ $qrCode->logo_margin ?? 4 }},
            correctLevel: QRCode.CorrectLevel.H
        };
        qrCode = new QRCode(previewContainer[0], options);
    }

    function selectContentType(categoryId, categoryKey) {
        $('#category_id').val(categoryId);
        $('.content-tab').removeClass('active');
        $('.content-tab[data-category="' + categoryId + '"]').addClass('active');

        $('.content-input input, .content-input textarea').removeAttr('required');
        $('.content-input').hide();

        const inputId = '#input-' + categoryKey;
        $(inputId).show();
        $(inputId).find('[data-required="true"]').attr('required', 'required');
        
        updatePreview();
    }

    $('.content-tab').on('click', function() {
        const categoryId = $(this).data('category');
        const categoryKey = $(this).data('name').toLowerCase().replace(' ', '-');
        selectContentType(categoryId, categoryKey);
    });

    function updateColorFromHex(colorInputId, hexValue) {
        if (hexValue.match(/^#[0-9A-Fa-f]{6}$/i)) {
            $('#' + colorInputId).val(hexValue);
            updatePreview();
        }
    }

    $('#bg-color').on('change', updatePreview);
    $('#square-color').on('change', updatePreview);
    $('#pixel-color').on('change', updatePreview);
    $('#bg-color-hex').on('change', function() { updateColorFromHex('bg-color', this.value) });
    $('#square-color-hex').on('change', function() { updateColorFromHex('square-color', this.value) });
    $('#pixel-color-hex').on('change', function() { updateColorFromHex('pixel-color', this.value) });

    function selectPattern(pattern) {
        $('.pattern-btn').removeClass('active');
        $('.pattern-btn[data-pattern="' + pattern + '"]').addClass('active');
        $('#pattern-style').val(pattern);
        updatePreview();
    }

    $('.pattern-btn').on('click', function() {
        selectPattern($(this).data('pattern'));
    });

    let currentLogo = "{{ $qrCode->logo_path ? asset('storage/' . $qrCode->logo_path) : null }}";
    function handleLogoUpload(input) {
        const file = input.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                currentLogo = e.target.result;
                $('#logo-image').attr('src', currentLogo);
                $('#logo-preview').show();
                updatePreview();
            };
            reader.readAsDataURL(file);
        }
    }

    $('#logo-upload').on('change', function() { handleLogoUpload(this) });

    function removeLogo() {
        currentLogo = null;
        $('#logo-upload').val('');
        $('#logo-preview').hide();
        updatePreview();
    }

    $('.remove-logo').on('click', removeLogo);

    function togglePasswordField() {
        const isChecked = $('input[name="is_protected"]').is(':checked');
        const passwordField = $('#password-field');
        const passwordInput = passwordField.find('input');

        if (isChecked) {
            passwordField.show();
            passwordInput.attr('required', 'required');
        } else {
            passwordField.hide();
            passwordInput.removeAttr('required').val('');
        }
    }

    $('input[name="is_protected"]').on('change', togglePasswordField);

    function updatePreview() {
        clearTimeout(previewTimeout);
        previewTimeout = setTimeout(generatePreview, 300);
    }

    function generatePreview() {
        const content = getContentForPreview();
        if (!content) {
            if(qrCode) qrCode.clear();
            return;
        }

        const bgColor = $('#bg-color').val();
        const pixelColor = $('#pixel-color').val();
        const squareColor = $('#square-color').val();
        const pattern = $('#pattern-style').val();
        const logoSize = $('#logo-size').val();
        const logoMargin = $('#logo-margin').val();

        let dotScale = 1;
        switch(pattern) {
            case 'rounded': dotScale = 0.7; break;
            case 'dots': dotScale = 0.4; break;
            case 'smooth': dotScale = 0.6; break;
            case 'diamond': dotScale = 0.5; break;
            case 'star': dotScale = 0.5; break;
            case 'cross': dotScale = 0.5; break;
        }

        const previewContainer = $('#qr-preview');
        previewContainer.html('');
        var options = {
            text: content,
            width: 300,
            height: 300,
            colorDark: pixelColor,
            colorLight: bgColor,
            PO: squareColor,
            PI: squareColor,
            dotScale: dotScale,
            logo: currentLogo,
            logoWidth: logoSize,
            logoHeight: logoSize,
            logoMargin: parseInt(logoMargin),
            correctLevel: QRCode.CorrectLevel.H
        };
        qrCode = new QRCode(previewContainer[0], options);
    }

    function getContentForPreview() {
        const activeInput = $('.content-input:visible');
        if (!activeInput.length) return null;

        const categoryKey = $('.content-tab.active').data('name').toLowerCase().replace(' ', '-');

        switch(categoryKey) {
            case 'url': return activeInput.find('input[name="content"]').val();
            case 'pdf': return activeInput.find('input[name="content"]')[0].files[0]?.name || "{{ is_string($qrCode->content) ? $qrCode->content : null }}";
            case 'plain-text': return activeInput.find('textarea[name="content"]').val();
            case 'sms':
                const phone = activeInput.find('input[name="phone"]').val();
                const message = activeInput.find('textarea[name="message"]').val();
                return phone && message ? `SMSTO:${phone}:${message}` : null;
            case 'phone': return `TEL:${activeInput.find('input[name="phone"]').val()}`;
            case 'contact':
                const firstName = activeInput.find('input[name="vcard[first_name]"]').val();
                const lastName = activeInput.find('input[name="vcard[last_name]"]').val();
                return `vCard for ${firstName} ${lastName}`;
            case 'location': 
                const lat = activeInput.find('input[name="location[latitude]"]').val();
                const lng = activeInput.find('input[name="location[longitude]"]').val();
                return lat && lng ? `geo:${lat},${lng}` : null;
            case 'email':
                const to = activeInput.find('input[name="email[to]"]').val();
                const subject = activeInput.find('input[name="email[subject]"]').val();
                return to ? `mailto:${to}${subject ? '?subject=' + subject : ''}` : null;
            case 'socials': return 'Social media links';
            case 'app': return 'App download links';
            case 'multi-url': return 'Multiple URLs';
            case 'file': return activeInput.find('input[name="content"]')[0].files[0]?.name || "{{ is_string($qrCode->content) ? $qrCode->content : null }}";
            default: return null;
        }
    }

    let multiUrlCounter = {{ count(is_array($qrCode->content) ? $qrCode->content : []) }};
    function addOrUpdateMultiUrl(event) {
        event.preventDefault();
        const title = $('#multiUrlTitle').val().trim();
        const url = $('#multiUrl').val().trim();
        const editId = $('#multiUrlEditId').val();

        if (!title || !url) {
            alert('Please fill in both the title and URL.');
            return;
        }

        if (editId) {
            const item = $('#' + editId);
            item.find('span').text(`${title} - ${url}`);
            item.find('input[name$="[title]"]').val(title);
            item.find('input[name$="[url]"]').val(url);
        } else {
            const newItem = `
                <div class="multi-url-item" id="multi-url-item-${multiUrlCounter}">
                    <span>${title} - ${url}</span>
                    <div>
                        <input type="hidden" name="multi_url[${multiUrlCounter}][title]" value="${title}">
                        <input type="hidden" name="multi_url[${multiUrlCounter}][url]" value="${url}">
                        <button type="button" class="edit-multi-url" data-id="multi-url-item-${multiUrlCounter}">Edit</button>
                        <button type="button" class="remove-multi-url">Remove</button>
                    </div>
                </div>`;
            $('#multi-url-list').append(newItem);
            multiUrlCounter++;
        }

        closeModal('multiUrlModal');
        $('#multiUrlForm')[0].reset();
        $('#multiUrlModalTitle').text('Add URL');
        $('#multiUrlSubmitButton').text('Add');
        $('#multiUrlEditId').val('');
    }

    $('#multiUrlForm').on('submit', addOrUpdateMultiUrl);

    function editMultiUrl(itemId) {
        const item = $('#' + itemId);
        const title = item.find('input[name$="[title]"]').val();
        const url = item.find('input[name$="[url]"]').val();

        $('#multiUrlModalTitle').text('Edit URL');
        $('#multiUrlSubmitButton').text('Update');
        $('#multiUrlTitle').val(title);
        $('#multiUrl').val(url);
        $('#multiUrlEditId').val(itemId);

        $('#multiUrlModal').addClass('active');
    }

    $('#multi-url-list').on('click', '.edit-multi-url', function() {
        editMultiUrl($(this).data('id'));
    });

    $('#multi-url-list').on('click', '.remove-multi-url', function() {
        $(this).closest('.multi-url-item').remove();
    });

    function openModal(modalId) {
        $('#multiUrlModalTitle').text('Add URL');
        $('#multiUrlSubmitButton').text('Add');
        $('#multiUrlEditId').val('');
        $('#multiUrlTitle').val('');
        $('#multiUrl').val('');
        $('#' + modalId).addClass('active');
    }

    function closeModal(modalId) {
        $('#' + modalId).removeClass('active');
    }

    $('#addMultiUrlBtn').on('click', function() {
        openModal('multiUrlModal');
    });

    $('#closeMultiUrlModal').on('click', function() {
        closeModal('multiUrlModal');
    });

    $('#logo-size').on('input', function() {
        $('#logo-size-value').text(this.value + '%');
        updatePreview();
    });

    $('#logo-margin').on('input', function() {
        $('#logo-margin-value').text(this.value + 'px');
        updatePreview();
    });

    $('#qrForm').on('submit', function(e) {
        $('.content-input').each(function() {
            if ($(this).is(':visible')) {
                $(this).find('input, textarea, select').prop('disabled', false).each(function() {
                    if ($(this).attr('data-required')) {
                        $(this).attr('required', 'required');
                    }
                });
            } else {
                $(this).find('input, textarea, select').prop('disabled', true).removeAttr('required').val('');
            }
        });

        const passwordCheckbox = $('input[name="is_protected"]');
        if (passwordCheckbox.length) {
            const passwordInput = $('input[name="qrcode_password"]');
            if (passwordCheckbox.is(':checked')) {
                passwordInput.attr('required', 'required');
            } else {
                passwordInput.removeAttr('required');
            }
        }
    });

    // Initial setup
    initQRCode();
    const firstVisibleInput = $('.content-input:visible');
    if (firstVisibleInput.length) {
        firstVisibleInput.find('[data-required="true"]').attr('required', 'required');
    }
    $('input, textarea').on('input', updatePreview);

    let socialCounter = {{ count(is_array($qrCode->content) && $qrCode->category_id == 9 ? $qrCode->content : []) }};
    $('#add-social-link').on('click', function() {
        const platform = $('input[name="social_platform"]:checked').val();
        const url = $('#social-url').val().trim();

        if (!url) {
            alert('Please enter a URL or username.');
            return;
        }

        const newItem = `
            <div class="social-link-item" id="social-item-${socialCounter}">
                <span><i class="fab fa-${platform}"></i> ${url}</span>
                <div>
                    <input type="hidden" name="socials[${socialCounter}][platform]" value="${platform}">
                    <input type="hidden" name="socials[${socialCounter}][url]" value="${url}">
                    <button type="button" class="remove-social-link" data-id="social-item-${socialCounter}">Remove</button>
                </div>
            </div>`;
        $('#social-links-list').append(newItem);
        socialCounter++;
        $('#social-url').val('');
    });

    $('#social-links-list').on('click', '.remove-social-link', function() {
        const itemId = $(this).data('id');
        $('#' + itemId).remove();
    });
});
</script>
@endpush

@push('styles')
<style>
.social-links {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 1rem;
}
.social-link-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}
.social-link-item i {
    font-size: 1.5rem;
    width: 25px;
    text-align: center;
}
.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0,0,0,0.4);
}
.modal.active {
    display: flex;
    align-items: center;
    justify-content: center;
}
.modal-content {
    background-color: #fefefe;
    margin: auto;
    padding: 20px;
    border: 1px solid #888;
    width: 80%;
    max-width: 500px;
    border-radius: 5px;
    position: relative;
}
.close {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
    position: absolute;
    top: 0;
    right: 10px;
}
.close:hover,
.close:focus {
    color: black;
    text-decoration: none;
    cursor: pointer;
}
.social-radios {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    margin-bottom: 1rem;
}
.social-input-group {
    display: flex;
    gap: 0.5rem;
}
#social-links-list {
    margin-top: 1rem;
}
</style>
@endpush