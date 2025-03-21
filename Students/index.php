
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
            <h1 class="h3 mb-0 text-gray-800">Student Dashboard</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
            </ol>
          </div>

          <div class="row mb-3">
          
          
          <!-- Enrolled Courses -->
          <?php 
// Get the number of courses the logged-in student is enrolled in
$query_courses = mysqli_query($conn, 
    "SELECT COUNT(DISTINCT EnrolledCourse) AS course_count 
     FROM students 
     WHERE Id = '$_SESSION[userId]'"
);
$course_data = mysqli_fetch_assoc($query_courses);
$course_count = $course_data['course_count'];
?>
<div class="col-xl-3 col-md-6 mb-4">
  <div class="card h-100">
    <div class="card-body">
      <div class="row no-gutters align-items-center">
        <div class="col mr-2">
          <div class="text-xs font-weight-bold text-uppercase mb-1">Courses Enrolled</div>
          <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800"><?php echo $course_count; ?></div>
          <div class="mt-2 mb-0 text-muted text-xs">
            <!-- Additional Info Here -->
          </div>
        </div>
        <div class="col-auto">
          <i class="fa fa-book fa-2x text-success"></i>
        </div>
      </div>
    </div>
  </div>
</div>
            <!-- scheduled classes -->
            <?php 
// Get the courses the logged-in student is enrolled in
$query_courses = mysqli_query($conn, 
    "SELECT EnrolledCourse FROM students WHERE Id = '$_SESSION[userId]'"
);
$enrolled_courses = [];
while ($row = mysqli_fetch_assoc($query_courses)) {
    $enrolled_courses[] = "'" . $row['EnrolledCourse'] . "'";
}

// If student is enrolled in any courses
$scheduled_class_count = 0;
if (!empty($enrolled_courses)) {
    $course_list = implode(",", $enrolled_courses);

    // Get the number of scheduled classes for those courses
    $query_scheduled_classes = mysqli_query($conn, 
        "SELECT COUNT(*) AS class_count FROM scheduleclasses 
         WHERE Course IN ($course_list)"
    );
    $class_data = mysqli_fetch_assoc($query_scheduled_classes);
    $scheduled_class_count = $class_data['class_count'];
}
?>
<div class="col-xl-3 col-md-6 mb-4">
  <div class="card h-100">
    <div class="card-body">
      <div class="row align-items-center">
        <div class="col mr-2">
          <div class="text-xs font-weight-bold text-uppercase mb-1">Scheduled Classes</div>
          <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $scheduled_class_count; ?></div>
          <div class="mt-2 mb-0 text-muted text-xs">
            <!-- Additional Info Here -->
          </div>
        </div>
        <div class="col-auto">
          <i class="fas fa-calendar-alt fa-2x text-warning"></i>
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