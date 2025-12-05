<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Responsive Admin &amp; Dashboard Template based on Bootstrap 5">
    <meta name="author" content="AdminKit">
    <meta name="keywords" content="adminkit, bootstrap, bootstrap 5, admin, dashboard, template, responsive, css, sass, html, theme, front-end, ui kit, web">

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="shortcut icon" href="img/icons/icon-48x48.png" />

    <link rel="canonical" href="https://demo-basic.adminkit.io/pages-sign-in.html" />

    <title>Login | SAS</title>

    <link rel="icon" type="image/png" href="{{ asset('assets/image/logo.png') }}">

    <link href="{{ asset('https://unpkg.com/@adminkit/core@latest/dist/css/app.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">

    <!-- Dark mode overrides -->
    <style>
        :root {
            --bg-default: #ffffff;
            --text-default: #212529;
            --card-bg: #ffffff;
            --muted: #6c757d;
        }
        body.dark, html.dark {
            background-color: #0f1720 !important;
            color: #e6eef6 !important;
        }
        body.dark .container, html.dark .container {
            color: #e6eef6;
        }
        body.dark .card {
            background-color: #0b1220;
            border-color: rgba(255,255,255,0.04);
            color: #e6eef6;
        }
        body.dark .card .card-body {
            background: transparent;
        }
        body.dark .form-control {
            background-color: #0e1622;
            color: #e6eef6;
            border-color: rgba(255,255,255,0.06);
        }
        body.dark .form-control::placeholder { color: rgba(230,238,246,0.6); }
        body.dark .form-check-label, body.dark .form-label, body.dark .lead, body.dark .h2 {
            color: #e6eef6;
        }
        body.dark .btn-primary {
            background-color: #2563eb;
            border-color: #2563eb;
            color: #fff;
        }
        body.dark a { color: #93c5fd; }
        /* small toggle positioning adjustments */
        .dark-toggle {
            position: absolute;
            right: 1rem;
            top: 0.75rem;
            z-index: 50;
        }
        /* ensure full-height background looks consistent */
        main.d-flex.w-100 { min-height: 100vh; }
    </style>
</head>

<body>
    <!-- Dark mode toggle -->
    <div class="dark-toggle">
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" id="darkModeToggle" aria-label="Toggle dark mode">
            <label class="form-check-label small" for="darkModeToggle">Dark Mode</label>
        </div>
    </div>

    <main class="d-flex w-100">
        <div class="container d-flex flex-column">
            <!-- ...existing code... -->
            <div class="row vh-100">
                <div class="col-sm-10 col-md-8 col-lg-6 col-xl-5 mx-auto d-table h-100">
                    <div class="d-table-cell align-middle">

                        <div class="text-center mt-4">
                            <div class="text-center mt-3">
                                <img src="{{ asset('assets/image/logo-sas.png') }}" alt="SAS Logo" style="max-width:160px; height:auto;">
                            </div>
                            <p class="lead">
                                PT Sarana Agung Sejahtera
                            </p>
                        </div>
                        
                        <div class="card">
                            <div class="card-body">
                                <div class="m-sm-3">
                                    <h1 class="h2 text-center"><strong>Login</strong></h1>
                                    <form action="{{ route('autentikasi') }}" method="POST">
										@csrf
                                        <div class="mb-3">
                                            <label class="form-label">Email</label>
                                            <input class="form-control form-control-lg @error('email') is-invalid @enderror" type="email" name="email" value="{{ old('email') }}" placeholder="Masukkan email" />
                                            @error('email') 
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div> 
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Password</label>
                                            <div class="input-group">
                                                <input class="form-control form-control-lg @error('password') is-invalid @enderror" type="password" id="password" name="password" placeholder="Masukkan password" />
                                                <button class="btn btn-outline-primary" type="button" id="togglePassword" aria-label="Tampilkan password">
                                                    <i data-feather="eye"></i>
                                                </button>
                                                @error('password') 
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div> 
                                                @enderror
                                            </div>
                                        </div>
                                        <div>
                                            <div class="form-check align-items-center">
                                                <input id="customControlInline" type="checkbox" class="form-check-input" value="remember-me" name="remember-me">
                                                <label class="form-check-label text-small" for="customControlInline">Ingat saya</label>
                                            </div>
                                        </div>
                                        <div class="d-grid gap-2 mt-3">
                                            <button type="submit" class="btn btn-lg btn-primary">Login</button>
                                            <!-- <button type="button" class="btn btn-lg btn-secondary">Kembali</button> -->
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="text-center mb-3">
                            <a href="/"><i clas="align-middle" data-feather="arrow-left-circle"></i> Kembali</a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ...existing code... -->
        </div>
    </main>

<script src="{{ asset('https://unpkg.com/@adminkit/core@latest/dist/js/app.js') }}"></script>

<!-- Dark mode script: reads system preference, localStorage, and persists toggle -->
<script>
    (function () {
        const toggle = document.getElementById('darkModeToggle');
        const darkClass = 'dark';
        const storageKey = 'sas-prefers-dark';

        // determine initial preference: localStorage -> OS preference -> false
        const stored = localStorage.getItem(storageKey);
        const initial = stored !== null
            ? stored === 'true'
            : (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches);

        function apply(dark) {
            document.documentElement.classList.toggle(darkClass, dark);
            document.body.classList.toggle(darkClass, dark);
            if (toggle) toggle.checked = dark;
        }

        apply(initial);

        if (toggle) {
            toggle.addEventListener('change', function () {
                const dark = !!this.checked;
                apply(dark);
                localStorage.setItem(storageKey, dark);
            });
        }

        // Update if OS theme changes and user has not explicitly set a preference
        if (window.matchMedia) {
            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
                if (localStorage.getItem(storageKey) === null) {
                    apply(e.matches);
                }
            });
        }
    })();
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // feather icons
    if (window.feather) { feather.replace(); }

    // Toggle password visibility (sederhana, ganti ikon sesuai state)
    const toggleBtn = document.getElementById('togglePassword');
    const pwdInput = document.getElementById('password');
    if (toggleBtn && pwdInput) {
        toggleBtn.addEventListener('click', function () {
            const isPwd = pwdInput.getAttribute('type') === 'password';
            pwdInput.setAttribute('type', isPwd ? 'text' : 'password');

            // ubah ikon
            const icon = this.querySelector('i');
            if (icon) {
                icon.setAttribute('data-feather', isPwd ? 'eye-off' : 'eye');
                if (window.feather) feather.replace();
            }
        });
    }
});
</script>

</body>
</html>