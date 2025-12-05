<link rel="preconnect" href="https://fonts.gstatic.com">
<link rel="canonical" href="https://demo-basic.adminkit.io/" />

<!-- Select2 CSS -->
<link href="{{ asset('css/select2.min.css') }}" rel="stylesheet" />

<link href="{{ asset('css/app.css') }}" rel="stylesheet" />
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">

<style>
 /* Tombol Logout Tanpa Style Default */
.btn-logout {
    border: none;
    background: none;
    padding: 0;
    cursor: pointer;
}

/* Navbar Sticky */
.navbar {
    position: sticky;
    top: 0;
    z-index: 1030;
}

/* Hamburger Icon */
.hamburger {
    font-size: 1.2rem;
}

/* Dropdown Menu Lebar Minimal & Sesuai Konten */
.dropdown-menu {
    min-width: auto;       /* hilangkan min-width default */
    width: auto;           /* menyesuaikan isi */
    padding: 0.25rem 0;    /* beri padding kecil */
    max-width: 200px;      /* opsional, supaya tidak terlalu besar */
}

/* Semua item dropdown rata kanan & lebar sesuai isi */
.dropdown-menu.text-end .dropdown-item {
    text-align: right;
    padding: 0.25rem 1rem; /* padding normal tombol */
    width: auto;            /* jangan ambil lebar penuh */
}

/* Dropdown tetap di kanan saat mobile */
@media (max-width: 991.98px) {
    .navbar .dropdown-menu {
        position: absolute !important;
        right: 0 !important;
        left: auto !important;
    }
}

</style>
@stack('styles')