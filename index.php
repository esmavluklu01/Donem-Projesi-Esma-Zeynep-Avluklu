<?php
require_once './config/config.php';

// Get user information if logged in
$user_logged_in = isset($_SESSION['user_id']);
if ($user_logged_in) {
    $user_id = $_SESSION['user_id'];
    $stmt_user = $pdo->prepare("SELECT * FROM users WHERE id = :id");
    $stmt_user->bindParam(':id', $user_id, PDO::PARAM_INT);
    $stmt_user->execute();
    $user = $stmt_user->fetch(PDO::FETCH_ASSOC);
}

// Fetch all articles from the "fizyonomi" table
$degisken = $pdo->query("SELECT * FROM fizyonomi");
$fizyonomies = $degisken->fetchAll(PDO::FETCH_ASSOC);

// Fetch all categories from the "categories" table
$categories_query = $pdo->query("SELECT * FROM categories");
$categories = $categories_query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>





   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="stylesheet" href="public/css/index.css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
   <title>Document</title>





   <!-- Slider CSS -->
   <style>
      .slider {
         position: relative;
         width: 100%;
         max-width: 100%;
         margin: auto;
         overflow: hidden;
      }

      .slider-images {
         display: flex;
         transition: transform 0.5s ease;
      }

      .slider-images img {
         width: 100%;
         height: 100vh;
         object-fit: cover;
      }

      .prev, .next {
         position: absolute;
         top: 50%;
         transform: translateY(-50%);
         background-color: rgba(0, 0, 0, 0.5);
         color: white;
         border: none;
         font-size: 18px;
         padding: 10px;
         cursor: pointer;
      }

      .prev {
         left: 10px;
      }

      .next {
         right: 10px;
      }

      /* For the fade transition effect */
      .fade {
         animation-name: fade;
         animation-duration: 1.5s;
      }

      @keyframes fade {
         from {
            opacity: 0.4;
         }
         to {
            opacity: 1;
         }
      }

      .show-more {
         display: inline-block;
         margin-top: 10px;
         font-size: 14px;
         text-decoration: none;
         color: #007BFF;
      }

      .show-more:hover {
         text-decoration: underline;
      }
   </style>
</head>

<body>
   <?php include('./includes/header.php'); ?>






   <header class="header target">
      <!-- Slider Section -->
      <div class="slider">
         <div class="slider-images">
            <div class="fade">
               <img src="../public/img/background/kapak2.jpg" alt="Slider Image 1">
            </div>
            <div class="fade">
               <img src="../public/img/background/kapak3.jpg" alt="Slider Image 2">
            </div>
            <div class="fade">
               <img src="https://us.v-cdn.net/5021068/uploads/editor/27/9p74k4na3mzp.jpg" alt="Slider Image 3">
            </div>
         </div>
         <button class="prev">&#10094;</button>
         <button class="next">&#10095;</button>
      </div>
      <h1>FİZYONOMİ</h1>
      
   </header>

   <div class="home">
      <div class="wrapper">
         <div class="posts">
            <?php foreach ($fizyonomies as $fizyonomi): ?>
               <?php if ($fizyonomi['isHome'] == 1 && $fizyonomi['isActive'] == 1): ?>
               <div class="post target">
                  <h1><a href="detailBlog.php?id=<?= $fizyonomi['id'] ?>"><?= $fizyonomi['title'] ?></a></h1>
                  <img src="<?= $fizyonomi['image'] ?>" class="content" alt="content">
                  <p><?= substr($fizyonomi['content'], 0, 100) . '...' ?></p>
               </div>
               <?php endif; ?>
            <?php endforeach; ?>
         </div>

         <div class="sidebar">
            <div class="area target">
               <h3>Ben Kimim</h3>
               <img src="public/img/user/rr.jpg" alt="user">
               <?php if ($user_logged_in): ?>
               <div class="user-info">
               <h3>Dönem projesi  </h3>   
               <h4>Welcome, <?= htmlspecialchars($user['username']) ?></h4>
                  <p>Email: <?= htmlspecialchars($user['email']) ?></p>
                  <p>Role: <?= htmlspecialchars($user['role']) ?></p>
               </div>
               <?php else: ?>
               <p>Please log in to see your profile information.</p>
               <?php endif; ?>
            </div>
              <?php include('./includes/categories.php'); ?>
         </div>
      </div>
   </div>

   <script src="public/js/search.js"></script>
   <script src="public/js/scroll.js"></script>

   <!-- Slider JavaScript -->
   <script>
      let currentIndex = 0;
      const slides = document.querySelectorAll('.slider-images .fade');
      const prevButton = document.querySelector('.prev');
      const nextButton = document.querySelector('.next');

      function showImage(index) {
         const totalImages = slides.length;
         if (index < 0) {
            currentIndex = totalImages - 1;
         } else if (index >= totalImages) {
            currentIndex = 0;
         } else {
            currentIndex = index;
         }
         for (let i = 0; i < totalImages; i++) {
            slides[i].style.display = "none";
         }
         slides[currentIndex].style.display = "block";
      }

      prevButton.addEventListener('click', () => showImage(currentIndex - 1));
      nextButton.addEventListener('click', () => showImage(currentIndex + 1));

      // Initialize the slider to the first image
      showImage(currentIndex);

      // Automatically transition every 5 seconds
      setInterval(() => {
         showImage(currentIndex + 1);
      }, 5000);
   </script>
</body>
</html>
