<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\State;

class StateController extends Controller
{
    public function getStatesByCountry($country_id)
    {
        $states = State::where('country_id', $country_id)->get(['stateid', 'state_name']);
        return response()->json($states);
    }
}