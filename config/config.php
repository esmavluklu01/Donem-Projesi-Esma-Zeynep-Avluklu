<?php
$host = 'localhost';
$dbname = 'fizyonomi';
$username = 'root';
$password = '';

try {

    // VERİ TABANI BİLGİLERİ BURADA YER ALIYOR
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // SQL injection AÇIĞINI ENGELLEMEK İÇİN KULLANILIYOR
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

} catch (PDOException $e) {
    echo "Bağlantı hatası: " . $e->getMessage();  // HATA MESAJLARI EKRANA YAZILIR
    exit();
}
?>
