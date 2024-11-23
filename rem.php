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
                        <a href="History.php">
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
                <div>
                    <div class="header">
                        <div class="dekor">
                            <h1>Reminder</h1>
                            <div class="dekor">
                                <div class="lingkaran"></div>
                                <div class="garis4"></div>
                            </div>
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
                                <option value="">All Categories</option>
                                <option value="C001">Rumah</option>
                                <option value="C002">Kantor</option>
                                <option value="C003">Kuliah</option>
                            </select>
                        </form>
                    </div>
                </div>
                <div class="describe-container">
                    <table cellspacing="10">
                        <form action="updatestatus.php" method="POST">
                            <div class="grid-container">
                            </div>
                            <button type="submit" name="submit">Tandai Sudah Selesai</button>
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

    // Panggil fungsi updateDateTime setiap detik
    setInterval(updateDateTime, 1000);
</script>

</html>