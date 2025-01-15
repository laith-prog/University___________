<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Twilio\Rest\Client; // make sure to import the Twilio client

class AuthController extends Controller
{




    public function getProfile(Request $request)
{
    // Retrieve the authenticated user
    $user = Auth::user();

    // Return user data
    return response()->json([
        'message' => 'Profile retrieved successfully',
        'data' => [
            'id' => $user->id,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'phone_number' => $user->phone_number,
            'profile_image' => $user->profile_image,
            'location' => $user->location,
            'role' => $user->role,
            
        ]
    ]);
}

public function sendOTP(Request $request)
{
    $request->validate([
        'phone_number' => 'required|string|max:20'
    ]);

    $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    
    // Store the OTP in the cache for 5 minutes
    cache()->put('otp_' . $request->phone_number, $otp, now()->addMinutes(5));

    // Send OTP using Twilio
    try {
        
        return response()->json([
            'message' => 'OTP sent successfully',
            'otp' => $otp // Only for development or testing; hide in production!
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Failed to send OTP',
            'error' => $e->getMessage(),
        ], 500);
    }
}
    public function sendOTP2(Request $request)
    {
        // Validate the email address
        $request->validate([
            'email' => 'required|email|max:255'
        ]);
    
        // Generate a 6-digit OTP
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    
        // Cache the OTP for the email, with a 5-minute expiration
        cache()->put('otp_' . $request->email, $otp, now()->addMinutes(5));
    
        // Send the OTP via email
        Mail::raw("Your OTP is: $otp", function ($message) use ($request) {
            $message->to($request->email)
                    ->subject('Your OTP Code');
        });
    
        // Return a success response
        return response()->json([
            'message' => 'OTP sent successfully to your email',
            'otp' => $otp // Optional: Remove this in production for security
        ]);
    }

    public function verifyOTP(Request $request)
    {
        $request->validate([
            'phone_number' => 'required|string|max:20',
            'otp' => 'required|string|size:6'
        ]);

        $cachedOTP = cache()->get('otp_' . $request->phone_number);
        if (!$cachedOTP || $cachedOTP !== $request->otp) {
            return response()->json([
                'message' => 'Invalid OTP'
            ], 401);
        }

        $user = User::firstOrCreate(
            ['phone_number' => $request->phone_number],
            ['role' => 'user']
        );
        $token = $user->createToken('auth_token')->plainTextToken;


        

        Cache::forget('otp_' . $request->phone_number);

        return response()->json([
            'message' => 'Authenticated successfully',
            'user' => $user,
            'token' => $token
        ]);
    }
    public function verifyOTP2(Request $request)
{
    // Validate the email and OTP
    $request->validate([
        'email' => 'required|email|max:255',
        'otp' => 'required|string|size:6'
    ]);

    // Retrieve the cached OTP for the email
    $cachedOTP = cache()->get('otp_' . $request->email);

    // Check if the OTP is valid
    if (!$cachedOTP || $cachedOTP !== $request->otp) {
        return response()->json([
            'message' => 'Invalid OTP'
        ], 401);
    }

    // Create or retrieve the user based on email
    $user = User::firstOrCreate(
        ['email' => $request->email],
        ['role' => 'user'] // Default role can be adjusted
    );

    // Generate an authentication token for the user
    $token = $user->createToken('auth_token')->plainTextToken;

    // Remove the OTP from the cache
    Cache::forget('otp_' . $request->email);

    // Return success response with user and token
    return response()->json([
        'message' => 'Authenticated successfully',
        'user' => $user,
        'token' => $token
    ]);
}

    public function signOut(Request $request)
    {
        $request->user()->currentAccessToken()->delete();


        return response()->json([
            'message' => 'Signed out successfully'
        ]);
    }

    public function updateProfile(Request $request)
    {
        // Get the authenticated user using the token
        $user = auth::user();
    
        if (!$user) {
            return response()->json(['message' => 'User not authenticated.'], 401);
        }
    
        // Validate the incoming request
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'location' => 'required|string|max:100',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Max 2MB
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
    
        // Update user details
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->location = $request->location;
    
        // Handle profile image upload if provided
        if ($request->hasFile('profile_image')) {
            $profileImage = $request->file('profile_image');
            $path = $profileImage->store('profile_images', 'public');
            $user->profile_image = $path;
        }
    
        $user->save();
    
        return response()->json([
            'message' => 'Profile updated successfully.',
            'user' => $user
        ], 200);
    }
    
    
}
