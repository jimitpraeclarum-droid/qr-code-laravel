<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\QRData;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Fetch all QR records for this user
        $qrData = QRData::where('user_id', $user->id)->get();

        return view('profile.index', compact('user', 'qrData'));

    }
}
