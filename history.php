<?php
include('conn.php');
session_start();
if (!isset($_SESSION['ID'])) {
    header("Location: landing.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>History</title>
    <link rel="stylesheet" href="Asset/history.css">
    <link href="https://fonts.googleapis.com/css?family=Gelasio" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Gabriela" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Newsreader" rel="stylesheet" />
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
                        <img src="Asset/home1.png" alt="" class=" Icon" />
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
                        <a href="#">
                            <span class="Description">History</span>
                        </a>
                    </li>
                    <li>
                        <div class="logout">
                            <a href="logout.php">Log Out</a>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="konten">
                <div class="header">
                    <div class="judul">
                        <h1>History</h1>
                    </div>
                    <div class="dekor">
                        <div class="lingkaran"></div>
                        <div class="garis4"></div>
                    </div>
                </div>
                <div class="describe-container">
                    <table>
                        <thead>
                            <th>Judul</th>
                            <th>Deskrpsi</th>
                            <th>Deadline</th>
                            <th>Finished</th>
                        </thead>
                        <?php
                        //proses menampilkan data dari database:
                        //siapkan query SQL
                        $idPengguna = $_SESSION['ID'];
                        $query = "SELECT *from history where ID_Pengguna ='$idPengguna'";
                        $result = mysqli_query(connection(), $query);
                        ?>
                        <tbody>
                            <?php while ($data = mysqli_fetch_array($result)): ?>
                                <tr>
                                    <td>
                                        <?php echo $data['ID_History']; ?>
                                    </td>
                                    <td>
                                        <?php echo $data['Judul']; ?>
                                    </td>
                                    <td>
                                        <?php echo $data['Tanggal']; ?>
                                    </td>
                                    <td>
                                        <?php echo $data['Catatan']; ?>
                                    </td>
                                </tr>
                            <?php endwhile ?>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
        <div class="garis">
            <div class="garis2"></div>
            <div class="garis1"></div>
        </div>
    </div>
    <div class="popup">
        <div class="popup-content">
            <span class="close-btn">&times;</span>
            <h2>Detail</h2>
            <p id="popup-description"></p>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var popup = document.querySelector('.popup');
            var popupDescription = document.getElementById('popup-description');

            var tableCells = document.querySelectorAll('td');

            tableCells.forEach(function (cell) {
                cell.addEventListener('click', function () {
                    var description = this.textContent;
                    popupDescription.textContent = description;
                    popup.style.display = 'block';
                });
            });

            var closeBtn = document.querySelector('.close-btn');
            closeBtn.addEventListener('click', function () {
                popup.style.display = 'none';
            });
        });
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
</body>

</html>