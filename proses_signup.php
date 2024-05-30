<?php
session_start(); //memulai session
include ('config.php'); //mengarahkan ke file connect agar terkoneksi dengan database

if (isset($_SESSION['ID'])) {
    header('Location: login.php');
    exit;
}

$email = $_POST['email'];
$username = $_POST['username'];
$password = $_POST['password'];

//$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$sql = "INSERT INTO user (username, email, password) VALUES ('$username', '$email', '$password')";

if (mysqli_query($is_connect, $sql)) {
    header('Location: home.php');
} else {
    echo "Registrasi gagal! Error: " . mysqli_error($is_connect);
}

mysqli_close($is_connect);