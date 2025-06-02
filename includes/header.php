<?php

ob_start();


session_start();

include('./config/config.php'); // Veritabanı bağlantısı için

// Eğer kullanıcı giriş yaptıysa, oturum bilgilerini al
$user_logged_in = isset($_SESSION['user_id']); 

// Eğer kullanıcı giriş yaptıysa, kullanıcı bilgilerini al
if ($user_logged_in) {
    // Kullanıcı bilgilerini veritabanından çek
    $user_id = $_SESSION['user_id'];
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
    $stmt->bindParam(':id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Header with Dropdown</title>
    
    <style>
        /* Inline CSS to maintain original design */
        body {
            font-family: Arial, sans-serif;
        }

        .topbar {
            background-color: #333;
            padding: 10px;
            color: white;
        }

        .wrapper {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .left, .center, .right {
            display: flex;
            align-items: center;
        }

        .left i, .right i {
            margin-right: 10px;
            font-size: 20px;
        }

        .right {
            position: relative;
        }

        .searchEngine input {
            padding: 5px;
            border-radius: 5px;
            border: none;
            font-size: 14px;
            margin-left: 10px;
        }

        /* Dropdown Styling */
        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-toggle {
            cursor: pointer;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            overflow: hidden;
        }

        /* Choose dropdown direction (Right or Left) */
        .dropdown-menu {
            display: none;
            position: absolute;
            left: 0;
            top: 100%;
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 10px;
            width: 180px;
            text-align: left;
            z-index: 10;
        }

        .dropdown-menu a {
            display: block;
            padding: 8px 10px;
            text-decoration: none;
            color: #333;
            font-size: 14px;
        }

        .dropdown-menu a:hover {
            background-color: #f1f1f1;
        }

        .dropdown:hover .dropdown-menu {
            display: block;
        }

        .center a {
            color: white;
            text-decoration: none;
            margin: 0 10px;
            font-weight: bold;
        }

        .center a:hover {
            color: #ccc;
        }

        .error-message {
            color: red;
        }
    </style>
</head>
<body>

<!-- Dropdown menülü üst çubuk -->
<div class="topbar mb-5">
    <div class="wrapper target">
        
        <div class="left">
    
    <a href="https://instagram.com/fizyonomistemre" target="_blank" rel="noopener noreferrer">
        <i class="fa-brands fa-square-instagram"></i>
    </a>
    
    <a href="https://www.youtube.com/@UzmFizyonomistAhmetBurak" target="_blank" rel="noopener noreferrer">
    <i class="fa-brands fa-youtube"></i>
    </a>
    
    <a href="https://pinterest.com/fizyonomi" target="_blank" rel="noopener noreferrer">
        <i class="fa-brands fa-square-pinterest"></i>
    </a>
</div>
        <div class="center">
            <!-- EĞER KULLANICI GİRİŞ YAPTIYSA-->
            <?php if ($user_logged_in): ?>
                <a href="./index.php">ANA SAYFA</a>
                <a href="./about.php">HAKKINDA</a>
                <a href="./categories.php">KATEGORİLER</a>
            <?php else: ?>
            <!-- EĞER KULLANICI GİRİŞ YAPMADIYSA-->
                <a href="./index.php">ANA SAYFA</a>
                 <a href="./about.php">HAKKINDA</a>
                <a href="./login.php">LOGIN</a>
                <a href="./register.php">REGISTER</a>
            <?php endif; ?>
        </div>
        <div class="right">
            <?php if ($user_logged_in): ?>
                <div class="dropdown">
                    <!-- User profile image, click to open dropdown -->
                    <img src="public/img/user/kız.jpg" alt="user" class="dropdown-toggle">
                    
                    <!-- Dropdown menu -->
                    <div class="dropdown-menu">
                        <?php if ($user['role'] == 'admin'): ?>
                            <a href="../admin">ADMIN PANEL</a>
                        <?php endif; ?>
                        <a href="index.php?logout=true">Çıkış</a>
                    </div>
                </div>
            <?php else: ?>
                <span>
                    <img src="public/img/user/kız.jpg" alt="user">
                </span>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
// Logout functionality (redirect after logout)
if (isset($_GET['logout'])) {
    session_start(); // session başlatıyor
    session_unset(); // sessiondaki verileri unset ediyor
    session_destroy(); // sessioon siliyor
    header("Location: index.php"); //index e gönderiyor
    exit(); //fonksiyondan çıkıyor
}
?>

</body>
</html>
