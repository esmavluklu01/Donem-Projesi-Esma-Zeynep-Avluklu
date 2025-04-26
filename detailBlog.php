<?php
session_start();
require_once './config/config.php';

if (isset($_GET['id'])) {
    $blog_id = $_GET['id'];
    
    // Veritabanından ilgili blogu çekme
    $degisken = $pdo->prepare("SELECT * FROM blogs WHERE id = :id");
    $degisken->bindParam(':id', $blog_id, PDO::PARAM_INT);
    $degisken->execute();
    $blog = $degisken->fetch(PDO::FETCH_ASSOC);
    
    if (!$blog) {
        // Eğer blog bulunamazsa 404 sayfasına yönlendirme yapabilirsiniz.
        header("HTTP/1.0 404 Not Found");
        echo "Blog not found.";
        exit();
    }
} else {
    // ID yoksa yönlendirme yap
    header("Location: index.php");
    exit();
}
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
      <img src="./public/img/background/fizyonomi1.jpg" alt="background">
      <h1>fizyonomi</h1>
      <h3>el yüz çizgileri bize ne anlatıyor</h3>
   </header>

   <div class="home">
      <div class="wrapper">
         <div class="posts">
            <!-- Detaylı Blog Yazısı -->
            <div class="post target">
               <h1><?= $blog['title'] ?></h1>
               <img src="<?= $blog['image'] ?>" class="content" alt="content">
               <p>
                  <?= $blog['description'] ?>
               </p>
               <div class="authorAndDate">
                  <!-- Author and date can be added here if needed -->
               </div>
            </div>
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
