
<?php 
error_reporting(0);
include '../Includes/dbcon.php';
include '../Includes/session.php';

$query = "SELECT * FROM students Where Id = '$_SESSION[userId]'";

    $rs = $conn->query($query);
    $num = $rs->num_rows;
    $rrw = $rs->fetch_assoc();


$courseQuery = mysqli_query($conn, "SELECT EnrolledCourse FROM students WHERE Id = '$_SESSION[userId]'");
$courseRow = mysqli_fetch_assoc($courseQuery);
$studentCourse = $courseRow['EnrolledCourse']; 

// You might want to add a check to ensure the course was found
if (!$studentCourse) {
    // Handle the case where no course is found - perhaps set a default or error state
    $studentCourse = ''; // Empty string will return no results rather than all results
}

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
<?php include 'includes/title.php';?>
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

		<div class="container-fluid" id="container-wrapper">		
		<div class="col-xl-12" align="left">
						  
        <h1 class="h3 mb-0 text-gray-800">Notifications</h1>
		<br>
							
		<div class="notification-container">
        <?php 
        // Query to fetch all packages for the current vendor
        $query1 = mysqli_query($conn, "SELECT * FROM announcements WHERE NotifiedCourse = '$studentCourse'");
        // Check if there are any packages
        if (mysqli_num_rows($query1) > 0) {
            while ($notification = mysqli_fetch_assoc($query1)) {
        ?>
            <div class="notification-card">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col-md-4 mb-2">
                                <div class="text-s font-weight-bold  text-gray-dark mb-1">Subject</div>
                                <div class="h6 mb-0  text-gray-800"><?php echo htmlspecialchars($notification['Subject']); ?></div>
                            </div>
							
						<br>
						<div class="row no-gutters align-items-center">
						<div class="col-md-12 mb-2">
                                <div class="text-s font-weight-bold  text-gray-dark mb-1">Description</div>
                                <div class="h6 mb-0  text-gray-800"><?php echo htmlspecialchars($notification['Description']); ?></div>
                            </div>
						</div>
                    </div>
                </div>
            </div>
		</div>
        <?php 
            }
        } else {
            // Display a message if no packages are found
        ?>
            <div class="notification-card">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="text-center text-muted">No notifications available</div>
                    </div>
                </div>
            </div>
        <?php 
        } 
        ?>
    
    
    <style>
        .notification-container {
            display: flex;
            flex-direction: column;
            gap: 20px; /* Adds space between notification cards */
            width: 100%;
        }
        
        .notification-card {
            width: 100%;
        }
        
        .notification-card .card-body {
            padding: 1rem;
        }
        
        @media (max-width: 768px) {
            .notification-card .row {
                flex-direction: column;
            }
            
            .notification-card .col-md-4 {
                margin-bottom: 10px;
                text-align: left;
            }
        }
    </style>
</div>
			</div>
					
          </div>
    </div>
      <!-- Footer -->
       <?php include "Includes/footer.php";?>
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
   <!-- Page level plugins -->
  <script src="../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../vendor/datatables/dataTables.bootstrap4.min.js"></script>

  <!-- Page level custom scripts -->
  <script>
    $(document).ready(function () {
      $('#dataTable').DataTable(); // ID From dataTable 
      $('#dataTableHover').DataTable(); // ID From dataTable with Hover
    });
  </script>
</body>

</html>