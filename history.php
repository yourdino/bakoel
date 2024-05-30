<?php
include 'config.php';
session_start();

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['ID'])) {
    // Jika belum, redirect ke halaman login.php
    header("Location: login.php");
    exit;
}

// Mengambil data pembelian dari database
$sql_beli = "SELECT * FROM beli WHERE id_user='{$_SESSION['ID']}'";
$result_beli = $is_connect->query($sql_beli);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bakoel : Riwayat Beli</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&family=Poppins:wght@100&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/font-awesome-line-awesome/css/all.min.css">
    <link rel="stylesheet" href="./history.css">
</head>
<body>

    <div class="container">
        <div id="navbar">
            <img src="./img/logo.jpg" class="logo">
            <nav>
                <ul id="menuList">
                    <li><a href="home.php">Home</a></li>
                    <li><a href="belanja.php">Belanja</a></li>
                    <li><a href="history.php">Riwayat</a></li>
                    <li><a href="profile.php">Profile</a></li>
                </ul>
            </nav>
            <a href="./detail.html" class="icon-link">
                <i class='bx bx-cart-alt'></i>
            </a>
            <img src="./img/menu.png" class="menu-icon" onclick="togglemenu()">
        </div>

        <div id="form">
            <div class="form-box">
                <div class="form-content">
                    <form action="">
                        <h1 class="judul">Riwayat Beli</h1>
                        <p>Riwayat pembelianmu akhir akhir ini</p>
                        <div class="table-content">
                            <table>
                                <tr>
                                    <th> </th>
                                    <th>Tanggal pembelian</th>
                                    <th>Jumlah</th>
                                    <th>Total Harga</th>
                                    <th> </th>
                                </tr>
                                <?php
                                if ($result_beli->num_rows > 0) {
                                    while ($row = $result_beli->fetch_assoc()) {
                                        echo "<tr>";
                                        // Pastikan kolom 'gambar' ada dalam hasil kueri
                                        if(isset($row['gambar'])) {
                                            echo "<td><img src='./img/{$row['gambar']}' alt=''></td>";
                                        } else {
                                            echo "<td><img src='./img/default.jpg' alt=''></td>"; // Jika 'gambar' tidak ada, tampilkan gambar default atau sesuaikan dengan kebutuhan Anda
                                        }
                                        echo "<td class='nama'>" . date('Y-m-d H:i:s', strtotime($row['waktu_beli'])) . "</td>";
                                        echo "<td>{$row['jumlah_beli']}</td>";
                                        echo "<td>{$row['total_harga']}</td>";
                                        echo "<td><button type='button' class='myBtn'>Detail</button></td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='5'>Tidak ada riwayat pembelian</td></tr>";
                                }
                                ?>
                            </table>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div id="myModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <div class="modal-container">
                    <table>
                        <tr>
                            <th>Id Produk</th>
                            <th>Metode Pembayaran</th>
                            <th>Jumlah Beli</th>
                            <th>Waktu Pembelian</th>
                            <th>Catatan</th>
                        </tr>
                        <!-- Konten modal akan diisi dengan data detail pembelian saat detail diklik -->
                    </table>
                </div>
            </div>
        </div>

        <div id="footer">
            <p class="copyright">&copy; 2024 Bakoel. Hak Cipta Dilindungi Undang-Undang.</p>
        </div>
    </div>

    <script>
        // Get the modal
        var modal = document.getElementById("myModal");

        // Get the buttons that open the modal
        var buttons = document.querySelectorAll(".myBtn");

        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName("close")[0];

        // When the user clicks on any button, open the modal
        buttons.forEach(function(btn, index) {
            btn.onclick = function() {
                // Mendapatkan data detail pembelian untuk indeks baris yang sesuai dengan tombol yang diklik
                var tableRows = document.querySelectorAll("table tr");
                var rowData = tableRows[index + 1].querySelectorAll("td");
                var idProduk = rowData[0].innerText;
                var metodePembayaran = rowData[1].innerText;
                var jumlahBeli = rowData[2].innerText;
                var waktuPembelian = rowData[3].innerText;
                var catatan = rowData[4].innerText;

                // Mengisi konten modal dengan data detail pembelian
                var modalContent = document.querySelector(".modal-container table");
                modalContent.innerHTML = `
                    <tr>
                        <td>${idProduk}</td>
                        <td>${metodePembayaran}</td>
                        <td>${jumlahBeli}</td>
                        <td>${waktuPembelian}</td>
                        <td>${catatan}</td>
                    </tr>
                `;
                modal.style.display = "block";
            };
        });

        // When the user clicks on <span> (x), close the modal
        span.onclick = function() {
            modal.style.display = "none";
        };

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        };
</script>

</body>
</html>