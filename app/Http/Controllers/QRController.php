<?php
namespace App\Http\Controllers;



use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;
use App\Models\QRData;
use App\Models\QRHistory;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Validation\Rule;
use Jenssegers\Agent\Agent;
use Stevebauman\Location\Facades\Location;

class QRController extends Controller
{

    public function create()
    {
        $categories = Category::where('active', 1)->get();
        return view('qr.create', compact('categories'));
    }


    // AJAX rename QR code title
    public function rename(Request $request, $qrcode_id)
    {
        if (!$request->ajax()) {
            return response()->json(['message' => 'Invalid request'], 400);
        }
        $qrData = QRData::findOrFail($qrcode_id);
        if ($qrData->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
        ]);
        $qrData->title = $request->input('title');
        $qrData->save();
        return response()->json(['title' => $qrData->title, 'message' => 'Title updated successfully.']);
    }

    public function index(Request $request)
    {
        $query = QRData::with('category')->where('user_id', Auth::id());

        if ($request->has('search')) {
            $searchTerm = $request->input('search');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('content', 'like', '%' . $searchTerm . '%')
                  ->orWhere('qrcode_key', 'like', '%' . $searchTerm . '%')
                  ->orWhere('title', 'like', '%' . $searchTerm . '%');
            });
        }

        $qrCodes = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('qr.index', compact('qrCodes'));
    }

    public function preview(Request $request)
    {
        $content = $request->input('content', 'Preview');
        $logoPath = null;

        if ($request->has('logo_base64')) {
            $logoData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $request->input('logo_base64')));
            $logoPath = tempnam(sys_get_temp_dir(), 'qrlogo');
            file_put_contents($logoPath, $logoData);
        }

        // Generate QR code with styling
        $qrImage = $this->generateStyledQRCode($content, $request, $logoPath);

        if ($logoPath && file_exists($logoPath)) {
            unlink($logoPath);
        }

        return response($qrImage)->header('Content-Type', 'image/png');
    }


    public function store(Request $request)
    {
        \Log::info('Store method called');
        if (!Auth::check()) {
            $request->session()->put('qr_creation_data', $request->all());
            return redirect()->route('login')->with('info', 'Please login or register to save your QR code.');
        }

        \Log::info('Request data: ' . json_encode($request->all()));

        try {
            // Common validation
            $validatedData = $request->validate([
                'title'             => ['nullable', 'string', 'max:255'],
                'status'            => ['required', Rule::in(['enabled', 'disabled'])],
                'category_id'       => ['required', 'exists:categories,category_id'],
                'end_date'          => ['nullable','date'], 
                'qrcode_password'   => ['required_if:is_protected,Y','nullable','min:4'],
                // Design validation
                'bg_color'          => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
                'square_color'      => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
                'pixel_color'       => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
                'pattern_style'     => ['nullable', Rule::in(['classic', 'rounded', 'dots', 'smooth'])],
                'frame_style'       => ['nullable', Rule::in(['none', 'rounded', 'square'])],
                'logo_size'         => ['nullable', 'integer', 'min:20', 'max:100'],
                'logo'              => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            ]);
            \Log::info('Common validation passed');

            $category = Category::find($validatedData['category_id']);
            $categoryKey = $category->category_key;

            $content = '';

            switch ($categoryKey) {
                case 'url':
                    $validated = $request->validate(['content' => 'required|url']);
                    $content = $validated['content'];
                    break;
                // ... other cases
            }

            $validatedData['content'] = $content;
            $validatedData['user_id'] = Auth::id();
            $validatedData['created_by'] = Auth::id();
            $validatedData['updated_by'] = Auth::id();
            $validatedData['qrcode_key'] = strtoupper(Str::random(6));

            if ($request->hasFile('logo')) {
                $logoPath = $request->file('logo')->store('uploads/logos', 'public');
                $validatedData['logo_path'] = $logoPath;
            }

            \Log::info('Creating QRData with data: ' . json_encode($validatedData));
            $qrData = QRData::create($validatedData);
            \Log::info('QRData created with ID: ' . $qrData->id);

            // Generate and save QR code image
            $fileName = 'qr_' . $qrData->id . '_' . time() . '.png';
            $filePath = public_path('qrcodes/' . $fileName);
            $qrUrl = route('qr.show', ['qrcode_key' => $qrData->qrcode_key]);
            $logoAbsPath = $qrData->logo_path ? storage_path('app/public/' . $qrData->logo_path) : null;
            $qrImage = $this->generateStyledQRCode($qrUrl, $request, $logoAbsPath);
            file_put_contents($filePath, $qrImage);

            $qrData->update(['qrcode_image' => 'qrcodes/' . $fileName]);

            return redirect()->route('qr.index')->with('success', 'QR Code created successfully!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation failed: ' . json_encode($e->errors()));
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            \Log::error('Error creating QR code: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while creating the QR code.')->withInput();
        }
    }

private function generateStyledQRCode($content, $request, $logoPath = null)
{
    // Get design options from the request
    $bgColor = $request->input('bg_color', '#ffffff');
    $squareColor = $request->input('square_color', '#000000');
    $logoSize = $request->input('logo_size', 30);
    $patternStyle = $request->input('pattern_style', 'square');

    // Basic QR code generation
    $qr = QrCode::format('png')
        ->size(300)
        ->errorCorrection('H')
        ->margin(1);

    // Apply colors
    $qr->backgroundColor(hexdec(substr($bgColor, 1, 2)), hexdec(substr($bgColor, 3, 2)), hexdec(substr($bgColor, 5, 2)));
    $qr->color(hexdec(substr($squareColor, 1, 2)), hexdec(substr($squareColor, 3, 2)), hexdec(substr($squareColor, 5, 2)));

    // Apply pattern style
    $style = 'square'; // default
    if ($patternStyle === 'dots') {
        $style = 'dot';
    } else if ($patternStyle === 'rounded') {
        $style = 'round';
    }
    $qr->style($style);

    // Add logo if it exists
    if ($logoPath && file_exists($logoPath)) {
        $qr->merge($logoPath, $logoSize / 100, true);
    }

    // Generate and return the QR code image data
    return $qr->generate($content);
}



    public function show(Request $request, $qrcode_key)
    {
        $qrData = QRData::where('qrcode_key', $qrcode_key)->select('qrcode_id', 'is_protected', 'end_date', 'content', 'category_id', 'qrcode_image', 'title')->firstOrFail();

        // Handle password protection
        if ($qrData->is_protected == 'Y') {
            // This is a simple example, you would want a more secure way to handle this
            if (!session()->has('qr_access_' . $qrData->id)) {
                return view('qr.password', compact('qrData'));
            }
        }

        // Handle expiration
        if ($qrData->end_date && now()->gt($qrData->end_date)) {
            return view('qr.expired');
        }

        // Get location and device details
        $agent = new Agent();
        $location = Location::get($request->ip());

        // Log the scan in history
        QRHistory::create([
            'qrcode_id' => $qrData->qrcode_id,
            'scanned_at' => now(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'device' => $agent->device(),
            'browser' => $agent->browser(),
            'city' => $location ? $location->cityName : null,
            'state' => $location ? $location->regionName : null,
            'country' => $location ? $location->countryName : null,
        ]);

        return view('qr.show', compact('qrData'));
    }

    public function update(Request $request, $qrcode_id)
    {
        $qrData = QRData::findOrFail($qrcode_id);

        if ($qrData->user_id !== Auth::id()) {
            return redirect()->route('qr.index')->with('error', 'Unauthorized action.');
        }

        $categoryId = (int) $request->input('category_id');
        
        try {
            // Common validation
            $request->validate([
                'title'             => ['nullable', 'string', 'max:255'],
                'status'            => ['required', Rule::in(['enabled', 'disabled'])],
                'end_date'          => ['nullable','date'], 
                'qrcode_password'   => ['nullable','min:4'],
                // Design validation
                'bg_color'          => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
                'square_color'      => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
                'pixel_color'       => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
                'pattern_style'     => ['nullable', Rule::in(['classic', 'rounded', 'dots', 'smooth'])],
                'frame_style'       => ['nullable', Rule::in(['none', 'rounded', 'square'])],
                'logo_size'         => ['nullable', 'integer', 'min:20', 'max:100'],
                'logo'              => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            ]);

            // Validation based on category
            $category = Category::find($categoryId);
            $categoryKey = $category ? $category->category_key : '';

            switch ($categoryKey) {
                case 'url':
                    $request->validate(['content' => 'required|url']);
                    $content = $request->content;
                    break;
                case 'pdf':
                    if ($request->hasFile('content')) {
                        $request->validate(['content' => 'required|file|mimes:pdf|max:2048']);
                        $content = $request->file('content')->store('uploads/pdf', 'public');
                    } else {
                        $content = $qrData->content;
                    }
                    break;
                case 'plain-text':
                    $request->validate(['content' => 'required|string']);
                    $content = $request->content;
                    break;
                case 'sms':
                    $request->validate([
                        'phone'   => 'required|digits_between:10,15',
                        'message' => 'required|string'
                    ]);
                    $content = "sms:" . $request->phone . "?body=" . $request->message;
                    break;
                case 'phone':
                    $request->validate(['phone' => 'required|digits_between:10,15']);
                    $content = "tel:" . $request->phone;
                    break;
                case 'file':
                    if ($request->hasFile('content')) {
                        $request->validate(['content' => 'required|file|max:5120']); // 5MB
                        $content = $request->file('content')->store('uploads/files', 'public');
                    } else {
                        $content = $qrData->content;
                    }
                    break;
                case 'contact':
                    $vcard = $request->input('vcard');
                    $content = json_encode($vcard);
                    break;
                case 'socials':
                    $socials = $request->input('socials');
                    $content = json_encode($socials);
                    break;
                case 'app':
                    $appUrls = $request->input('app');
                    $content = json_encode(array_filter($appUrls));
                    break;
                case 'location':
                    $request->validate([
                        'location.latitude' => 'required|numeric|between:-90,90',
                        'location.longitude' => 'required|numeric|between:-180,180',
                    ]);
                    $content = json_encode($request->location);
                    break;
                case 'email':
                    $request->validate([
                        'email.to' => 'required|email',
                        'email.subject' => 'nullable|string',
                        'email.body' => 'nullable|string',
                    ]);
                    $content = json_encode($request->email);
                    break;
                case 'wifi':
                    $request->validate([
                        'wifi.ssid' => 'required|string',
                        'wifi.password' => 'nullable|string',
                        'wifi.encryption' => ['required', Rule::in(['WPA', 'WEP', 'nopass'])],
                    ]);
                    $content = json_encode($request->wifi);
                    break;
                case 'event':
                    $request->validate([
                        'event.title' => 'required|string',
                        'event.location' => 'nullable|string',
                        'event.start' => 'nullable|date',
                        'event.end' => 'nullable|date|after_or_equal:event.start',
                        'event.description' => 'nullable|string',
                    ]);
                    $content = json_encode($request->event);
                    break;
                case 'multi-url':
                    $multiUrl = $request->input('multi_url');
                    $content = json_encode($multiUrl);
                    break;
                default:
                    $content = $request->content;
            }

        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        }

        // Handle logo upload
        $logoPath = $qrData->logo_path;
        if ($request->hasFile('logo')) {
            $logoFile = $request->file('logo');
            $logoFileName = 'logo_' . time() . '_' . $logoFile->getClientOriginalName();
            $logoPath = $logoFile->storeAs('uploads/logos', $logoFileName, 'public');
        }

        // Update DB
        $qrData->update([
            'title'          => $request->input('title'),
            'status'         => $request->input('status'),
            'category_id'    => $categoryId,
            'content'        => $content,
            'is_protected'   => $request->input('is_protected', 'N'),
            'end_date'       => $request->input('end_date'),
            'qrcode_password'=> $request->input('is_protected') === 'Y' && $request->qrcode_password ? bcrypt($request->qrcode_password) : $qrData->qrcode_password,
            'updated_by'     => Auth::id(),
            // Design settings
            'bg_color'       => $request->input('bg_color', '#ffffff'),
            'square_color'   => $request->input('square_color', '#000000'),
            'pixel_color'    => $request->input('pixel_color', '#000000'),
            'pattern_style'  => $request->input('pattern_style', 'classic'),
            'frame_style'    => $request->input('frame_style', 'none'),
            'logo_path'      => $logoPath,
            'logo_size'      => $request->input('logo_size', 30),
        ]);

        // Regenerate QR code if content or design changed
        $fileName = 'qr_' . $qrData->id . '_' . time() . '.png';
        $filePath = public_path('qrcodes/' . $fileName);

        $qrUrl = route('qr.show', ['qrcode_key' => $qrData->qrcode_key]);
        $qrImage = $this->generateStyledQRCode($qrUrl, $request, $logoPath);
        
        file_put_contents($filePath, $qrImage);

        // Delete old image
        if ($qrData->qrcode_image && file_exists(public_path($qrData->qrcode_image))) {
            unlink(public_path($qrData->qrcode_image));
        }

        $qrData->update(['qrcode_image' => 'qrcodes/' . $fileName]);

        return redirect()->route('qr.index')->with('success', 'QR Code updated successfully!');
    }

    public function destroy($qrcode_id)
    {
        $qrcode = QRData::findOrFail($qrcode_id);

        if ($qrcode->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Optional: Delete associated QR code image file
        if ($qrcode->qrcode_image && file_exists(public_path($qrcode->qrcode_image))) {
            unlink(public_path($qrcode->qrcode_image));
        }

        $qrcode->delete();

        return response()->json([
            'id' => $qrcode->qrcode_id,
            'message' => 'QR code deleted successfully.'
        ]);
    }

    public function edit($qrcode_id)
    {
        $qrCode = QRData::where('qrcode_id', $qrcode_id)->where('user_id', Auth::id())->firstOrFail();
        $categories = Category::where('active', 1)->get();

        // Decode content for certain categories
        $categoryKey = $qrCode->category->category_key;
        if (in_array($categoryKey, ['contact', 'socials', 'app', 'email', 'multi-url'])) {
            $qrCode->content = json_decode($qrCode->content, true);
        }

        return view('qr.edit', compact('qrCode', 'categories'));
    }
}
