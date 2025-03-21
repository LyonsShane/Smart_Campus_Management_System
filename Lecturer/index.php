
<?php 
include '../Includes/dbcon.php';
include '../Includes/session.php';


    $query = "SELECT * FROM lecturers Where Id = '$_SESSION[userId]'";

    $rs = $conn->query($query);
    $num = $rs->num_rows;
    $rrw = $rs->fetch_assoc();


?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <link href="img/logo/attnlg.png" rel="icon">
  <title>SCMS</title>
  <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
  <link href="css/ruang-admin.min.css" rel="stylesheet">
</head>

<body id="page-top">
  <div id="wrapper">
    <!-- Sidebar -->
   <?php include "Includes/sidebar.php";?>
    <!-- Sidebar -->
    <div id="content-wrapper" class="d-flex flex-column">
      <div id="content">
        <!-- TopBar -->
           <?php include "Includes/topbar.php";?>
        <!-- Topbar -->
        <!-- Container Fluid-->
        <div class="container-fluid" id="container-wrapper">
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Lecturer Dashboard</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
            </ol>
          </div>

          <div class="row mb-3">
          
          
          <!-- Students -->
          <?php 
// Get the assigned course for the logged-in lecturer
$query_lecture = mysqli_query($conn, "SELECT AssignedCourse FROM lecturers WHERE Id = '$_SESSION[userId]'");
$lecture_data = mysqli_fetch_assoc($query_lecture);
$assigned_course = $lecture_data['AssignedCourse'];

// Get the number of students enrolled in the same course
$query_students = mysqli_query($conn, "SELECT COUNT(*) AS student_count FROM students WHERE EnrolledCourse = '$assigned_course'");
$student_data = mysqli_fetch_assoc($query_students);
$students = $student_data['student_count'];
?>
<div class="col-xl-3 col-md-6 mb-4">
  <div class="card h-100">
    <div class="card-body">
      <div class="row no-gutters align-items-center">
        <div class="col mr-2">
          <div class="text-xs font-weight-bold text-uppercase mb-1">Students (Your Course)</div>
          <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800"><?php echo $students;?></div>
          <div class="mt-2 mb-0 text-muted text-xs">
            <!-- Additional Info Here -->
          </div>
        </div>
        <div class="col-auto">
          <i class="fas fa-users fa-2x text-info"></i>
        </div>
      </div>
    </div>
  </div>
</div>
            <!-- classes -->
            <?php 
// Get the assigned classes for the logged-in lecturer
$query_lecture = mysqli_query($conn, "SELECT AssignedClasses FROM lecturers WHERE Id = '$_SESSION[userId]'");
$lecture_data = mysqli_fetch_assoc($query_lecture);
$assigned_classes = $lecture_data['AssignedClasses'];

// Count the number of assigned classes
$class_count = !empty($assigned_classes) ? count(explode(',', $assigned_classes)) : 0;
?>
<div class="col-xl-3 col-md-6 mb-4">
  <div class="card h-100">
    <div class="card-body">
      <div class="row align-items-center">
        <div class="col mr-2">
          <div class="text-xs font-weight-bold text-uppercase mb-1">Modules (Assigned)</div>
          <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $class_count;?></div>
          <div class="mt-2 mb-0 text-muted text-xs">
            <!-- Additional Info Here -->
          </div>
        </div>
        <div class="col-auto">
          <i class="fas fa-chalkboard fa-2x text-primary"></i>
        </div>
      </div>
    </div>
  </div>
</div>


        </div>
        <!---Container Fluid-->
      </div>
      <!-- Footer -->
      <?php include 'includes/footer.php';?>
      <!-- Footer -->
    </div>
  </div>

  <!-- Scroll to top -->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <script src="../vendor/jquery/jquery.min.js"></script>
  <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="js/ruang-admin.min.js"></script>
  <script src="../vendor/chart.js/Chart.min.js"></script>
  <script src="js/demo/chart-area-demo.js"></script>  
</body>

</html>