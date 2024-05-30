<?php
session_start();
include 'config.php';

if (!isset($_SESSION['ID'])) {
    header('Location: login.php');
    exit();
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

$id_user = $_SESSION['ID'];

$sql_id_toko = "SELECT ID FROM toko WHERE id_user = '$id_user'";
$result_id_toko = $is_connect->query($sql_id_toko);

if ($result_id_toko->num_rows > 0) {
    $row = $result_id_toko->fetch_assoc();
    $id_toko = $row['ID'];

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['produk'])) {
        $nama_produk = $_POST['nama_produk'];
        $harga_produk = $_POST['harga_produk'];
        $stok = $_POST['stok'];
        $foto = $_FILES['foto']['name'];
        $target_dir = "img/";
        $target_file = $target_dir . basename($foto);

        $allowed_types = array('jpg', 'jpeg', 'png', 'gif');
        $file_extension = pathinfo($foto, PATHINFO_EXTENSION);

        if (!in_array($file_extension, $allowed_types)) {
            echo "Only image files (JPG, JPEG, PNG, GIF) are allowed.";
            exit();
        }

        if (move_uploaded_file($_FILES['foto']['tmp_name'], $target_file)) {
            $sql = "INSERT INTO produk (nama_produk, harga_produk, stok, gambar, id_toko) VALUES ('$nama_produk', '$harga_produk', '$stok', '$foto', '$id_toko')";
            if ($is_connect->query($sql) === TRUE) {
                echo "Produk berhasil ditambahkan!";
            } else {
                echo "Error: " . $sql . "<br>" . $is_connect->error;
            }
        } else {
            echo "Error uploading file: " . $_FILES['foto']['error'];
        }
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['updateBtn'])) {
        $id_produk = $_POST['modal-id'];
        $nama_produk = $_POST['modal-nama'];
        $harga_produk = $_POST['modal-harga_produk'];
        $stok = $_POST['modal-stok'];
        $foto = $_FILES['modal-foto']['name'];
        $target_dir = "img/";
        $target_file = $target_dir . basename($foto);

        $allowed_types = array('jpg', 'jpeg', 'png', 'gif');
        $file_extension = pathinfo($foto, PATHINFO_EXTENSION);

        if ($foto && !in_array($file_extension, $allowed_types)) {
            echo "Only image files (JPG, JPEG, PNG, GIF) are allowed.";
            exit();
        }

        if ($foto && move_uploaded_file($_FILES['modal-foto']['tmp_name'], $target_file)) {
            $sql_update = "UPDATE produk SET nama_produk='$nama_produk', harga_produk='$harga_produk', stok='$stok', gambar='$foto' WHERE ID='$id_produk'";
        } else {
            $sql_update = "UPDATE produk SET nama_produk='$nama_produk', harga_produk='$harga_produk', stok='$stok' WHERE ID='$id_produk'";
        }

        if ($is_connect->query($sql_update) === TRUE) {
            echo "success";
        } else {
            echo "Error updating record: " . $is_connect->error;
        }
        exit();
    }

    $sql_products = "SELECT * FROM produk WHERE id_toko = '$id_toko'";
    $result_products = $is_connect->query($sql_products);
    $products = array();

    if ($result_products->num_rows > 0) {
        while ($row = $result_products->fetch_assoc()) {
            $products[] = $row;
        }
    } else {
        echo "No products found.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bakoel : Menu</title>
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
    <link rel="stylesheet" href="./menu.css">
</head>
<body>
    <div class="container">
        <div id="navbar">
            <img src="./img/logo.jpg" class="logo">
            <nav>
                <ul id="menuList">
                    <li class="nav-li"><a href="./home.php">Home</a></li>
                    <li class="nav-li"><a href="./belanja.php">Belanja</a></li>
                    <li class="nav-li"><a href="./pesanan.php">Riwayat</a></li>
                    <li class="nav-li"><a href="./profile.php">Profile</a></li>
                </ul>
            </nav>
            <a href="./detail.php" class="icon-link">
                <i class='bx bx-cart-alt'></i>
            </a>
            <img src="./img/menu.png" class="menu-icon" onclick="togglemenu()">
        </div>

        <div class="halaman">
            <ul class="accordion">
                <li>
                    <input type="radio" name="accordion" id="first" checked>
                    <label class="label-1" for="first">Tambah Menu</label>
                    <div class="content">
                        <form action="menu.php" method="POST" enctype="multipart/form-data">
                            <label class="label-2" for="nama-produk">Nama Produk</label>
                            <input type="text" id="nama" name="nama_produk" placeholder="Nama Produk.." required>
                          
                            <label for="harga_produk-produk">Harga Produk</label>
                            <input type="text" id="harga_produk" name="harga_produk" placeholder="Harga Produk.." required>
                          
                            <label for="jumlah-produk">Jumlah</label>
                            <input type="text" id="jumlah" name="stok" placeholder="Jumlah.." required>

                            <label for="foto-produk">Upload Foto Produk</label>
                            <input type="file" id="foto" name="foto" required>
                          
                            <input type="submit" name="produk" value="Tambahkan">
                        </form>
                    </div>
                </li>
                <li>
                    <input type="radio" name="accordion" id="second">
                    <label class="label-1" for="second">List Menu</label>
                    <div class="content">
                        <div class="table-content">
                            <table>
                                <tr>
                                    <th>Foto</th>
                                    <th>Nama Produk</th>
                                    <th>Stok</th>
                                    <th>Harga Produk</th>
                                    <th>Aksi</th>
                                </tr>
                                <?php foreach ($products as $product): ?>
                                <tr>
                                    <td><img src="img/<?php echo $product['gambar']; ?>"></td>
                                    <td class="nama"><?php echo $product['nama_produk']; ?></td>
                                    <td><?php echo $product['stok']; ?></td>
                                    <td><?php echo $product['harga_produk']; ?></td>
                                    <td>
                                        <button class="myBtnEdit" data-id="<?php echo $product['ID']; ?>" data-nama="<?php echo $product['nama_produk']; ?>" data-harga_produk="<?php echo $product['harga_produk']; ?>" data-stok="<?php echo $product['stok']; ?>" data-gambar="<?php echo $product['gambar']; ?>">
                                            Edit
                                        </button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </table>
                        </div>
                        <div id="myModal" class="modal">
                            <div class="modal-content">
                                <span class="close">&times;</span>
                                <div class="modal-container">
                                    <form id="editForm" enctype="multipart/form-data">
                                        <div class="row">
                                            <div class="col-1">
                                                <img id="modal-foto" src="" alt="">
                                                <input type="file" id="modal-file" name="foto">
                                            </div>
                                            <div class="col-2">
                                                <h2>Nama Produk</h2>
                                                <input type="text" id="modal-nama" name="modal-nama" placeholder="Nama Produk">
    
                                                <h2>Harga Produk</h2>
                                                <input type="text" id="modal-harga_produk" name="modal-harga_produk" placeholder="Harga Produk">
    
                                                <h2>Stok</h2>
                                                <input type="text" id="modal-stok" name="modal-stok" placeholder="Stok">
    
                                                <input type="hidden" id="modal-id" name="modal-id">
                                                <button type="button" id="updateBtn">Simpan</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        </div>

        <div id="footer">
            <p class="copyright">&copy; 2024 Bakoel. Hak Cipta Dilindungi Undang-Undang.</p>
        </div>
    </div>

    <script>
        function togglemenu(){
            var menuList = document.getElementById("menuList");
            menuList.style.maxHeight = menuList.style.maxHeight == "0px" ? "130px" : "0px";
        }

        var modal = document.getElementById("myModal");

        var buttonsEdit = document.querySelectorAll(".myBtnEdit");

        buttonsEdit.forEach(function(btn) {
            btn.onclick = function() {
                var id = btn.getAttribute('data-id');
                var nama = btn.getAttribute('data-nama');
                var harga_produk = btn.getAttribute('data-harga_produk');
                var stok = btn.getAttribute('data-stok');
                var foto = btn.getAttribute('data-gambar');

                document.getElementById('modal-id').value = id;
                document.getElementById('modal-nama').value = nama;
                document.getElementById('modal-harga_produk').value = harga_produk;
                document.getElementById('modal-stok').value = stok;
                document.getElementById('modal-foto').src = 'img/' + foto;

                modal.style.display = "block";
            };
        });

        var span = document.getElementsByClassName("close")[0];

        span.onclick = function() {
            modal.style.display = "none";
        };

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        };

        document.getElementById("updateBtn").addEventListener("click", function() {
            var id_produk = document.getElementById('modal-id').value;
            var form = document.getElementById("editForm");
            var formData = new FormData(form);
            formData.append('updateBtn', true);
            formData.append('modal-id', id_produk);
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "menu.php", true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    if (xhr.responseText === 'success') {
                        updateProductTable();
                        modal.style.display = "none";
                    } else {
                        alert('Yey terupdate.');
                    }
                }
            };
            xhr.send(formData);
        });

        function updateProductTable() {
            var table = document.querySelector('.table-content table');
            var tbody = table.querySelector('tbody');
            tbody.innerHTML = '';
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'get_products.php', true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    var products = JSON.parse(xhr.responseText);
                    products.forEach(function(product) {
                        var row = document.createElement('tr');
                        row.innerHTML = `
                            <td><img src="img/${product.gambar}"></td>
                            <td class="nama">${product.nama_produk}</td>
                            <td>${product.stok}</td>
                            <td>${product.harga_produk}</td>
                            <td>
                                <button class="myBtnEdit" data-id="${product.ID}" data-nama="${product.nama_produk}" data-harga_produk="${product.harga_produk}" data-stok="${product.stok}" data-gambar="${product.gambar}">
                                    Edit
                                </button>
                            </td>
                        `;
                        tbody.appendChild(row);
                    });
                }
            };
            xhr.send();
        };
    </script>
</body>
</html>
