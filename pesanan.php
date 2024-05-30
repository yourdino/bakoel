<?php
include 'config.php';
session_start();

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['ID'])) {
    header("Location: login.php");
    exit();
}

// Buat variabel status yang mencakup status dengan kode unik
$status_pending = 'pending%'; // % adalah wildcard untuk mencocokkan apapun setelah 'pending_'
$status_selesai = 'selesai%'; // % adalah wildcard untuk mencocokkan apapun setelah 'selesai_'

// Query untuk mendapatkan pesanan baru (status pending)
$query_pesanan = "SELECT beli.*, user.username FROM beli JOIN user ON beli.id_user = user.ID WHERE beli.status LIKE '$status_pending'";
$result_pesanan = mysqli_query($is_connect, $query_pesanan);

// Query untuk mendapatkan riwayat pesanan selesai (status selesai)
$query_riwayat = "SELECT beli.*, user.username FROM beli JOIN user ON beli.id_user = user.ID WHERE beli.status LIKE '$status_selesai'";
$result_riwayat = mysqli_query($is_connect, $query_riwayat);

// Proses update status pesanan
if (isset($_POST['done'])) {
    $id_pesanan = $_POST['id_pesanan'];

    $status_selesai = 'selesai_' . md5(uniqid(rand(), true));

    // Check apakah sudah ada pesanan dengan status 'selesai'
    $query_check_selesai = "SELECT * FROM beli WHERE status LIKE '$status_selesai' LIMIT 1";
    $result_check_selesai = mysqli_query($is_connect, $query_check_selesai);

    if (mysqli_num_rows($result_check_selesai) == 0) {
        // Tidak ada pesanan 'selesai' dengan kode unik yang sama, maka lakukan UPDATE
        $update_query = "UPDATE beli SET status='$status_selesai' WHERE ID='$id_pesanan'";
        if (mysqli_query($is_connect, $update_query)) {
            header("Location: pesanan.php"); // Refresh halaman setelah update
            exit();
        } else {
            echo "Error: " . mysqli_error($is_connect);
        }
    } else {
        echo "Tidak dapat menyelesaikan pesanan. Pesanan lain sudah memiliki status 'selesai' dengan kode unik yang sama.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bakoel : Pesanan</title>
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
    <link rel="stylesheet" href="./pesanan.css">
</head>
<body>
    <div class="container">
        <div id="navbar">
            <img src="./img/logo.jpg" class="logo">
            <nav>
                <ul id="menuList">
                    <li><a href="home.php">Home</a></li>
                    <li><a href="belanja.php">Belanja</a></li>
                    <li><a href="pesanan.php">Riwayat</a></li>
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
                    <h1 class="judul">Pesanan Baru</h1>
                    <p>Segera terima pesananmu agar pembeli tidak menunggu lama</p>
                    <div class="table-content">
                        <table>
                            <tr>
                                <th> </th>
                                <th>Username</th>
                                <th>Jumlah</th>
                                <th>Total Harga</th>
                                <th> </th>
                            </tr>
                            <?php
                            if (mysqli_num_rows($result_pesanan) > 0) {
                                while ($row = mysqli_fetch_assoc($result_pesanan)) {
                                    echo "<tr>
                                            <td><img src='./img/user (3).png' alt=''></td>
                                            <td class='nama'>{$row['username']}</td>
                                            <td>{$row['jumlah_beli']}</td>
                                            <td>{$row['total_harga']}</td>
                                            <td>
                                                <button class='myBtn' data-id='{$row['ID']}'>
                                                    detail
                                                </button>
                                            </td>
                                        </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='5'>Tidak ada pesanan.</td></tr>";
                            }
                            ?>
                        </table>
                    </div>
                </div>
            </div>

            <div class="form-box2">
                <div class="form-content">
                    <h1 class="judul">Riwayat Pemesanan</h1>
                    <p>Riwayat pesanan yang sudah diselesaikan</p>
                    <div class="table-content">
                        <table>
                            <tr>
                                <th> </th>
                                <th>Username</th>
                                <th>Jumlah</th>
                                <th>Total Harga</th>
                                <th> </th>
                            </tr>
                            <?php
                            if (mysqli_num_rows($result_riwayat) > 0) {
                                while ($row_riwayat = mysqli_fetch_assoc($result_riwayat)) {
                                    echo "<tr>
                                            <td><img src='./img/user (3).png' alt=''></td>
                                            <td class='nama'>{$row_riwayat['username']}</td>
                                            <td>{$row_riwayat['jumlah_beli']}</td>
                                            <td>{$row_riwayat['total_harga']}</td>
                                            <td>
                                                <button class='myBtn2' data-id='{$row_riwayat['ID']}'>
                                                    detail
                                                </button>
                                            </td>
                                        </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='5'>Tidak ada riwayat pesanan.</td></tr>";
                            }
                            ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>`
        <div id="myModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <div class="modal-container">
                    <div id="modalDetail"></div>
                    <form method="post">
                        <input type="hidden" name="id_pesanan" id="id_pesanan">
                        <button type="submit" name="done" class="btn-done">Done</button>
                    </form>
                </div>
            </div>
        </div>

        <div id="myModal2" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <div class="modal-container">
                    <div id="modalDetail2"></div>
                    <p>Terima kasih sudah membeli di toko kami!</p>
                </div>
            </div>
        </div>

        <div id="footer">
            <p class="copyright">&copy; 2024 Bakoel. Hak Cipta Dilindungi Undang-Undang.</p>
        </div>
    </div>

    <script>
        var menuList = document.getElementById("menuList");

        menuList.style.maxHeight = "0px";

        function togglemenu() {
            if (menuList.style.maxHeight == "0px") {
                menuList.style.maxHeight = "130px";
            } else {
                menuList.style.maxHeight = "0px";
            }
        }

        // Modal
        var modal = document.getElementById("myModal");
        var close = document.getElementsByClassName("close")[0];

        document.querySelectorAll(".myBtn").forEach(button => {
            button.addEventListener("click", function() {
                var id = this.getAttribute("data-id");
                document.getElementById("id_pesanan").value = id;

                // AJAX request untuk mengambil detail pesanan
                var xhr = new XMLHttpRequest();
                xhr.open("GET", "get_pesanan_detail.php?id=" + id, true);
                xhr.onload = function() {
                    if (this.status == 200) {
                        document.getElementById("modalDetail").innerHTML = this.responseText;
                        modal.style.display = "block";
                    }
                }
                xhr.send();
            });
        });

        close.onclick = function() {
            modal.style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }

        // Modal 2
        var modal2 = document.getElementById("myModal2");
        var close2 = document.getElementsByClassName("close")[1];

        document.querySelectorAll(".myBtn2").forEach(button => {
            button.addEventListener("click", function() {
                var id = this.getAttribute("data-id");

                // AJAX request untuk mengambil detail pesanan selesai
                var xhr = new XMLHttpRequest();
                xhr.open("GET", "get_pesanan_detail.php?id=" + id, true);
                xhr.onload = function() {
                    if (this.status == 200) {
                        document.getElementById("modalDetail2").innerHTML = this.responseText;
                        modal2.style.display = "block";
                    }
                }
                xhr.send();
            });
        });

        close2.onclick = function() {
            modal2.style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target == modal2) {
                modal2.style.display = "none";
            }
        }
    </script>
</body>
</html>
