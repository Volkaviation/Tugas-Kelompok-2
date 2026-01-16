<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

include 'koneksi.php';

// Ambil nama user dari session
$nama_user = $_SESSION['nama_lengkap'] ?? 'User';

// Query untuk mengambil semua data pelamar
$query_pelamar = mysqli_query($koneksi, "SELECT * FROM pelamar ORDER BY tanggal_melamar DESC");
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
             cursor: pointer;
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

        /* Main Content Styles */
        .main-content {
            margin-left: var(--sidebar-width);
            width: calc(100% - var(--sidebar-width));
            padding: 25px;
            background: #f8f9fa;
        }

        .header h1{font-size:28px}
        .header p{color:#555;margin-top:4px}
        .hr{height:1px;background:#e5e5e5;margin:20px 0}

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
        
        td {
            padding: 15px;
            border-bottom: 1px solid #e0e0e0;
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

        .top{
            display:flex;
            justify-content:space-between;
            align-items:center;
            margin-bottom:10px;
        }
        .search-box{position:relative}
        .search-box i{
            position:absolute;
            left:14px;
            top:50%;
            transform:translateY(-50%);
            color:#666;
        }
        .search{
            width:260px;
            padding:12px 18px 12px 40px;
            border-radius:30px;
            border:1px solid #ccc;
        }
        /* Action Buttons */
        .actions button{
            border:none;
            cursor:pointer;
            margin-left:6px;
            display:inline-flex;
            align-items:center;
            gap:8px;
        }
        .add{
            background:#1e88ff;
            color:#fff;
            padding:12px 20px;
            border-radius:30px;
        }
        .icon{
            background:#eaf2ff;
            padding:10px 14px;
            border-radius:10px;
        }

        /* DATA TITLE */
        .data-title{font-size:24px;margin-top:10px}
        .sort{font-size:13px;color:#555;margin-bottom:10px}
        .sort span{color:#1e88ff}

        /* TABLE */
        .table-box{
            border:1px solid #999;
            border-radius:10px;
            overflow:hidden;
        }
        table{
            width:100%;
            border-collapse:collapse;
        }
        thead{
            background:#3db3ff;
            color:#fff;
        }
        th,td{
            padding:14px 12px;
            border-bottom:1px solid #333;
            font-size:14px;
            vertical-align:top;
        }
        
        .show{
            text-align:center;
            padding:12px;
        }
        .backup{
            color:#1e88ff;
            margin-top:10px;
            display:inline-block;
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
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(5px);
        }

        .modal.show {
            display: flex;
            align-items: center;
            justify-content: center;
            animation: fadeIn 0.3s ease;
        }

        .modal-content {
            background: white;
            padding: 30px;
            border-radius: 16px;
            width: 90%;
            max-width: 600px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            animation: slideDown 0.3s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideDown {
            from { transform: translateY(-50px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
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
        .form-group select {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.3s, box-shadow 0.3s;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(0, 122, 255, 0.1);
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

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            font-size: 14px;
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background: #5a6268;
            transform: translateY(-2px);
        }

        .btn-primary {
            background: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background: #0056b3;
            transform: translateY(-2px);
        }

        /* Action Buttons in Table */
        .btn-edit-small, .btn-delete-small {
            border: none;
            padding: 6px 12px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 13px;
            transition: all 0.2s;
            margin: 0 2px;
            color: white;
        }

        .btn-edit-small {
            background: #4A90E2;
        }

        .btn-edit-small:hover {
            background: #357ABD;
            transform: translateY(-1px);
        }

        .btn-delete-small {
            background: #FF6B6B;
        }

        .btn-delete-small:hover {
            background: #E55555;
            transform: translateY(-1px);
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
                <a href="jadwal_interview.php" class="nav-item">
                    <span class="nav-icon"><i class="fas fa-clock"></i></span>
                    <span>Jadwal Interview</span>
                </a>
                <a href="datapelamar.php" class="nav-item active">
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
                    <h1>Welcome, <?php echo htmlspecialchars($nama_user); ?></h1>
                    <p>Showroom Mobil masa kini !!</p>
                </div>
            </div>

<div class="hr"></div>

<div class="top">
<div class="search-box">
<i class="fa-solid fa-magnifying-glass"></i>
<input class="search" id="searchInput" placeholder="Search here...">
</div>

<div class="actions">
<button class="add" id="btnTambahPelamar"><i class="fa-solid fa-plus"></i>Tambah Pelamar</button>
<button class="icon"><i class="fa-solid fa-print"></i></button>
<button class="icon"><i class="fa-solid fa-share"></i></button>
</div>
</div>

<div class="data-title">Data</div>
<div class="sort">Sort by <span>Recently <i class="fa-solid fa-chevron-down"></i></span></div>

<div class="table-box">
<table>
<thead>
<tr>
<th>Id Pelamar</th>
<th>Nama</th>
<th>Pendidikan</th>
<th>Posisi</th>
<th>Pengalaman</th>
<th>CV</th>
<th>Status</th>
<th>Tanggal Melamar</th>
</tr>
</thead>

<tbody>
<?php
$no = 1;
while($row = mysqli_fetch_assoc($query_pelamar)) {
    ?>
    <?php
    $data_json = htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8');
    ?>
    <tr data-id="<?php echo $row['id']; ?>" data-pelamar="<?php echo $data_json; ?>">
        <td><?php echo $row['id']; ?></td>
        <td><?php echo htmlspecialchars($row['nama']); ?></td>
        <td><?php echo htmlspecialchars($row['pendidikan']); ?></td>
        <td><?php echo htmlspecialchars($row['posisi']); ?></td>
        <td><?php echo htmlspecialchars($row['pengalaman_kerja']); ?></td>
        <td><?php echo $row['cv'] == 'Ada' ? '<i class="fa-solid fa-check"></i> Ada' : '-'; ?></td>
        <td><?php echo htmlspecialchars($row['status_akhir']); ?></td>
        <td><?php echo date('d-m-Y', strtotime($row['tanggal_melamar'])); ?></td>
        <td>
            <button class="btn-edit-small" data-id="<?php echo $row['id']; ?>" title="Edit"><i class="fas fa-edit"></i></button>
            <button class="btn-delete-small" data-id="<?php echo $row['id']; ?>" title="Hapus"><i class="fas fa-trash"></i></button>
        </td>
    </tr>
    <?php
    $no++;
}
?>
</tbody>
</table>

<div class="show">Show All Data <i class="fa-solid fa-chevron-down"></i></div>
</div>

<a class="backup">Backup data pelamar</a>

<!-- Modal Tambah/Edit Pelamar -->
<div id="modalPelamar" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="modalTitle"><i class="fas fa-user-plus"></i> Tambah Pelamar Baru</h2>
            <button class="close-btn" id="closeModal">&times;</button>
        </div>
        <form id="formPelamar">
            <div class="form-group">
                <label for="nama">Nama Lengkap <span style="color: red;">*</span></label>
                <input type="text" id="nama" name="nama" required placeholder="Masukkan nama lengkap">
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="pendidikan">Pendidikan <span style="color: red;">*</span></label>
                    <select id="pendidikan" name="pendidikan" required>
                        <option value="">Pilih Pendidikan</option>
                        <option value="SMK">SMK</option>
                        <option value="D3">D3</option>
                        <option value="S1">S1</option>
                        <option value="S2">S2</option>
                    </select>
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
                        <option value="PROGRAMMER">PROGRAMMER</option>
                        <option value="DESIGNER">DESIGNER</option>
                        <option value="DATA ANALYST">DATA ANALYST</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="pengalaman_kerja">Pengalaman Kerja <span style="color: red;">*</span></label>
                    <select id="pengalaman_kerja" name="pengalaman_kerja" required>
                        <option value="">Pilih Pengalaman</option>
                        <option value="0 tahun">0 tahun (Fresh Graduate)</option>
                        <option value="5 bulan">5 bulan</option>
                        <option value="7 bulan">7 bulan</option>
                        <option value="8 bulan">8 bulan</option>
                        <option value="1 tahun">1 tahun</option>
                        <option value="2 tahun">2 tahun</option>
                        <option value="3 tahun+">3 tahun+</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="status_akhir">Status <span style="color: red;">*</span></label>
                    <select id="status_akhir" name="status_akhir" required>
                        <option value="">Pilih Status</option>
                        <option value="Wawancara">Wawancara</option>
                        <option value="Tes Tulis">Tes Tulis</option>
                        <option value="Diterima">Diterima</option>
                        <option value="Tidak Lolos">Tidak Lolos</option>
                    </select>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="btnBatal">Batal</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
            </div>
        </form>
    </div>
</div>

</main>
</div>
<script>
document.getElementById('searchInput').addEventListener('keyup', function () {
    const keyword = this.value.toLowerCase();
    const rows = document.querySelectorAll('tbody tr');

    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(keyword) ? '' : 'none';
    });
});

// Modal functionality
const modal = document.getElementById('modalPelamar');
const btnTambahPelamar = document.getElementById('btnTambahPelamar');
const closeModal = document.getElementById('closeModal');
const btnBatal = document.getElementById('btnBatal');
const formPelamar = document.getElementById('formPelamar');
const modalTitle = document.getElementById('modalTitle');

let editMode = false;
let editPelamarId = null;

// Open modal for adding
btnTambahPelamar.addEventListener('click', function() {
    editMode = false;
    editPelamarId = null;
    modalTitle.innerHTML = '<i class="fas fa-user-plus"></i> Tambah Pelamar Baru';
    formPelamar.reset();
    modal.classList.add('show');
});

// Close modal function
function closeModalFunc() {
    modal.classList.remove('show');
    formPelamar.reset();
    editMode = false;
    editPelamarId = null;
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
formPelamar.addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = {
        nama: document.getElementById('nama').value,
        pendidikan: document.getElementById('pendidikan').value,
        posisi: document.getElementById('posisi').value,
        pengalaman_kerja: document.getElementById('pengalaman_kerja').value,
        status_akhir: document.getElementById('status_akhir').value,
        cv: 'Ada',
        tanggal_melamar: new Date().toISOString().split('T')[0]
    };

    // Add ID for edit mode
    if (editMode && editPelamarId) {
        formData.id = editPelamarId;
    }

    // Disable submit button
    const submitBtn = formPelamar.querySelector('.btn-primary');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';

    // Determine endpoint
    const apiEndpoint = editMode ? 'api/pelamar_update.php' : 'api/pelamar_add.php';
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
            alert('✅ Pelamar berhasil ' + successMessage + '!\n\nNama: ' + formData.nama + '\nPosisi: ' + formData.posisi);
            closeModalFunc();
            location.reload();
        } else {
            alert('❌ Gagal ' + (editMode ? 'mengupdate' : 'menambahkan') + ' pelamar: ' + data.message);
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-save"></i> Simpan';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('❌ Terjadi kesalahan saat menyimpan data');
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="fas fa-save"></i> Simpan';
    });
});

// Edit button handler
document.addEventListener('click', function(e) {
    if (e.target.closest('.btn-edit-small')) {
        const button = e.target.closest('.btn-edit-small');
        const row = button.closest('tr');
        const pelamarData = JSON.parse(row.getAttribute('data-pelamar'));
        
        // Set edit mode
        editMode = true;
        editPelamarId = pelamarData.id;
        modalTitle.innerHTML = '<i class="fas fa-user-edit"></i> Edit Data Pelamar';
        
        // Populate form
        document.getElementById('nama').value = pelamarData.nama;
        document.getElementById('pendidikan').value = pelamarData.pendidikan;
        document.getElementById('posisi').value = pelamarData.posisi;
        document.getElementById('pengalaman_kerja').value = pelamarData.pengalaman_kerja;
        document.getElementById('status_akhir').value = pelamarData.status_akhir;
        
        // Open modal
        modal.classList.add('show');
    }
});

// Delete button handler
document.addEventListener('click', function(e) {
    if (e.target.closest('.btn-delete-small')) {
        const button = e.target.closest('.btn-delete-small');
        const row = button.closest('tr');
        const pelamarData = JSON.parse(row.getAttribute('data-pelamar'));
        
        if (confirm('Yakin ingin menghapus data pelamar?\n\nNama: ' + pelamarData.nama + '\nPosisi: ' + pelamarData.posisi)) {
            // Disable button
            button.disabled = true;
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            
            fetch('api/pelamar_delete.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id: pelamarData.id })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('✅ Data pelamar berhasil dihapus!');
                    location.reload();
                } else {
                    alert('❌ Gagal menghapus data: ' + data.message);
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
</script>

</body>
</html>