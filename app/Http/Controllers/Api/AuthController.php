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
use App\Mail\ResetPasswordOtpMail;

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

    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.exists' => 'Email ini belum terdaftar di sistem kami.',
        ]);

        $throttleKey = 'forgot-password|' . strtolower($request->email) . '|' . $request->ip();
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return response()->json([
                'message' => 'Terlalu banyak permintaan reset password. Silakan coba lagi dalam ' . ceil($seconds / 60) . ' menit.'
            ], 429);
        }
        RateLimiter::hit($throttleKey, 600);

        $user = User::where('email', $request->email)->first();

        $otp = (string) random_int(100000, 999999);

        $user->update([
            'otp' => $otp,
            'otp_expires_at' => now()->addMinutes(10),
        ]);

        try {
            Mail::to($user->email)->send(new ResetPasswordOtpMail($otp, $user));
        } catch (\Exception $e) {
            \Log::error('Failed to send Reset Password OTP: ' . $e->getMessage());
            return response()->json(['message' => 'Gagal mengirim email OTP. Silakan periksa koneksi internet.'], 500);
        }

        return response()->json([
            'message' => 'Kode OTP untuk reset password telah dikirim ke email Anda.',
            'email' => $user->email
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'otp' => 'required|string|size:6',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.exists' => 'Email tidak terdaftar.',
            'otp.required' => 'Kode OTP wajib diisi.',
            'otp.size' => 'Kode OTP harus 6 digit.',
            'password.required' => 'Password baru wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $otpThrottleKey = 'reset-otp-attempts|' . strtolower($request->email);
        if (RateLimiter::tooManyAttempts($otpThrottleKey, 5)) {
            $seconds = RateLimiter::availableIn($otpThrottleKey);
            return response()->json([
                'message' => 'Terlalu banyak percobaan OTP yang salah. Akses diblokir sementara selama ' . ceil($seconds / 60) . ' menit.'
            ], 429);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || $user->otp !== $request->otp) {
            RateLimiter::hit($otpThrottleKey, 900);
            return response()->json(['message' => 'Kode OTP tidak valid atau salah.'], 400);
        }

        if ($user->otp_expires_at < now()) {
            return response()->json(['message' => 'Kode OTP sudah kedaluwarsa. Silakan ajukan reset password kembali.'], 400);
        }

        // Secure Database Update
        $user->update([
            'password' => Hash::make($request->password),
            'otp' => null,
            'otp_expires_at' => null,
        ]);

        // Revoke all existing tokens for enterprise-grade security
        $user->tokens()->delete();

        // Clear rate limiters
        RateLimiter::clear($otpThrottleKey);

        return response()->json([
            'message' => 'Password Anda berhasil diperbarui! Silakan login kembali dengan password baru Anda.'
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

    public function profile(Request $request)
    {
        return response()->json([
            'status' => 'success',
            'user' => $request->user()
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6',
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first()
            ], 422);
        }

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        if ($request->hasFile('foto_profil')) {
            $path = $request->file('foto_profil')->store('profil', 'public');
            $user->foto_profil = '/storage/' . $path;
        }

        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Profil berhasil diperbarui!',
            'user' => $user
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
