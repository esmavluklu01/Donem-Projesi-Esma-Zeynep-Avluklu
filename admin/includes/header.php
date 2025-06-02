<?php
/// Çıkış zaten başlamışsa başlık sorunlarını önlemek için çıkış arabelleğine başlama
ob_start();
// Oturum başlamadan ve başlık gönderilmeden önce başka bir çıktı olmadığından emin olun
include('../config/config.php'); // Veritabanı bağlantısı için

// Check if user is logged in and get user info
$user_logged_in = isset($_SESSION['user_id']); 
if ($user_logged_in) {
    // Kullanıcı bilgilerini veritabanından çek
    $user_id = $_SESSION['user_id'];
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
    $stmt->bindParam(':id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // "Yönetici olmayan kullanıcıları ana sayfaya yönlendir
    if ($user['role'] !== 'admin') {
        header("Location: index.php"); // Redirect non-admin users to the homepage
        exit();
    }
} else {
    // "Kimliği doğrulanmamış kullanıcıları giriş sayfasına yönlendir
    header("Location: ../index.php");
    exit();
}

// // Yönetici panelinde CRUD işlemleri (örneğin: kategorileri görüntüle ve yenilerini oluştur)
// if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['category_name'])) {
//     $category_name = $_POST['category_name'];

//     // Insert new category into the database
//     $stmt = $pdo->prepare("INSERT INTO categories (name) VALUES (:name)");
//     $stmt->bindParam(':name', $category_name, PDO::PARAM_STR);
//     $stmt->execute();
// }

// // Fetch all categories for display
// $categories_query = $pdo->query("SELECT * FROM categories");
// $categories = $categories_query->fetchAll(PDO::FETCH_ASSOC);
// ?>



<header>
    <div class="header">


      <div class="navigation">
        <nav class="navbar navbar-expand-lg navbar-bg">
          
          <div class="brand-logo">
            <a class="navbar-brand" href="index.html" id="menu-action">
              <div class="user-photo d-desktop"><img src="assets/images/icons/favicon.png" alt=""></div>
              <span>Fizyonomi Admin</span>
            </a>    
            <div id="nav-toggle">
                <div class="cta">
                    <div class="toggle-btn type1"></div>
                </div>
            </div>     
          </div>
        <!--   "Mobil menü simgesini aç/kapatma işlemi için -->
          <div class="for-mobile d-mobile">
              <div class= "toggle-button" id = "toggle-button">
                <span class="material-icons">
                menu
                </span>
              </div>

          </div>
              <!--   "Mobil menü simgesini aç/kapatma işlemi için -->

          <div class="collapse navbar-collapse pr-3" id="#">

            <ul class="navbar-nav user-info ml-auto mt-2 mt-lg-0">
              <li class="nav-item dropdown show">
                <a href="#" class="navbar-nav-link dropdown-toggle text-light" data-toggle="dropdown" aria-expanded="true">
                  <div class="user-photo"><img src="https://dw3i9sxi97owk.cloudfront.net/uploads/thumbs/db9c4e1327eb8fe5e9395a4b04e1ea4a_70x70.jpg" alt=""></div>
                  admin@admin.com
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                  <a href="account.html" class="dropdown-item"> 
                    <i class="material-icons">
                    supervisor_account
                    </i>
                  Account Settings</a>
                  <div class="menu-dropdown-divider"></div>
                  <a class="dropdown-item" href="login.html"><i class="material-icons">exit_to_app</i>Logout</a>
                </div>
              </li>
            </ul>

          </div>


        </nav>
        
      </div>
    <!--   For Toggle Mobile Nav -->
     <div class="toggle-user-menu" id = "toggle-user-menu">
        <ul>
          <li><a href="#"><div class="user-photo"><img src="https://dw3i9sxi97owk.cloudfront.net/uploads/thumbs/db9c4e1327eb8fe5e9395a4b04e1ea4a_70x70.jpg" alt=""></div><?= htmlspecialchars($category['email']) ?></a></li>
          <li><a href="">
            <i class="material-icons mr-2">
                    supervisor_account
                    </i>
                  Account Settings
                </a></li>
          <li><a href=""><i class="material-icons mr-2">exit_to_app</i>Logout</a></li>

        </ul>
      </div>
    <!--   For Toggle Mobile Nav -->
    </div>  
</header>