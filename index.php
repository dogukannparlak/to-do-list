<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>To do list</title>
</head>
<body>
<form name="form" action="index.php" method="post">
        <label>username</label>
        <label>
            <input name="username1" type="text" >
        </label>


        <label>password</label>
        <label>
            <input name="password1" type="text" >
        </label>
    <label>
        <input type="submit">
    </label>
    <a class="links" href="signup.php">Signup</a>
</form>
</body>
</html>
<?php
session_start();
include('db_config.php');

if(isset($_POST['username1']) && isset($_POST['password1'])) {

$username1 = $_POST['username1'];
$password1 = $_POST['password1'];

// Kullanıcı adı daha önce kaydedilmiş mi kontrol et
$stmt =$conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
$stmt->bind_param("ss", $username1, $password1);
$stmt->execute();
$result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Kullanıcı adı ve şifre doğruysa, home.php sayfasına yönlendir
        header("Location: home.php");
        exit();
    } else {
        // Kullanıcı adı veya şifre yanlışsa, hata mesajı döndür
        $error_message = "Kullanıcı adı ya da şifre hatalı!";
        echo "<p style='color: red;'>$error_message</p>";
    }
}
