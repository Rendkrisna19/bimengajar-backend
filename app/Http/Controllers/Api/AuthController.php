<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendOtpMail;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $otp = rand(100000, 999999);
        
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user', // Default role
            'otp' => $otp,
            'otp_expires_at' => now()->addMinutes(10), 
        ]);

        try {
            Mail::to($user->email)->send(new SendOtpMail($otp, $user));
        } catch (\Exception $e) {
            \Log::error('Failed to send OTP: ' . $e->getMessage());
        }

        return response()->json([
            'message' => 'User registered successfully. Please verify your email with the OTP sent.',
            'user' => $user,
            'requires_otp' => true
        ], 201);
    }

    public function login(LoginRequest $request)
    {
        $this->ensureIsNotRateLimited($request);

        if (! Auth::attempt($request->only('email', 'password'))) {
            RateLimiter::hit($this->throttleKey($request));
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey($request));

        $user = Auth::user();

        if ($user->role === 'user' && is_null($user->email_verified_at)) {
            // Generate new OTP if expired or not exists
            if (is_null($user->otp) || $user->otp_expires_at < now()) {
                $otp = rand(100000, 999999);
                $user->update([
                    'otp' => $otp,
                    'otp_expires_at' => now()->addMinutes(10)
                ]);
                
                try {
                    Mail::to($user->email)->send(new SendOtpMail($otp, $user));
                } catch (\Exception $e) {
                    \Log::error('Failed to send OTP: ' . $e->getMessage());
                }
            }

            return response()->json([
                'message' => 'Silakan verifikasi akun Anda terlebih dahulu.',
                'requires_otp' => true,
                'email' => $user->email
            ], 403);
        }

        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'user' => $user,
            'token' => $token
        ]);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        if ($user->otp !== $request->otp) {
            return response()->json(['message' => 'Kode OTP salah'], 400);
        }

        if ($user->otp_expires_at < now()) {
            return response()->json(['message' => 'Kode OTP kedaluwarsa'], 400);
        }

        $user->update([
            'email_verified_at' => now(),
            'otp' => null,
            'otp_expires_at' => null,
        ]);

        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'message' => 'Verifikasi berhasil!',
            'user' => $user,
            'token' => $token
        ]);
    }

    public function resendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        if (!is_null($user->email_verified_at)) {
            return response()->json(['message' => 'Akun sudah diverifikasi'], 400);
        }

        $otp = rand(100000, 999999);
        $user->update([
            'otp' => $otp,
            'otp_expires_at' => now()->addMinutes(10)
        ]);

        try {
            Mail::to($user->email)->send(new SendOtpMail($otp, $user));
        } catch (\Exception $e) {
            \Log::error('Failed to send OTP: ' . $e->getMessage());
            return response()->json(['message' => 'Gagal mengirim email OTP'], 500);
        }

        return response()->json([
            'message' => 'Kode OTP berhasil dikirim ulang.'
        ]);
    }

    public function logout(Request $request)
    {
        if ($request->user()) {
            $request->user()->currentAccessToken()->delete();
        }

        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }

    protected function ensureIsNotRateLimited(Request $request): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey($request), 5)) {
            return;
        }

        $seconds = RateLimiter::availableIn($this->throttleKey($request));

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    protected function throttleKey(Request $request): string
    {
        return strtolower($request->input('email')).'|'.$request->ip();
    }
}
