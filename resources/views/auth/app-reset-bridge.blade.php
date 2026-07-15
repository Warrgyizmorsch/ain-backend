<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Open AIN App</title>
    <style>
        *{box-sizing:border-box}body{margin:0;min-height:100vh;display:grid;place-items:center;padding:22px;background:linear-gradient(145deg,#eef2ff,#f8fafc 55%,#eef6ff);font-family:Inter,Arial,sans-serif;color:#182230}.card{width:min(100%,440px);background:#fff;border:1px solid #e5eaf2;border-radius:22px;padding:34px;text-align:center;box-shadow:0 22px 55px rgba(16,24,40,.12)}.logo{display:inline-grid;place-items:center;width:62px;height:62px;border-radius:17px;background:linear-gradient(135deg,#243b72,#4f46e5);color:#fff;font-weight:800;font-size:20px;letter-spacing:1px;box-shadow:0 12px 24px rgba(79,70,229,.25)}h1{font-size:25px;margin:23px 0 9px;letter-spacing:-.5px}p{color:#667085;font-size:14px;line-height:1.7;margin:0 0 24px}.button{display:block;width:100%;padding:15px 20px;border-radius:11px;background:#4f46e5;color:#fff;text-decoration:none;font-size:15px;font-weight:750;box-shadow:0 8px 18px rgba(79,70,229,.22)}.hint{margin-top:18px;color:#98a2b3;font-size:12px;line-height:1.55}
    </style>
</head>
<body>
<main class="card">
    <div class="logo">AIN</div>
    <h1>Continue in the AIN app</h1>
    <p>Your secure reset link is ready. Tap below to open the password reset screen in the mobile app.</p>
    <a class="button" id="openApp" href="{{ $deepLink }}">Open AIN App</a>
    <div class="hint">If the app does not open, make sure it is installed and then tap the button again.</div>
</main>
<script>
    window.setTimeout(function () {
        window.location.href = @json($deepLink);
    }, 500);
</script>
</body>
</html>
