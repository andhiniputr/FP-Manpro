<?php
include('conn.php');
session_start();
if (!isset($_SESSION['ID'])) {
    header("Location: Landing.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Reminder</title>
    <link rel="stylesheet" href="Asset/rem.css" />
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
                        <img src="Asset/home1.png" alt="" class="Icon" />
                        <a href="index.php">
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
                            <a href="logout.php">Log Out</a>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="konten">
                <div class="header">
                    <div class="judul">
                        <h1>Reminder</h1>
                    </div>
                    <div class="dekor">
                        <div class="lingkaran"></div>
                        <div class="garis4"></div>
                    </div>
                </div>
                <div class="dropdown">
                    <form id="sortForm" method="POST" action="">
                        <select id="sort" name="sort" class="sort"
                            onchange="document.getElementById('sortForm').submit()">
                            <option value=" "><b>Sort</b></option>
                            <option value="date">By Date</option>
                            <option value="label">By Label</option>
                        </select>
                        <select id="categoryFilter" name="categoryFilter" class="sort"
                            onchange="document.getElementById('sortForm').submit()">
                            <option value="C004">All Categories</option>
                            <option value="C001">Rumah</option>
                            <option value="C002">Kantor</option>
                            <option value="C003">Kuliah</option>
                        </select>
                    </form>
                </div>
                <div class="action-container">
                    <button type="submit" name="submit" form="taskForm" class="btn-complete">Tandai Sudah
                        Selesai</button>
                </div>
                <div class="describe-container">
                    <?php
                    // Proses menampilkan data dari database:
                    // Siapkan query SQL
                    $idPengguna = $_SESSION['ID'];
                    $orderBy = isset($_POST['sort']) ? $_POST['sort'] : ''; // Mengambil nilai sort dari form
                    $categoryFilter = isset($_POST['categoryFilter']) ? $_POST['categoryFilter'] : ''; // Mengambil nilai filter kategori dari form
                    
                    $query = "SELECT tugas.*, kategori.Nama_Kategori
                                FROM tugas
                                INNER JOIN kategori ON tugas.ID_Kategori = kategori.ID_Kategori
                                WHERE tugas.ID_Pengguna = '$idPengguna' and Status = 0 ";

                    if ($categoryFilter != '') {
                        $query .= " AND kategori.ID_Kategori = '$categoryFilter'";
                    }

                    if ($orderBy == 'date') {
                        $query .= " ORDER BY tugas.Deadline";
                    } elseif ($orderBy == 'label') {
                        $query .= " ORDER BY tugas.ID_label";
                    } else {
                        $query .= " ORDER BY tugas.ID_Tugas"; // Jika tidak ada sort yang dipilih, tampilkan tanpa pengurutan
                    }

                    $result = mysqli_query(connection(), $query);
                    ?>
                    <table cellspacing="10">
                        <form id="taskForm" action="updatestatus.php" method="POST">
                            <div class="grid-container">
                                <?php while ($data = mysqli_fetch_array($result)): ?>
                                    <?php
                                    $datetime = new DateTime($data['Deadline']);
                                    $tanggal = $datetime->format('Y-m-d');
                                    $jam = $datetime->format('H:i:s');
                                    $currentDateTime = new DateTime();
                                    $isLate = ($datetime < $currentDateTime);
                                    $gridItemClass = ($isLate) ? "grid-item-Late" : "grid-item";
                                    ?>
                                    <div class="<?php echo $gridItemClass; ?>">
                                        <h1>
                                            <?php echo $data['Judul']; ?>
                                            <span class="submit">
                                                <input type="checkbox" name="taskIds[]"
                                                    value="<?php echo $data['ID_Tugas']; ?>" />
                                            </span>
                                            <span class="fa fa-star">
                                                <img src="Asset/<?php echo "StarLabel" . $data['ID_label'] . ".png"; ?>"
                                                    alt="">
                                            </span>
                                        </h1>
                                        <div class="category">
                                            <?php echo "Kategori: " . $data['Nama_Kategori']; ?>
                                        </div>
                                        <div class="date1">
                                            <?php echo $tanggal; ?>
                                        </div>
                                        <div class="date2">
                                            <?php echo $jam; ?>
                                        </div>
                                    </div>
                                <?php endwhile ?>
                            </div>
                        </form>
                    </table>
                </div>
            </div>
        </div>
        <div class="garis">
            <div class="garis2"></div>
            <div class="garis1"></div>
        </div>
    </div>
</body>
<script>
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
</script>

</html>
