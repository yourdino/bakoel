<?php
include 'config.php';

session_start();

$id_user = $_SESSION['ID'];

$sql_id_toko = "SELECT ID FROM toko WHERE id_user = '$id_user'";
$result_id_toko = $is_connect->query($sql_id_toko);

if ($result_id_toko->num_rows > 0) {
    $row = $result_id_toko->fetch_assoc();
    $id_toko = $row['ID'];

    $sql_products = "SELECT * FROM produk WHERE id_toko = '$id_toko'";
    $result_products = $is_connect->query($sql_products);

    $products = array();

    if ($result_products->num_rows > 0) {
        while ($row = $result_products->fetch_assoc()) {
            $products[] = $row;
        }
    }
    echo json_encode($products);
} else {
    echo json_encode([]);
}
?>
