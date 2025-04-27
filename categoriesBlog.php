<?php
session_start();
require_once './config/config.php';

$category_id = intval($_GET['id']); // Güvenlik için int'e çevirdik

// Kategori bilgisi çekiliyor
$category_stmt = $pdo->prepare("SELECT * FROM categories WHERE id = :id");
$category_stmt->execute(['id' => $category_id]);
$category = $category_stmt->fetch(PDO::FETCH_ASSOC);

if (!$category) {
    die("Kategori bulunamadı.");
}

// Bu kategoriye ait yayımlanmış yazılar çekiliyor
$fizyonomi_stmt = $pdo->prepare("SELECT * FROM fizyonomi WHERE category_id = :id ORDER BY created_at DESC");
$fizyonomi_stmt->execute(['id' => $category_id]);
$posts = $fizyonomi_stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="tr">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="stylesheet" href="public/css/index.css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
      integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
      crossorigin="anonymous" referrerpolicy="no-referrer" />
   <title><?= htmlspecialchars($category['name']) ?> | Kategori</title>
</head>

<body>

   <?php include('./includes/header.php'); ?>

   <header class="header target">
      <img src="./public/img/background/indir (2).jpg" alt="background">
      <h1><?= htmlspecialchars($category['name']) ?> Kategorisi</h1>
   </header>

   <div class="home">
      <div class="wrapper">
         <div class="posts">
         <?php foreach ($posts as $post) : ?>
            <div class="post target">
               <h1><a href="detailBlog.php?id=<?= $post['id'] ?>"><?= htmlspecialchars($post['title']) ?></a></h1>
               <img src="<?= htmlspecialchars($post['image']) ?>" class="content" alt="content">
               <p>
                  <?= htmlspecialchars(substr($post['content'], 0, 100)) . '...' ?>
               </p>
               <div class="authorAndDate">
                  <!-- İstersen burada yazar veya tarih bilgisi ekleyebilirsin -->
               </div>
            </div>
         <?php endforeach; ?>

         <?php if (count($posts) === 0): ?>
            <p>Bu kategoride henüz yazı bulunmamaktadır.</p>
         <?php endif; ?>
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
               <a href="#">Your Performance</a>
               <a href="#">Number of Views</a>
            </div>
         </div>
      </div>
   </div>

   <script src="public/js/search.js"></script>
   <script src="public/js/scroll.js"></script>
</body>

</html>
