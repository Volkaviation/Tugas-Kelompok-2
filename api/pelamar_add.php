<?php
header('Content-Type: application/json');
session_start();

if (!isset($_SESSION['email'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

include '../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    // Validate required fields
    if (empty($data['nama']) || empty($data['posisi']) || empty($data['pendidikan'])) {
        echo json_encode(['success' => false, 'message' => 'Nama, posisi, dan pendidikan wajib diisi!']);
        exit;
    }
    
    $nama = mysqli_real_escape_string($koneksi, $data['nama']);
    $pendidikan = mysqli_real_escape_string($koneksi, $data['pendidikan']);
    $posisi = mysqli_real_escape_string($koneksi, $data['posisi']);
    $pengalaman = mysqli_real_escape_string($koneksi, $data['pengalaman_kerja'] ?? '0 tahun');
    $cv = mysqli_real_escape_string($koneksi, $data['cv'] ?? 'Ada');
    $tanggal = mysqli_real_escape_string($koneksi, $data['tanggal_melamar'] ?? date('Y-m-d'));
    $status = mysqli_real_escape_string($koneksi, $data['status_akhir'] ?? 'Wawancara');
    
    $query = "INSERT INTO pelamar (nama, pendidikan, posisi, pengalaman_kerja, cv, tanggal_melamar, status_akhir) 
              VALUES ('$nama', '$pendidikan', '$posisi', '$pengalaman', '$cv', '$tanggal', '$status')";
    
    if (mysqli_query($koneksi, $query)) {
        $id = mysqli_insert_id($koneksi);
        echo json_encode([
            'success' => true, 
            'message' => 'Pelamar berhasil ditambahkan!',
            'id' => $id
        ]);
    } else {
        echo json_encode([
            'success' => false, 
            'message' => 'Gagal menambahkan pelamar: ' . mysqli_error($koneksi)
        ]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
