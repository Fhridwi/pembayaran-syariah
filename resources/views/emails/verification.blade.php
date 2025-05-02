<!-- resources/views/emails/verification.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Verifikasi</title>
</head>
<body>
    <h2>Verifikasi Email Anda</h2>
    <p>Berikut adalah kode OTP untuk verifikasi email Anda: <strong>{{ $otp }}</strong></p>
    <p>Silakan masukkan kode tersebut untuk melanjutkan proses verifikasi.</p>
</body>
</html>
