<?php
session_start();
header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Anda harus login terlebih dahulu'
    ]);
    exit;
}

include '../koneksi.php';

// Get JSON input
$input = json_decode(file_get_contents('php://input'), true);

// Validate input
if (!isset($input['current_password']) || !isset($input['new_password']) || !isset($input['confirm_password'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Data tidak lengkap'
    ]);
    exit;
}

$current_password = $input['current_password'];
$new_password = $input['new_password'];
$confirm_password = $input['confirm_password'];
$id_user = $_SESSION['id_user'];

// Get user data from database
$query = mysqli_query($koneksi, "SELECT password FROM user WHERE id_user = '$id_user'");
$user_data = mysqli_fetch_assoc($query);

if (!$user_data) {
    echo json_encode([
        'success' => false,
        'message' => 'User tidak ditemukan'
    ]);
    exit;
}

// Validate current password
if ($current_password != $user_data['password']) {
    echo json_encode([
        'success' => false,
        'message' => 'Password saat ini salah!'
    ]);
    exit;
}

// Validate new password confirmation
if ($new_password != $confirm_password) {
    echo json_encode([
        'success' => false,
        'message' => 'Password baru tidak cocok!'
    ]);
    exit;
}

// Validate password length
if (strlen($new_password) < 5) {
    echo json_encode([
        'success' => false,
        'message' => 'Password minimal 5 karakter!'
    ]);
    exit;
}

// Update password in database
$update_query = "UPDATE user SET password = '$new_password' WHERE id_user = '$id_user'";
$result = mysqli_query($koneksi, $update_query);

if ($result) {
    echo json_encode([
        'success' => true,
        'message' => 'Password berhasil diubah!'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Gagal mengubah password: ' . mysqli_error($koneksi)
    ]);
}
?>
