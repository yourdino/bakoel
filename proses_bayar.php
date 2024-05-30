<?php
include 'config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_user = $_SESSION['ID'];
    $keranjang = isset($_SESSION['keranjang']) ? $_SESSION['keranjang'] : [];
    $total_harga = $_POST['total_harga'];
    $metode_pembayaran = $_POST['metode_pembayaran'];
    $catatan = $_POST['catatan'];

    // Menggunakan status unik untuk setiap pesanan
    $status = 'pending_' . md5(uniqid(rand(), true));

    // Menyimpan setiap item keranjang sebagai baris terpisah dalam tabel beli
    foreach ($keranjang as $id_produk => $item) {
        $id_produk = $item['id_produk'];
        $jumlah_beli = $item['jumlah'];
        $harga = $item['harga_produk'];
        $query = "INSERT INTO beli (id_user, id_produk, jumlah_beli, total_harga, metode_pembayaran, catatan, status) 
                  VALUES ('$id_user', '$id_produk', '$jumlah_beli', '$harga', '$metode_pembayaran', '$catatan', '$status')";
        if (!mysqli_query($is_connect, $query)) {
            echo "Error: " . mysqli_error($is_connect);
            exit;
        }
    }

    // Mengosongkan keranjang setelah checkout
    unset($_SESSION['keranjang']);

    // Redirect ke halaman konfirmasi atau halaman lain
    header("Location: konfirmasi.php?status=" . $status);
    exit;
} else {
    header("Location: checkout.php");
    exit;
}
?>
