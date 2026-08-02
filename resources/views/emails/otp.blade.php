<!DOCTYPE html>
<html>
<head>
    <title>Verifikasi OTP BI Mengajar</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f7f6; margin: 0; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 30px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); text-align: center;">
        
        @if(file_exists(public_path('images/logo.png')))
            <img src="{{ $message->embed(public_path('images/logo.png')) }}" alt="BI Mengajar" style="max-height: 60px; margin-bottom: 20px;">
        @else
            <h1 style="color: #003366; margin-bottom: 20px; font-weight: 800;">BI-MENGAJAR</h1>
        @endif
        
        <h2 style="color: #003366; margin-top: 0;">Verifikasi Akun Anda</h2>
        <p style="color: #555; font-size: 16px; line-height: 1.6; text-align: left;">
            Halo <strong>{{ $user->name }}</strong>,<br><br>
            Terima kasih telah mendaftar di <strong>BI Mengajar</strong>. Untuk melanjutkan pendaftaran dan memastikan keamanan akun Anda, silakan masukkan kode OTP berikut pada halaman verifikasi:
        </p>
        
        <div style="background-color: #f8fafc; border: 2px dashed #003366; border-radius: 8px; padding: 15px; margin: 25px 0; display: inline-block;">
            <span style="font-size: 36px; font-weight: bold; color: #003366; letter-spacing: 8px;">{{ $otp }}</span>
        </div>
        
        <p style="color: #555; font-size: 14px; line-height: 1.5; text-align: left;">
            Kode ini hanya berlaku selama <strong>10 menit</strong>. Jangan bagikan kode ini kepada siapapun demi keamanan akun Anda. Jika Anda tidak merasa melakukan pendaftaran, abaikan email ini.
        </p>
        <div style="margin-top: 40px; border-top: 1px solid #eee; padding-top: 20px;">
            <p style="color: #888; font-size: 12px;">
                &copy; {{ date('Y') }} Bank Indonesia Mengajar. Hak Cipta Dilindungi.
            </p>
        </div>
    </div>
</body>
</html>
