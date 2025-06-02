<?php

// Include config file to get database connection
include('../config/config.php');
// Start the session at the very top (this must be the first line of PHP execution)

session_start();

// Kullanıcının oturum açıp açmadığını kontrol etmek ve kullanıcı bilgilerini almak 
$user_logged_in = isset($_SESSION['user_id']); 

if ($user_logged_in) {
    // Kullanıcı bilgilerini veritabanından çek
    $user_id = $_SESSION['user_id'];
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
    $stmt->bindParam(':id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Redirect non-admin users to the home page
    if ($user['role'] !== 'admin') {
        header("Location: index.php"); 
        // Yönetici değilse ana sayfaya yönlendir
        exit();
    }
} else {
    //  Eğer kullanıcı oturum açmamışsa index sayfasına yönlendir
    header("Location: ../index.php");
    exit();
}
// Fizyonomi kayıtlarını getirmek için sorgu
try {
    $stmt = $pdo->prepare("SELECT * FROM fizyonomi");
    $stmt->execute();
    $fizyonomies = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error fetching fizyonomi records: " . $e->getMessage();
    exit();
}

// Oluşturma, Güncelleme ve Silme işlemlerini yönetmek
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Create (Insert) Fizyonomi
    if (isset($_POST['create'])) {
        $category_id = $_POST['category_id'];
        $title = $_POST['title'];
        $slug = $_POST['slug'];
        $content = $_POST['content'];
        $status = $_POST['status'];  // Fetch status from form
        $image = $_POST['image'];
        $isActive = isset($_POST['isActive']) ? 1 : 0;
        $isHome = isset($_POST['isHome']) ? 1 : 0;

        try {
            // Yeni bir fizyonomi kaydı ekle
            $stmt = $pdo->prepare("INSERT INTO fizyonomi (user_id, category_id, title, slug, content, status, image, isActive, isHome) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$user_id, $category_id, $title, $slug, $content, $status, $image, $isActive, $isHome]);
        } catch (PDOException $e) {
            echo "Error adding fizyonomi: " . $e->getMessage();
        }
    }

    // güncelleme işlemi
    if (isset($_POST['update']) && isset($_POST['fizyonomiId'])) {
        $fizyonomiId = $_POST['fizyonomiId'];
        $category_id = $_POST['category_id'];
        $title = $_POST['title'];
        $slug = $_POST['slug'];
        $content = $_POST['content'];
        $status = $_POST['status'];  // Fetch status from form
        $image = $_POST['image'];
        $isActive = isset($_POST['isActive']) ? 1 : 0;
        $isHome = isset($_POST['isHome']) ? 1 : 0;

        try {
            //  Fizyonomi kaydını güncelle
            $stmt = $pdo->prepare("UPDATE fizyonomi SET category_id = ?, title = ?, slug = ?, content = ?, status = ?, image = ?, isActive = ?, isHome = ? WHERE id = ?");
            $stmt->execute([$category_id, $title, $slug, $content, $status, $image, $isActive, $isHome, $fizyonomiId]);
        } catch (PDOException $e) {
            echo "Error updating fizyonomi: " . $e->getMessage();
        }
    }

    // Delete Fizyonomi
    if (isset($_POST['delete']) && isset($_POST['fizyonomiId'])) {
        $fizyonomiId = $_POST['fizyonomiId'];

        try {
            // Delete fizyonomi record
            $stmt = $pdo->prepare("DELETE FROM fizyonomi WHERE id = ?");
            $stmt->execute([$fizyonomiId]);
        } catch (PDOException $e) {
            echo "Error deleting fizyonomi: " . $e->getMessage();
        }
    }

    // crud işlemlerinden sonra verileri güncelle listeleme
    try {
        $stmt = $pdo->prepare("SELECT * FROM fizyonomi");
        $stmt->execute();
        $fizyonomies = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error fetching fizyonomi records: " . $e->getMessage();
        exit();
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <!--====== Required meta tags ======-->
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="description" content="">

    <!--====== Title ======-->
    <title>Admin Dashboard</title>

    <!--====== Favicon Icon ======-->
    <link rel="shortcut icon" href="assets/images/icons/favicon.png" type="image/png">

    <!--====== Google Fonts ======-->
    <link href="https://fonts.googleapis.com/css?family=Nunito&display=swap" rel="stylesheet">

    <!--====== Material Icons ======-->
    <link rel="stylesheet" href="assets/iconfont/material-icons.css">

    <!-- dataTables.bootstrap4.min css-->
    <link href="assets/css/dataTables.bootstrap4.min.css" rel="stylesheet" media="screen">

    <!-- Chart.min css-->
    <link href="assets/css/Chart.min.css" rel="stylesheet" media="screen">

    <!-- animate css-->
    <link href="assets/css/animate.css" rel="stylesheet" media="screen">
    <!-- normalize css-->
    <link href="assets/css/normalize.css" rel="stylesheet" media="screen">
    <!--====== Bootstrap css ======-->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">

    <!--====== Style css ======-->
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/sidebar.css">
    <!--====== Responsive css ======-->
    <link rel="stylesheet" href="assets/css/responsive.css">

</head>
<body>

<!-- Preloader -->
<div class="spinner_body">
    <div class="spinner"></div>  
</div>

<!-- Preloader -->

<?php include('../admin/includes/header.php'); ?>
<?php include('../admin/includes/sidebar.php'); ?>

<section class="d-flex" id="wrapper">
    <div class="page-content-wrapper">
        <div class="content-header">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html"><i class="material-icons">home</i>Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Fizyonomi</li>
                </ol>
            </nav>
            <div class="create-item">
                <button class="theme-primary-btn btn btn-primary" data-toggle="modal" data-target="#createFizyonomiModal"><i class="material-icons">add</i>Add New Fizyonomi</button>
            </div>
        </div>

        <div class="main-content">
            <div class="row mt-3">
                <div class="col-md-12">
                    <div class="card bg-white">
                        <div class="card-body mt-3">
                            <div class="table-responsive">
                                <table id="fizyonomiTable" class="table table-striped table-borderless" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>SL</th>
                                            <th>Title</th>
                                            <th>Category</th>
                                            <th>Status</th>
                                            <th>Image</th>
                                            <th>isActive</th>
                                            <th>isHome</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($fizyonomies)): ?>
                                            <?php foreach ($fizyonomies as $fizyonomi): ?>
                                                <tr>
                                                    <td><?= $fizyonomi['id'] ?></td>
                                                    <td><?= $fizyonomi['title'] ?></td>
                                                    <td><?= $fizyonomi['category_id'] ?></td>
                                                    <td><?= $fizyonomi['status'] ?></td>
                                                    <td><img src="<?= $fizyonomi['image'] ?>" alt="Fizyonomi Image" width="50"></td>
                                                    <td><?= $fizyonomi['isActive'] == 1 ? 'Active' : 'Inactive' ?></td>
                                                    <td><?= $fizyonomi['isHome'] == 1 ? 'Yes' : 'No' ?></td>
                                                    <td>
                                                        <button class="btn btn-sm btn-info" data-toggle="modal" data-target="#editFizyonomiModal<?= $fizyonomi['id'] ?>">Edit</button> 
                                                        <form method="POST" action="" style="display:inline;">
                                                            <input type="hidden" name="fizyonomiId" value="<?= $fizyonomi['id'] ?>">
                                                            <button type="submit" name="delete" class="btn btn-sm btn-danger">Delete</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr><td colspan="8">No records found.</td></tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>  
    </div>
</section>


<!-- Create Fizyonomi Modal -->
<div class="modal fade" id="createFizyonomiModal" tabindex="-1" role="dialog" aria-labelledby="createFizyonomiModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createFizyonomiModalLabel">Create New Fizyonomi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="category_id">Category</label>
                        <select class="form-control" id="category_id" name="category_id" required>
                            <option value="">Select Category</option>
                            <?php
                            // Fetch categories from database
                            try {
                                $stmt = $pdo->prepare("SELECT * FROM categories");
                                $stmt->execute();
                                $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                foreach ($categories as $category) {
                                    echo "<option value='{$category['id']}'>{$category['name']}</option>";
                                }
                            } catch (PDOException $e) {
                                echo "Error fetching categories: " . $e->getMessage();
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="title">Title</label>
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>
                    <div class="form-group">
                        <label for="slug">Slug</label>
                        <input type="text" class="form-control" id="slug" name="slug" required>
                    </div>
                    <div class="form-group">
                        <label for="content">Content</label>
                        <textarea class="form-control" id="content" name="content" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="image">Image URL</label>
                        <input type="text" class="form-control" id="image" name="image" required>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" name="isActive" id="isActive">
                        <label class="form-check-label" for="isActive">Active</label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" name="isHome" id="isHome">
                        <label class="form-check-label" for="isHome">Featured on Home</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" name="create" class="btn btn-primary">Create</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Fizyonomi Modal -->
<?php foreach ($fizyonomies as $fizyonomi): ?>
    <div class="modal fade" id="editFizyonomiModal<?= $fizyonomi['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="editFizyonomiModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editFizyonomiModalLabel">Edit Fizyonomi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" action="">
                    <input type="hidden" name="fizyonomiId" value="<?= $fizyonomi['id'] ?>">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="category_id">Category</label>
                            <select class="form-control" id="category_id" name="category_id" required>
                                <option value="">Select Category</option>
                                <?php
                                try {
                                    $stmt = $pdo->prepare("SELECT * FROM categories");
                                    $stmt->execute();
                                    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                    foreach ($categories as $category) {
                                        $selected = $fizyonomi['category_id'] == $category['id'] ? 'selected' : '';
                                        echo "<option value='{$category['id']}' {$selected}>{$category['name']}</option>";
                                    }
                                } catch (PDOException $e) {
                                    echo "Error fetching categories: " . $e->getMessage();
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="title">Title</label>
                            <input type="text" class="form-control" id="title" name="title" value="<?= $fizyonomi['title'] ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="slug">Slug</label>
                            <input type="text" class="form-control" id="slug" name="slug" value="<?= $fizyonomi['slug'] ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="content">Content</label>
                            <textarea class="form-control" id="content" name="content" required><?= $fizyonomi['content'] ?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="image">Image URL</label>
                            <input type="text" class="form-control" id="image" name="image" value="<?= $fizyonomi['image'] ?>" required>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="isActive" id="isActive" <?= $fizyonomi['isActive'] == 1 ? 'checked' : '' ?>>
                            <label class="form-check-label" for="isActive">Active</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="isHome" id="isHome" <?= $fizyonomi['isHome'] == 1 ? 'checked' : '' ?>>
                            <label class="form-check-label" for="isHome">Featured on Home</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" name="update" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endforeach; ?>
<!--====== JQuery from CDN ======-->
<script src="assets/js/jquery.min.js"></script>

<!--====== Bootstrap js ======-->
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/js/popper.min.js"></script>

<!--====== dataTables js ======-->
<script src="assets/js/dataTables.bootstrap4.min.js"></script>
<script src="assets/js/jquery.dataTables.min.js"></script>

<!--====== Main js ======-->
<script src="assets/js/script.js"></script>

</body>
</html>

