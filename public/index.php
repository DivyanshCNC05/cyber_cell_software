<?php
require __DIR__ . '/../includes/db.php';
session_start();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $pass  = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("
        SELECT user_id, email, password_hash, password_hash_new, role, full_name, is_active, user_number
        FROM login_credentials
        WHERE email = :email
        LIMIT 1
    ");
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch();

    if (!$user) {
        $error = 'Email not found';
    } elseif ((int)$user['is_active'] !== 1) {
        $error = 'User is inactive';
    } else {
        $hash = $user['password_hash_new'] ?: $user['password_hash'];

        if (!$hash || !password_verify($pass, $hash)) {
            $error = 'Wrong password';
        } else {
            session_regenerate_id(true);

            $_SESSION['user_id']      = (int)$user['user_id'];
            $_SESSION['role']         = $user['role'];
            $_SESSION['name']         = $user['full_name'];
            $_SESSION['user_number']  = $user['user_number'];

            if ($user['role'] === 'ADMIN') {
                $dest = BASE_PATH . '/dashboards/admin.php';
                header('Location: ' . $dest); exit;
            }
            if ($user['role'] === 'CEIR_USER') {
                $dest = BASE_PATH . '/dashboards/ceir.php';
                header('Location: ' . $dest); exit;
            }
            if ($user['role'] === 'CYBER_USER') {
                if ((int)$user['user_number'] === 1) { $dest = BASE_PATH . '/dashboards/user1.php'; header('Location: ' . $dest); exit; }
                if ((int)$user['user_number'] === 2) { $dest = BASE_PATH . '/dashboards/user2.php'; header('Location: ' . $dest); exit; }
                if ((int)$user['user_number'] === 3) { $dest = BASE_PATH . '/dashboards/user3.php'; header('Location: ' . $dest); exit; }
                $error = 'CYBER_USER must have user_number 1,2,3';
            } else {
                $error = 'Invalid role';
            }
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Cyber Cell Portal</title>
  <link rel="icon" href="./assets/image/cyber-logo.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            background:url('./assets/image/cyber_cell_flex.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            /* background-position: center; */
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            position: relative;
            overflow-x: hidden;
        }

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            /* background: url('./assets/image/cyber_cell_flex.jpg') center/cover no-repeat; */
            z-index: -2;
            filter: blur(1px) brightness(0.6);
        }

        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.3);
            padding: 3rem;
            width: 100%;
            max-width: 420px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            position: relative;
            overflow: hidden;
            animation: slideUp 0.8s ease-out;
        }

        .login-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            /* background: linear-gradient(90deg, #0a1431, #1e3a8a, #0a1431); */
            border-radius: 24px 24px 0 0;
        }

        .logo-container {
            text-align: center;
            margin-bottom: 2rem;
            position: relative;
        }

        .logo {
            width: 80px;
            height: 80px;
            border-radius: 20px;
            background: linear-gradient(135deg, #0a1431, #1e3a8a);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            box-shadow: 0 10px 30px rgba(10, 20, 49, 0.4);
            border: 3px solid rgba(255, 255, 255, 0.3);
        }

        .logo img {
            max-width: 60px;
            max-height: 60px;
            border-radius: 12px;
        }

        .login-title {
            font-size: 1.8rem;
            font-weight: 700;
            background: linear-gradient(135deg, #0a1431, #1e3a8a);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 0.5rem;
            letter-spacing: -0.02em;
        }

        .login-subtitle {
            color: #6b7280;
            font-size: 0.95rem;
            font-weight: 500;
        }

        .form-floating {
            position: relative;
            margin-bottom: 1.5rem;
        }

        .form-control {
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            padding: 1rem 1rem 1rem 1rem;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.8);
            height: 56px;
        }

        .form-control:focus {
            border-color: #0a1431;
            box-shadow: 0 0 0 0.2rem rgba(10, 20, 49, 0.15);
            background: rgba(255, 255, 255, 1);
            transform: translateY(-1px);
        }

        .form-floating > label {
            padding: 1rem 0.75rem;
            color: #6b7280;
            font-weight: 500;
            transform-origin: 0 0;
            transition: all 0.3s ease;
        }

        .form-floating > .form-control:focus ~ label,
        .form-floating > .form-control:not(:placeholder-shown) ~ label {
            color: #0a1431;
            font-size: 0.8rem;
            transform: translateY(-1.5rem) scale(0.85);
        }

        .btn-login {
            background: linear-gradient(135deg, #0a1431 0%, #1e3a8a 100%);
            border: none;
            border-radius: 12px;
            padding: 1rem 2rem;
            font-size: 1rem;
            font-weight: 600;
            color: white;
            width: 100%;
            height: 56px;
            transition: all 0.3s ease;
            box-shadow: 0 10px 30px rgba(10, 20, 49, 0.3);
            position: relative;
            overflow: hidden;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 40px rgba(10, 20, 49, 0.4);
            color: white;
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .alert {
            border: none;
            border-radius: 12px;
            padding: 1rem 1.25rem;
            margin-bottom: 1.5rem;
            backdrop-filter: blur(10px);
        }

        .alert-danger {
            background: rgba(239, 68, 68, 0.15);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #dc2626;
        }

        .footer-text {
            margin-top: 2rem;
            text-align: center;
            color: #9ca3af;
            font-size: 0.85rem;
        }

        .floating-shapes {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 0;
        }

        .shape {
            position: absolute;
            background: rgba(10, 20, 49, 0.1);
            border-radius: 50%;
            animation: float 20s infinite linear;
        }

        .shape:nth-child(1) { width: 80px; height: 80px; top: 20%; left: 10%; animation-delay: 0s; }
        .shape:nth-child(2) { width: 120px; height: 120px; top: 60%; right: 15%; animation-delay: -10s; }
        .shape:nth-child(3) { width: 60px; height: 60px; bottom: 20%; left: 20%; animation-delay: -5s; }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            33% { transform: translateY(-20px) rotate(120deg); }
            66% { transform: translateY(-10px) rotate(240deg); }
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 576px) {
            .login-card {
                margin: 1rem;
                padding: 2rem 1.5rem;
            }
            .logo {
                width: 70px;
                height: 70px;
            }
            .logo img {
                max-width: 50px;
                max-height: 50px;
            }
        }
    </style>
</head>
<body>
    <div class="floating-shapes">
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
    </div>

    <div class="login-container">
        <div class="login-card">
            <div class="logo-container">
                <div class="logo">
                    <img src="./assets/image/cyber-logo.png" alt="Cyber Cell Logo" onerror="this.style.display='none'; this.parentNode.innerHTML='<i class=\'fas fa-shield-alt fa-2x text-white\'></i>'">
                </div>
                <h1 class="login-title">Cyber Cell Portal</h1>
                <div class="login-subtitle">Secure Login Required</div>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form method="post" novalidate>
                <div class="form-floating">
                    <input class="form-control" type="email" name="email" id="email" placeholder="name@example.com" required autofocus>
                    <label for="email">Username</label>
                </div>
                
                <div class="form-floating">
                    <input class="form-control" type="password" name="password" id="password" placeholder="Password" required>
                    <label for="password">Password</label>
                </div>

                <button type="submit" class="btn btn-login">
                    <i class="fas fa-sign-in-alt me-2"></i>
                    Sign In
                </button>
            </form>

            <div class="footer-text">
                <i class="fas fa-lock me-1"></i>
                © <?= date('Y') ?> Cyber Cell Portal. All rights reserved.
            </div>
        </div>
    </div>

    <script>
        // Form enhancement
        document.querySelector('form').addEventListener('submit', function(e) {
            const btn = document.querySelector('.btn-login');
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Signing In...';
            btn.disabled = true;
        });

        // Auto-focus email field
        document.getElementById('email').focus();
    </script>
</body>
</html>
