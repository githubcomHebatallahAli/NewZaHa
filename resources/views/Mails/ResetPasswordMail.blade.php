{{-- <!DOCTYPE html>
<html>
<head>
    <title>Reset Password Mail</title>
</head>
<body>
    <p>اهلا بكم {{ $user->name }},</p>
    <h1>مرحبا بكم في ZaHa Script</h1>
    <p>نحن نتفهم ما حدث </p>
    <a href="https://zaha-script.vercel.app/reset/{{ $user->remember_token }}">Reset Your Password</a>
    <p>في حاله حدوث اي مشكله اتصل بنا</p>
    <br> ZaHa Script Team</p>
</body>
</html> --}}

<!DOCTYPE html>
<html>
<head>
    <title>Reset Password Mail</title>
</head>
<body>
    <p>اهلا بكم {{ $user->name }},</p>
    <h1>مرحبا بكم في ZaHa Script</h1>
    <p>نحن نتفهم ما حدث </p>
    <a href="https://zaha-script.vercel.app/{{ $user->remember_token }}">Reset Your Password</a>
    <p>في حاله حدوث اي مشكله اتصل بنا</p>
    <br> ZaHa Script Team</p>
</body>
</html>

