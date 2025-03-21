<?php 
error_reporting(0);
include '../Includes/dbcon.php';
include '../Includes/session.php';

if (isset($_GET['Id']) && isset($_GET['action']) && $_GET['action'] == "request") {
  $Id = $_GET['Id'];
  $userId = $_SESSION['userId']; // Assuming the user is logged in

  // Update the status and set BookedBy
  $query = mysqli_query($conn, "UPDATE resources SET Status='Pending', BookedBy='$userId' WHERE Id='$Id'");

  if ($query) {
      $statusMsg = "<div class='alert alert-success' id='statusMessage' style='margin-right:700px;'>Your resource booking request was successfully sent!</div>";
  } else {
      $statusMsg = "<div class='alert alert-danger' id='statusMessage' style='margin-right:700px;'>An error occurred while processing your request.</div>";
  }
}

// Handle "Release" action to change status to "Available"
if (isset($_GET['releaseId']) && $_GET['action'] == "release") {
  $releaseId = $_GET['releaseId'];

  // Update the status to "Available" and clear the BookedBy
  $releaseQuery = mysqli_query($conn, "UPDATE resources SET Status='Available', BookedBy=NULL WHERE Id='$releaseId'");

  if ($releaseQuery) {
      $statusMsg = "<div class='alert alert-success' id='statusMessage' style='margin-right:700px;'>The resource has been released successfully!</div>";
  } else {
      $statusMsg = "<div class='alert alert-danger' id='statusMessage' style='margin-right:700px;'>An error occurred while releasing the resource.</div>";
  }
}

?>

<script>
    setTimeout(function() {
        var statusMessage = document.getElementById("statusMessage");
        if (statusMessage) {
            statusMessage.style.display = "none"; // Hide the message
        }
    }, 3000); // Hide after 3 seconds
</script>

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

<style>
    .status.available {
        color: green;
    }

    .status.pending {
        color: orange;
    }

    .status.booked {
        color: red;
    }
</style>

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
            <h1 class="h3 mb-0 text-gray-800">Resources Booking</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Resources Booking</li>
            </ol>
          </div>

          <div class="row">
            <div class="col-lg-12">
              <!-- Input Group -->
              <div class="row">
                <div class="col-lg-12">
                  <div class="card mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                      <h6 class="m-0 font-weight-bold text-primary">All Resources</h6>
                    </div>
                    <?php echo $statusMsg; ?>
                    <div class="table-responsive p-3">
                      <table class="table align-items-center table-flush table-hover" id="dataTableHover">
                        <thead class="thead-light">
                          <tr>
                            <th>#</th>
                            <th>Resource Name</th>
                            <th>Status</th>
                            <th>Booked By</th>
                            <th>Actions</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                              $query = "SELECT resources.*, lecturers.firstName AS BookedBy 
                                        FROM resources 
                                        LEFT JOIN lecturers ON resources.BookedBy = lecturers.Id 
                                        ORDER BY resources.Id ASC";

                              $rs = $conn->query($query);
                              $num = $rs->num_rows;
                              $sn = 0;

                              if($num > 0) { 
                                  while ($rows = $rs->fetch_assoc()) {
                                      $sn = $sn + 1;
                                      $statusClass = strtolower($rows['Status']);
                                      
                                      // Display the resource details
                                      echo "<tr>
                                              <td>" . $sn . "</td>
                                              <td>" . $rows['ResourceName'] . "</td>
                                              <td class='status " . $statusClass . "'>
                                                  " . $rows['Status'] . "
                                              </td>";

                                      // Show the "Booked By" only for Pending or Booked status
                                      if ($rows['Status'] == 'Available') {
                                          echo "<td>-</td>"; // Hide Booked By if Available
                                      } else {
                                          echo "<td>" . ($rows['BookedBy'] ? $rows['BookedBy'] : '-') . "</td>";
                                      }
                                      
                                      // Show the "Request" button only if the status is "Available"
                                      echo "<td>";
                                      if ($rows['Status'] == 'Available') {
                                          echo "<a href='?action=request&Id=" . $rows['Id'] . "' onclick='return confirm(\"Are you sure you want to request this resource?\");'class='btn btn-primary'>
                                                  <i class='fa fa-mouse-pointer'></i> Request
                                                </a>";
                                      } elseif ($rows['Status'] == 'Booked') {
                                          // Show the "Release" button only if the status is "Booked"
                                          echo "<a href='?action=release&releaseId=" . $rows['Id'] . "' onclick='return confirm(\"Are you sure you want to release this resource?\");' class='btn btn-warning'>
                                                  <i class='fa fa-arrow-circle-right'></i> Release
                                                </a>";
                                      } else {
                                          echo "-";
                                      }
                                      echo "</td>
                                          </tr>";
                                  }
                              } else {
                                  echo "<div class='alert alert-danger' role='alert'>No Record Found!</div>";
                              }
                          ?>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!--Row-->
          </div>
          <!---Container Fluid-->
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
