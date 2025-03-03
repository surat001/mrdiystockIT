<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
// ดึงชื่อผู้ใช้จากเซสชัน
$name = $_SESSION['name'] ?? "User";
$group = $_SESSION['group_name'] ?? "Group";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select System</title>
    <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="assets/vendor/simple-datatables/style.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">

  <!-- =======================================================
  * Template Name: NiceAdmin
  * Template URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
  * Updated: Apr 20 2024 with Bootstrap v5.3.3
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
    <style>
        body {
            background-color: #f8f9fa;
        }
        .card {
            padding: 2rem 1rem 1rem 1rem;
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s;
        }
        .card:hover {
            transform: scale(1.05);
        }
        .card-icon {
            font-size: 4rem;
            color: #007bff;
            
        }
        .card-title1 {
            margin-top: 10px;
            font-size: 1.5rem;
            font-weight: bold;
        }
        .card-text1 {
            font-size: 1rem;
            color: #6c757d;
        }
        .btn-primary {
            background-color: #007bff;
            border: none;
            border-radius: 20px;
            padding: 10px 20px;
            font-size: 1rem;
        }
    </style>
</head>
<body>
<?php include 'navbarforhome.php'; ?>
<br><br>
    <div class="container mt-5">
        <h1 class="text-center mb-4"><strong>Select System</strong></h1>
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-boxes card-icon"></i>
                        <h5 class="card-title1">Stock IT System</h5>
                        <p class="card-text1">Manage stock and inventory.</p>
                        <a href="check_access.php?system=stock" class="btn btn-primary">Go to Stock IT</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-user-plus card-icon"></i>
                        <h5 class="card-title1">User Account Registration</h5>
                        <p class="card-text1">Register new user accounts.</p>
                        <a href="user_registration.php" class="btn btn-primary">Go to User Registration</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-tools card-icon"></i>
                        <h5 class="card-title1">IT Service Request Submission</h5>
                        <p class="card-text1">Submit IT service requests.</p>
                        <a href="service_request.php" class="btn btn-primary">Go to Service Request</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-database card-icon"></i>
                        <h5 class="card-title1">Backup System Inspection</h5>
                        <p class="card-text1">Inspect backup systems.</p>
                        <a href="backup_inspection.php" class="btn btn-primary">Go to Backup Inspection</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Vendor JS Files -->
  <script src="assets/vendor/apexcharts/apexcharts.min.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/chart.js/chart.umd.js"></script>
  <script src="assets/vendor/echarts/echarts.min.js"></script>
  <script src="assets/vendor/quill/quill.js"></script>
  <script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="assets/vendor/tinymce/tinymce.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>
    <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>
</body>
</html>