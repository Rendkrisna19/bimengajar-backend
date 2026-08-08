<!DOCTYPE html>
<html>
<head>
    <title>Reset Password OTP BI Mengajar</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f7f6; margin: 0; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 30px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.06); text-align: center;">
        
        @if(file_exists(public_path('images/logo.png')))
            <img src="{{ $message->embed(public_path('images/logo.png')) }}" alt="BI Mengajar" style="max-height: 60px; margin-bottom: 20px;">
        @else
            <h1 style="color: #003366; margin-bottom: 20px; font-weight: 800;">BI-MENGAJAR</h1>
        @endif
        
        <h2 style="color: #003366; margin-top: 0;">Permintaan Reset Password</h2>
        <p style="color: #555; font-size: 15px; line-height: 1.6; text-align: left;">
            Halo <strong>{{ $user->name }}</strong>,<br><br>
            Kami menerima permintaan untuk mengatur ulang kata sandi (reset password) akun Anda di <strong>BI Mengajar</strong>. Gunakan kode OTP berikut untuk melanjutkan proses reset password Anda:
        </p>
        
        <div style="background-color: #f8fafc; border: 2px dashed #003366; border-radius: 10px; padding: 18px; margin: 25px 0; display: inline-block;">
            <span style="font-size: 38px; font-weight: bold; color: #003366; letter-spacing: 8px;">{{ $otp }}</span>
        </div>
        
        <p style="color: #555; font-size: 14px; line-height: 1.5; text-align: left;">
            Kode OTP ini berlaku selama <strong>10 menit</strong>. Jika Anda tidak mengajukan permintaan ini, silakan abaikan email ini atau hubungi tim administrator untuk mengamankan akun Anda.
        </p>
        <div style="margin-top: 35px; border-top: 1px solid #eee; padding-top: 18px;">
            <p style="color: #888; font-size: 12px;">
                &copy; {{ date('Y') }} Bank Indonesia Pematangsiantar. Hak Cipta Dilindungi.
            </p>
        </div>
    </div>
</body>
</html>
