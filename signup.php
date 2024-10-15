<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>To do list</title>
</head>
<body>
<form name="form" action="signup.php" method="post">
    <label>username</label>
    <label>
        <input name="username" type="text" required>
    </label>

    <label>password</label>
    <label>
        <input name="password" type="password" required>
    </label>
    <label>
        <input type="submit" value="Sign Up">
    </label>
    <a class="links" href="index.php">LOGİN</a>
</form>

<?php
session_start();

// Veritabanı bağlantısını ekliyoruz
include('db_config.php');
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $errors = [];  // Hataları tutacak dizi

    // Şifreyi doğrula
    if (strlen($password) < 8) {
        $errors[] = "Şifre en az 8 karakter olmalıdır.";
    }

    if (!preg_match('/[A-Z]/', $password)) {
        $errors[] = "Şifre en az bir büyük harf içermelidir.";
    }

    if (!preg_match('/[a-z]/', $password)) {
        $errors[] = "Şifre en az bir küçük harf içermelidir.";
    }

    if (!preg_match('/[0-9]/', $password)) {
        $errors[] = "Şifre en az bir rakam içermelidir.";
    }

    if (!preg_match('/[\W_]/', $password)) {
        $errors[] = "Şifre en az bir özel karakter içermelidir.";
    }

    if (count($errors) > 0) {
        // Hataları yazdır
        echo "Şifre şartlara uymuyor:<br>";
        foreach ($errors as $error) {
            echo "- " . $error . "<br>";
        }
        exit();  // Hatalı şifre ile işlemi durduruyoruz
    }

    echo "Şifre başarılı!<br>";  // Şifre geçerli

    // Kullanıcı adı daha önce kaydedilmiş mi kontrol et
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Aynı kullanıcı adı var
        echo "Bu kullanıcı adı zaten kullanılıyor, lütfen başka bir kullanıcı adı seçin.";
    } else {
        // Şifreyi hashle
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Yeni kullanıcıyı kaydet
        $id = uniqid(); // Kullanıcıya unique ID atıyoruz
        $stmt = $conn->prepare("INSERT INTO users (username, password, id) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $hashed_password, $id);
        if ($stmt->execute()) {
            // Kullanıcı kaydedildiyse, session'a ekle ve yönlendir
            $_SESSION['userdata'] = [
                'username' => $username,
                'id' => $id
            ];
            header("Location: index.php"); // Başarıyla kaydedildiyse yönlendir
            exit();
        } else {
            echo "Kullanıcı kaydı sırasında bir hata oluştu.";
        }
    }

    $stmt->close(); // Bağlantıyı kapatıyoruz
}

// Veritabanından tüm kullanıcıları çekme
$sql = "SELECT * FROM users";
$result = $conn->query($sql);

// Kullanıcıları listele
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "Username: " . $row['username'] . "<br>";
        echo "Password: " . $row['password'] . "<br>"; // Parolayı gizli tutmalısınız
        echo "ID: " . $row['id'] . "<br><br>";
        echo " ***************************************************** <br>";
    }
} else {
    echo "Kayıtlı kullanıcı bulunamadı.";
}

$conn->close(); // Bağlantıyı kapatıyoruz
?>
</body>
</html>
