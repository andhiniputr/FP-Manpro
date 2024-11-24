<?php

include 'conn.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

if (isset($_SESSION['ID'])) {
    header("Location: home.php");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $email = $_POST['Email'];
    $password = $_POST['Password'];

    $query = "SELECT * FROM pengguna WHERE Email = '$email' AND Password = '$password'";
    $result = mysqli_query(connection(), $query);

    if ($result && mysqli_num_rows($result) > 0) {
        // Successful login
        $row = mysqli_fetch_assoc($result);
        $_SESSION['ID'] = $row['ID_Pengguna'];
        header("Location: home.php");
        exit;
    } else {
        // Incorrect username or password
        echo "<script>alert('Incorrect username or password.');</script>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Notify</title>
    <link href="Asset/landing.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Gabriela" rel="stylesheet" />
    <style>
        #login-page {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f1f1f1;
            opacity: 0;
            animation: slide-up 3s ease-in-out 1.5s forwards;
        }

        @keyframes slide-up {
            0% {
                transform: translateY(100%);
                opacity: 0;
            }

            100% {
                transform: translateY(0%);
                opacity: 1;
            }
        }
    </style>
</head>

<body>
    <div id="landing-page">
        <div class="lingkaran">
            <span class="circle top-right"></span>
            <span class="circle bottom-left"></span>
        </div>
        <div class="container">
            <div class="box">
                <div class="gambar-1"><img src="Asset/gambar1.png" alt="gambar-1" /></div>
                <div class="judul">
                    <h1 class="notification">Notify</h1>
                    <div class="line"></div>
                </div>
                <div class="gambar-2"><img src="Asset/gambar2.png" alt="gambar-2" /></div>
            </div>
        </div>
    </div>
    <div id="login-page">
        <div class="lingkaran">
            <span class="circle top-right"></span>
            <span class="circle bottom-left"></span>
        </div>
        <div class="container">
            <div class="box">
                <div class="gambar-1 login"><img src="Asset/gambar1.png" alt="gambar-1" /></div>
                <div class="judul">
                    <h1 class="notification">Notify</h1>
                    <div class="line"></div>
                    <div class="login-container">
                        <h2>Sign In</h2>
                        <form action="" method="POST">
                            <input type="text" id="Email" name="Email" required placeholder="Email" />
                            <input type="Password" id="Password" name="Password" required placeholder="Password" />
                            <button type="submit">Sign In</button>
                            <a class="login-link" href="signUp.php">Create Account</a>
                            <div class="remember-me">
                                <input type="checkbox" id="remember" name="remember" />
                                <label for="remember">Remember Me</label>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="gambar-2 login"><img src="Asset/gambar2.png" alt="gambar-2" /></div>
            </div>
        </div>
    </div>
</body>
<script>
    setTimeout(function () {
        document.getElementById("landing-page").style.display = "none";
        document.getElementById("login-page").style.display = "flex";
    }, 2000);
</script>

</html>