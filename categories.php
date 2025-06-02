<?php
require_once './config/config.php';

$degisken = $pdo->query("SELECT * FROM categories");  
// TÜM KATEGORİLERİ GETİRİYOR
$categories = $degisken->fetchAll(PDO::FETCH_ASSOC);  
// kategori bilgilerini FETCH EDİP DEĞİŞKENE ATANIYOR
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

   <?php include('./includes/header.php'); ?>  <!--includes klasörü içindeki header.php dosyasını buraya ekle-->
   
   
   <header class="header target">
      <img src="https://i.pinimg.com/736x/5b/a4/d8/5ba4d87fefea09725e3079bd631cdb75.jpg" alt="background">
      <h1>KATEGORİLER</h1>
   </header>

   <div class="home">
      <div class="wrapper">
         <div class="posts">
         <?php foreach ($categories as $category) : ?>
            <?php if ($category['isHome'] == 1 && $category['isActive'] == 1) : ?>
                
                <div class="post target">
               <h1><a href="categoriesBlog.php?id=<?= $category['id'] ?>"><?= $category['name'] ?></a></h1>
               <img src="<?= $category['image'] ?>" class="content" alt="content">
               <p>
                  <?= substr($category['description'], 0, 100) . '...' ?>
               </p>
               <div class="authorAndDate">
                  <!-- Author and date can be added here if needed -->
               </div>
            </div>
            <?php endif; ?>
         <?php endforeach; ?>
         </div>
      </div>
   </div>

   <script src="public/js/search.js"></script>
   <script src="public/js/scroll.js"></script>
</body>

</html>
