<?php
include('./config/config.php'); // Veritabanı bağlantısı için

// Eğer kullanıcı giriş yaptıysa, oturum bilgilerini al
$user_logged_in = !isset($_SESSION['user_id']);
?>


<div class="topbar">
      <div class="wrapper target">
         <div class="left">
            <i class="fa-brands fa-square-x-twitter"></i>
            <i class="fa-brands fa-square-instagram"></i>
            <i class="fa-brands fa-square-facebook"></i>
            <i class="fa-brands fa-square-pinterest"></i>
         </div>
         <div class="center">
         <?php if ($user_logged_in): ?>
            <a href="./index.php">HOME</a>
            <a href="./about.php">ABOUT</a>
            <a href="./add.php">ADD</a>
            <a href="./categories.php">KATEGORİLER</a>
            <?php else: ?>
            <a href="./index.php">HOME</a>            
            <a href="./login.php">LOGIN</a>
            <a href="./register.php">REGISTER</a>
            <?php endif; ?>
         </div>
         <div class="right">
            <span>
               <img src="public/img/user/user-1.png" alt="user">
            </span>
            <div class="searchEngine" id="searchEngine">
               <input type="text" placeholder="...">
               <i class="fa-solid fa-magnifying-glass" id="searchIcon"></i>
            </div>
         </div>
      </div>
   </div>

