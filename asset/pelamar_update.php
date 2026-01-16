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
    
    if (empty($data['id'])) {
        echo json_encode(['success' => false, 'message' => 'ID pelamar tidak valid']);
        exit;
    }
    
    $id = (int)$data['id'];
    $nama = mysqli_real_escape_string($koneksi, $data['nama']);
    $pendidikan = mysqli_real_escape_string($koneksi, $data['pendidikan']);
    $posisi = mysqli_real_escape_string($koneksi, $data['posisi']);
    $pengalaman = mysqli_real_escape_string($koneksi, $data['pengalaman_kerja']);
    $status = mysqli_real_escape_string($koneksi, $data['status_akhir']);
    
    $query = "UPDATE pelamar SET 
              nama = '$nama',
              pendidikan = '$pendidikan',
              posisi = '$posisi',
              pengalaman_kerja = '$pengalaman',
              status_akhir = '$status'
              WHERE id = $id";
    
    if (mysqli_query($koneksi, $query)) {
        echo json_encode(['success' => true, 'message' => 'Data pelamar berhasil diupdate!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Gagal update data: ' . mysqli_error($koneksi)]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
