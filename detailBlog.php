<?php
require_once './config/config.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $fizyonomi_id = intval($_GET['id']);
    
    $stmt = $pdo->prepare("SELECT * FROM fizyonomi WHERE id = :id AND isActive = 1");
    $stmt->execute(['id' => $fizyonomi_id]);
    $fizyonomi = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$fizyonomi) {
        header("HTTP/1.0 404 Not Found");
        echo "İçerik bulunamadı.";
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}
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
   <style>
      .detail-container {
         max-width: 900px;
         margin: 40px auto;
         padding: 20px;
         background-color: #fff;
         border-radius: 10px;
         box-shadow: 0 2px 8px rgba(0,0,0,0.1);
      }
      .detail-title {
         font-size: 32px;
         margin-bottom: 20px;
         text-align: center;
         color: #333;
      }
      .detail-image {
         width: 100%;
         max-height: 500px;
         object-fit: cover;
         margin-bottom: 20px;
         border-radius: 8px;
      }
      .detail-content {
         font-size: 18px;
         line-height: 1.8;
         color: #555;
         white-space: pre-line;
      }
      .author-date {
         margin-top: 30px;
         font-size: 14px;
         color: #999;
         text-align: center;
      }
   </style>
   <title><?= htmlspecialchars($fizyonomi['title']) ?> | Detay</title>
</head>

<body>

   <?php include('./includes/header.php'); ?>

   <div class="detail-container">
      <h1 class="detail-title"><?= htmlspecialchars($fizyonomi['title']) ?></h1>
      
      <?php if (!empty($fizyonomi['image'])): ?>
         <img src="<?= htmlspecialchars($fizyonomi['image']) ?>" alt="<?= htmlspecialchars($fizyonomi['title']) ?>" class="detail-image">
      <?php endif; ?>
      
      <div class="detail-content">
         <?= nl2br(htmlspecialchars($fizyonomi['content'])) ?>
      </div>

      <div class="author-date">
         <?php if (!empty($fizyonomi['author'])): ?>
            Yazar: <?= htmlspecialchars($fizyonomi['author']) ?> |
         <?php endif; ?>
         Yayın Tarihi: <?= date('d.m.Y', strtotime($fizyonomi['created_at'])) ?>
      </div>
   </div>

   <script src="public/js/search.js"></script>
   <script src="public/js/scroll.js"></script>
</body>

</html>
