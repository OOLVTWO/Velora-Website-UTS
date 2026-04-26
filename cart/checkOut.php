<?php
// checkout.php - Tommy Marketplace | Checkout & Payment Page
// Developer: Jody

session_start();

// Dummy cart data (in real app, this comes from session/database)
$cart_items = [
    [
        'id' => 1,
        'name' => 'Ergonomic Oak Chair',
        'variant' => 'Natural / Walnut',
        'price' => 3200000,  
        'qty' => 1,
        'image' => 'https://images.unsplash.com/photo-1567538096630-e0c55bd6374c?w=120&q=80'
    ],
    [
        'id' => 2,
        'name' => 'Minimal Desk Lamp',
        'variant' => 'Matte Black',
        'price' => 780000,
        'qty' => 2,
        'image' => 'https://images.unsplash.com/photo-1507473885765-e6ed057f782c?w=120&q=80'
    ],
];

$subtotal = array_sum(array_map(fn($i) => $i['price'] * $i['qty'], $cart_items));
$shipping = 45000;
$tax = round($subtotal * 0.11);
$total = $subtotal + $shipping + $tax;

function formatRp($amount) {
    return 'Rp ' . number_format($amount, 0, ',', '.');
}

// Handle form submission
$success = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Process payment here
    $success = true;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout — VELORA Marketplace</title>

    <link rel="stylesheet" href="../assets/style.css">
    <meta name="description" content="Secure checkout for VELORA Marketplace — shipping, payment, and confirmation.">
</head>
<body>

<!-- ── NAVBAR (VELORA shared) ── -->
<nav class="nx-navbar" id="navbar">
    <a href="../index.php" class="nx-brand">VELORA<span class="dot">.</span></a>
    <ul class="nx-nav-links">
        <li><a href="../index.php">Home</a></li>
        <li><a href="shoppingCart.php">Cart</a></li>
    </ul>
    <div class="nx-nav-actions">
        <button class="theme-toggle" title="Toggle theme"><i class="bi bi-moon-fill"></i></button>
        <span style="font-size:13px;color:var(--text-muted);"><i class="bi bi-lock me-1"></i>Secure Checkout</span>
    </div>
    <button class="nx-hamburger" id="hamburger" aria-label="Menu"><span></span><span></span><span></span></button>
</nav>
<div class="nx-mobile-menu" id="mobileMenu">
    <a href="../index.php">Home</a>
    <a href="shoppingCart.php">Cart</a>
</div>

<!-- ── MAIN ── -->
<div class="checkout-wrap">

    <!-- LEFT COLUMN -->
    <div>

        <!-- Shipping Address -->
        <div class="card-section">
            <div class="section-title">Shipping Address</div>
            <form id="checkoutForm" method="POST" action="">
            <div class="row g-3" style="grid-template-columns:1fr 1fr;">
                <div class="col-md-6">
                    <label class="form-label">First Name</label>
                    <input type="text" name="first_name" class="form-control" placeholder="Jody" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Last Name</label>
                    <input type="text" name="last_name" class="form-control" placeholder="Santoso" required>
                </div>
                <div class="col-12">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" placeholder="jody@email.com" required>
                </div>
                <div class="col-12">
                    <label class="form-label">Phone Number</label>
                    <input type="tel" name="phone" class="form-control" placeholder="+62 812 0000 0000" required>
                </div>
                <div class="col-12">
                    <label class="form-label">Street Address</label>
                    <input type="text" name="address" class="form-control" placeholder="Jl. Kemang Raya No. 12" required>
                </div>
                <div class="col-12" style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:12px;">
                    <div>
                        <label class="form-label">City</label>
                        <input type="text" name="city" class="form-control" placeholder="Jakarta" required>
                    </div>
                    <div>
                        <label class="form-label">Province</label>
                        <select name="province" class="form-select" required>
                            <option value="">Select...</option>
                            <option>DKI Jakarta</option>
                            <option>Jawa Barat</option>
                            <option>Jawa Tengah</option>
                            <option>Jawa Timur</option>
                            <option>Banten</option>
                            <option>Bali</option>
                            <option>Sumatera Utara</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Postal Code</label>
                        <input type="text" name="postal" class="form-control" placeholder="12160" required>
                    </div>
                </div>
            </div>
        </div>

        <!-- Shipping Method -->
        <div class="card-section">
            <div class="section-title">Shipping Method</div>
            <div class="row g-2 three-col">
                <?php
                $shippings = [
                    ['id'=>'reg', 'name'=>'Regular', 'desc'=>'3-5 business days', 'price'=>'Rp 45.000', 'icon'=>'bi-box'],
                    ['id'=>'exp', 'name'=>'Express', 'desc'=>'1-2 business days',  'price'=>'Rp 85.000', 'icon'=>'bi-lightning-charge'],
                    ['id'=>'same','name'=>'Same Day', 'desc'=>'Within 12 hours',   'price'=>'Rp 150.000','icon'=>'bi-rocket-takeoff'],
                ];
                foreach ($shippings as $s): ?>
                <div class="col-md-4">
                    <label class="pay-option <?= $s['id']==='reg' ? 'selected' : '' ?>" style="flex-direction:column; align-items:flex-start; gap:6px;">
                        <div class="d-flex align-items-center gap-2 w-100">
                            <input type="radio" name="shipping_method" value="<?= $s['id'] ?>"
                                   <?= $s['id']==='reg' ? 'checked' : '' ?> onchange="selectShipping(this)">
                            <span class="pay-label"><i class="bi <?= $s['icon'] ?> me-1"></i><?= $s['name'] ?></span>
                        </div>
                        <div style="padding-left:24px;">
                            <div class="pay-sub"><?= $s['desc'] ?></div>
                            <div style="font-size:13px;font-weight:600;color:var(--primary);margin-top:2px"><?= $s['price'] ?></div>
                        </div>
                    </label>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Payment Method -->
        <div class="card-section">
            <div class="section-title">Payment Method</div>

            <!-- Credit / Debit Card -->
            <div class="pay-option selected" onclick="selectPayment('card', this)">
                <input type="radio" name="payment" id="pay_card" value="card" checked>
                <div class="pay-icon">💳</div>
                <div>
                    <div class="pay-label">Credit / Debit Card</div>
                    <div class="pay-sub">Visa, Mastercard, JCB</div>
                </div>
            </div>

            <!-- Card Detail Form -->
            <div class="card-detail-form show" id="cardForm">
                <div class="row g-3" style="grid-template-columns:1fr 1fr;">
                    <div class="col-12">
                        <label class="form-label">Card Number</label>
                        <div class="card-number-wrap">
                            <input type="text" name="card_number" class="form-control"
                                   placeholder="0000  0000  0000  0000" maxlength="19"
                                   oninput="formatCard(this)">
                            <span class="card-type-icon" id="cardTypeIcon">💳</span>
                        </div>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Cardholder Name</label>
                        <input type="text" name="card_name" class="form-control" placeholder="JODY SANTOSO">
                    </div>
                    <div class="col-6">
                        <label class="form-label">Expiry Date</label>
                        <input type="text" name="expiry" class="form-control" placeholder="MM / YY"
                               maxlength="7" oninput="formatExpiry(this)">
                    </div>
                    <div class="col-6">
                        <label class="form-label">CVV</label>
                        <input type="password" name="cvv" class="form-control" placeholder="•••" maxlength="4">
                    </div>
                </div>
            </div>

            <!-- Transfer Bank -->
            <div class="pay-option" onclick="selectPayment('bank', this)">
                <input type="radio" name="payment" id="pay_bank" value="bank">
                <div class="pay-icon">🏦</div>
                <div>
                    <div class="pay-label">Bank Transfer</div>
                    <div class="pay-sub">BCA, Mandiri, BNI, BRI</div>
                </div>
            </div>

            <!-- QRIS -->
            <div class="pay-option" onclick="selectPayment('qris', this)">
                <input type="radio" name="payment" id="pay_qris" value="qris">
                <div class="pay-icon">📱</div>
                <div>
                    <div class="pay-label">QRIS</div>
                    <div class="pay-sub">GoPay, OVO, DANA, ShopeePay</div>
                </div>
            </div>

            <!-- COD -->
            <div class="pay-option" onclick="selectPayment('cod', this)">
                <input type="radio" name="payment" id="pay_cod" value="cod">
                <div class="pay-icon">💵</div>
                <div>
                    <div class="pay-label">Cash on Delivery</div>
                    <div class="pay-sub">Pay when delivered</div>
                </div>
            </div>

            </form><!-- end form -->
        </div>

    </div><!-- end left -->

    <!-- RIGHT COLUMN — ORDER SUMMARY -->
    <div>
        <div class="summary-card">
            <div class="summary-title">Order Summary</div>

            <!-- Cart Items -->
            <?php foreach ($cart_items as $item): ?>
            <div class="cart-item">
                <img src="<?= $item['image'] ?>" class="cart-img" alt="<?= $item['name'] ?>">
                <div class="cart-info flex-grow-1">
                    <div class="name">
                        <?= $item['name'] ?>
                        <span class="qty-badge">×<?= $item['qty'] ?></span>
                    </div>
                    <div class="variant"><?= $item['variant'] ?></div>
                    <div class="price"><?= formatRp($item['price'] * $item['qty']) ?></div>
                </div>
            </div>
            <?php endforeach; ?>

            <!-- Promo -->
            <div class="promo-wrap">
                <input type="text" class="form-control" placeholder="Promo code" id="promoInput">
                <button class="btn-promo" type="button" onclick="applyPromo()">Apply</button>
            </div>

            <!-- Summary Rows -->
            <div class="summary-row">
                <span>Subtotal</span>
                <span class="val"><?= formatRp($subtotal) ?></span>
            </div>
            <div class="summary-row">
                <span>Shipping</span>
                <span class="val" id="shippingCost"><?= formatRp($shipping) ?></span>
            </div>
            <div class="summary-row">
                <span>Tax (11%)</span>
                <span class="val"><?= formatRp($tax) ?></span>
            </div>
            <div class="summary-row" id="promoRow" style="display:none; color:var(--success)">
                <span>Promo</span>
                <span class="val" style="color:var(--success)">− Rp 0</span>
            </div>
            <div class="summary-row total">
                <span>Total</span>
                <span class="val" id="totalAmount"><?= formatRp($total) ?></span>
            </div>

            <button class="btn-pay" type="button" onclick="submitOrder()">
                <i class="bi bi-shield-lock-fill"></i>
                Pay Now · <?= formatRp($total) ?>
            </button>

            <div class="secure-note">
                <i class="bi bi-shield-check text-success"></i>
                <span>256-bit SSL Encryption · Secured Payment</span>
            </div>
        </div>
    </div>

</div><!-- end checkout-wrap -->

<!-- ── SUCCESS OVERLAY ── -->
<div class="success-overlay" id="successOverlay">
    <div class="success-box">
        <div class="success-icon">✓</div>
        <h4>Payment Successful!</h4>
        <p>Your order has been placed and is being processed. You'll receive a confirmation email shortly.</p>
        <div class="order-id" id="orderId">#TM-<?= strtoupper(substr(uniqid(), -6)) ?></div>
        <button class="btn-done" onclick="window.location.href='../index.php'">Back to Home</button>
    </div>
</div>

<script src="../js/main.js"></script>
<script>
// ── Payment selection
function selectPayment(type, el) {
    document.querySelectorAll('.pay-option').forEach(o => {
        o.classList.remove('selected');
        o.querySelector('input[type="radio"]').checked = false;
    });
    el.classList.add('selected');
    el.querySelector('input[type="radio"]').checked = true;

    const cardForm = document.getElementById('cardForm');
    if (type === 'card') {
        cardForm.classList.add('show');
    } else {
        cardForm.classList.remove('show');
    }
}

// ── Shipping selection
const shippingPrices = { reg: 45000, exp: 85000, same: 150000 };
const subtotal = <?= $subtotal ?>;
const tax = <?= $tax ?>;

function selectShipping(radio) {
    document.querySelectorAll('.pay-option').forEach(o => {
        if (o.querySelector('input[name="shipping_method"]')) o.classList.remove('selected');
    });
    radio.closest('.pay-option').classList.add('selected');

    const cost = shippingPrices[radio.value] || 45000;
    const total = subtotal + cost + tax;
    document.getElementById('shippingCost').textContent = formatRp(cost);
    document.getElementById('totalAmount').textContent = formatRp(total);
    document.querySelector('.btn-pay').innerHTML =
        `<i class="bi bi-shield-lock-fill"></i> Pay Now · ${formatRp(total)}`;
}

// ── Card number formatting
function formatCard(input) {
    let v = input.value.replace(/\D/g, '').substring(0, 16);
    input.value = v.match(/.{1,4}/g)?.join('  ') || v;

    const icon = document.getElementById('cardTypeIcon');
    if (v.startsWith('4')) icon.textContent = '💳'; // Visa
    else if (v.startsWith('5')) icon.textContent = '💳'; // MC
    else icon.textContent = '💳';
}

// ── Expiry formatting
function formatExpiry(input) {
    let v = input.value.replace(/\D/g, '').substring(0, 4);
    if (v.length >= 2) v = v.substring(0,2) + ' / ' + v.substring(2);
    input.value = v;
}

// ── Promo
function applyPromo() {
    const code = document.getElementById('promoInput').value.trim().toUpperCase();
    if (code === 'VELORA10') {
        document.getElementById('promoRow').style.display = 'flex';
        document.getElementById('promoRow').querySelector('.val').textContent = '− Rp 50.000';
        window.showToast('✅ Promo VELORA10 applied! Discount Rp 50.000', 'success');
    } else if (code) {
        window.showToast('❌ Invalid promo code. Try VELORA10', 'error');
    }
}

// ── Submit
function submitOrder() {
    const form = document.getElementById('checkoutForm');
    const inputs = form.querySelectorAll('input[required], select[required]');
    let valid = true;
    inputs.forEach(i => {
        if (!i.value.trim()) {
            i.classList.add('is-invalid');
            valid = false;
        } else {
            i.classList.remove('is-invalid');
        }
    });
    if (!valid) {
        window.showToast('⚠️ Please fill all required fields', 'error');
        return;
    }
    document.getElementById('successOverlay').classList.add('show');
}

// ── Helpers
function formatRp(n) {
    return 'Rp ' + n.toLocaleString('id-ID');
}
</script>
</body>
</html>
