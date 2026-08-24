<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reset Password OTP Plat-BK</title>
</head>
<body style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f7f6; margin: 0; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 30px; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); text-align: center; border: 1px solid #e2e8f0;">
        
        @if(file_exists(public_path('images/logo.png')))
            <img src="{{ $message->embed(public_path('images/logo.png')) }}" alt="Plat-BK" style="max-height: 55px; max-width: 220px; margin-bottom: 20px; display: inline-block;">
        @else
            <h1 style="color: #003366; margin-bottom: 20px; font-weight: 800;">PLAT-BK</h1>
        @endif
        
        <h2 style="color: #003366; margin-top: 0; font-size: 20px;">Permintaan Reset Password</h2>
        <p style="color: #475569; font-size: 15px; line-height: 1.6; text-align: left;">
            Halo <strong>{{ $user->name }}</strong>,<br><br>
            Kami menerima permintaan untuk mengatur ulang kata sandi akun Anda di <strong>Plat-BK</strong>. Gunakan kode OTP berikut untuk melanjutkan proses reset kata sandi Anda:
        </p>
        
        <div style="background-color: #f8fafc; border: 2px dashed #003366; border-radius: 10px; padding: 16px; margin: 25px 0; display: inline-block;">
            <span style="font-size: 36px; font-weight: bold; color: #003366; letter-spacing: 8px;">{{ $otp }}</span>
        </div>
        
        <p style="color: #64748b; font-size: 13px; line-height: 1.5; text-align: left;">
            Kode OTP ini berlaku selama <strong>10 menit</strong>. Jika Anda tidak mengajukan permintaan ini, silakan abaikan email ini atau hubungi tim administrator untuk mengamankan akun Anda.
        </p>
        <div style="margin-top: 40px; border-top: 1px solid #e2e8f0; padding-top: 20px;">
            <p style="color: #64748b; font-size: 12px; margin: 0 0 4px 0; font-weight: 600;">
                &copy; {{ date('Y') }} Plat-BK. Hak Cipta Dilindungi.
            </p>
            <p style="color: #94a3b8; font-size: 11px; margin: 0;">
                Email notifikasi otomatis dari sistem Plat-BK.
            </p>
        </div>
    </div>
</body>
</html>
