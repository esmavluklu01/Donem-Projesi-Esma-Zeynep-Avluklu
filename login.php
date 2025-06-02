<?php
include('./config/config.php');  // Ensure the connection is included

$error_message = ''; // Hata mesajı değişkenini başlat

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    //  Kullanıcıyı kontrol etmek için sorguyu hazırla
    $query = "SELECT * FROM users WHERE email = :email";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();
    
    // Sonucu al
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Parolayı doğrula / kontrol et
        if (password_verify($password, $user['password'])) {
            // Başarılı girişte oturumu başlat
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            header("Location: index.php"); // Redirect after successful login
            exit;
        } else {
            $error_message = "Hatalı şifre. Lütfen tekrar deneyin.";
        }
    } else {
        $error_message = "Kullanıcı bulunamadı. Lütfen kayıt olun.";
    }
}
?>

<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../public/css/index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Giriş Yap</title>

    <style>
        /* Style for Labels */
        label {
            font-size: 16px;
            font-weight: bold;
            color: #333;
            margin-bottom: 8px;
            display: block;
            text-align: left;
            transition: color 0.3s ease;
        }

        /* Style for Input Fields */
        input {
            width: 100%;
            padding: 12px 15px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 16px;
            color: #333;
            background-color: #f9f9f9;
            transition: all 0.3s ease-in-out;
        }

        input:focus {
            border-color: #007BFF;
            background-color: #fff;
            outline: none;
        }

        input::placeholder {
            color: #aaa;
        }

        /* Style for Error Messages */
        .error-message {
            background-color: #ffcccc;
            color: #ff0000;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
            font-weight: bold;
        }

        /* Hover and Focus Effects for Labels */
        label:hover {
            color: #007BFF;
        }
    </style>
</head>

<body>

    <?php include('./includes/header.php'); ?>

    <div class="logReg">
        <img src="./public/img/background/login.jpg" alt="login background">
        <form method="POST">
            <h2 style="color:white">Giriş Yap</h2>

            <?php if (isset($error_message)) : ?>
                <div class="error-message">
                    <p><?php echo $error_message; ?></p>
                </div>
            <?php endif; ?>

            <label style="color:white" for="email">E-posta:</label>
            <input type="email" name="email" id="email" required placeholder="E-posta adresinizi girin"><br><br>

            <label style="color:white" for="password">Şifre:</label>
            <input type="password" name="password" id="password" required placeholder="Şifrenizi girin"><br><br>

            <button type="submit">Giriş Yap</button>
        </form>
    </div>

    <script src="../public/js/search.js"></script>
    <script src="../public/js/scroll.js"></script>
</body>

</html>