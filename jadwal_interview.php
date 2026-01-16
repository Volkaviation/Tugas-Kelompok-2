<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

include 'koneksi.php';

// Query untuk mengambil semua jadwal interview
$query_jadwal = mysqli_query($koneksi, "SELECT * FROM jadwal_interview ORDER BY tanggal_interview ASC, waktu_interview ASC");

// Query untuk statistik
$today = date('Y-m-d');
$week_start = date('Y-m-d', strtotime('monday this week'));
$week_end = date('Y-m-d', strtotime('sunday this week'));
$month_start = date('Y-m-01');
$month_end = date('Y-m-t');

// Total interview minggu ini
$query_week = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM jadwal_interview WHERE tanggal_interview BETWEEN '$week_start' AND '$week_end'");
$total_week = mysqli_fetch_assoc($query_week)['total'];

// Interview hari ini
$query_today = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM jadwal_interview WHERE tanggal_interview = '$today'");
$total_today = mysqli_fetch_assoc($query_today)['total'];

// Interview bulan ini (yang sudah selesai)
$query_month = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM jadwal_interview WHERE tanggal_interview BETWEEN '$month_start' AND '$month_end' AND status_interview = 'SELESAI'");
$total_month = mysqli_fetch_assoc($query_month)['total'];

// Get upcoming interview untuk notifikasi
$query_upcoming = mysqli_query($koneksi, "SELECT * FROM jadwal_interview WHERE tanggal_interview = '$today' AND status_interview = 'DIJADWALKAN' ORDER BY waktu_interview ASC LIMIT 1");
$upcoming_interview = mysqli_fetch_assoc($query_upcoming);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jadwal Interview - Showroom Mobil</title>
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
            background: linear-gradient(90deg, #ffffff 0%, #ffffff 100%);
            color: black;
        }

        .row-2 {
            background: linear-gradient(90deg, #ffffff 0%, #ffffff 100%);
            color: black;
        }

        .row-3 {
            background: linear-gradient(90deg, #ffffff 0%, #ffffff 100%);
            color: black;
        }

        .row-4 {
            background: linear-gradient(90deg, #ffffff 0%, #ffffff 100%);
            color: black;
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

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(5px);
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideDown {
            from { 
                opacity: 0;
                transform: translateY(-50px);
            }
            to { 
                opacity: 1;
                transform: translateY(0);
            }
        }

        .modal.show {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background: white;
            padding: 30px;
            border-radius: 16px;
            width: 90%;
            max-width: 600px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            animation: slideDown 0.3s ease;
            max-height: 90vh;
            overflow-y: auto;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid #e0e0e0;
        }

        .modal-header h2 {
            color: var(--primary);
            font-size: 24px;
            font-weight: 700;
        }

        .close-btn {
            background: none;
            border: none;
            font-size: 28px;
            cursor: pointer;
            color: #999;
            transition: color 0.2s, transform 0.2s;
            padding: 0;
            width: 35px;
            height: 35px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .close-btn:hover {
            color: var(--danger);
            transform: rotate(90deg);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.3s, box-shadow 0.3s;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(0, 122, 255, 0.1);
        }

        .form-group textarea {
            resize: vertical;
            min-height: 80px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-secondary:hover {
            background: #5a6268;
            transform: translateY(-2px);
        }

        .btn-success {
            background: var(--primary);
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-success:hover {
            background: #0056b3;
            transform: translateY(-2px);
        }

        @media (max-width: 576px) {
            .modal-content {
                padding: 20px;
                width: 95%;
            }

            .form-row {
                grid-template-columns: 1fr;
            }

            .modal-header h2 {
                font-size: 20px;
            }
        }

        /* Action Buttons in Table */
        .btn-edit, .btn-delete {
            border: none;
            padding: 6px 12px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.2s;
            margin: 0 2px;
        }

        .btn-edit {
            background: #4A90E2;
            color: white;
        }

        .btn-edit:hover {
            background: #357ABD;
            transform: translateY(-1px);
        }

        .btn-delete {
            background: #FF6B6B;
            color: white;
        }

        .btn-delete:hover {
            background: #E55555;
            transform: translateY(-1px);
        }

        .btn-join {
            background: #28a745;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.2s;
            margin: 0 2px;
        }

        .btn-join:hover {
            background: #218838;
            transform: translateY(-1px);
        }

        .btn-join:disabled {
            background: #ccc;
            cursor: not-allowed;
            opacity: 0.6;
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
                <a href="jadwal_interview.php" class="nav-item active">
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
                    <h1>Jadwal Interview</h1>
                    <p>Kelola jadwal interview kandidat</p>
                </div>
            </div>

            <div class="stats-container">
                <div class="stats-card">
                    <h3>Total Interview Minggu ini</h3>
                    <p><?php echo $total_week; ?> Jadwal</p>
                </div>
                <div class="stats-card">
                    <h3>Interview Hari ini</h3>
                    <p><?php echo $total_today; ?> Kandidat</p>
                </div>
                <div class="stats-card">
                    <h3>Interview Selesai Bulan ini</h3>
                    <p><?php echo $total_month; ?> Kandidat</p>
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
                            <th>AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        if (mysqli_num_rows($query_jadwal) > 0) {
                            $row_num = 1;
                            while ($jadwal = mysqli_fetch_assoc($query_jadwal)) {
                                // Format tanggal
                                $tanggal_obj = new DateTime($jadwal['tanggal_interview']);
                                $bulan = ['JAN', 'FEB', 'MAR', 'APR', 'MEI', 'JUN', 'JUL', 'AGU', 'SEP', 'OKT', 'NOV', 'DES'];
                                $tanggal_formatted = $tanggal_obj->format('d') . ' ' . $bulan[$tanggal_obj->format('n') - 1] . ' ' . $tanggal_obj->format('Y');
                                
                                // Format waktu
                                $waktu_formatted = date('H.i', strtotime($jadwal['waktu_interview']));
                                
                                // Determine row class
                                $row_class = 'row-' . (($row_num % 4) + 1);
                                
                                // Determine status class
                                $status_class = '';
                                switch ($jadwal['status_interview']) {
                                    case 'BELUM HADIR':
                                        $status_class = 'status-belum';
                                        break;
                                    case 'SELESAI':
                                        $status_class = 'status-selesai';
                                        break;
                                    case 'DIJADWALKAN':
                                        $status_class = 'status-dijadwalkan';
                                        break;
                                    case 'DIBATALKAN':
                                        $status_class = 'status-dibatalkan';
                                        break;
                                }
                                
                                // Encode data for JavaScript
                                $data_json = htmlspecialchars(json_encode($jadwal), ENT_QUOTES, 'UTF-8');
                                
                                echo "<tr class='$row_class' data-id='{$jadwal['id_jadwal']}' data-schedule='$data_json'>";
                                echo "<td>" . htmlspecialchars($jadwal['nama_pelamar']) . "</td>";
                                echo "<td>" . htmlspecialchars($jadwal['posisi']) . "</td>";
                                echo "<td>$tanggal_formatted</td>";
                                echo "<td>$waktu_formatted</td>";
                                echo "<td><span class='status $status_class'>" . $jadwal['status_interview'] . "</span></td>";
                                echo "<td>" . ($jadwal['link_interview'] ? htmlspecialchars($jadwal['link_interview']) : '-') . "</td>";
                                echo "<td>";
                                // Join Interview button - only show if link exists
                                if (!empty($jadwal['link_interview']) && $jadwal['link_interview'] != '-') {
                                    echo "<button class='btn-join' data-link='" . htmlspecialchars($jadwal['link_interview']) . "' title='Join Interview'><i class='fas fa-video'></i></button> ";
                                } else {
                                    echo "<button class='btn-join' disabled title='Tidak ada link'><i class='fas fa-video'></i></button> ";
                                }
                                echo "<button class='btn-edit' data-id='{$jadwal['id_jadwal']}' title='Edit'><i class='fas fa-edit'></i></button> ";
                                echo "<button class='btn-delete' data-id='{$jadwal['id_jadwal']}' title='Hapus'><i class='fas fa-trash'></i></button>";
                                echo "</td>";
                                echo "</tr>";
                                
                                $row_num++;
                            }
                        } else {
                            echo "<tr><td colspan='7' style='text-align:center; padding:20px;'>Belum ada jadwal interview</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <?php if ($upcoming_interview): ?>
            <div class="notification-bar" id="notificationBar">
                <div class="notification-icon">
                    <i class="fas fa-bell"></i>
                </div>
                <div class="notification-content">
                    <p>Interview "<?php echo htmlspecialchars($upcoming_interview['nama_pelamar']); ?>" dijadwalkan hari ini pukul <?php echo date('H:i', strtotime($upcoming_interview['waktu_interview'])); ?>!</p>
                </div>
                <button class="notification-btn" data-link="<?php echo htmlspecialchars($upcoming_interview['link_interview']); ?>">Buka Room Interview</button>
            </div>
            <?php endif; ?>

            <div class="action-buttons">
                <button class="btn btn-primary" id="btnTambahJadwal">
                    <i class="fas fa-plus"></i> Tambah Jadwal
                </button>
            </div>
        </main>
    </div>

    <!-- Modal Tambah Jadwal -->
    <div id="modalTambahJadwal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2><i class="fas fa-calendar-plus"></i> Tambah Jadwal Interview</h2>
                <button class="close-btn" id="closeModal">&times;</button>
            </div>
            <form id="formTambahJadwal">
                <div class="form-group">
                    <label for="namaPelamar">Nama Pelamar <span style="color: red;">*</span></label>
                    <input type="text" id="namaPelamar" name="namaPelamar" required placeholder="Masukkan nama pelamar">
                </div>

                <div class="form-group">
                    <label for="posisi">Posisi <span style="color: red;">*</span></label>
                    <select id="posisi" name="posisi" required>
                        <option value="">Pilih Posisi</option>
                        <option value="SUPERVISOR">SUPERVISOR</option>
                        <option value="OPERATOR MESIN">OPERATOR MESIN</option>
                        <option value="STAFF ADMIN">STAFF ADMIN</option>
                        <option value="STAFF AKUNTANSI">STAFF AKUNTANSI</option>
                        <option value="MARKETING">MARKETING</option>
                        <option value="HRD">HRD</option>
                    </select>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="tanggal">Tanggal Interview <span style="color: red;">*</span></label>
                        <input type="date" id="tanggal" name="tanggal" required>
                    </div>

                    <div class="form-group">
                        <label for="waktu">Waktu Interview <span style="color: red;">*</span></label>
                        <input type="time" id="waktu" name="waktu" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="link">Link Interview (Zoom/Google Meet)</label>
                    <input type="url" id="link" name="link" placeholder="https://zoom.us/j/xxxxx atau https://meet.google.com/xxx">
                </div>

                <div class="form-group">
                    <label for="statusInterview">Status Interview</label>
                    <select id="statusInterview" name="statusInterview">
                        <option value="DIJADWALKAN">DIJADWALKAN</option>
                        <option value="BELUM HADIR">BELUM HADIR</option>
                        <option value="SELESAI">SELESAI</option>
                        <option value="DIBATALKAN">DIBATALKAN</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="catatan">Catatan</label>
                    <textarea id="catatan" name="catatan" placeholder="Catatan tambahan untuk interview (opsional)"></textarea>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn-secondary" id="btnBatal">Batal</button>
                    <button type="submit" class="btn-success"><i class="fas fa-save"></i> Simpan Jadwal</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Modal functionality
        const modal = document.getElementById('modalTambahJadwal');
        const btnTambahJadwal = document.getElementById('btnTambahJadwal');
        const closeModal = document.getElementById('closeModal');
        const btnBatal = document.getElementById('btnBatal');
        const formTambahJadwal = document.getElementById('formTambahJadwal');
        const modalTitle = document.querySelector('.modal-header h2');
        
        let editMode = false;
        let editScheduleId = null;

        // Open modal for adding new schedule
        btnTambahJadwal.addEventListener('click', function() {
            editMode = false;
            editScheduleId = null;
            modalTitle.innerHTML = '<i class="fas fa-calendar-plus"></i> Tambah Jadwal Interview';
            formTambahJadwal.reset();
            document.getElementById('statusInterview').value = 'DIJADWALKAN';
            modal.classList.add('show');
        });

        // Close modal functions
        function closeModalFunc() {
            modal.classList.remove('show');
            formTambahJadwal.reset();
            editMode = false;
            editScheduleId = null;
        }

        closeModal.addEventListener('click', closeModalFunc);
        btnBatal.addEventListener('click', closeModalFunc);

        // Close modal when clicking outside
        window.addEventListener('click', function(event) {
            if (event.target === modal) {
                closeModalFunc();
            }
        });

        // Form submission
        formTambahJadwal.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = {
                namaPelamar: document.getElementById('namaPelamar').value,
                posisi: document.getElementById('posisi').value,
                tanggal: document.getElementById('tanggal').value,
                waktu: document.getElementById('waktu').value,
                link: document.getElementById('link').value,
                catatan: document.getElementById('catatan').value,
                status_interview: document.getElementById('statusInterview').value
            };

            // Add id_jadwal for edit mode
            if (editMode && editScheduleId) {
                formData.id_jadwal = editScheduleId;
            }

            // Disable submit button to prevent double submission
            const submitBtn = document.querySelector('.btn-success');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';

            // Determine API endpoint
            const apiEndpoint = editMode ? 'api/jadwal_interview_update.php' : 'api/jadwal_interview_save.php';
            const successMessage = editMode ? 'diupdate' : 'ditambahkan';

            // Send to API
            fetch(apiEndpoint, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(formData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('✅ Jadwal interview berhasil ' + successMessage + '!\n\nNama: ' + formData.namaPelamar + '\nPosisi: ' + formData.posisi);
                    closeModalFunc();
                    // Reload page to show new data
                    location.reload();
                } else {
                    alert('❌ Gagal ' + (editMode ? 'mengupdate' : 'menambahkan') + ' jadwal: ' + data.message);
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="fas fa-save"></i> Simpan Jadwal';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('❌ Terjadi kesalahan saat menyimpan data');
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-save"></i> Simpan Jadwal';
            });
        });

        // Edit button handler
        document.addEventListener('click', function(e) {
            if (e.target.closest('.btn-edit')) {
                const button = e.target.closest('.btn-edit');
                const row = button.closest('tr');
                const scheduleData = JSON.parse(row.getAttribute('data-schedule'));
                
                // Set edit mode
                editMode = true;
                editScheduleId = scheduleData.id_jadwal;
                modalTitle.innerHTML = '<i class="fas fa-edit"></i> Edit Jadwal Interview';
                
                // Populate form
                document.getElementById('namaPelamar').value = scheduleData.nama_pelamar;
                document.getElementById('posisi').value = scheduleData.posisi;
                document.getElementById('tanggal').value = scheduleData.tanggal_interview;
                document.getElementById('waktu').value = scheduleData.waktu_interview;
                document.getElementById('link').value = scheduleData.link_interview || '';
                document.getElementById('catatan').value = scheduleData.catatan || '';
                document.getElementById('statusInterview').value = scheduleData.status_interview || 'DIJADWALKAN';
                
                // Open modal
                modal.classList.add('show');
            }
        });

        // Delete button handler
        document.addEventListener('click', function(e) {
            if (e.target.closest('.btn-delete')) {
                const button = e.target.closest('.btn-delete');
                const scheduleId = button.getAttribute('data-id');
                const row = button.closest('tr');
                const scheduleData = JSON.parse(row.getAttribute('data-schedule'));
                
                if (confirm('Yakin ingin menghapus jadwal interview untuk "' + scheduleData.nama_pelamar + '"?\n\nPosisi: ' + scheduleData.posisi + '\nTanggal: ' + scheduleData.tanggal_interview)) {
                    // Disable button
                    button.disabled = true;
                    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                    
                    fetch('api/jadwal_interview_delete.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ id: scheduleId })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('✅ Jadwal berhasil dihapus!');
                            location.reload();
                        } else {
                            alert('❌ Gagal menghapus jadwal: ' + data.message);
                            button.disabled = false;
                            button.innerHTML = '<i class="fas fa-trash"></i>';
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('❌ Terjadi kesalahan');
                        button.disabled = false;
                        button.innerHTML = '<i class="fas fa-trash"></i>';
                    });
                }
            }
        });

        // Buka room interview functionality
        const notificationBtn = document.querySelector('.notification-btn');
        if (notificationBtn) {
            notificationBtn.addEventListener('click', function() {
                const interviewLink = this.getAttribute('data-link');
                
                // Cek apakah ada link interview yang valid
                if (interviewLink && interviewLink !== '-' && interviewLink !== 'end' && interviewLink.startsWith('http')) {
                    // Buka link di tab baru
                    window.open(interviewLink, '_blank');
                    
                    // Tampilkan konfirmasi
                    alert('✅ Membuka ruang interview...\n\nLink: ' + interviewLink);
                } else {
                    alert('⚠️ Link interview tidak tersedia untuk jadwal ini.');
                }
            });
        }

        // Join Interview button handler
        document.addEventListener('click', function(e) {
            if (e.target.closest('.btn-join')) {
                const button = e.target.closest('.btn-join');
                const interviewLink = button.getAttribute('data-link');
                
                // Check if link is valid
                if (interviewLink && interviewLink.startsWith('http')) {
                    // Open link in new tab
                    window.open(interviewLink, '_blank');
                    alert('✅ Membuka ruang interview...\n\nLink: ' + interviewLink);
                } else {
                    alert('⚠️ Link interview tidak tersedia untuk jadwal ini.');
                }
            }
        });

        // Tambahkan fungsi untuk membuka link interview dari tabel
        // Event delegation untuk handle dynamic table rows
        document.querySelector('tbody').addEventListener('click', function(e) {
            // Cek apakah yang diklik adalah cell yang berisi link
            const cell = e.target.closest('td');
            if (cell && cell.cellIndex === 5) { // Column index 5 adalah kolom LINK
                const link = cell.textContent.trim();
                
                // Cek apakah link valid
                if (link && link.startsWith('http')) {
                    if (confirm('Buka link interview ini?\n\n' + link)) {
                        window.open(link, '_blank');
                    }
                }
            }
        });
        
        // Button hover effects
        const buttons = document.querySelectorAll('.btn, .btn-primary, .btn-secondary, .btn-success');
        buttons.forEach(button => {
            button.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-2px)';
            });
            
            button.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });

        // Set minimum date untuk input tanggal (hari ini)
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('tanggal').setAttribute('min', today);
    </script>


</body>
</html>
        const closeModal = document.getElementById('closeModal');
        const btnBatal = document.getElementById('btnBatal');
        const formTambahJadwal = document.getElementById('formTambahJadwal');

        // Open modal
        btnTambahJadwal.addEventListener('click', function() {
            modal.classList.add('show');
        });

        // Close modal functions
        function closeModalFunc() {
            modal.classList.remove('show');
            formTambahJadwal.reset();
        }

        closeModal.addEventListener('click', closeModalFunc);
        btnBatal.addEventListener('click', closeModalFunc);

        // Close modal when clicking outside
        window.addEventListener('click', function(event) {
            if (event.target === modal) {
                closeModalFunc();
            }
        });

        // Form submission
        formTambahJadwal.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = {
                namaPelamar: document.getElementById('namaPelamar').value,
                posisi: document.getElementById('posisi').value,
                tanggal: document.getElementById('tanggal').value,
                waktu: document.getElementById('waktu').value,
                link: document.getElementById('link').value,
                catatan: document.getElementById('catatan').value
            };

            // Disable submit button to prevent double submission
            const submitBtn = document.querySelector('.btn-success');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';

            // Send to API
            fetch('api/jadwal_interview_save.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(formData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('✅ Jadwal interview berhasil ditambahkan!\n\nNama: ' + formData.namaPelamar + '\nPosisi: ' + formData.posisi);
                    closeModalFunc();
                    // Reload page to show new data
                    location.reload();
                } else {
                    alert('❌ Gagal menambahkan jadwal: ' + data.message);
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="fas fa-save"></i> Simpan Jadwal';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('❌ Terjadi kesalahan saat menyimpan data');
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-save"></i> Simpan Jadwal';
            });
        });

        // Buka room interview functionality
        document.querySelector('.notification-btn').addEventListener('click', function() {
            const interviewLink = 'https://zoom-contoh.com/meeting-12345'; // Link dari data
            
            // Cek apakah ada link interview yang valid
            if (interviewLink && interviewLink !== '-' && interviewLink !== 'end') {
                // Buka link di tab baru
                window.open(interviewLink, '_blank');
                
                // Tampilkan konfirmasi
                alert('✅ Membuka ruang interview untuk YANTO WIJAYA...\n\nLink: ' + interviewLink);
            } else {
                alert('⚠️ Link interview tidak tersedia untuk jadwal ini.');
            }
        });

        // Tambahkan fungsi untuk membuka link interview dari tabel
        // Event delegation untuk handle dynamic table rows
        document.querySelector('tbody').addEventListener('click', function(e) {
            // Cek apakah yang diklik adalah cell yang berisi link
            const cell = e.target.closest('td');
            if (cell && cell.cellIndex === 5) { // Column index 5 adalah kolom LINK
                const link = cell.textContent.trim();
                
                // Cek apakah link valid
                if (link && link.startsWith('http')) {
                    if (confirm('Buka link interview ini?\n\n' + link)) {
                        window.open(link, '_blank');
                    }
                }
            }
        });
        
        // Button hover effects
        const buttons = document.querySelectorAll('.btn, .btn-primary, .btn-secondary, .btn-success');
        buttons.forEach(button => {
            button.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-2px)';
            });
            
            button.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });

        // Set minimum date untuk input tanggal (hari ini)
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('tanggal').setAttribute('min', today);
    </script>
</body>
</html>
