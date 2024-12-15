<?php
include('conn.php');
session_start();
if (!isset($_SESSION['ID'])) {
    header("Location: landing.php");
    exit;
}
$idPengguna = $_SESSION['ID'];
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add'])) {
        // Mengambil nilai yang dikirimkan melalui form
        $judul = $_POST['judul'];
        $deskripsi = $_POST['deskripsi'];
        $deadline_date = $_POST['tanggal'];
        $deadline_time = $_POST['waktu'];
        $deadline = $deadline_date . ' ' . $deadline_time;
        $category = $_POST['category'];
        $label = $_POST['label'];

        $created_at = date('Y-m-d H:i:s');
        $query = "INSERT INTO tugas (Judul, Deskripsi, Deadline,Status, ID_Kategori, ID_Label, ID_Pengguna, created_at)
            VALUES ('$judul', '$deskripsi', '$deadline',0, '$category', '$label', '$idPengguna', '$created_at')";
        $result = mysqli_query(connection(), $query);

        $query = "SELECT ID_Tugas FROM tugas WHERE created_at = '$created_at'";

        $result = mysqli_query(connection(), $query);
        $row = mysqli_fetch_assoc($result);

        // ID_Tugas yang sudah diformat
        $formatted_id = $row['ID_Tugas'];

        $api_url = "https://notify-api.hoaks.my.id/tugas/" . $formatted_id . "/callback";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $api_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Content-Type: application/json"
        ]);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            echo 'Error: ' . curl_error($ch);
        }

        curl_close($ch);
    }

    if (isset($_POST['update'])) {
        $tugas_id = $_POST['id_pengguna']; // ID tugas yang akan diupdate
        $judul = $_POST['judul'];
        $deskripsi = $_POST['deskripsi'];
        $deadline_date = $_POST['tanggal'];
        $deadline_time = $_POST['waktu'];
        $deadline = $deadline_date . ' ' . $deadline_time;
        $category = $_POST['category'];
        $label = $_POST['label'];

        // Mengupdate data pada tabel database berdasarkan ID tugas
        $query = "UPDATE tugas SET Judul='$judul', Deskripsi='$deskripsi', Deadline='$deadline', Status=0, ID_Kategori='$category', ID_Label='$label'
            WHERE ID_Tugas='$tugas_id'";
        $result = mysqli_query(connection(), $query);
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Describe</title>
    <link rel="stylesheet" href="Asset/desc.css" />
    <link href="https://fonts.googleapis.com/css?family=Gelasio" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Cousine" rel="stylesheet" />
</head>

<body>
    <div class="container">
        <div class="garis">
            <div class="garis1"></div>
            <div class="garis2"></div>
        </div>
        <div class="konten-container">
            <div class="sidebar">
                <ul>
                    <li>
                        <span class="datetime" id="datetime"></span>
                    </li>

                    <li>
                        <img src="Asset/home1.png" alt="" class="Icon" />
                        <a href="home.php">
                            <span class="Description">Home</span>
                        </a>
                    </li>
                    <li>
                        <img src="Asset/writing1.png" alt="" class="Icon" />
                        <a href="desc.php">
                            <span class="Description">Description</span>
                        </a>
                    </li>
                    <li>
                        <img src="Asset/notification1.png" alt="" class="Icon" />
                        <a href="rem.php">
                            <span class="Description">Reminder</span>
                        </a>
                    </li>
                    <li>
                        <img src="Asset/sand-watch1.png" alt="" class="Icon" />
                        <a href="history.php">
                            <span class="Description">History</span>
                        </a>
                    </li>
                    <li>
                        <div class="logout">
                            <a href="logout.php"> Log Out</a>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="konten">
                <div class="header">
                    <div class="judul">
                        <h1>Describe</h1>
                    </div>
                    <div class="dekor">
                        <div class="lingkaran"></div>
                        <div class="garis4"></div>
                    </div>
                    <button class="addplan" onclick="showPopup()">
                        <div class="addplan-text">Add Plan <b>+</b></div>
                    </button>
                </div>
                <?php
                //proses menampilkan data dari database:
                //siapkan query SQL
                $idPengguna = $_SESSION['ID'];
                $query = "SELECT * FROM tugas WHERE ID_Pengguna = '$idPengguna' and Status = 0 ";
                $result = mysqli_query(connection(), $query);
                ?>
                <div class="describe-container">
                    <?php while ($data = mysqli_fetch_array($result)): ?>
                        <?php $datetime = new DateTime($data['Deadline']);
                        $tanggal = $datetime->format('Y-m-d');
                        $jam = $datetime->format('H:i:s'); ?>
                        <div class="describe">
                            <div>
                                <h1>
                                    <?php echo $data['Judul']; ?>
                                </h1>
                                <div class="isi">
                                    <?php echo $data['Deskripsi']; ?>
                                </div>
                                <div class="date1">
                                    <?php echo $tanggal; ?>
                                </div>
                                <div class="date2">
                                    <?php echo $jam; ?>
                                </div>
                                <button class="adduser"
                                    onclick="window.location.href='delete.php?idtugas=<?php echo $data['ID_Tugas']; ?>'">
                                    <div class="adduser-text">
                                        Delete
                                    </div>
                                    <div>
                                        <button class="update"
                                            onclick="showPopup2('<?php echo $data['ID_Tugas']; ?>', '<?php echo $data['Judul']; ?>', '<?php echo $data['Deskripsi']; ?>', '<?php echo $tanggal; ?>', '<?php echo $jam; ?>', '<?php echo $data['ID_Kategori']; ?>', '<?php echo $data['ID_label']; ?>')">Update</button>
                                    </div>
                            </div>
                        <?php endwhile ?>
                    </div>

                </div>

            </div>
            <div class="garis">
                <div class="garis2"></div>
                <div class="garis1"></div>
            </div>
        </div>
        <div id="overlay" onclick="hidePopup()"></div>
        <div id="overlay" onclick="hidePopup1()"></div>
        <div id="overlay" onclick="hidePopup2()"></div>

        <div id="popup">
            <button class="close-button" onclick="hidePopup()">x</button>
            <form action="desc.php" class="form-addplan" method="POST">
                <input type="text" name="judul" placeholder="Judul" required>
                <textarea type="textarea" id="Deskripsi" name="deskripsi" placeholder="Deskripsi Tugas"
                    required></textarea>
                <div class="deadline-container">
                    Deadline :
                    <input type="date" name="tanggal" />
                    <input type="time" name="waktu" />
                </div>

                <div class="category">
                    <label for="category">Category :</label required>
                    <select id="category" name="category">
                        <option value=" "></option>
                        <option value="C003">Kuliah</option>
                        <option value="C002">Kantor</option>
                        <option value="C001">Rumah</option>
                    </select>
                </div>
                <div class="label">
                    <label for="label">Label :</label>
                    <select id="label" name="label" required>
                        <option value=" "></option>
                        <option value="3">Red</option>
                        <option value="2">Yellow</option>
                        <option value="1">Green</option>
                    </select>
                </div>

                <button type="submit" name="add">Selesai</button>
            </form>
        </div>

        <div id="update">
            <button class="close-button" onclick="hidePopup2()">x</button>

            <form action="desc.php" class="form-update" method="POST">
                <h3>Update</h3>
                <input type="hidden" name="id_pengguna" id="id" value="">
                <input type="text" name="judul" id="input_judul" placeholder="Judul" value="" required>
                <textarea type="textarea" id="input_deskripsi" name="deskripsi" placeholder="Deskripsi Tugas"
                    required></textarea>
                <div class="deadline-container">
                    Deadline :
                    <input type="date" name="tanggal" id="input_tanggal" />
                    <input type="time" name="waktu" id="input_jam" />
                </div>
                <div class="category">
                    <label for="category">Category :</label required>
                    <select id="input_category" name="category">
                        <option value=""></option>
                        <option value="C003">Kuliah</option>
                        <option value="C002">Kantor</option>
                        <option value="C001">Rumah</option>
                    </select>
                </div>
                <div class="label">
                    <label for="label">Label :</label>
                    <select id="input_label" name="label" required>
                        <option value=""></option>
                        <option value="3">Red</option>
                        <option value="2">Yellow</option>
                        <option value="1">Green</option>
                    </select>
                </div>
                <button type="submit" name="update">Simpan</button>
            </form>


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

            function showPopup2(idTugas, judul, deskripsi, tanggal, jam, category, label) {
                document.getElementById("overlay").style.display = "block";
                document.getElementById("update").style.display = "block";
                // Mengambil elemen input dengan nama 'id'
                var idInput = document.getElementById('id');
                var inputJudul = document.getElementById('input_judul');
                var inputDeskripsi = document.getElementById('input_deskripsi');
                var inputTanggal = document.getElementById('input_tanggal');
                var inputJam = document.getElementById('input_jam');
                var inputCategory = document.getElementById('input_category');
                var inputLabel = document.getElementById('input_label');

                // Mengisi nilai pada elemen input
                idInput.value = idTugas;
                inputJudul.value = judul;
                inputDeskripsi.value = deskripsi;
                inputTanggal.value = tanggal;
                inputJam.value = jam;
                inputCategory.value = category;
                inputLabel.value = label;
            }

            function hidePopup2() {
                document.getElementById("overlay").style.display = "none";
                document.getElementById("update").style.display = "none";
            }
            function updateDateTime() {
                var now = new Date();

                var months = [
                    'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli',
                    'Agustus', 'September', 'Oktober', 'November', 'Desember'
                ];

                var days = [
                    'Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'
                ];

                var month = months[now.getMonth()];
                var day = days[now.getDay()];
                var date = now.getDate();
                var hour = now.getHours();
                var minute = now.getMinutes();
                var second = now.getSeconds();

                // Tambahkan angka 0 di depan angka yang kurang dari 10
                hour = hour < 10 ? '0' + hour : hour;
                minute = minute < 10 ? '0' + minute : minute;
                second = second < 10 ? '0' + second : second;

                var dateTimeString = day + ', ' + date + ' ' + month + ' ' + now.getFullYear() + ' ' + hour + ':' + minute + ':' + second;

                document.getElementById('datetime').textContent = dateTimeString;
            }

            // Panggil fungsi updateDateTime setiap detik
            setInterval(updateDateTime, 1000);
        </script>

</html>
