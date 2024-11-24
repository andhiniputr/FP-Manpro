<?php
include('conn.php');
session_start();

if (!isset($_SESSION['ID'])) {
    header("Location: landing.php");
    exit;
}
$idPengguna = $_SESSION['ID'];

if (isset($_POST['uGambar'])) {
    // Mendapatkan informasi file gambar
    $file = $_FILES['gambar'];
    $nama = $file['name'];
    $tipe = $file['type'];
    $ukuran = $file['size'];
    $tmp = $file['tmp_name'];

    // Tentukan direktori penyimpanan
    $lokasi_penyimpanan = 'Asset/'; // Ganti dengan lokasi penyimpanan yang sesuai

    // Pindahkan file sementara ke lokasi penyimpanan
    $tujuan = $lokasi_penyimpanan . $nama;
    move_uploaded_file($tmp, $tujuan);

    // Periksa apakah ada gambar sebelumnya untuk ID pengguna yang sedang login
    $query = "SELECT namaGambar FROM gambar WHERE ID_Pengguna = '$idPengguna'";
    $result = mysqli_query(connection(), $query);

    if (mysqli_num_rows($result) > 0) {
        // Gambar sebelumnya sudah ada, lakukan update dan hapus gambar sebelumnya
        $row = mysqli_fetch_assoc($result);
        $gambar_sebelumnya = $row['namaGambar'];

        $query = "UPDATE gambar SET namaGambar = '$nama' WHERE ID_Pengguna = '$idPengguna'";
        mysqli_query(connection(), $query);

        // Hapus gambar sebelumnya
        unlink($lokasi_penyimpanan . $gambar_sebelumnya);
    } else {
        // Gambar sebelumnya belum ada, lakukan insert
        $query = "INSERT INTO gambar (namaGambar, ID_Pengguna) VALUES ('$nama','$idPengguna')";
        mysqli_query(connection(), $query);
    }
}

// Memeriksa apakah form telah dikirimkan
if (isset($_POST['update-profile'])) {
    // Mengambil nilai dari inputan form
    $new_username = $_POST['new-username'];
    $new_password = $_POST['new-password'];
    $new_email = $_POST['new-email'];

    // Query untuk melakukan update data pengguna berdasarkan ID
    $query = "UPDATE pengguna SET Username='$new_username', Email='$new_email', Password='$new_password' WHERE ID_Pengguna='$idPengguna'";
    mysqli_query(connection(), $query);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Home</title>
    <link rel="stylesheet" href="Asset/home.css" />
    <link href="https://fonts.googleapis.com/css?family=Gelasio" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Gabriela" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Newsreader" rel="stylesheet" />
</head>

<body>
    <div class="container">
        <div class="garis">
            <div class="garis1"></div>
            <div class="garis2"></div>
            <div class="header">
                <div class="dekor">
                    <div class="garis3"></div>
                    <div class="lingkaran"></div>
                </div>
                <h1>Notify</h1>
                <div class="dekor">
                    <div class="lingkaran"></div>
                    <div class="garis4"></div>
                </div>
                <button class="profile" onclick="showPopup()">
                    <span class="profile-text">Profile</span>
                    <?php
                    $nama_gambar = '';

                    $query = "SELECT namaGambar FROM gambar WHERE ID_Pengguna = '$idPengguna'"; // Ganti dengan query yang sesuai untuk mengambil nama gambar 
                    $result = mysqli_query(connection(), $query);

                    if (mysqli_num_rows($result) > 0) {
                        // Foto profil telah diunggah, gunakan foto tersebut
                        $row = mysqli_fetch_assoc($result);
                        $nama_gambar = $row['namaGambar'];
                    } else {
                        // Foto profil tidak ada, gunakan foto default
                        $nama_gambar = 'defaultprofile.jpg'; // Ganti dengan nama file foto abu-abu default yang sesuai
                    }

                    // Tampilkan foto profil menggunakan tag <img>
                    echo "<img src='Asset/" . $nama_gambar . "' alt='Foto Profil'" . "class='profile-image'/>";
                    ?>
                </button>
            </div>
        </div>


        <div class="reminder-history-describe">
            <div class="describe">
                <div>
                    <h1><span>D</span>escribe</h1>
                    <p>Make your task more manageable using this feature. Easier your life with us.</p>
                </div>
                <img src="Asset/gambar3.png" alt="Gambar 3">
            </div>
            <div class="reminder-history-container">
                <a href="rem.php" class="reminder">
                    <div>
                        <h1><span>R</span>eminder</h1>
                        <p>Make sure you don't miss anything about your task. Set your reminder through this feature.
                        </p>
                    </div>
                </a>

                <a href="history.php" class="history">
                    <div>
                        <h1><span>H</span>istory</h1>
                        <p>Want to know how hard you work on assignments. Find it here.</p>
                    </div>
                </a>
            </div>
        </div>

        <div class="garis">
            <div class="garis2"></div>
            <div class="garis1"></div>
        </div>
    </div>

    <div id="overlay" onclick="hidePopup()"></div>

    <div id="popup">
        <button class="close-button" onclick="hidePopup()">x</button>
        <div class="profile-form">
            <h3>Profile</h3>
            <div class="profile-header">
                <?php echo "<img src='Asset/" . $nama_gambar . "' alt='Foto Profil'/>"; ?>
                <span class="username">
                    <?php
                    $query = "SELECT * FROM pengguna WHERE ID_Pengguna = '$idPengguna'";
                    $result = mysqli_query(connection(), $query);
                    $data = mysqli_fetch_array($result);
                    echo $data['Username'];
                    ?>
                </span>
            </div>
            <form action="home.php" method="POST" enctype="multipart/form-data">
                <input type="file" name="gambar" />
                <button type="submit" name="uGambar" class="Ugambar">Simpan Gambar</button>
            </form>
            <div class="garis5"></div>
        </div>
        <form class="form-profile" action="home.php" method="POST">
            <input type="text" id="new-username" name="new-username" placeholder="new-username" />
            <input type="password" id="new-password" name="new-password" placeholder="new-password" />
            <input type="email" id="new-email" name="new-email" placeholder="new-email" />
            <button type="submit" name="update-profile">Save</button>
        </form>
        <div class="form-profile">
            <a href="logout.php" class="btn">Logout</a>
        </div>
    </div>
    <script>
        function showPopup() {
            document.getElementById("overlay").style.display = "block";
            document.getElementById("popup").style.display = "block";
        }

        function hidePopup() {
            document.getElementById("overlay").style.display = "none";
            document.getElementById("popup").style.display = "none";
        }
    </script>
</body>

</html>