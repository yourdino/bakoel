<?php
include 'config.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "SELECT * FROM beli WHERE ID='$id'";
    $result = mysqli_query($is_connect, $query);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        echo "<p>ID Pesanan: {$row['ID']}</p>";
        echo "<p>Jumlah: {$row['jumlah_beli']}</p>";
        echo "<p>Total Harga: {$row['total_harga']}</p>";
        echo "<p>Deskripsi: {$row['catatan']}</p>"; // Menampilkan deskripsi pesanan
        // Tambahkan detail lain yang diperlukan
    } else {
        echo "<p>Detail pesanan tidak ditemukan.</p>";
    }
} else {
    echo "<p>ID pesanan tidak diberikan.</p>";
}
?>
