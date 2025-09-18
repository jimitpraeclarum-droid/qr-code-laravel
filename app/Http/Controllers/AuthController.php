<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function showRegister()
    {
        $countries = \App\Models\Country::all();
        \Log::info('Countries fetched:', $countries->toArray());
        return view('auth.register', compact('countries'));
    }

    public function register(Request $request)
    {
        \Log::info('Registration attempt:', $request->all());
        
        $request->validate([
            'name'     => 'required|string|max:255|min:2',
            'email'    => 'required|email|unique:users,email|max:255',
            'password' => 'required|string|min:6|confirmed',
            'country'  => 'required|exists:countries,country_id',
            'state'    => 'required|exists:states,stateid',
            'city'     => 'required|string|max:255|min:2',
            'pincode'  => 'required|string|regex:/^\d{5,10}$/',
            'phone'    => 'required|string|regex:/^\+?[\d\s-]{10,15}$/',
        ], [
            'name.required' => 'Full name is required.',
            'name.min' => 'Name must be at least 2 characters.',
            'email.required' => 'Email address is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email address is already registered.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 6 characters.',
            'password.confirmed' => 'Password confirmation does not match.',
            'country.required' => 'Please select a country.',
            'country.exists' => 'Selected country is invalid.',
            'state.required' => 'Please select a state.',
            'state.exists' => 'Selected state is invalid.',
            'city.required' => 'City is required.',
            'city.min' => 'City name must be at least 2 characters.',
            'pincode.required' => 'Pincode is required.',
            'pincode.regex' => 'Pincode must be 5-10 digits.',
            'phone.required' => 'Phone number is required.',
            'phone.regex' => 'Please enter a valid phone number.',
        ]);

        try {
            \Log::info('Creating user with data:', [
                'name' => trim($request->name),
                'email' => strtolower(trim($request->email)),
                'country_id' => $request->country,
                'state_id' => $request->state,
                'city' => trim($request->city),
                'pincode' => $request->pincode,
                'phone' => $request->phone,
            ]);

            $user = User::create([
                'name'       => trim($request->name),
                'email'      => strtolower(trim($request->email)),
                'password'   => Hash::make($request->password),
                'country_id' => $request->country,
                'state_id'   => $request->state,
                'city'       => trim($request->city),
                'pincode'    => $request->pincode,
                'phone'      => $request->phone,
            ]);

            \Log::info('User created successfully:', ['user_id' => $user->id]);

            Auth::login($user);

            if (session()->has('qr_creation_data')) {
                return redirect()->route('qr.create')->with('success', 'Account created successfully! Let\'s finish creating your QR code.');
            }

            return redirect()->route('profile')->with('success', 'Account created successfully! Welcome to Digital Footprint.');
        } catch (\Exception $e) {
            \Log::error('Registration failed:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->withErrors(['error' => 'Something went wrong. Please try again.'])->withInput();
        }
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email|max:255',
            'password' => 'required|string|min:1',
        ], [
            'email.required' => 'Email address is required.',
            'email.email' => 'Please enter a valid email address.',
            'password.required' => 'Password is required.',
        ]);

        $credentials['email'] = strtolower(trim($credentials['email']));
        $user = User::where('email', $credentials['email'])->first();

        if ($user && Hash::check($credentials['password'], $user->password)) {
            Auth::login($user, $request->boolean('remember'));
            $request->session()->regenerate();

            if ($request->session()->has('qr_creation_data')) {
                return redirect()->route('qr.create')->with('success', 'Welcome back! Let\'s finish creating your QR code.');
            }

            return redirect()->intended(route('profile'))->with('success', 'Welcome back!');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }

    public function getStates($countryId)
    {
        $states = \App\Models\State::where('country_id', $countryId)->get();
        \Log::info("States fetched for country_id {$countryId}:", $states->toArray());
        return response()->json($states);
    }
}