<?php
// Include the database connection (PDO)
include('./config/config.php');  // Ensure the connection is included

$error_message = ''; // Initialize error message variable
$success_message = ''; // Initialize success message variable

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capture form data
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // E-posta veya kullanıcı adının veritabanında zaten olup olmadığını kontrol et

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email OR username = :username");
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $error_message = "E-posta veya kullanıcı adı zaten mevcut.";
    } else {
        //  Şifreyi veritabanına eklemeden önce hash’le
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        //  Yeni kullanıcıyı veritabanına ekle
        $stmt = $pdo->prepare("INSERT INTO users (email, username, password) VALUES (:email, :username, :password)");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->bindParam(':password', $hashed_password, PDO::PARAM_STR);
        
        if ($stmt->execute()) {
            $success_message = "Kayıt başarılı! Giriş yapmak için <a href='login.php'>buraya</a> tıklayın.";
        } else {
            $error_message = "Kayıt sırasında bir hata oluştu. Lütfen tekrar deneyin.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../public/css/index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
          integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
          crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Kayıt Ol</title>
</head>

<body>

<?php include('./includes/header.php'); ?>

<div class="logReg">
    <img src="../public/img/background/register.jpg" alt="register">
    <form method="POST">
        <div class="formGroup">
            <input type="email" name="email" required>
            <span>Email *</span>
        </div>

        <div class="formGroup">
            <input type="text" name="username" required>
            <span>Kullanıcı Adı *</span>
        </div>

        <div class="formGroup">
            <input type="password" name="password" required>
            <span>Şifre *</span>
        </div>

        <?php if ($error_message) : ?>
            <div class="error-message">
                <p><?php echo $error_message; ?></p>
            </div>
        <?php elseif ($success_message) : ?>
            <div class="success-message">
                <p><?php echo $success_message; ?></p>
            </div>
        <?php endif; ?>

        <div class="btnGroup">
            <a href="./login.php">Zaten bir hesabınız var mı?</a>
            <button type="submit">Kayıt Ol</button>
        </div>
    </form>
</div>

<script src="../public/js/search.js"></script>
<script src="../public/js/scroll.js"></script>

</body>

</html>
