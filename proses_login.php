<?php
session_start(); //memulai session
include ('config.php'); //mengarahkan ke file connect agar terkoneksi dengan database

$email = $_POST['email'];
$password = $_POST['password'];

$query = mysqli_query($is_connect, "SELECT * from user WHERE email = '".$email."' AND password ='".$password."'"); //menjalankan query untuk mengecek usn dan pswd
$data = mysqli_fetch_assoc($query); //mysqli_fetch_assoc untuk menyimpan query yang dijalankan

if (NULL != $data){ // pengecekan data apakah ada atau tidak, implementasi dari query
    $_SESSION['ID'] = $data['ID']; //pembuatan session
    $_SESSION['email'] = $email; //pembuatan session
    $_SESSION['username'] = $data['username']; //pembuatan session
    header('Location: home.php'); //proses meredirect ke halaman lain
} else {
    echo 'Login gagal! Email atau password salah.'; //muncul pesan gagal login
}