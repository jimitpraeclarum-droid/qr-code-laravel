@extends('layouts.app')

@section('title', 'Create QR Code')

@section('content')
<div class="qr-creator-container">
    <div class="qr-header">
        <h1>Create QR Code</h1>
        <p>Design and customize your QR code with our advanced editor</p>
    </div>

    <form method="POST" action="{{ route('qr.store') }}" enctype="multipart/form-data" id="qrForm">
        @csrf
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
            'email' => 'fas fa-envelope-open-text',
            'wifi' => 'fas fa-wifi',
            'vcard' => 'fas fa-address-card',
            'event' => 'fas fa-calendar-check',
            'location' => 'fas fa-map-marked-alt',
            'socials' => 'fas fa-share-alt',
            'app' => 'fas fa-mobile-alt',
            'multi-url' => 'fas fa-globe',
            'file' => 'fas fa-file',
        ];
        $activeCategoryId = $qrCreationData['category_id'] ?? $categories->first()->category_id;
    @endphp
    @foreach($categories as $cat)
                            <button type="button" class="content-tab {{ $cat->category_id == $activeCategoryId ? 'active' : '' }}" 
                                    data-category="{{ $cat->category_id }}" 
                                    data-name="{{ $cat->category_name }}">
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
                    <input type="hidden" name="category_id" id="category_id" value="{{ $qrCreationData['category_id'] ?? 1 }}">
                    
                    <div class="design-section">
                        <h3><i class="fas fa-info-circle"></i> Details</h3>
                        <div class="form-group">
                            <label for="title">Title</label>
                            <input type="text" name="title" id="title" class="form-control" placeholder="Enter a title for your QR code" value="{{ $qrCreationData['title'] ?? '' }}">
                        </div>
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select name="status" id="status" class="form-control" required>
                                <option value="enabled" {{ (isset($qrCreationData['status']) && $qrCreationData['status'] == 'enabled') ? 'selected' : '' }}>Enabled</option>
                                <option value="disabled" {{ (isset($qrCreationData['status']) && $qrCreationData['status'] == 'disabled') ? 'selected' : '' }}>Disabled</option>
                            </select>
                        </div>
                    </div>

                    <div class="design-section">
                        <h3><i class="fas fa-edit"></i> Content</h3>
                        <div id="content-inputs">
                            <!-- URL Input -->
                            <div class="content-input" id="input-url" style="display: {{ ($qrCreationData['category_id'] ?? 1) == 1 ? 'block' : 'none' }};">
                                <label>Enter URL:</label>
                                <input type="url" name="content" placeholder="https://example.com" data-required="true" value="{{ $qrCreationData['content'] ?? '' }}" class="form-control">
                            </div>
                            
                            <!-- PDF Input -->
                            <div class="content-input" id="input-pdf" style="display: {{ ($qrCreationData['category_id'] ?? 0) == 2 ? 'block' : 'none' }};">
                                <label>Upload PDF:</label>
                                <div class="file-upload">
                                    <input type="file" name="content" accept="application/pdf" data-required="true">
                                    <div class="file-upload-text">
                                        <i class="fas fa-cloud-upload-alt"></i>
                                        <span>Choose PDF file or drag & drop</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Text Input -->
                            <div class="content-input" id="input-plain-text" style="display: {{ ($qrCreationData['category_id'] ?? 0) == 3 ? 'block' : 'none' }};">
                                <label>Enter Text:</label>
                                <textarea name="content" placeholder="Enter your text here..." data-required="true" class="form-control">{{ $qrCreationData['content'] ?? '' }}</textarea>
                            </div>
                            
                            <!-- SMS Input -->
                            <div class="content-input" id="input-sms" style="display: {{ ($qrCreationData['category_id'] ?? 0) == 4 ? 'block' : 'none' }};">
                                <label>Phone Number:</label>
                                <input type="tel" name="phone" placeholder="+1234567890" data-required="true" value="{{ $qrCreationData['phone'] ?? '' }}" class="form-control">
                                <label>Message:</label>
                                <textarea name="message" placeholder="Enter your message..." data-required="true" class="form-control">{{ $qrCreationData['message'] ?? '' }}</textarea>
                            </div>
                            
                            <!-- Phone Input -->
                            <div class="content-input" id="input-phone" style="display: {{ ($qrCreationData['category_id'] ?? 0) == 5 ? 'block' : 'none' }};">
                                <label>Phone Number:</label>
                                <input type="tel" name="phone" placeholder="+1234567890" data-required="true" value="{{ $qrCreationData['phone'] ?? '' }}" class="form-control">
                            </div>

                            <!-- Email Input -->
                            <div class="content-input" id="input-email" style="display: {{ ($qrCreationData['category_id'] ?? 0) == 6 ? 'block' : 'none' }};">
                                <label>Email To:</label>
                                <input type="email" name="email[to]" placeholder="recipient@example.com" data-required="true" value="{{ $qrCreationData['email']['to'] ?? '' }}" class="form-control">
                                <label>Subject:</label>
                                <input type="text" name="email[subject]" placeholder="Email Subject" value="{{ $qrCreationData['email']['subject'] ?? '' }}" class="form-control">
                                <label>Body:</label>
                                <textarea name="email[body]" placeholder="Email body..." class="form-control">{{ $qrCreationData['email']['body'] ?? '' }}</textarea>
                            </div>


                            <!-- vCard Input -->
                            <div class="content-input" id="input-contact" style="display: {{ ($qrCreationData['category_id'] ?? 0) == 8 ? 'block' : 'none' }};">
                                <div class="form-row">
                                    <div class="form-column">
                                        <label>First Name:</label>
                                        <input type="text" name="vcard[first_name]" data-required="true" value="{{ $qrCreationData['vcard']['first_name'] ?? '' }}" class="form-control">
                                        <label>Last Name:</label>
                                        <input type="text" name="vcard[last_name]" data-required="true" value="{{ $qrCreationData['vcard']['last_name'] ?? '' }}" class="form-control">
                                        <label>Prefix:</label>
                                        <input type="text" name="vcard[prefix]" value="{{ $qrCreationData['vcard']['prefix'] ?? '' }}" class="form-control">
                                        <label>Organization:</label>
                                        <input type="text" name="vcard[organization]" value="{{ $qrCreationData['vcard']['organization'] ?? '' }}" class="form-control">
                                        <label>Job Title:</label>
                                        <input type="text" name="vcard[job_title]" value="{{ $qrCreationData['vcard']['job_title'] ?? '' }}" class="form-control">
                                    </div>
                                    <div class="form-column">
                                        <label>Email:</label>
                                        <input type="email" name="vcard[email]" value="{{ $qrCreationData['vcard']['email'] ?? '' }}" class="form-control">
                                        <label>Phone:</label>
                                        <input type="tel" name="vcard[phone]" value="{{ $qrCreationData['vcard']['phone'] ?? '' }}" class="form-control">
                                        <label>Mobile Phone:</label>
                                        <input type="tel" name="vcard[mobile_phone]" value="{{ $qrCreationData['vcard']['mobile_phone'] ?? '' }}" class="form-control">
                                        <label>Fax:</label>
                                        <input type="tel" name="vcard[fax]" value="{{ $qrCreationData['vcard']['fax'] ?? '' }}" class="form-control">
                                        <label>URL/Website/Social:</label>
                                        <input type="url" name="vcard[url]" value="{{ $qrCreationData['vcard']['url'] ?? '' }}" class="form-control">
                                    </div>
                                </div>
                                <h4>Address</h4>
                                <div class="form-row">
                                    <div class="form-column">
                                        <label>Street:</label>
                                        <input type="text" name="vcard[street]" value="{{ $qrCreationData['vcard']['street'] ?? '' }}" class="form-control">
                                        <label>City:</label>
                                        <input type="text" name="vcard[city]" value="{{ $qrCreationData['vcard']['city'] ?? '' }}" class="form-control">
                                        <label>Region:</label>
                                        <input type="text" name="vcard[region]" value="{{ $qrCreationData['vcard']['region'] ?? '' }}" class="form-control">
                                    </div>
                                    <div class="form-column">
                                        <label>Postcode:</label>
                                        <input type="text" name="vcard[postcode]" value="{{ $qrCreationData['vcard']['postcode'] ?? '' }}" class="form-control">
                                        <label>Country:</label>
                                        <input type="text" name="vcard[country]" value="{{ $qrCreationData['vcard']['country'] ?? '' }}" class="form-control">
                                    </div>
                                </div>
                            </div>

                            <!-- Event Input -->
                            <div class="content-input" id="input-event" style="display: {{ ($qrCreationData['category_id'] ?? 0) == 9 ? 'block' : 'none' }};">
                                <label>Event Title:</label>
                                <input type="text" name="event[title]" data-required="true" value="{{ $qrCreationData['event']['title'] ?? '' }}" class="form-control">
                                <label>Location:</label>
                                <input type="text" name="event[location]" value="{{ $qrCreationData['event']['location'] ?? '' }}" class="form-control">
                                <label>Start Time:</label>
                                <input type="datetime-local" name="event[start]" value="{{ $qrCreationData['event']['start'] ?? '' }}" class="form-control">
                                <label>End Time:</label>
                                <input type="datetime-local" name="event[end]" value="{{ $qrCreationData['event']['end'] ?? '' }}" class="form-control">
                                <label>Description:</label>
                                <textarea name="event[description]" class="form-control">{{ $qrCreationData['event']['description'] ?? '' }}</textarea>
                            </div>

                            <!-- Location Input -->
                            <div class="content-input" id="input-location" style="display: {{ ($qrCreationData['category_id'] ?? 0) == 10 ? 'block' : 'none' }};">
                                <label>Latitude:</label>
                                <input type="text" name="location[latitude]" data-required="true" value="{{ $qrCreationData['location']['latitude'] ?? '' }}" class="form-control">
                                <label>Longitude:</label>
                                <input type="text" name="location[longitude]" data-required="true" value="{{ $qrCreationData['location']['longitude'] ?? '' }}" class="form-control">
                            </div>

                            <!-- Socials Input -->
                            <div class="content-input" id="input-socials" style="display: none;">
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
                                <div id="social-links-list"></div>
                            </div>

                            <!-- App Input -->
                            <div class="content-input" id="input-app" style="display: none;">
                                <label>URL for Android:</label>
                                <input type="url" name="app[android]" placeholder="https://play.google.com/store/apps/details?id=...">
                                <label>URL for iOS:</label>
                                <input type="url" name="app[ios]" placeholder="https://apps.apple.com/app/...">
                                <label>URL for other devices:</label>
                                <input type="url" name="app[other]" placeholder="https://example.com/app">
                            </div>

                            <!-- Multi-URL Input -->
                            <div class="content-input" id="input-multi-url" style="display: none;">
                                <button type="button" id="addMultiUrlBtn" class="btn btn-secondary">Add URL</button>
                                <div id="multi-url-list"></div>
                            </div>

                            <!-- File Input -->
                            <div class="content-input" id="input-file" style="display: none;">
                                <label>Upload File (Max: 5MB):</label>
                                <div class="file-upload">
                                    <input type="file" name="content" data-required="true">
                                    <div class="file-upload-text">
                                        <i class="fas fa-cloud-upload-alt"></i>
                                        <span>Choose a document, image, video, or audio file</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Column 2: Design -->
                <div class="qr-creator-panel">
                    <div class="design-section">
                        <h3><i class="fas fa-palette"></i> Design</h3>
                        <div class="color-options">
                            <h4>Colors</h4>
                            <div class="color-group">
                                <label>Background:</label>
                                <div class="color-picker-group">
                                    <input type="color" id="bg-color" name="bg_color" value="#ffffff">
                                    <input type="text" id="bg-color-hex" value="#ffffff">
                                </div>
                            </div>
                            <div class="color-group">
                                <label>Squares:</label>
                                <div class="color-picker-group">
                                    <input type="color" id="square-color" name="square_color" value="#000000">
                                    <input type="text" id="square-color-hex" value="#000000">
                                </div>
                            </div>
                            <div class="color-group">
                                <label>Pixels:</label>
                                <div class="color-picker-group">
                                    <input type="color" id="pixel-color" name="pixel_color" value="#000000">
                                    <input type="text" id="pixel-color-hex" value="#000000">
                                </div>
                            </div>
                        </div>

                        <div class="logo-options">
                            <h4>Logo</h4>
                            <div class="logo-upload">
                                <input type="file" id="logo-upload" name="logo" accept="image/*">
                                <div class="logo-upload-area">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    <span>Upload Logo</span>
                                </div>
                            </div>
                            <div class="logo-preview" id="logo-preview" style="display: none;">
                                <img id="logo-image" src="" alt="Logo Preview">
                                <button type="button" class="remove-logo">×</button>
                            </div>
                            <div class="logo-size">
                                <label>Logo Size:</label>
                                <input type="range" id="logo-size" name="logo_size" min="10" max="100" value="30">
                                <span id="logo-size-value">30%</span>
                            </div>
                            <div class="logo-margin">
                                <label>Logo Margin:</label>
                                <input type="range" id="logo-margin" name="logo_margin" min="0" max="30" value="4">
                                <span id="logo-margin-value">4px</span>
                            </div>
                        </div>

                        <div class="pattern-options">
                            <h4>Pattern & Style</h4>
                            <div class="pattern-grid">
                                <button type="button" class="pattern-btn active" data-pattern="classic">
                                    <div class="pattern-preview classic"></div>
                                    <span>Classic</span>
                                </button>
                                <button type="button" class="pattern-btn" data-pattern="rounded">
                                    <div class="pattern-preview rounded"></div>
                                    <span>Rounded</span>
                                </button>
                                <button type="button" class="pattern-btn" data-pattern="dots">
                                    <div class="pattern-preview dots"></div>
                                    <span>Dots</span>
                                </button>
                                <button type="button" class="pattern-btn" data-pattern="smooth">
                                    <div class="pattern-preview smooth"></div>
                                    <span>Smooth</span>
                                </button>
                                <button type="button" class="pattern-btn" data-pattern="diamond">
                                    <div class="pattern-preview diamond"></div>
                                    <span>Diamond</span>
                                </button>
                                <button type="button" class="pattern-btn" data-pattern="star">
                                    <div class="pattern-preview star"></div>
                                    <span>Star</span>
                                </button>
                                <button type="button" class="pattern-btn" data-pattern="cross">
                                    <div class="pattern-preview cross"></div>
                                    <span>Cross</span>
                                </button>
                            </div>
                            <input type="hidden" id="pattern-style" name="pattern_style" value="classic">
                        </div>
                    </div>
                    <div class="design-section">
                        <h3><i class="fas fa-shield-alt"></i> Security</h3>
                        <div class="security-options">
                            <label class="checkbox-label">
                                <input type="checkbox" name="is_protected" value="Y">
                                <span class="checkmark"></span>
                                Password Protected
                            </label>
                            <div class="password-field" id="password-field" style="display: none;">
                                <label>Set Password:</label>
                                <input type="password" name="qrcode_password" placeholder="Enter password" minlength="4" data-required="true">
                            </div>
                            <label>Expire Date:</label>
                            <input type="date" name="end_date" class="form-control">
                        </div>
                    </div>
                </div>

                <!-- Column 3: Preview -->
                <div class="qr-creator-panel preview-panel">
                    <div class="preview-header">
                        <h3>Preview</h3>
                    </div>
                    <div class="qr-preview-container">
                        <div class="qr-preview" id="qr-preview">
                            <div class="qr-placeholder">
                                <i class="fas fa-qrcode"></i>
                                <p>Enter content to see preview</p>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary create-qr-btn w-100">
                        <i class="fas fa-magic"></i>
                        Create QR Code
                    </button>
                </div>
            </div>
        </div>
    </form>
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
    let previewTimeout = null;

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

    let currentLogo = null;
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
        previewTimeout = setTimeout(function() {
            const formData = new FormData($('#qrForm')[0]);
            formData.set('content', getContentForPreview() || 'Preview');
            if (currentLogo) {
                formData.set('logo_base64', currentLogo);
            }

            $.ajax({
                url: '{{ route("qr.preview") }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                xhrFields: {
                    responseType: 'blob'
                },
                success: function(response) {
                    const previewContainer = $('#qr-preview');
                    const imageUrl = URL.createObjectURL(response);
                    previewContainer.html(`<img src="${imageUrl}" alt="QR Code Preview">`);
                },
                error: function() {
                    const previewContainer = $('#qr-preview');
                    previewContainer.html('<div class="qr-placeholder"><i class="fas fa-exclamation-triangle"></i><p>Preview failed</p></div>');
                }
            });
        }, 300);
    }

    $('input, textarea, select').on('input change', updatePreview);

    function getContentForPreview() {
        const activeInput = $('.content-input:visible');
        if (!activeInput.length) return null;

        const categoryKey = $('.content-tab.active').data('name').toLowerCase().replace(' ', '-');

        switch(categoryKey) {
            case 'url': return activeInput.find('input[name="content"]').val();
            case 'pdf': return activeInput.find('input[name="content"]')[0].files[0]?.name || null;
            case 'plain-text': return activeInput.find('textarea[name="content"]').val();
            case 'sms':
                const phone = activeInput.find('input[name="phone"]').val();
                const message = activeInput.find('textarea[name="message"]').val();
                return phone && message ? `SMSTO:${phone}:${message}` : null;
            case 'phone': return `TEL:${activeInput.find('input[name="phone"]').val()}`;
            case 'file': return activeInput.find('input[name="content"]')[0].files[0]?.name || null;
            case 'vcard':
                const firstName = activeInput.find('input[name="vcard[first_name]"]').val();
                const lastName = activeInput.find('input[name="vcard[last_name]"]').val();
                return `vCard for ${firstName} ${lastName}`;
            case 'socials': return 'Social media links';
            case 'app': return 'App download links';
            case 'location': return `geo:${activeInput.find('input[name="location[latitude]"]').val()},${activeInput.find('input[name="location[longitude]"]').val()}`;
            case 'email':
                const to = activeInput.find('input[name="email[to]"]').val();
                const subject = activeInput.find('input[name="email[subject]"]').val();
                return `mailto:${to}?subject=${subject}`;
            case 'multi-url': return 'Multiple URLs';
            default: return null;
        }
    }

    let multiUrlCounter = $('#multi-url-list .multi-url-item').length;

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
            // Update existing item
            const item = $('#' + editId);
            item.find('span').text(`${title} - ${url}`);
            item.find('input[name$="[title]"]').val(title);
            item.find('input[name$="[url]"]').val(url);
        } else {
            // Add new item
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
        // Reset modal title and button for next add
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
        if ($('#logo-upload').get(0).files.length === 0) {
            $('#logo-upload').remove();
        }

        // Only enable and require visible content inputs, disable and remove required from hidden
        $('.content-input').each(function() {
            if ($(this).is(':visible')) {
                $(this).find('input, textarea, select').prop('disabled', false).each(function() {
                    if ($(this).attr('data-required')) {
                        $(this).attr('required', 'required');
                    }
                });
            } else {
                // Remove required and disable for all hidden fields, but also clear their values to avoid browser validation
                $(this).find('input, textarea, select').prop('disabled', true).removeAttr('required').val('');
            }
        });

        // Password logic
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
    const firstVisibleInput = $('.content-input:visible');
    if (firstVisibleInput.length) {
        firstVisibleInput.find('[data-required="true"]').attr('required', 'required');
    }
    updatePreview();

    let socialCounter = 0;
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