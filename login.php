<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Showroom Mobil</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: linear-gradient(180deg, #0f0f1e 0%, #1a1a2e 50%, #16213e 100%);
            color: #fff;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            position: relative;
            overflow: hidden;
        }

        /* Rain Animation */
        .rain-container {
            position: fixed;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: 0;
            pointer-events: none;
        }

        .rain {
            position: absolute;
            width: 2px;
            height: 80px;
            background: linear-gradient(to bottom, transparent, rgba(255, 255, 255, 0.6), transparent);
            animation: rainfall linear infinite;
        }

        .rain:nth-child(1) { left: 5%; animation-delay: 0s; height: 80px; animation-duration: 1.2s; }
        .rain:nth-child(2) { left: 10%; animation-delay: 0.15s; height: 100px; animation-duration: 1.5s; }
        .rain:nth-child(3) { left: 15%; animation-delay: 0.3s; height: 90px; animation-duration: 1.3s; }
        .rain:nth-child(4) { left: 20%; animation-delay: 0.1s; height: 70px; animation-duration: 1.1s; }
        .rain:nth-child(5) { left: 25%; animation-delay: 0.4s; height: 95px; animation-duration: 1.4s; }
        .rain:nth-child(6) { left: 30%; animation-delay: 0.05s; height: 85px; animation-duration: 1.25s; }
        .rain:nth-child(7) { left: 35%; animation-delay: 0.25s; height: 75px; animation-duration: 1.35s; }
        .rain:nth-child(8) { left: 40%; animation-delay: 0.35s; height: 105px; animation-duration: 1.6s; }
        .rain:nth-child(9) { left: 45%; animation-delay: 0.2s; height: 80px; animation-duration: 1.2s; }
        .rain:nth-child(10) { left: 50%; animation-delay: 0.45s; height: 90px; animation-duration: 1.45s; }
        .rain:nth-child(11) { left: 55%; animation-delay: 0.12s; height: 85px; animation-duration: 1.3s; }
        .rain:nth-child(12) { left: 60%; animation-delay: 0.38s; height: 95px; animation-duration: 1.5s; }
        .rain:nth-child(13) { left: 65%; animation-delay: 0.08s; height: 70px; animation-duration: 1.15s; }
        .rain:nth-child(14) { left: 70%; animation-delay: 0.28s; height: 100px; animation-duration: 1.55s; }
        .rain:nth-child(15) { left: 75%; animation-delay: 0.42s; height: 80px; animation-duration: 1.25s; }
        .rain:nth-child(16) { left: 80%; animation-delay: 0.18s; height: 90px; animation-duration: 1.4s; }
        .rain:nth-child(17) { left: 85%; animation-delay: 0.32s; height: 85px; animation-duration: 1.35s; }
        .rain:nth-child(18) { left: 90%; animation-delay: 0.5s; height: 75px; animation-duration: 1.2s; }
        .rain:nth-child(19) { left: 95%; animation-delay: 0.22s; height: 95px; animation-duration: 1.45s; }
        .rain:nth-child(20) { left: 12%; animation-delay: 0.48s; height: 80px; animation-duration: 1.3s; }

        @keyframes rainfall {
            0% {
                top: -100px;
                opacity: 0;
            }
            10% {
                opacity: 1;
            }
            90% {
                opacity: 1;
            }
            100% {
                top: 100vh;
                opacity: 0;
            }
        }

        .login-container {
            text-align: center;
            max-width: 400px;
            width: 100%;
            position: relative;
            z-index: 1;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            padding: 40px;
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        }

        .sun-icon {
            width: 60px;
            height: 60px;
            display: block;
            margin: 0 auto 24px;
            object-fit: contain;
        }

        h1 {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 8px;
            color: #fff;
        }

        .subtitle {
            font-size: 14px;
            color: rgba(255, 255, 255, 0.7);
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 20px;
            position: relative;
        }

        .form-group label {
            display: block;
            text-align: left;
            margin-bottom: 8px;
            font-weight: 500;
            color: rgba(255, 255, 255, 0.9);
        }

        .form-control {
            width: 100%;
            padding: 10px 0;
            border: none;
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
            font-size: 16px;
            background: transparent;
            color: #fff;
        }

        .form-control:focus {
            outline: none;
            border-bottom-color: #4A90E2;
        }

        .password-container {
            position: relative;
        }

        .toggle-password {
            position: absolute;
            right: 0;
            top: 10px;
            cursor: pointer;
            color: rgba(255, 255, 255, 0.6);
        }

        .remember-forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 15px 0 25px;
            color: rgba(255, 255, 255, 0.8);
            font-size: 14px;
        }

        .forgot-password {
            color: #4A90E2;
            text-decoration: none;
            font-size: 14px;
        }

        .btn {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 50px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            margin-bottom: 15px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #4A90E2, #007AFF);
            color: white;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #5BA3F5, #1E88E5);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(74, 144, 226, 0.4);
        }
    </style>
</head>

<body>
<!-- Rain Animation -->
<div class="rain-container">
    <div class="rain"></div>
    <div class="rain"></div>
    <div class="rain"></div>
    <div class="rain"></div>
    <div class="rain"></div>
    <div class="rain"></div>
    <div class="rain"></div>
    <div class="rain"></div>
    <div class="rain"></div>
    <div class="rain"></div>
    <div class="rain"></div>
    <div class="rain"></div>
    <div class="rain"></div>
    <div class="rain"></div>
    <div class="rain"></div>
    <div class="rain"></div>
    <div class="rain"></div>
    <div class="rain"></div>
    <div class="rain"></div>
    <div class="rain"></div>
</div>

<div class="login-container">

    <img src="asset/sun-icon.png" alt="Sun icon" class="sun-icon">

    <h1>Welcome Again!</h1>
    <p class="subtitle">Please enter your details!</p>

    <!-- FORM -->
    <form method="post" action="login_proses.php" id="login-form">

        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Password</label>
            <div class="password-container">
                <input
                    type="password"
                    id="password"
                    name="password"
                    class="form-control"
                    required
                >
                <i class="fas fa-eye toggle-password" id="togglePassword"></i>
            </div>
        </div>

        <div class="remember-forgot">
            <label><input type="checkbox"> Remember for 30 days</label>
            <a href="#" class="forgot-password">Forgot Password?</a>
        </div>

        <button type="submit" class="btn btn-primary">Login</button>
    </form>

</div>

<script>
    // Toggle show/hide password
    document.getElementById('togglePassword').addEventListener('click', function () {
        const passwordInput = document.getElementById('password');
        const type = passwordInput.type === 'password' ? 'text' : 'password';
        passwordInput.type = type;
        this.classList.toggle('fa-eye-slash');
    });

    // Simple animation
    document.addEventListener('DOMContentLoaded', () => {
        const container = document.querySelector('.login-container');
        container.style.opacity = '0';
        container.style.transform = 'translateY(20px)';

        setTimeout(() => {
            container.style.transition = '0.5s';
            container.style.opacity = '1';
            container.style.transform = 'translateY(0)';
        }, 100);
    });
</script>

</body>
</html>
