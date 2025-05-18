<?php
// Start output buffering to prevent header issues if output already started
ob_start();
session_start();
// Start the session at the very top (this must be the first line of PHP execution)
session_start();

// Ensure no further output before session starts and header is sent
include('../config/config.php'); // Veritabanı bağlantısı için

// Check if user is logged in and get user info
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
        header("Location: index.php"); // Redirect non-admin users to the homepage
        exit();
    }
} else {
    // Redirect unauthenticated users to the login page
    header("Location: ../index.php");
    exit();
}

// // Admin Panel CRUD Operations (For example: Display categories and create new ones)
// if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['category_name'])) {
//     $category_name = $_POST['category_name'];

//     // Insert new category into the database
//     $stmt = $pdo->prepare("INSERT INTO categories (name) VALUES (:name)");
//     $stmt->bindParam(':name', $category_name, PDO::PARAM_STR);
//     $stmt->execute();
// }

// // Fetch all categories for display
// $categories_query = $pdo->query("SELECT * FROM categories");
// $categories = $categories_query->fetchAll(PDO::FETCH_ASSOC);
// ?>

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

    <!--====== Bootstrap css ======-->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">

    <!--====== Style css ======-->
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/sidebar.css">
	   <!--====== Responsive css ======-->
    <link rel="stylesheet" href="assets/css/responsive.css">

     <!--====== CkEditors js ======-->
    <link rel="stylesheet" href="assets/js/ckeditor.js">

  

</head>
<body>

<!-- Prealoder -->
<div class="spinner_body">
   <div class="spinner"></div>  
</div>

<!-- Prealoder -->


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
                <li class="breadcrumb-item"><a href="#">Library</a></li>
                <li class="breadcrumb-item active" aria-current="page">Data</li>
              </ol>
            </nav>
<!--             <div class="create-item">
                <a href="" class="btn btn-primary"><i class="material-icons">add</i>Create user</a>
            </div> -->
        </div>
          <!--  Header BreadCrumb -->   
          <!--  main-content -->   
        <div class="main-content">



        <!-- Dashboard Box -->
        <div class="row animated fadeInUp">
            <div class="col-xl-3 col-sm-6 mb-3">
              <div class="card text-white bg-info o-hidden h-100">
                <div class="card-body">
                  <div class="card-body-icon">
    <i class="material-icons float-right text-white md-5em">group_add</i>
                  </div>
                  <div class="mr-5">( 123 )New Users</div>
                </div>
                <a class="card-footer text-white clearfix small z-1" href="#">
                  <span class="float-left">View Details</span>
                  <span class="float-right">
                    <i class="material-icons">
    keyboard_arrow_right
    </i>
                  </span>
                </a>
              </div>
            </div>
            <div class="col-xl-3 col-sm-6 mb-3">
              <div class="card text-white bg-primary o-hidden h-100">
                <div class="card-body">
                  <div class="card-body-icon">
                    <i class="material-icons float-right text-white md-5em">supervisor_account</i>
                  </div>
                  <div class="mr-5">( 123 )Active Users</div>
                </div>
                <a class="card-footer text-white clearfix small z-1" href="#">
                  <span class="float-left">View Details</span>
                  <span class="float-right">
                    <i class="material-icons">
    keyboard_arrow_right
    </i>
                  </span>
                </a>
              </div>
            </div>
            <div class="col-xl-3 col-sm-6 mb-3">
              <div class="card text-white bg-danger o-hidden h-100">
                <div class="card-body">
                  <div class="card-body-icon">
                    <i class="material-icons float-right text-white md-5em">person_outline</i>
                  </div>
                  <div class="mr-5">( 123 )Banned Users</div>
                </div>
                <a class="card-footer text-white clearfix small z-1" href="#">
                  <span class="float-left">View Details</span>
                  <span class="float-right">
                   <i class="material-icons">
    keyboard_arrow_right
    </i>
                  </span>
                </a>
              </div>
            </div>
            <div class="col-xl-3 col-sm-6 mb-3">
              <div class="card text-white bg-success o-hidden h-100">
                <div class="card-body">
                  <div class="card-body-icon">
                    <i class="material-icons float-right text-white md-5em">people_outline</i>
                  </div>
                  <div class="mr-5">( 123 )Total Users</div>
                </div>
                <a class="card-footer text-white clearfix small z-1" href="#">
                  <span class="float-left">View Details</span>
                  <span class="float-right">
                    <i class="material-icons">
    keyboard_arrow_right
    </i>
                  </span>
                </a>
              </div>
            </div>
        </div>
         <!-- Dashboard Box -->   








        <div class="row mt-3">


            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">Github Users</div>
                    <div class="card-body">
                       <table class="table table-striped">
                            <tbody>
                               <tr>
                                    <td><a href="https://www.elmasapp.online/users/248">yash</a></td>
                                    <td width="1" class="nowrap">Date</td>
                                </tr>
                                <tr>
                                    <td><a href="https://www.elmasapp.online/users/247">test</a></td>
                                    <td width="1" class="">Date</td>
                                </tr>

                            </tbody>
                       </table>
                    </div>
                </div>
            </div>


            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">Facebook Users</div>
                    <div class="card-body">
                       <table class="table table-striped">
                            <tbody>
                               <tr>
                                    <td><a href="https://www.elmasapp.online/users/248">yash</a></td>
                                    <td width="1" class="nowrap">Date</td>
                                </tr>
                                <tr>
                                    <td><a href="https://www.elmasapp.online/users/247">test</a></td>
                                    <td width="1" class="">Date</td>
                                </tr>

                            </tbody>
                       </table>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">Gmail Users</div>
                    <div class="card-body">
                       <table class="table table-striped">
                            <tbody>
                               <tr>
                                    <td><a href="https://www.elmasapp.online/users/248">yash</a></td>
                                    <td width="1" class="nowrap">Date</td>
                                </tr>
                                <tr>
                                    <td><a href="https://www.elmasapp.online/users/247">test</a></td>
                                    <td width="1" class="">Date</td>
                                </tr>

                            </tbody>
                       </table>
                    </div>
                </div>
            </div>




        </div>


        <div class="row mt-3">

            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="chart-legend-normal"></canvas>
                        </div>
                    </div>
                </div>
            </div>


        </div>






        </div>  
        <!--  main-content -->   
    </div> 

</section>

<!--====== End Main Wrapper Section======-->




    <!--====== JQuery from CDN ======-->
    <script src="assets/js/jquery.min.js"></script>

    <!--====== Bootstrap js ======-->
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/popper.min.js"></script>

    <!--====== dataTables js ======-->
    <script src="assets/js/dataTables.bootstrap4.min.js"></script>
    <script src="assets/js/jquery.dataTables.min.js"></script>

    <!--====== Chart.min js ======-->
    <script src="assets/js/Chart.min.js"></script>
    <script src="assets/js/Chart.bundle.min.js"></script>
    <script src="assets/js/chartfunction.js"></script>

    <!--====== wow.min js ======-->
    <script src="assets/js/wow.min.js"></script>
    <!--====== Main js ======-->
    <script src="assets/js/script.js"></script>

    <script>
        var color = Chart.helpers.color;
        function createConfig(colorName) {
            return {
                type: 'line',
                data: {
                    labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
                    datasets: [{
                        label: 'My First dataset',
                        data: [
                            randomScalingFactor(),
                            randomScalingFactor(),
                            randomScalingFactor(),
                            randomScalingFactor(),
                            randomScalingFactor(),
                            randomScalingFactor(),
                            randomScalingFactor()
                        ],
                        backgroundColor: color(window.chartColors[colorName]).alpha(0.5).rgbString(),
                        borderColor: window.chartColors[colorName],
                        borderWidth: 1,
                        pointStyle: 'rectRot',
                        pointRadius: 5,
                        pointBorderColor: 'rgb(0, 0, 0)'
                    }]
                },
                options: {
                    responsive: true,
                    legend: {
                        labels: {
                            usePointStyle: false
                        }
                    },
                    scales: {
                        xAxes: [{
                            display: true,
                            scaleLabel: {
                                display: true,
                                labelString: 'Month'
                            }
                        }],
                        yAxes: [{
                            display: true,
                            scaleLabel: {
                                display: true,
                                labelString: 'Value'
                            }
                        }]
                    },
                    title: {
                        display: true,
                        text: 'Normal Legend'
                    }
                }
            };
        }

        function createPointStyleConfig(colorName) {
            var config = createConfig(colorName);
            config.options.legend.labels.usePointStyle = true;
            config.options.title.text = 'Point Style Legend';
            return config;
        }

        window.onload = function() {
            [{
                id: 'chart-legend-normal',
                config: createConfig('red')
            }, {
                id: 'chart-legend-pointstyle',
                config: createPointStyleConfig('blue')
            }].forEach(function(details) {
                var ctx = document.getElementById(details.id).getContext('2d');
                new Chart(ctx, details.config);
            });
        };
    </script>

</body>
</html>