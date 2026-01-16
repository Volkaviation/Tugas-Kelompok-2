<?php
session_start();
include 'koneksi.php';

$email    = $_POST['email'];
$password = $_POST['password'];

$query = mysqli_query($koneksi,
    "SELECT * FROM user WHERE email='$email' AND password='$password'"
);

$data = mysqli_fetch_assoc($query);

if (mysqli_num_rows($query) > 0) {
    $_SESSION['id_user'] = $data['id_user'];
    $_SESSION['email'] = $data['email'];
    $_SESSION['nama_lengkap'] = $data['nama_lengkap'];

    header("Location: dashboard.php");
} else {
    echo "Login gagal! <a href='login.php'>Coba lagi</a>";
}
?>
