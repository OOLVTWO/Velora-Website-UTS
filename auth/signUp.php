<?php
/**
 * VELORA — Sign Up Page
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

// Simpan nilai lama form supaya tidak hilang jika error
$old = [
    'firstName' => '',
    'lastName'  => '',
    'email'     => '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = trim($_POST['firstName'] ?? '');
    $lastName  = trim($_POST['lastName']  ?? '');
    $email     = trim($_POST['email']     ?? '');
    $password  = $_POST['password']        ?? '';
    $terms     = isset($_POST['terms']);

    $old = [
        'firstName' => htmlspecialchars($firstName),
        'lastName'  => htmlspecialchars($lastName),
        'email'     => htmlspecialchars($email),
    ];

    if (empty($firstName) || empty($lastName) || empty($email) || empty($password)) {
        $error = 'Mohon isi semua field yang diperlukan.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Format email tidak valid.';
    } elseif (strlen($password) < 8) {
        $error = 'Password minimal 8 karakter.';
    } elseif (!$terms) {
        $error = 'Kamu harus menyetujui Terms of Service terlebih dahulu.';
    } else {
        // TODO: Simpan ke database
        // Simulasi registrasi berhasil (UI demo)
        $_SESSION['user_name']  = $firstName . ' ' . $lastName;
        $_SESSION['user_email'] = $email;
        $success = 'Akun berhasil dibuat! Selamat datang di VELORA 🎉';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Create Account — VELORA Marketplace</title>
<meta name="description" content="Join VELORA — the premium curated marketplace. Create your free account today.">
<link rel="stylesheet" href="../assets/style.css">
</head>
<body>

<!-- Form Side -->
<div class="auth-form-side">
    <div class="auth-form-wrap">
        <div class="auth-topbar">
            <a href="../index.php"><i class="bi bi-arrow-left"></i> Back to VELORA</a>
            <button class="theme-toggle" title="Toggle theme"><i class="bi bi-moon-fill"></i></button>
        </div>

        <div class="auth-header">
            <h1 class="serif">Create your account</h1>
            <p>Join thousands of curators. It's free forever.</p>
        </div>

        <!-- PHP: Tampilkan pesan error/success dari server -->
        <?php if ($error): ?>
        <div role="alert" style="display:flex;align-items:center;gap:10px;padding:12px 16px;background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.3);border-radius:8px;margin-bottom:20px;font-size:13px;color:#ef4444;">
            <i class="bi bi-exclamation-circle-fill"></i>
            <?= htmlspecialchars($error) ?>
        </div>
        <?php endif; ?>
        <?php if ($success): ?>
        <div role="alert" style="display:flex;align-items:center;gap:10px;padding:12px 16px;background:rgba(16,185,129,.1);border:1px solid rgba(16,185,129,.3);border-radius:8px;margin-bottom:20px;font-size:13px;color:#10b981;">
            <i class="bi bi-check-circle-fill"></i>
            <?= htmlspecialchars($success) ?>
        </div>
        <?php endif; ?>

        <!-- Social -->
        <div class="social-row">
            <a href="#" class="btn-social" id="googleBtn"><i class="bi bi-google"></i> Google</a>
            <a href="#" class="btn-social" id="fbBtn"><i class="bi bi-facebook"></i> Facebook</a>
        </div>
        <div class="divider"><span>or sign up with email</span></div>

        <!-- Signup Form -->
        <form action="" method="POST" id="signupForm">
            <div class="name-row">
                <div class="nx-form-group">
                    <label for="firstName">First Name</label>
                    <input class="nx-input" type="text" id="firstName" name="firstName"
                           placeholder="John" required autocomplete="given-name"
                           value="<?= $old['firstName'] ?>">
                </div>
                <div class="nx-form-group">
                    <label for="lastName">Last Name</label>
                    <input class="nx-input" type="text" id="lastName" name="lastName"
                           placeholder="Doe" required autocomplete="family-name"
                           value="<?= $old['lastName'] ?>">
                </div>
            </div>

            <div class="nx-form-group">
                <label for="email">Email Address</label>
                <input class="nx-input" type="email" id="email" name="email"
                       placeholder="you@example.com" required autocomplete="email"
                       value="<?= $old['email'] ?>">
            </div>

            <div class="nx-form-group">
                <label for="password">Password</label>
                <div class="pass-wrap">
                    <input class="nx-input" type="password" id="password" name="password" placeholder="Min. 8 characters" required autocomplete="new-password" style="padding-right:44px;" oninput="checkStrength(this.value)">
                    <button type="button" class="pass-toggle" id="passToggle"><i class="bi bi-eye" id="passIcon"></i></button>
                </div>
                <div class="strength-bar">
                    <div class="strength-segment" id="s1"></div>
                    <div class="strength-segment" id="s2"></div>
                    <div class="strength-segment" id="s3"></div>
                    <div class="strength-segment" id="s4"></div>
                </div>
                <div class="strength-label" id="strengthLabel">Enter a password</div>
            </div>

            <div class="terms-check">
                <input type="checkbox" id="terms" name="terms" required>
                <label for="terms">I agree to the <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>.</label>
            </div>

            <button type="submit" class="auth-submit" id="signupBtn">
                Create Free Account <i class="bi bi-arrow-right"></i>
            </button>
        </form>

        <div class="auth-footer">
            Already have an account? <a href="signIn.php">Sign in</a>
        </div>
    </div>
</div>

<!-- Decorative Panel -->
<div class="auth-panel">
    <div class="panel-content">
        <div class="panel-logo">VELORA<span>.</span></div>
        <div class="panel-tagline">Your curated journey starts here.</div>
        <div class="panel-steps">
            <div class="ps-item">
                <div class="ps-num">1</div>
                <div class="ps-text">
                    <strong>Create Your Account</strong>
                    <span>Quick, free, no card needed</span>
                </div>
            </div>
            <div class="ps-item">
                <div class="ps-num">2</div>
                <div class="ps-text">
                    <strong>Browse the Collection</strong>
                    <span>12,400+ curated products</span>
                </div>
            </div>
            <div class="ps-item">
                <div class="ps-num">3</div>
                <div class="ps-text">
                    <strong>Checkout Seamlessly</strong>
                    <span>Secure, fast &amp; tracked delivery</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="../js/main.js"></script>
<script>
// Password toggle
const passToggle = document.getElementById('passToggle');
const passInput  = document.getElementById('password');
const passIcon   = document.getElementById('passIcon');
passToggle.addEventListener('click', () => {
    const show = passInput.type === 'password';
    passInput.type = show ? 'text' : 'password';
    passIcon.className = show ? 'bi bi-eye-slash' : 'bi bi-eye';
});

// Strength checker
function checkStrength(val) {
    const segs = [document.getElementById('s1'), document.getElementById('s2'),
                  document.getElementById('s3'), document.getElementById('s4')];
    const label = document.getElementById('strengthLabel');
    let score = 0;
    if (val.length >= 8)        score++;
    if (/[A-Z]/.test(val))      score++;
    if (/[0-9]/.test(val))      score++;
    if (/[^A-Za-z0-9]/.test(val)) score++;
    const colors = ['', '#EF4444', '#F59E0B', '#3B82F6', '#10B981'];
    const labels = ['', 'Weak', 'Fair', 'Good', 'Strong'];
    segs.forEach((s, i) => { s.style.background = i < score ? colors[score] : 'var(--border)'; });
    label.textContent = val.length === 0 ? 'Enter a password' : labels[score];
    label.style.color = colors[score] || 'var(--text-soft)';
}

// Social login → landing page
document.getElementById('googleBtn').addEventListener('click', e => {
    e.preventDefault();
    window.showToast('Signing up with Google…', 'default');
    setTimeout(() => { window.location.href = '../index.php'; }, 1200);
});
document.getElementById('fbBtn').addEventListener('click', e => {
    e.preventDefault();
    window.showToast('Signing up with Facebook…', 'default');
    setTimeout(() => { window.location.href = '../index.php'; }, 1200);
});

// Submit feedback → success toast then redirect
document.getElementById('signupForm').addEventListener('submit', e => {
    e.preventDefault();
    const btn = document.getElementById('signupBtn');
    btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Creating account…';
    btn.style.opacity = '.75';
    btn.disabled = true;
    window.showToast('Account created! Welcome to VELORA 🎉', 'success');
    setTimeout(() => { window.location.href = '../index.php'; }, 1800);
});
</script>
</body>
</html>