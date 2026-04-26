<?php
/**
 * VELORA — Login Page
 * Developer: Felysia
 */
session_start();

// Redirect jika sudah login
if (isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}

$error   = '';
$success = '';
$old_email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $old_email = htmlspecialchars($email);

    if (empty($email) || empty($password)) {
        $error = 'Mohon isi semua field yang diperlukan.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Format email tidak valid.';
    } elseif (strlen($password) < 6) {
        $error = 'Password minimal 6 karakter.';
    } else {
        // TODO: Verifikasi ke database
        // Simulasi login berhasil (UI demo)
        $_SESSION['user_email'] = $email;
        $_SESSION['user_name']  = explode('@', $email)[0];
        $success = 'Login berhasil! Mengarahkan ke halaman utama…';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sign In — VELORA Marketplace</title>
<meta name="description" content="Sign in to your VELORA account to access your premium curated marketplace.">
<link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<!-- Decorative Panel -->
<div class="auth-panel">
    <div class="panel-content">
        <div class="panel-logo">VELORA<span>.</span></div>
        <div class="panel-tagline">The curated marketplace for those who demand more.</div>
        <div class="panel-feature">
            <div class="pf-item">
                <div class="pf-icon">🛍️</div>
                <div class="pf-text">
                    <strong>12,400+ Products</strong>
                    <span>Curated &amp; verified by experts</span>
                </div>
            </div>
            <div class="pf-item">
                <div class="pf-icon">⚡</div>
                <div class="pf-text">
                    <strong>Instant Access</strong>
                    <span>Sign in once, shop everywhere</span>
                </div>
            </div>
            <div class="pf-item">
                <div class="pf-icon">🔒</div>
                <div class="pf-text">
                    <strong>Secure &amp; Private</strong>
                    <span>Your data is always protected</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Form Side -->
<div class="auth-form-side">
    <div class="auth-form-wrap">
        <div class="auth-topbar">
            <a href="../index.php"><i class="bi bi-arrow-left"></i> Back to VELORA</a>
            <button class="theme-toggle" title="Toggle theme"><i class="bi bi-moon-fill"></i></button>
        </div>

        <div class="auth-header">
            <h1 class="serif">Welcome back</h1>
            <p>Sign in to continue your curated experience.</p>
        </div>

        <!-- PHP: Tampilkan pesan error/success dari server -->
        <?php if ($error): ?>
        <div class="nx-alert nx-alert-error" role="alert" style="display:flex;align-items:center;gap:10px;padding:12px 16px;background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.3);border-radius:8px;margin-bottom:20px;font-size:13px;color:#ef4444;">
            <i class="bi bi-exclamation-circle-fill"></i>
            <?= htmlspecialchars($error) ?>
        </div>
        <?php endif; ?>
        <?php if ($success): ?>
        <div class="nx-alert nx-alert-success" role="alert" style="display:flex;align-items:center;gap:10px;padding:12px 16px;background:rgba(16,185,129,.1);border:1px solid rgba(16,185,129,.3);border-radius:8px;margin-bottom:20px;font-size:13px;color:#10b981;">
            <i class="bi bi-check-circle-fill"></i>
            <?= htmlspecialchars($success) ?>
        </div>
        <?php endif; ?>

        <!-- Social Login -->
        <div class="social-row">
            <a href="#" class="btn-social" id="googleBtn"><i class="bi bi-google"></i> Google</a>
            <a href="#" class="btn-social" id="fbBtn"><i class="bi bi-facebook"></i> Facebook</a>
        </div>

        <div class="divider"><span>or continue with email</span></div>

        <!-- Login Form -->
        <form action="" method="POST" id="loginForm">
            <div class="nx-form-group">
                <label for="email">Email Address</label>
                <input class="nx-input" type="email" id="email" name="email" placeholder="you@example.com" required autocomplete="email">
            </div>
            <div class="nx-form-group">
                <label for="password">Password</label>
                <div class="pass-wrap">
                    <input class="nx-input" type="password" id="password" name="password" placeholder="••••••••" required autocomplete="current-password" style="padding-right:44px;">
                    <button type="button" class="pass-toggle" id="passToggle" aria-label="Show password">
                        <i class="bi bi-eye" id="passIcon"></i>
                    </button>
                </div>
            </div>
            <div class="form-extras">
                <label><input type="checkbox" name="remember"> Remember me</label>
                <a href="#">Forgot password?</a>
            </div>
            <button type="submit" class="auth-submit" id="loginBtn">
                Sign In <i class="bi bi-arrow-right"></i>
            </button>
        </form>

        <div class="auth-footer">
            Don't have an account? <a href="signUp.php">Create one — it's free</a>
        </div>
    </div>
</div>

<script src="../js/main.js"></script>
<script>
// Password visibility toggle
const passToggle = document.getElementById('passToggle');
const passInput  = document.getElementById('password');
const passIcon   = document.getElementById('passIcon');
passToggle.addEventListener('click', () => {
    const show = passInput.type === 'password';
    passInput.type = show ? 'text' : 'password';
    passIcon.className = show ? 'bi bi-eye-slash' : 'bi bi-eye';
});

// Social login → go to landing page (UI demo)
document.getElementById('googleBtn').addEventListener('click', e => {
    e.preventDefault();
    window.showToast('Signing in with Google…', 'default');
    setTimeout(() => { window.location.href = '../index.php'; }, 1200);
});
document.getElementById('fbBtn').addEventListener('click', e => {
    e.preventDefault();
    window.showToast('Signing in with Facebook…', 'default');
    setTimeout(() => { window.location.href = '../index.php'; }, 1200);
});

// Form submit — show success toast then redirect
document.getElementById('loginForm').addEventListener('submit', e => {
    e.preventDefault();
    const btn = document.getElementById('loginBtn');
    btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Signing in…';
    btn.style.opacity = '.75';
    btn.disabled = true;
    window.showToast('Welcome back! Redirecting…', 'success');
    setTimeout(() => { window.location.href = '../index.php'; }, 1500);
});
</script>
</body>
</html>
