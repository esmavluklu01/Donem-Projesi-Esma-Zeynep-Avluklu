<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="stylesheet" href="../public/css/index.css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
      integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
      crossorigin="anonymous" referrerpolicy="no-referrer" />
   <title>Document</title>
</head>

<body>


   
<?php include('./includes/header.php'); ?>



   <div class="logReg">
      <img src="./public/img/background/login.jpg" alt="register">
      <form>

         <div class="formGroup">
            <input type="text" required>
            <span>Username *</span>
         </div>

         <div class="formGroup">
            <input type="text" required>
            <span>Password *</span>
         </div>

         <div class="btnGroup">
            <a href="./login.html">Do you have an account?</a>
            <button>Register</button>
         </div>
      </form>
   </div>




   <script src="../public/js/search.js"></script>
   <script src="../public/js/scroll.js"></script>
</body>

</html>