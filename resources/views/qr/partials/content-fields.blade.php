{{-- This partial is used for both create and edit QR code forms --}}
<!-- URL Input -->
<div class="content-input" id="input-url" style="display: {{ $categoryKey == 'url' ? 'block' : 'none' }};">
    <label>Enter URL:</label>
    <input type="url" name="content" placeholder="https://example.com" data-required="true" value="{{ old('content', $categoryKey == 'url' ? ($qrCode->content ?? '') : '') }}" class="form-control">
</div>

<!-- PDF Input -->
<div class="content-input" id="input-pdf" style="display: {{ $categoryKey == 'pdf' ? 'block' : 'none' }};">
    @if(is_string($qrCode->content))
        <label>Current PDF: <a href="{{ asset('storage/' . $qrCode->content) }}" target="_blank">View PDF</a></label>
    @else
        <label>No PDF file found.</label>
    @endif
    <label>Upload New PDF (optional):</label>
    <div class="file-upload">
        <input type="file" name="content" accept="application/pdf">
        <div class="file-upload-text">
            <i class="fas fa-cloud-upload-alt"></i>
            <span>Choose new PDF file or drag & drop</span>
        </div>
    </div>
</div>

<!-- Text Input -->
<div class="content-input" id="input-plain-text" style="display: {{ $categoryKey == 'plain-text' ? 'block' : 'none' }};">
    <label>Enter Text:</label>
    <textarea name="content" placeholder="Enter your text here..." data-required="true" class="form-control">{{ old('content', $categoryKey == 'plain-text' ? ($qrCode->content ?? '') : '') }}</textarea>
</div>

<!-- SMS Input -->
<div class="content-input" id="input-sms" style="display: {{ $categoryKey == 'sms' ? 'block' : 'none' }};">
    <label>Phone Number:</label>
    <input type="tel" name="phone" placeholder="+1234567890" data-required="true" value="{{ old('phone', $categoryKey == 'sms' ? (isset($qrCode->content) ? Str::before(Str::after($qrCode->content, 'sms:'), '?body=') : '') : '') }}" class="form-control">
    <label>Message:</label>
    <textarea name="message" placeholder="Enter your message..." data-required="true" class="form-control">{{ old('message', $categoryKey == 'sms' ? (isset($qrCode->content) ? Str::after($qrCode->content, '?body=') : '') : '') }}</textarea>
</div>

<!-- Phone Input -->
<div class="content-input" id="input-phone" style="display: {{ $categoryKey == 'phone' ? 'block' : 'none' }};">
    <label>Phone Number:</label>
    <input type="tel" name="phone" placeholder="+1234567890" data-required="true" value="{{ old('phone', $categoryKey == 'phone' ? (isset($qrCode->content) ? Str::after($qrCode->content, 'tel:') : '') : '') }}" class="form-control">
</div>

<!-- Email Input -->
<div class="content-input" id="input-email" style="display: {{ $categoryKey == 'email' ? 'block' : 'none' }};">
    <label>Email To:</label>
    <input type="email" name="email[to]" placeholder="recipient@example.com" data-required="true" value="{{ old('email.to', $qrCode->content['to'] ?? '') }}" class="form-control">
    <label>Subject:</label>
    <input type="text" name="email[subject]" placeholder="Email Subject" value="{{ old('email.subject', $qrCode->content['subject'] ?? '') }}" class="form-control">
    <label>Body:</label>
    <textarea name="email[body]" placeholder="Email body..." class="form-control">{{ old('email.body', $qrCode->content['body'] ?? '') }}</textarea>
</div>

<!-- WiFi Input -->
<div class="content-input" id="input-wifi" style="display: {{ $categoryKey == 'wifi' ? 'block' : 'none' }};">
    <label>SSID (Network Name):</label>
    <input type="text" name="wifi[ssid]" data-required="true" value="{{ old('wifi.ssid', $qrCode->content['ssid'] ?? '') }}" class="form-control">
    <label>Password:</label>
    <input type="password" name="wifi[password]" value="" class="form-control">
    <label>Encryption:</label>
    <select name="wifi[encryption]" class="form-control">
        <option value="WPA" @if(old('wifi.encryption', $qrCode->content['encryption'] ?? '') == 'WPA') selected @endif>WPA/WPA2</option>
        <option value="WEP" @if(old('wifi.encryption', $qrCode->content['encryption'] ?? '') == 'WEP') selected @endif>WEP</option>
        <option value="nopass" @if(old('wifi.encryption', $qrCode->content['encryption'] ?? '') == 'nopass') selected @endif>No Password</option>
    </select>
    <label class="checkbox-label"><input type="checkbox" name="wifi[hidden]" value="true" @if(old('wifi.hidden', $qrCode->content['hidden'] ?? false)) checked @endif> Hidden Network</label>
</div>

<!-- Event Input -->
<div class="content-input" id="input-event" style="display: {{ $categoryKey == 'event' ? 'block' : 'none' }};">
    <label>Event Title:</label>
    <input type="text" name="event[title]" data-required="true" value="{{ old('event.title', $qrCode->content['title'] ?? '') }}" class="form-control">
    <label>Location:</label>
    <input type="text" name="event[location]" value="{{ old('event.location', $qrCode->content['location'] ?? '') }}" class="form-control">
    <label>Start Time:</label>
    <input type="datetime-local" name="event[start]" value="{{ old('event.start', $qrCode->content['start'] ?? '') }}" class="form-control">
    <label>End Time:</label>
    <input type="datetime-local" name="event[end]" value="{{ old('event.end', $qrCode->content['end'] ?? '') }}" class="form-control">
    <label>Description:</label>
    <textarea name="event[description]" class="form-control">{{ old('event.description', $qrCode->content['description'] ?? '') }}</textarea>
</div>

<!-- Location Input -->
<div class="content-input" id="input-location" style="display: {{ $categoryKey == 'location' ? 'block' : 'none' }};">
    <label>Latitude:</label>
    <input type="text" name="location[latitude]" data-required="true" value="{{ old('location.latitude', $qrCode->content['latitude'] ?? '') }}" class="form-control">
    <label>Longitude:</label>
    <input type="text" name="location[longitude]" data-required="true" value="{{ old('location.longitude', $qrCode->content['longitude'] ?? '') }}" class="form-control">
</div>
