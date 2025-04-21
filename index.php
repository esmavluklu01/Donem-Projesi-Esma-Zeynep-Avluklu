<?php
session_start();
require_once './config/config.php';

$degisken = $pdo->query("SELECT * FROM blogs");  // TÜM ESERLERİ GETİRİYOR
$blogs = $degisken->fetchAll(PDO::FETCH_ASSOC);  // SANAT ESERLERİ FETCH EDİP DEĞİŞKENE ATANIYOR
?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="stylesheet" href="public/css/index.css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
      integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
      crossorigin="anonymous" referrerpolicy="no-referrer" />
   <title>Document</title>
</head>

<body>

   <?php include('./includes/header.php'); ?>
   <header class="header target">
      <img src="./public/img/background/face1.jpg" alt="background">
      <h1>fizyonomi</h1>
      <h3>el yüz çizgileri bize ne anlatıyor</h3>
   </header>

   <div class="home">
      <div class="wrapper">
         <div class="posts">
         <?php foreach ($blogs as $blog) : ?>
            <div class="post target">
               <h1><a href="detailBlog.php?id=<?= $blog['id'] ?>"><?= $blog['title'] ?></a></h1>
               <img src="<?= $blog['image'] ?>" class="content" alt="content">
               <p>
                  <?= substr($blog['description'], 0, 100) . '...' ?>
               </p>
               <div class="authorAndDate">
                  <!-- Author and date can be added here if needed -->
               </div>
            </div>
         <?php endforeach; ?>
         </div>

         <div class="sidebar">
            <div class="area target">
               <h3>Who I Am</h3>
               <img src="public/img/user/user-2.png" alt="user">
               <div class="categories">
                  <span>#art</span>
                  <span>#design</span>
                  <span>#paint</span>
                  <span>#culture</span>
               </div>
            </div>

            <div class="area target">
               <h3>Your Space</h3>
               <a href="/admin/add">Add Post</a>
               <a href="#">Your Perfonce</a>
               <a href="#">Number of Views</a>
            </div>
         </div>
      </div>
   </div>

   <script src="public/js/search.js"></script>
   <script src="public/js/scroll.js"></script>
</body>

</html>
