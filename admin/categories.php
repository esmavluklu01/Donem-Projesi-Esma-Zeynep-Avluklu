<?php
// Include config file to get database connection
include('../config/config.php');
session_start();
// Query to fetch categories
try {
    $stmt = $pdo->prepare("SELECT * FROM categories"); // Change "categories" if the table name differs
    $stmt->execute();
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error fetching categories: " . $e->getMessage();
    exit();
}

// ekleme işlemi
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Create (Insert) Category
    if (isset($_POST['create'])) {
        $name = $_POST['name'];
        $description = $_POST['description'];
        $image = $_POST['image'];
        $isActive = isset($_POST['isActive']) ? 1 : 0;
        $isHome = isset($_POST['isHome']) ? 1 : 0;

        try {
            $stmt = $pdo->prepare("INSERT INTO categories (name, description, image, isActive, isHome) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$name, $description, $image, $isActive, $isHome]);
        } catch (PDOException $e) {
            echo "Error adding category: " . $e->getMessage();
        }
    }

    // Update Category
    if (isset($_POST['update']) && isset($_POST['categoryId'])) {
        $categoryId = $_POST['categoryId'];
        $name = $_POST['name'];
        $description = $_POST['description'];
        $image = $_POST['image'];
        $isActive = isset($_POST['isActive']) ? 1 : 0;
        $isHome = isset($_POST['isHome']) ? 1 : 0;

        try {
            $stmt = $pdo->prepare("UPDATE categories SET name = ?, description = ?, image = ?, isActive = ?, isHome = ? WHERE id = ?");
            $stmt->execute([$name, $description, $image, $isActive, $isHome, $categoryId]);
        } catch (PDOException $e) {
            echo "Error updating category: " . $e->getMessage();
        }
    }

    // Delete Category
    if (isset($_POST['delete']) && isset($_POST['categoryId'])) {
        $categoryId = $_POST['categoryId'];

        try {
            $stmt = $pdo->prepare("DELETE FROM categories WHERE id = ?");
            $stmt->execute([$categoryId]);
        } catch (PDOException $e) {
            echo "Error deleting category: " . $e->getMessage();
        }
    }

    // crud işlemlerinden sonra verileri tekrar listeliyor
    try {
        $stmt = $pdo->prepare("SELECT * FROM categories");
        $stmt->execute();
        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error fetching categories: " . $e->getMessage();
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

<!--====== Start Main Wrapper Section======-->
<section class="d-flex" id="wrapper">
    <div class="page-content-wrapper">
        <!--  Header BreadCrumb -->
        <div class="content-header">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html"><i class="material-icons">home</i>Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Roles</li>
                </ol>
            </nav>
            <div class="create-item">
                <button class="theme-primary-btn btn btn-primary" data-toggle="modal" data-target="#createCategoryModal"><i class="material-icons">add</i>Add New Role</button>
            </div>
        </div>
        <!--  Header BreadCrumb -->   
        <!--  main-content -->   
        <div class="main-content">
            <!-- Users DataTable-->
            <div class="row mt-3">
                <div class="col-md-12">
                    <div class="card bg-white">
                        <div class="card-body mt-3">
                            <div class="table-responsive">
                                <table id="roleTable" class="table table-striped table-borderless" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>SL</th>
                                            <th>Name</th>
                                            <th>Description</th>
                                            <th>Image</th>
                                            <th>isActive</th>
                                            <th>isHome</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($categories)): ?>
                                            <?php foreach ($categories as $category): ?>
                                                <tr>
                                                    <td><?= $category['id'] ?></td>
                                                    <td><?= $category['name'] ?></td>
                                                    <td><?= $category['description'] ?></td>
                                                    <td><img src="<?= $category['image'] ?>" alt="Category Image" width="50"></td>
                                                    <td><?= $category['isActive'] == 1 ? 'Active' : 'Inactive' ?></td>
                                                    <td><?= $category['isHome'] == 1 ? 'Yes' : 'No' ?></td>
                                                    <td>
                                                        <button class="btn btn-sm btn-info" data-toggle="modal" data-target="#editCategoryModal<?= $category['id'] ?>">Edit Role</button> 
                                                        <form method="POST" action="" style="display:inline;">
                                                            <input type="hidden" name="categoryId" value="<?= $category['id'] ?>">
                                                            <button type="submit" name="delete" class="btn btn-sm btn-danger">Delete</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr><td colspan="7">No categories found.</td></tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Users DataTable-->   
        </div>  
        <!--  main-content -->   
    </div>
</section>

<!-- Create Category Modal -->
<div class="modal fade" id="createCategoryModal" tabindex="-1" role="dialog" aria-labelledby="createCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createCategoryModalLabel">Create New Category</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" id="description" name="description" required></textarea>
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

<!-- Edit Category Modal -->
<?php foreach ($categories as $category): ?>
    <div class="modal fade" id="editCategoryModal<?= $category['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editCategoryModalLabel">Edit Category</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" action="">
                    <input type="hidden" name="categoryId" value="<?= $category['id'] ?>">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="editName">Name</label>
                            <input type="text" class="form-control" id="editName" name="name" value="<?= $category['name'] ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="editDescription">Description</label>
                            <textarea class="form-control" id="editDescription" name="description" required><?= $category['description'] ?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="editImage">Image URL</label>
                            <input type="text" class="form-control" id="editImage" name="image" value="<?= $category['image'] ?>" required>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="isActive" id="editIsActive" <?= $category['isActive'] ? 'checked' : '' ?>>
                            <label class="form-check-label" for="editIsActive">Active</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="isHome" id="editIsHome" <?= $category['isHome'] ? 'checked' : '' ?>>
                            <label class="form-check-label" for="editIsHome">Featured on Home</label>
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
