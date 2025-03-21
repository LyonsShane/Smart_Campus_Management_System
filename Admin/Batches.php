
<?php 
error_reporting(0);
include '../Includes/dbcon.php';
include '../Includes/session.php';

//------------------------SAVE--------------------------------------------------
if(isset($_POST['save'])){
  $CourseId = mysqli_real_escape_string($conn, $_POST['Course']);
  $BatchName = mysqli_real_escape_string($conn, $_POST['BatchName']);

  // Fetch Course Name
  $courseQuery = mysqli_query($conn, "SELECT CourseName FROM courses WHERE Id = '$CourseId'");
if ($courseRow = mysqli_fetch_assoc($courseQuery)) {
    $CourseName = $courseRow['CourseName'];
} else {
    $CourseName = ""; // Handle cases where no course is found
}

  // Check if the batch already exists
  $checkQuery = mysqli_query($conn, "SELECT * FROM batches WHERE Course = '$CourseName' AND BatchName = '$BatchName'");
  if (mysqli_num_rows($checkQuery) > 0) { 
      $statusMsg = "<div id='statusMsg' class='alert alert-danger' style='margin-right:700px;'>This Batch Already Exists!</div>";
  } else {
      // Insert into database with CourseName instead of Id
      $query = mysqli_query($conn, "INSERT INTO batches (Course, BatchName) VALUES ('$CourseName', '$BatchName')");

      if ($query) {
          $statusMsg = "<div id='statusMsg' class='alert alert-success' style='margin-right:700px;'>Batch Created Successfully!</div>";
      } else {
          $statusMsg = "<div id='statusMsg' class='alert alert-danger' style='margin-right:700px;'>An error Occurred!</div>";
      }
  }
}


//--------------------EDIT------------------------------------------------------------

 if (isset($_GET['Id']) && isset($_GET['action']) && $_GET['action'] == "edit")
	{
        $Id= $_GET['Id'];

        $query=mysqli_query($conn,"select * from batches where Id ='$Id'");
        $row=mysqli_fetch_array($query);

        //------------UPDATE-----------------------------

        if(isset($_POST['update'])){
          $CourseId = $_POST['Course'];
          $BatchName = $_POST['BatchName'];
      
          // Fetch Course Name
          $courseQuery = mysqli_query($conn, "SELECT CourseName FROM courses WHERE Id = '$CourseId'");
          $courseRow = mysqli_fetch_assoc($courseQuery);
          $CourseName = $courseRow['CourseName']; // Get course name
      
          $query = mysqli_query($conn, "UPDATE batches SET Course='$CourseName', BatchName = '$BatchName' WHERE Id='$Id'");
      
          if ($query) {
              echo "<script type='text/javascript'>
                      window.location = ('Batches.php')
                    </script>"; 
          } else {
              $statusMsg = "<div id='statusMsg' class='alert alert-danger' style='margin-right:700px;'>An error Occurred!</div>";
          }
      }
      
    }


//--------------------------------DELETE------------------------------------------------------------------

  if (isset($_GET['Id']) && isset($_GET['action']) && $_GET['action'] == "delete")
	{
        $Id= $_GET['Id'];

        $query = mysqli_query($conn,"DELETE FROM batches WHERE Id='$Id'");

        if ($query == TRUE) {

                echo "<script type = \"text/javascript\">
                window.location = (\"Batches.php\")
                </script>";  
        }
        else{

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
            <h1 class="h3 mb-0 text-gray-800">Batches</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Batches</li>
            </ol>
          </div>

          <div class="row">
            <div class="col-lg-12">
              <!-- Form Basic -->
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Create a Batch</h6>
                  <div id="statusMessage">
                    <?php echo $statusMsg; ?>
                </div>
                </div>
                <div class="card-body">
                  <form method="post">
                    <div class="form-group row mb-3">

                    <div class="col-xl-6">
                      <label class="form-control-label">Select Course<span class="text-danger ml-2">*</span></label>
                      <?php
                      $qry = "SELECT * FROM courses ORDER BY Id ASC";
                      $result = $conn->query($qry);
                      
                      if ($result->num_rows > 0) {
                          echo '<select required name="Course" class="form-control mb-3">';
                          echo '<option value="">--Select Course--</option>';
                          
                          while ($rows = $result->fetch_assoc()) {
                              $selected = (isset($row['Course']) && $row['Course'] == $rows['CourseName']) ? "selected" : "";
                              echo '<option value="'.$rows['Id'].'" '.$selected.'>'.$rows['CourseName'].'</option>';
                          }
                          
                          echo '</select>';
                      }
                      ?>
                  </div>

                 <br>

                    <div class="col-xl-6">
                        <label class="form-control-label">Batch Name<span class="text-danger ml-2">*</span></label>
                        
                        <input type="text" class="form-control" name="BatchName" value="<?php echo $row['BatchName'];?>" id="exampleInputFirstName" placeholder="">
                        </div>


                        
                    </div>
                      <?php
                    if (isset($Id))
                    {
                    ?>
                    <button type="submit" name="update" class="btn btn-warning">Update</button>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <?php
                    } else {           
                    ?>
                    <button type="submit" name="save" class="btn btn-primary">Save</button>
                    <?php
                    }         
                    ?>
                  </form>
                </div>
              </div>

              <!-- Input Group -->
                 <div class="row">
              <div class="col-lg-12">
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">All Batches</h6>
                </div>
                <div class="table-responsive p-3">
                  <table class="table align-items-center table-flush table-hover" id="dataTableHover">
                    <thead class="thead-light">
                      <tr>
                        <th>#</th>
                        <th>Course</th>
                        <th>Batch Name</th>
                        <th>Edit</th>
                        <th>Delete</th>
                      </tr>
                    </thead>
                  
                    <tbody>

                  <?php
                      $query = "SELECT * FROM batches ORDER BY ID ASC";

                      $rs = $conn->query($query);
                      $num = $rs->num_rows;
                      $sn=0;
                      if($num > 0)
                      { 
                        while ($rows = $rs->fetch_assoc())
                          {
                             
                             $sn = $sn + 1;
                            echo"
                              <tr>
                                <td>".$sn."</td>
                                <td>".$rows['Course']."</td>
                                <td>".$rows['BatchName']."</td>
                                <td><a href='?action=edit&Id=".$rows['Id']."'><i class='fas fa-fw fa-edit'></i>Edit</a></td>
                                <td><a href='?action=delete&Id=".$rows['Id']."'><i class='fas fa-fw fa-trash'></i>Delete</a></td>
                              </tr>";
                          }
                      }
                      else
                      {
                           echo   
                           "<div class='alert alert-danger' role='alert'>
                            No Record Found!
                            </div>";
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