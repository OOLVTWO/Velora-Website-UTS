# Velora-Website-UTS


# VELORA Marketplace 🛍️
> A premium curated marketplace — UTS Project Web Programming

---

## 📁 Struktur Folder

```
Uts PWEB/
├── index.php                      ← Entry point utama (orchestrator)
│
├── main/
│   ├── section1.php         ← 🟣 Tom      — Navbar, Hero, Stats, Categories, Featured, POTW
│   └── section2.php         ← 🟣 Bagus    — Journal, Team, About / Footer
│
├── auth/
│   ├── signIn.php                 ← 🔵 Felysia  — Halaman Login
│   └── signUp.php                 ← 🔵 Felysia  — Halaman Register
│
├── cart/
│   ├── shoppingCart.php           ← 🟢 Jodi     — Halaman Keranjang Belanja
│   └── checkOut.php               ← 🟢 Jodi     — Halaman Checkout & Pembayaran
│
└── assets/
    ├── css/
    │   ├── style.css              ← Global design system (shared semua halaman)
    │   └── image/                 ← Foto anggota tim
    └── js/
        └── main.js                ← Global JS (navbar, theme toggle, toast)
```

---

## 👥 Pembagian Tugas Tim

| Anggota | Bagian | File |
|---------|--------|------|
| **Tom** 🟣 | Landing Page — Hero s.d. Product of the Week | `main/section1.php` |
| **Bagus** 🟣 | Landing Page — Journal, Team, Footer | `main/section2.php` |
| **Felysia** 🔵 | Authentication — Login & Register | `auth/signIn.php`, `auth/signUp.php` |
| **Jodi** 🟢 | Store — Shopping Cart & Checkout | `cart/shoppingCart.php`, `cart/checkOut.php` |

---

## 🏗️ Arsitektur: Bagaimana Semua Terintegrasi

```
[Browser] → index.php
                │
                ├── require main/section1.php  (Tom — Navbar + Hero + Categories + Featured + POTW)
                └── require partials/section2.php  (Bagus — Journal + Team + Footer)

[Navbar Cart button] → cart/shoppingCart.php  (Jodi)
                                │
                                └── Proceed to Checkout → cart/checkOut.php  (Jodi)

[Navbar Sign In]  → auth/signIn.php   (Felysia)
[Navbar Get Started] → auth/signUp.php  (Felysia)
                            │
                            └── Success → index.php  (kembali ke landing)
```

**Key integration points:**
- `index.php` meng-`define('VELORA_ENTRY', true)` → main files hanya bisa diakses via include (tidak bisa diakses langsung)
- `$_SESSION['cart_count']` di-set oleh `cart/shoppingCart.php` → dibaca oleh `index.php` untuk badge navbar
- `$_SESSION['user_id']` & `$_SESSION['user_name']` di-set oleh `auth/signIn.php` → dibaca oleh `index.php` untuk tampilan navbar (Sign In / Sign Out)
- Semua halaman menggunakan `assets/css/style.css` (VELORA design tokens) dan `assets/js/main.js` (theme toggle, navbar scroll, toast)

---

## ⚙️ Cara Menjalankan

### Prasyarat
- **XAMPP** (PHP 8.x + Apache)

### Langkah

```bash
# 1. Clone / copy folder ke htdocs XAMPP
cp -r "Uts PWEB" C:/xampp/htdocs/velora

# 2. Jalankan Apache dari XAMPP Control Panel

# 3. Buka browser
http://localhost/nama folder/index.php
```

> **Catatan:** Tidak perlu database untuk demo ini. Semua data menggunakan dummy/session PHP.

---

## 🔗 Navigasi Halaman

| URL | Halaman | Developer |
|-----|---------|-----------|
| `/index.php` | Landing Page | Tom + Bagus |
| `/auth/signIn.php` | Login | Felysia |
| `/auth/signUp.php` | Register | Felysia |
| `/cart/shoppingCart.php` | Keranjang Belanja | Jodi |
| `/cart/checkOut.php` | Checkout & Pembayaran | Jodi |

---

## 🎨 Design System (Shared)

Semua halaman menggunakan token CSS yang didefinisikan di `assets/css/style.css`:

```css
--primary       : #5B3FF8   /* Warna ungu utama VELORA */
--bg            : #0d0c1e   /* Background dark mode */
--surface       : #161428   /* Card background */
--text-main     : #ffffff
--text-muted    : rgba(255,255,255,.55)
--border        : rgba(255,255,255,.1)
--radius        : 14px
--shadow-lg     : 0 20px 60px rgba(0,0,0,.4)
```

Fonts: **DM Serif Display** (heading) + **DM Sans** (body) via Google Fonts.

---

## 📋 Detail per Bagian

### 🟣 Tom — `main/section1.php`
Bertanggung jawab atas semua section di bagian atas landing page:
- **Navbar** — dengan cart badge, theme toggle, dan kondisi login/logout dari `$_SESSION`
- **Hero Section** — headline animasi, search bar, CTA button
- **Stats Bar** — counter animasi (12.400+ products, 3.800+ customers, dst.)
- **Categories** — grid 5 kartu kategori dengan hover zoom effect
- **Featured Exhibit** — showcase produk/layanan unggulan
- **Product of the Week** — limited edition highlight

### 🟣 Bagus — `main/section2.php`
Bertanggung jawab atas section bawah landing page:
- **Journal / Editorial** — 3 artikel dengan thumbnail dari Unsplash
- **Team Section** — 4 kartu anggota tim dengan animasi glow + ring
- **Footer (About)** — newsletter, navigasi link, social media, copyright

### 🔵 Felysia — `auth/`
Bertanggung jawab atas sistem autentikasi:
- **`signIn.php`** — Split panel login: form kiri, decorative panel kanan. Fitur: password toggle, social login (demo), PHP server-side validation, session handling
- **`signUp.php`** — Split panel register: form kiri (nama, email, password + strength meter), decorative panel kanan dengan step guide. Fitur: password strength indicator, terms checkbox, PHP validation

### 🟢 Jodi — `cart/`
Bertanggung jawab atas sistem belanja:
- **`shoppingCart.php`** — Daftar item cart dengan qty +/-, remove item, select all/delete, promo code (VELORA10), order summary real-time, "You may also like" section. Session cart count di-sync ke `$_SESSION['cart_count']`
- **`checkOut.php`** — Form shipping address, pilihan metode pengiriman (Regular/Express/Same Day), metode pembayaran (Kartu/Transfer/QRIS/COD), card detail sub-form, order summary sticky, success modal pop-up

---

## 🔄 Alur Session PHP

```
signIn.php  ──── $_SESSION['user_id']
            ──── $_SESSION['user_name']    ──► index.php membaca → tampilkan nama user di navbar
            ──── $_SESSION['user_email']

shoppingCart.php ── $_SESSION['cart_count'] ──► index.php membaca → badge angka di cart icon

index.php ── ?logout=1 ──► session_destroy() ──► redirect → index.php?msg=logout
                                                              └─► flash toast "Signed out"
```

---

## 🚀 Kolaborasi GitHub

```bash
# Tom — push section1.php
git add main/section1.php
git commit -m "feat(landing): hero, stats, categories, featured, POTW - Tom"
git push

# Bagus — push section2.php
git add main/section2.php
git commit -m "feat(landing): journal, team, footer - Bagus"
git push

# Felysia — push auth/
git add auth/
git commit -m "feat(auth): signIn dan signUp page - Felysia"
git push

# Jodi — push cart/
git add cart/
git commit -m "feat(store): shoppingCart dan checkOut - Jodi"
git push
```

> Karena masing-masing mengerjakan **file yang berbeda**, tidak akan ada merge conflict! ✅

---

## 💡 Promo Code (Demo)

Masukkan kode **`VELORA10`** di halaman Cart atau Checkout untuk mendapatkan diskon Rp 50.000.

---

*VELORA Marketplace — UTS Web Programming | © 2026*
