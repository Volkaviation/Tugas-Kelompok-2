<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout - Showroom Mobil</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: white;
            color: #000;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .logout-container {
            text-align: center;
            max-width: 500px;
            padding: 20px;
        }

        .sun-icon {
            width: 250px;
            height: 250px;
            display: block;
            margin: 0 auto 24px;
            object-fit: contain;
        }

        h1 {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 12px;
            line-height: 1.2;
        }

        p {
            font-size: 18px;
            color: #555;
            margin-bottom: 40px;
        }

        .logout-btn {
            background: #000;
            color: white;
            border: none;
            padding: 15px 60px;
            border-radius: 50px;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            display: block;
            width: 100%;
            max-width: 350px;
            margin: 0 auto;
        }

        .logout-btn:hover {
            background: #333;
            transform: translateY(-2px);
        }

        /* Responsive Design */
        @media (max-width: 576px) {
            .sun-icon {
                width: 300px;
                height: 300px;
            }
            
            h1 {
                font-size: 28px;
            }
            
            p {
                font-size: 16px;
            }
        }

        @media (max-width: 425px) {
            .sun-icon {
                width: 250px;
                height: 250px;
            }
            
            h1 {
                font-size: 24px;
            }
            
            p {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="logout-container">
        <img 
            src="asset/sun-icon.png"
            alt="Sun icon indicating logout confirmation" 
            class="sun-icon"
            onerror="this.onerror=null; this.src='#';"
        >
        
        <h1>You're sign out right now</h1>
        <p>Thanks for choosing us</p>
        
        <form action="proses_logout.php" method="post">
    <button type="submit">Logout</button>
</form>
    </div>

    <script>
        function logout() {
            document.querySelector('.logout-container').style.opacity = '0.7';
            document.querySelector('.logout-container').style.transition = 'opacity 0.3s';
            
            setTimeout(() => {
                alert('You have been successfully logged out!');
                window.location.href = 'proses_logout.php';
            }, 800);
        }
        
        document.addEventListener('DOMContentLoaded', () => {
            const container = document.querySelector('.logout-container');
            container.style.opacity = '0';
            container.style.transform = 'translateY(20px)';
            
            setTimeout(() => {
                container.style.transition = 'opacity 0.5s, transform 0.5s';
                container.style.opacity = '1';
                container.style.transform = 'translateY(0)';
            }, 100);
        });
    </script>
</body>
</html>





