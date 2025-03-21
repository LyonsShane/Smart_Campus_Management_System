<?php 
error_reporting(0);
include '../Includes/dbcon.php';
include '../Includes/session.php';

$query = "SELECT * FROM students Where Id = '$_SESSION[userId]'";

    $rs = $conn->query($query);
    $num = $rs->num_rows;
    $rrw = $rs->fetch_assoc();

    $courseQuery = mysqli_query($conn, "SELECT * FROM students WHERE Id = '$_SESSION[userId]'");
    $courseRow = mysqli_fetch_assoc($courseQuery);
    $studentCourse = $courseRow['EnrolledCourse'];
    $studentName = $courseRow['firstName']; 

$uploadDirectory = "../Students_Uploaded_Assignments/"; // Directory where the files will be uploaded

//------------------------SAVE--------------------------------------------------
if (isset($_POST['save'])) {
  $firstName = $_POST['firstName'];
  $lastName = $_POST['lastName'];
  $fullName = $firstName . ' ' . $lastName;
  $studentCourse = $_POST['CourseName'];
  $AssignmentName = $_POST['AssignmentName'];
  $AssignmentDoc = ''; // Initialize the AssignmentDoc variable

  // Check if a file is uploaded
  if (isset($_FILES['AssignmentDoc']) && $_FILES['AssignmentDoc']['error'] == 0) {
      
      $fileTmpPath = $_FILES['AssignmentDoc']['tmp_name'];
      $fileName = $_FILES['AssignmentDoc']['name'];
      $fileSize = $_FILES['AssignmentDoc']['size'];
      $fileType = $_FILES['AssignmentDoc']['type'];
      $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);

      // Generate a unique name for the file
      $newFileName = time() . "_" . $fileName;

      // Define allowed file types (optional)
      $allowedTypes = ['pdf', 'doc', 'docx', 'ppt', 'pptx']; // You can modify the allowed types based on your requirement
      if (in_array(strtolower($fileExtension), $allowedTypes)) {
          
          // Move the uploaded file to the desired directory
          $destinationPath = $uploadDirectory . $newFileName;
          if (move_uploaded_file($fileTmpPath, $destinationPath)) {
              // File successfully uploaded
              $AssignmentDoc = $destinationPath; // Store the file path in the database
          } else {
              $statusMsg = "<div id='statusMsg' class='alert alert-danger' style='margin-right:700px;'>File upload failed!</div>";
          }
      } else {
          $statusMsg = "<div id='statusMsg' class='alert alert-danger' style='margin-right:700px;'>Invalid file type. Only PDF, DOC, DOCX, PPT, PPTX are allowed!</div>";
      }
  }

  $SubmittedDate = date("Y-m-d");

  // Check if the assignment already exists
  $checkQuery = mysqli_query($conn, "SELECT * FROM uploadedassignments WHERE AssignmentName = '$AssignmentName'");
  if (mysqli_num_rows($checkQuery) > 0) { 
      $statusMsg = "<div id='statusMsg' class='alert alert-danger' style='margin-right:700px;'>This Assignment Already Exists!</div>";
  } else {
      // Insert the assignment into the database
      $query = mysqli_query($conn, "INSERT INTO uploadedassignments (StudentName, CourseName, AssignmentName, AssignmentDoc, SubmittedDate) VALUES ('$fullName','$studentCourse','$AssignmentName', '$AssignmentDoc', '$SubmittedDate')");
      if ($query) {
          $statusMsg = "<div id='statusMsg' class='alert alert-success' style='margin-right:700px;'>Assignment Uploaded Successfully!</div>";
      } else {
          $statusMsg = "<div id='statusMsg' class='alert alert-danger' style='margin-right:700px;'>An error Occurred!</div>";
      }
  }
}

//--------------------------------DELETE------------------------------------------------------------------
if (isset($_GET['Id']) && isset($_GET['action']) && $_GET['action'] == "delete") {
    $Id = $_GET['Id'];
    $query = mysqli_query($conn, "DELETE FROM uploadedassignments WHERE Id='$Id'");
    if ($query == TRUE) {
        echo "<script type = \"text/javascript\">
                window.location = (\"UploadAssignments.php\")
              </script>";  
    } else {
        $statusMsg = "<div id='statusMsg' class='alert alert-danger' style='margin-right:700px;'>An error Occurred!</div>"; 
    }
}

?>

<script>
    setTimeout(function() {
        var statusMsg = document.getElementById("statusMsg");
        if (statusMsg) {
          statusMsg.style.display = "none"; // Hide the message
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
            <h1 class="h3 mb-0 text-gray-800">Upload Assignments</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Assignments</li>
            </ol>
          </div>

          <div class="row">
            <div class="col-lg-12">
              <!-- Form Basic -->
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Upload a Assignment</h6>
                  <div id="statusMessage">
                    <?php echo $statusMsg; ?>
                </div>
                </div>
                <div class="card-body">
                  <form method="post" enctype="multipart/form-data">
                    <div class="form-group row mb-3">
                      <div class="col-xl-6">
                        <label class="form-control-label">Assignments Name<span class="text-danger ml-2">*</span></label>
                        <select required name="AssignmentName" class="form-control mb-3">
                        <option value="">--Select Assignment--</option>
                        <?php
                        $qry = "SELECT * FROM assignments 
                        WHERE CourseName = '$studentCourse' 
                        AND AssignmentName NOT IN 
                            (SELECT AssignmentName FROM uploadedassignments WHERE StudentName = '$fullName') 
                        ORDER BY Id ASC";
                
                        $result = $conn->query($qry);
                        while ($rows = $result->fetch_assoc()) {
                            $selected = ($row['AssignmentName'] == $rows['AssignmentName']) ? 'selected' : '';
                            echo '<option value="'.$rows['AssignmentName'].'" '.$selected.'>'.$rows['AssignmentName'].'</option>';
                        }
                        ?>
                    </select>
                        
                      </div>

                      <div class="col-xl-6">
                        <label class="form-control-label">Upload Assignment Document<span class="text-danger ml-2">*</span></label>
                        <input type="file" class="form-control" name="AssignmentDoc" id="exampleInputFirstName">
                      </div>
                    </div>

            


                    <!-- Add this hidden input for CourseName -->
                    <input type="hidden" name="CourseName" value="<?php echo $studentCourse; ?>">
                    <input type="hidden" name="firstName" value="<?php echo $courseRow['firstName']; ?>">
                    <input type="hidden" name="lastName" value="<?php echo $courseRow['lastName']; ?>">


                    <button type="submit" name="save" class="btn btn-primary">Save</button>
                  </form>
                </div>
              </div>

              <!-- Input Group -->
              <div class="row">
                <div class="col-lg-12">
                  <div class="card mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                      <h6 class="m-0 font-weight-bold text-primary">All Uploaded Assignments</h6>
                    </div>
                    <div class="table-responsive p-3">
                      <table class="table align-items-center table-flush table-hover" id="dataTableHover">
                        <thead class="thead-light">
                          <tr>
                            <th>#</th>
                            <th>Assignment Name</th>
                            <th>Assignment Doc</th>
                            <th>Submitted Date</th>
                            <th>Download</th>
                            <th>Delete</th>
                          </tr>
                        </thead>
                        <tbody>
                        <?php
$query = "SELECT * FROM uploadedassignments WHERE CourseName = '$studentCourse' ORDER BY ID ASC";
$rs = $conn->query($query);
$num = $rs->num_rows;
$sn = 0;
if ($num > 0) { 
    while ($rows = $rs->fetch_assoc()) {
        $sn = $sn + 1;
        $assignmentDocDisplay = substr($rows['AssignmentDoc'], 0, 50); // Limit to 50 characters
        if (strlen($rows['AssignmentDoc']) > 50) {
            $assignmentDocDisplay .= "..."; // Append '...' if the document path is longer than 50 characters
        }
        echo "
        <tr>
          <td>" . $sn . "</td>
          <td>" . $rows['AssignmentName'] . "</td>
          <td>" . $assignmentDocDisplay . "</td>
          <td>" . $rows['SubmittedDate'] . "</td>
          <td><a href='" . $rows['AssignmentDoc'] . "' download><i class='fa fa-download'></i> Download</a></td>
          <td><a href='?action=delete&Id=" . $rows['Id'] . "'><i class='fas fa-fw fa-trash'></i> Delete</a></td>
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
            <!-- Row -->
          </div>
          <!--- Container Fluid-->
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
        $('#dataTable').DataTable();
        $('#dataTableHover').DataTable();
      });
    </script>
</body>

</html>
