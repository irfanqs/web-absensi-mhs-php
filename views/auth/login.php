<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login &mdash; Sistem Absensi</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, sans-serif;
            background: #f0f2f5;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-wrapper {
            width: 100%;
            max-width: 400px;
            padding: 16px;
        }

        .login-brand {
            text-align: center;
            margin-bottom: 28px;
        }

        .login-brand h1 {
            font-size: 28px;
            font-weight: 800;
            color: #2c3e50;
        }

        .login-brand h1 span { color: #3498db; }

        .login-brand p {
            color: #6b7280;
            font-size: 14px;
            margin-top: 6px;
        }

        .login-card {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.10);
            padding: 32px;
        }

        .login-card h2 {
            font-size: 18px;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 24px;
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #444;
            margin-bottom: 6px;
        }

        .form-control {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 14px;
            color: #333;
            outline: none;
            transition: border-color 0.15s, box-shadow 0.15s;
        }

        .form-control:focus {
            border-color: #3498db;
            box-shadow: 0 0 0 3px rgba(52,152,219,0.15);
        }

        .btn-block {
            display: block;
            width: 100%;
            padding: 11px;
            background: #3498db;
            color: #fff;
            border: none;
            border-radius: 6px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 8px;
            transition: background 0.15s;
        }

        .btn-block:hover { background: #2980b9; }

        .password-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }
        .password-wrapper .form-control {
            padding-right: 40px; /* Space for the eye icon */
        }
        .toggle-password {
            position: absolute;
            right: 12px;
            cursor: pointer;
            font-size: 16px;
            color: #9ca3af;
            user-select: none;
        }
        .toggle-password:hover {
            color: #4b5563;
        }

        .flash {
            padding: 11px 14px;
            border-radius: 6px;
            margin-bottom: 18px;
            font-size: 13px;
        }

        .flash.success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .flash.error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .login-footer {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: #9ca3af;
        }

        .hint-box {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 6px;
            padding: 10px 14px;
            font-size: 12px;
            color: #1e40af;
            margin-top: 18px;
        }

        .hint-box strong { display: block; margin-bottom: 4px; }
    </style>
</head>
<body>
<div class="login-wrapper">
    <div class="login-brand">
        <h1>Sistem <span>Absensi</span></h1>
        <p>Aplikasi Manajemen Kehadiran Pegawai</p>
    </div>

    <div class="login-card">
        <h2>Sign In</h2>

        <?php if (!empty($_SESSION['flash'])): ?>
            <div class="flash <?= htmlspecialchars($_SESSION['flash']['type']) ?>">
                <?= $_SESSION['flash']['message'] ?>
            </div>
            <?php unset($_SESSION['flash']); ?>
        <?php endif; ?>

        <form action="<?= BASE_URL ?>/index.php?page=auth&action=login" method="POST">
            <input type="hidden" name="csrf_token" value="<?= Auth::generateCsrfToken() ?>">

            <div class="form-group">
                <label for="email">Email Address</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    class="form-control"
                    placeholder="admin@example.com"
                    value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                    required
                    autofocus
                >
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <div class="password-wrapper">
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="form-control"
                        placeholder="&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;"
                        required
                    >
                    <span class="toggle-password" id="togglePasswordIcon" onclick="togglePassword()">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                    </span>
                </div>
            </div>

            <button type="submit" class="btn-block">Sign In &rarr;</button>
        </form>

        <!-- <div class="hint-box">
            <strong>Default Credentials</strong>
            Email: admin@example.com<br>
            Password: aku233
        </div> -->
    </div>

    <div class="login-footer">
        &copy; <?= date('Y') ?> Sistem Absensi 
    </div>
</div>

<script>
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const toggleIcon = document.getElementById('togglePasswordIcon');
        
        const eyeOpen = `<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>`;
        const eyeOff = `<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye-off"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>`;

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.innerHTML = eyeOff;
            toggleIcon.style.opacity = '0.7';
        } else {
            passwordInput.type = 'password';
            toggleIcon.innerHTML = eyeOpen;
            toggleIcon.style.opacity = '1';
        }
    }
</script>
</body>
</html>
