<?php
// Include config file to get database connection
include('../config/config.php');
session_start();


//ekleme alanı
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Add User
    if (isset($_POST['create'])) {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash password
        $role = $_POST['role'];

        try {
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?,?)");
            $stmt->execute([$username, $email, $password, $role]);
        } catch (PDOException $e) {
            echo "Error adding user: " . $e->getMessage();
        }
    }

    //Kullanıcı bilgilerini güncelle
    if (isset($_POST['update']) && isset($_POST['userId'])) {
        $userId = $_POST['userId'];
        $username = $_POST['username'];
        $email = $_POST['email'];
        $role = $_POST['role'];

        try {
            $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ?, role = ? WHERE id = ?");
            $stmt->execute([$username, $email, $role, $userId]);
        } catch (PDOException $e) {
            echo "Error updating user: " . $e->getMessage();
        }
    }

    //  Kullanıcıyı sil
    if (isset($_POST['delete']) && isset($_POST['userId'])) {
        $userId = $_POST['userId'];

        try {
            $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
            $stmt->execute([$userId]);
        } catch (PDOException $e) {
            echo "Error deleting user: " . $e->getMessage();
        }
    }
}

$limit = 10;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

try {
   //admin' rolüne sahip kullanıcıları listeden çıkar
    $stmt = $pdo->prepare("SELECT * FROM users LIMIT :limit OFFSET :offset");
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmtTotal = $pdo->prepare("SELECT COUNT(*) FROM users");
    $stmtTotal->execute();
    $totalUsers = $stmtTotal->fetchColumn();
    $totalPages = ceil($totalUsers / $limit);
} catch (PDOException $e) {
    echo "Error fetching users: " . $e->getMessage();
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="description" content="">
    <title>Admin Dashboard</title>
    <link rel="shortcut icon" href="assets/images/icons/favicon.png" type="image/png">
    <link href="https://fonts.googleapis.com/css?family=Nunito&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/iconfont/material-icons.css">
    <link href="assets/css/dataTables.bootstrap4.min.css" rel="stylesheet" media="screen">
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/sidebar.css">
</head>
<body>

<!-- Preloader -->
<div class="spinner_body">
    <div class="spinner"></div>  
</div>

<?php include('../admin/includes/header.php'); ?>
<?php include('../admin/includes/sidebar.php'); ?>

<!-- Main Content -->
<section class="d-flex" id="wrapper">
    <div class="page-content-wrapper">
        <!-- Header Breadcrumb -->
        <div class="content-header">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html"><i class="material-icons">home</i>Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Users</li>
                </ol>
            </nav>
            <div class="create-item">
                <button class="theme-primary-btn btn btn-primary" data-toggle="modal" data-target="#createUserModal"><i class="material-icons">add</i>Add New User</button>
            </div>
        </div>

        <!-- Users DataTable -->
        <div class="main-content">
            <div class="row mt-3">
                <div class="col-md-12">
                    <div class="card bg-white">
                        <div class="card-body mt-3">
                            <div class="table-responsive">
                                <table class="table table-striped table-borderless">
                                    <thead>
                                        <tr>
                                            <th>SL</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Role</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($users)): ?>
                                            <?php foreach ($users as $user): ?>
                                                <tr>
                                                    <td><?= $user['id'] ?></td>
                                                    <td><?= $user['username'] ?></td>
                                                    <td><?= $user['email'] ?></td>
                                                    <td><?= $user['role'] ?></td>
                                                    <td>
                                                        <button class="btn btn-sm btn-info" data-toggle="modal" data-target="#editUserModal<?= $user['id'] ?>">Edit</button> 
                                                        <form method="POST" action="" style="display:inline;">
                                                            <input type="hidden" name="userId" value="<?= $user['id'] ?>">
                                                            <button type="submit" name="delete" class="btn btn-sm btn-danger">Delete</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr><td colspan="5">No users found.</td></tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pagination Controls -->
            <div class="row">
                <div class="col-md-12">
                    <nav aria-label="Page navigation example">
                        <ul class="pagination justify-content-center">
                            <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                                <a class="page-link" href="?page=1">&laquo;</a>
                            </li>
                            <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                                <a class="page-link" href="?page=<?= $page - 1 ?>">&lt;</a>
                            </li>
                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                                    <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                                </li>
                            <?php endfor; ?>
                            <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
                                <a class="page-link" href="?page=<?= $page + 1 ?>">&gt;</a>
                            </li>
                            <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
                                <a class="page-link" href="?page=<?= $totalPages ?>">&raquo;</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Create and Edit User Modals -->
<!-- user ekleme modulu -->
<div class="modal fade" id="createUserModal" tabindex="-1" role="dialog" aria-labelledby="createUserModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createUserModalLabel">Create New User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" id="name" name="username" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="form-group">
                        <label for="role">Role</label>
                        <select class="form-control" name="role" id="role" required>
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                        </select>
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

<!-- user güncelleme -->
<?php foreach ($users as $user): ?>
    <div class="modal fade" id="editUserModal<?= $user['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" action="">
                    <input type="hidden" name="userId" value="<?= $user['id'] ?>">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="editName">Name</label>
                            <input type="text" class="form-control" id="editName" name="username" value="<?= $user['username'] ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="editEmail">Email</label>
                            <input type="email" class="form-control" id="editEmail" name="email" value="<?= $user['email'] ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="editRole">Role</label>
                            <select class="form-control" name="role" id="editRole" required>
                                <option value="user" <?= $user['role'] == 'user' ? 'selected' : '' ?>>User</option>
                                <option value="admin" <?= $user['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
                            </select>
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

<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/js/popper.min.js"></script>
<script src="assets/js/dataTables.bootstrap4.min.js"></script>
<script src="assets/js/jquery.dataTables.min.js"></script>
<script src="assets/js/script.js"></script>

</body>
</html>
