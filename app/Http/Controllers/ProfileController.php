<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\QRData;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Fetch all QR records for this user
        $qrData = QRData::where('user_id', $user->id)->get();

        return view('profile.index', compact('user', 'qrData'));

    }

    public function updateName(Request $request)
    {
        $user = Auth::user();
        $user->name = $request->name;
        $user->save();

        return response()->json(['success' => true]);
    }

}   