<?php
session_start();

// File ini berfungsi sebagai landing page utama
// Jika user tidak login, redirect ke login.php
// Jika user sudah login, redirect ke dashboard.php
// Untuk halaman jadwal interview, gunakan jadwal_interview.php

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
} else {
    // Redirect ke dashboard jika sudah login
    header("Location: dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Showroom Mobil</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        :root {
            --primary: #007AFF;
            --secondary: #4A90E2;
            --success: #00FF00;
            --warning: #FFD700;
            --danger: #FF6B6B;
            --light: #F8F9FA;
            --dark: #212529;
            --sidebar-width: 250px;
        }

        body {
            background-color: #f5f5f5;
            color: var(--dark);
            overflow-x: hidden;
        }

        .container {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Styles */
        .sidebar {
            width: var(--sidebar-width);
            background: white;
            border-right: 1px solid #e0e0e0;
            height: 100vh;
            position: fixed;
            display: flex;
            flex-direction: column;
            z-index: 100;
            transition: all 0.3s ease;
        }

        .sidebar-header {
            padding: 20px;
            display: flex;
            align-items: center;
            border-bottom: 1px solid #e0e0e0;
        }

        .logo {
            font-size: 28px;
            color: var(--primary);
            margin-right: 10px;
        }

        .sidebar-nav {
            padding: 20px 0;
            flex-grow: 1;
        }

        .nav-item {
            position: relative;
            padding: 12px 20px;
            display: flex;
            align-items: center;
            text-decoration: none;
            transition: 
                background-color 0.25s ease,
                color 0.25s ease,
                padding-left 0.25s ease;
        }

        .nav-item:hover {
            background: #f0f0f0;
            padding-left: 26px;
        }

        /* garis kiri animasi */
        .nav-item::before {
            content: "";
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 0;
            background: var(--primary);
            transition: width 0.25s ease;
        }

        .nav-item:hover::before,
        .nav-item.active::before {
            width: 4px;
        }

        .sidebar .nav-item {
            color: #000;
        }

        .sidebar .nav-item {
            text-decoration: none;
        }

        .nav-item:hover {
            background: #f0f0f0;
            border-left: 3px solid var(--primary);
        }

        .nav-item.active {
            background: #e6f0ff;
            border-left: 3px solid var(--primary);
            color: var(--primary);
            font-weight: 600;
        }

        .nav-icon {
            margin-right: 12px;
            width: 20px;
            text-align: center;
        }

        .sidebar-footer {
            padding: 20px;
            border-top: 1px solid #e0e0e0;
        }

        /* Main Content Styles */
        .main-content {
            margin-left: var(--sidebar-width);
            width: calc(100% - var(--sidebar-width));
            padding: 25px;
            background: #f8f9fa;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .page-header {
        margin-bottom: 30px;
        padding-bottom: 15px;
        border-bottom: 1px solid #e0e0e0; /* GARIS HEADER */
        }


        .welcome {
            max-width: 600px;
        }

        .welcome h1 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .welcome p {
            font-size: 16px;
            color: #555;
        }

        /* Stats Cards */
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stats-card {
            background: #4A90E2;
            color: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }

        .stats-card h3 {
            font-size: 16px;
            opacity: 0.9;
            margin-bottom: 8px;
        }

        .stats-card p {
            font-size: 24px;
            font-weight: 700;
        }

        /* Table Styles */
        .table-container {
            background: white;
            border-radius: 0px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            overflow: hidden;
            margin-bottom: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #f0f0f0;
            padding: 20px;
            text-align: center;
            font-weight: 600;
            color: #000000ff;
            border: 1px solid #000000ff;
        }

        td {
            padding: 15px;
            border: 1px solid #000000ff;
            text-align: center;
        }

        /* Row gradients */
        .row-1 {
            background: linear-gradient(90deg, #50E3C2 0%, #4A90E2 100%);
            color: white;
        }

        .row-2 {
            background: linear-gradient(90deg, #F5A623 0%, #4A90E2 100%);
            color: white;
        }

        .row-3 {
            background: linear-gradient(90deg, #4A90E2 0%, #3B5998 100%);
            color: white;
        }

        .row-4 {
            background: linear-gradient(90deg, #007AFF 0%, #0056b3 100%);
            color: white;
        }

        /* Status styles */
        .status {
            padding: 5px 10px;
            border-radius: 20px;
            font-weight: 500;
            display: inline-block;
        }

        .status-belum {
            background: var(--warning);
            color: #000;
        }

        .status-selesai {
            background: var(--success);
            color: white;
        }

        .status-dijadwalkan {
            background: #6c757d;
            color: white;
        }

        .status-dibatalkan {
            background: var(--danger);
            color: white;
        }

        /* Notification Bar */
        .notification-bar {
            display: flex;
            align-items: center;
            background: #e6f7ff;
            border-radius: 12px;
            padding: 15px;
            margin-bottom: 30px;
            border: 1px solid #4A90E2;
        }

        .notification-icon {
            background: #4A90E2;
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            font-size: 18px;
        }

        .notification-content {
            flex-grow: 1;
        }

        .notification-content p {
            font-weight: 500;
        }

        .notification-btn {
            background: #000;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }

        .notification-btn:hover {
            background: #333;
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 15px;
        }

        .btn {
            padding: 12px 20px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background: #0066cc;
        }

        /* Responsive Design */
        @media (max-width: 992px) {
            .sidebar {
                width: 70px;
            }
            
            .sidebar-header, .nav-item span, .sidebar-footer {
                display: none;
            }
            
            .nav-icon {
                margin-right: 0;
                font-size: 18px;
            }
            
            .main-content {
                margin-left: 70px;
            }
            
            .stats-container {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media (max-width: 768px) {
            .stats-container {
                grid-template-columns: 1fr;
            }
            
            .header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
        }

        @media (max-width: 576px) {
            .sidebar {
                width: 60px;
            }
            
            .main-content {
                margin-left: 60px;
            }   
            
            table {
                font-size: 14px;
            }
            
            th, td {
                padding: 10px 8px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <aside class="sidebar">
            <div class="sidebar-header">
                <div class="logo">
                    <img src="asset/home.png" alt="Logo" width="60" height="60">
                </div>
            </div>
            
            <nav class="sidebar-nav">
                <a href="dashboard.php" class="nav-item">
                    <span class="nav-icon"><i class="fas fa-bookmark"></i></span>
                    <span>Dashboard</span>
                </a>
                <a href="posisi.php" class="nav-item">
                    <span class="nav-icon"><i class="fas fa-file"></i></span>
                    <span>Posisi</span>
                </a>
                <a href="index.php" class="nav-item active">
                    <span class="nav-icon"><i class="fas fa-clock"></i></span>
                    <span>Jadwal Interview</span>
                </a>
                <a href="datapelamar.php" class="nav-item">
                    <span class="nav-icon"><i class="fas fa-database"></i></span>
                    <span>Data Pelamar</span>
                </a>
            </nav>
            
            <div class="sidebar-footer-nav">
                <a href="settings.php" class="nav-item">
                    <span class="nav-icon"><i class="fas fa-cog"></i></span>
                    <span>Settings</span>
                </a>
                <a href="logout.php" class="nav-item">
                    <span class="nav-icon"><i class="fas fa-sign-out-alt"></i></span>
                    <span>Sign Out</span>
                </a>
            </div>
        </aside>

        <main class="main-content">
            <div class="header">
                <div class="welcome">
                    <h1>Welcome, Jos Jis</h1>
                    <p>Showroom Mobil masa kini !!</p>
                </div>
            </div>

            <div class="stats-container">
                <div class="stats-card">
                    <h3>Total Interview Minggu ini</h3>
                    <p>12 Jadwal</p>
                </div>
                <div class="stats-card">
                    <h3>Interview Hari ini</h3>
                    <p>3 Kandidat</p>
                </div>
                <div class="stats-card">
                    <h3>Interview Sesuai Bulan ini</h3>
                    <p>28 Kandidat</p>
                </div>
            </div>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>NAMA PELAMAR</th>
                            <th>POSISI</th>
                            <th>TANGGAL</th>
                            <th>WAKTU</th>
                            <th>STATUS</th>
                            <th>LINK</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="row-1">
                            <td>YANTO WIJAYA</td>
                            <td>SUPERVISOR</td>
                            <td>24 OKT 2025</td>
                            <td>09.00</td>
                            <td><span class="status status-belum">BELUM HADIR</span></td>
                            <td>https://zoom-contoh.com/meeting-12345</td>
                        </tr>
                        <tr class="row-2">
                            <td>YANTI KUSWANTI</td>
                            <td>OPERATOR MESIN</td>
                            <td>24 OKT 2025</td>
                            <td>10.30</td>
                            <td><span class="status status-selesai">SELESAI</span></td>
                            <td>end</td>
                        </tr>
                        <tr class="row-3">
                            <td>YANTA HERMAWAN</td>
                            <td>STAFF ADMIN</td>
                            <td>25 OKT 2025</td>
                            <td>08.00</td>
                            <td><span class="status status-dijadwalkan">DIJADWALKAN</span></td>
                            <td>-</td>
                        </tr>
                        <tr class="row-4">
                            <td>YANTE SEMELEKETE</td>
                            <td>STAFF AKUNTANSI</td>
                            <td>25 OKT 2025</td>
                            <td>13.00</td>
                            <td><span class="status status-dibatalkan">DIBATALKAN</span></td>
                            <td>-</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="notification-bar">
                <div class="notification-icon">
                    <i class="fas fa-bell"></i>
                </div>
                <div class="notification-content">
                    <p>Interview "Wijaya" akan dimulai 15 menit lagi!</p>
                </div>
                <button class="notification-btn">Buka Room Interview</button>
            </div>

            <div class="action-buttons">
                <button class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Jadwal
                </button>
            </div>
        </main>
    </div>

    <script>
        document.querySelector('.notification-btn').addEventListener('click', function() {
            alert('Membuka ruang interview untuk YANTO WIJAYA...');
        });
        
        const buttons = document.querySelectorAll('.btn');
        buttons.forEach(button => {
            button.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-2px)';
            });
            
            button.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });
    </script>
</body>
</html>