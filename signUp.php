<?php

include 'conn.php';

error_reporting(0);

session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = $_POST['username'];
  $email = $_POST['email'];
  $password = ($_POST['password']);
  if (strlen($_POST['password']) < 8) {
    echo "<script>alert('Password must be at least 8 characters long.');</script>";
    echo "<script>setTimeout(function() { window.location.href = 'signup.php'; }, 1000);</script>";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "<script>alert('Invalid email format.');</script>";
    echo "<script>setTimeout(function() { window.location.href = 'signup.php'; }, 1000);</script>";
  } else
    $query = "INSERT INTO pengguna (Username, Email, Password) VALUES ('$username','$email','$password')";
  //eksekusi query
  $result = mysqli_query(connection(), $query);

  if ($result) {
    echo "<script>alert('User created successfully.');</script>";
    echo "<script>setTimeout(function() { window.location.href = 'Landing.php'; }, 1000);</script>";
  } else {
    header("Location: signUp.php");
    echo "<script>setTimeout(function() { window.location.href = 'signup.php'; }, 1000);</script>";
  }
}





?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Sign Up Notify</title>
  <link href="Asset/landing.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css?family=Gabriela" rel="stylesheet" />
</head>

<body>
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
          <h2>Sign Up</h2>
          <form action="" method="Post">
            <input type="text" id="email" name="email" required placeholder="email" />
            <input type="text" id="username" name="username" required placeholder="username" />
            <input type="password" id="password" name="password" required placeholder="password" />
            <button type="submit">Create Account</button>
          </form>
        </div>
      </div>
      <div class="gambar-2 login"><img src="Asset/gambar2.png" alt="gambar-2" /></div>
    </div>
  </div>
</body>

</html>