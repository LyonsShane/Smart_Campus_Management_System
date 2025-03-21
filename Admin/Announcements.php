
<?php 
error_reporting(0);
include '../Includes/dbcon.php';
include '../Includes/session.php';

//------------------------SAVE--------------------------------------------------

if(isset($_POST['save'])){
    
  $Subject = $_POST['Subject'];
  $Description = $_POST['Description'];

  // Fetch selected course names instead of IDs
  if (!empty($_POST['NotifiedCourse'])) {
      $courseIds = $_POST['NotifiedCourse']; // Array of selected course IDs
      $courseNames = [];

      foreach ($courseIds as $courseId) {
          $courseQuery = mysqli_query($conn, "SELECT CourseName FROM courses WHERE Id='$courseId'");
          $courseRow = mysqli_fetch_assoc($courseQuery);
          $courseNames[] = $courseRow['CourseName'];
      }

      // Convert array to comma-separated string
      $NotifiedCourse = implode(", ", $courseNames);
  } else {
      $NotifiedCourse = "";
  }

  $query = mysqli_query($conn, "INSERT INTO Announcements (Subject, NotifiedCourse, Description) 
  VALUES ('$Subject', '$NotifiedCourse', '$Description')");

  if ($query) {
      $statusMsg = "<div id='statusMsg' class='alert alert-success' style='margin-right:700px;'>Announcement Created Successfully!</div>";
  } else {
      $statusMsg = "<div id='statusMsg' class='alert alert-danger' style='margin-right:700px;'>An error Occurred!</div>";
  }
}

//--------------------EDIT------------------------------------------------------------

if (isset($_GET['Id']) && isset($_GET['action']) && $_GET['action'] == "edit") {
  $Id = $_GET['Id'];
  $query = mysqli_query($conn, "SELECT * FROM Announcements WHERE Id='$Id'");
  $row = mysqli_fetch_assoc($query);
}

if(isset($_POST['update'])){
    
  $Subject = $_POST['Subject'];
  $Description = $_POST['Description'];

  // Fetch selected course names instead of IDs
  if (!empty($_POST['NotifiedCourse'])) {
      $courseIds = $_POST['NotifiedCourse']; // Array of selected course IDs
      $courseNames = [];

      foreach ($courseIds as $courseId) {
          $courseQuery = mysqli_query($conn, "SELECT CourseName FROM courses WHERE Id='$courseId'");
          $courseRow = mysqli_fetch_assoc($courseQuery);
          $courseNames[] = $courseRow['CourseName'];
      }

      // Convert array to comma-separated string
      $NotifiedCourse = implode(", ", $courseNames);
  } else {
      $NotifiedCourse = "";
  }

  // Perform the update query
  $query = mysqli_query($conn, "UPDATE Announcements SET 
      Subject='$Subject', 
      NotifiedCourse='$NotifiedCourse', 
      Description='$Description' 
      WHERE Id='$Id'");

  if ($query) {
      echo "<script type='text/javascript'>
              window.location = ('Announcements.php');
            </script>"; 
  } else {
      $statusMsg = "<div id='statusMsg' class='alert alert-danger' style='margin-right:700px;'>An error Occurred!</div>";
  }
}

//--------------------------------DELETE------------------------------------------------------------------

if (isset($_GET['Id']) && isset($_GET['action']) && $_GET['action'] == "delete")
	{
        $Id= $_GET['Id'];

        $query = mysqli_query($conn,"DELETE FROM Announcements WHERE Id='$Id'");

        if ($query == TRUE) {
                
                 echo "<script type = \"text/javascript\">
                window.location = (\"Announcements.php\")
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
  <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-container--default .select2-selection--multiple {
        min-height: 38px;
        border: 1px solid #ced4da;
        padding: 5px;
    }
    .select2-container .select2-selection--multiple .select2-selection__choice {
        background-color:rgb(105, 116, 212);
        color: white;
        border-radius: 3px;
        padding: 3px 10px;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
        color: white !important;
    }
    
    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
        color: #ccc !important;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
    display: flex;
    align-items: center;
    gap: 4px; /* Adjust the gap between text and remover */
    padding: 3px 8px; /* Adjust padding */
}

.select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
    color: white !important; 
    border-right: none !important;
    padding: 0;
    margin-left: 2px; /* Reduce space */
    font-size: 14px; /* Adjust size if needed */
}

.select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
    color: white !important;
}

</style>

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
            <h1 class="h3 mb-0 text-gray-800">Announcements</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Announcements</li>
            </ol>
          </div>

          <div class="row">
            <div class="col-lg-12">
              <!-- Form Basic -->
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Create Announcement</h6>
                    <?php echo $statusMsg; ?>
                </div>
                <div class="card-body">
                  <form method="post">
                   <div class="form-group row mb-3">
                        <div class="col-xl-6">
                        <label class="form-control-label">Subject<span class="text-danger ml-2">*</span></label>
                        <input type="text" class="form-control" required name="Subject" value="<?php echo $row['Subject'];?>" id="exampleInputFirstName" >
  
                        </div>
                        <br>
                        <div class="col-xl-6">
                        <label class="form-control-label">Course Which Notified<span class="text-danger ml-2">*</span></label>
                        <select required name="NotifiedCourse[]" id="courseSelect" class="form-control mb-3" multiple>
                        <?php
                        $qry = "SELECT * FROM courses ORDER BY Id ASC";
                        $result = $conn->query($qry);

                        $selectedCourses = [];
                        if (isset($_GET['Id']) && isset($_GET['action']) && $_GET['action'] == "edit") {
                            $selectedCourses = explode(", ", $row['NotifiedCourse']); // Fetch stored names
                        }

                        while ($rows = $result->fetch_assoc()) {
                            $selected = in_array($rows['CourseName'], $selectedCourses) ? 'selected' : '';
                            echo '<option value="'.$rows['Id'].'" '.$selected.'>'.$rows['CourseName'].'</option>';
                        }
                        ?>
                      </select>

                        </div>
                    </div>
                     


                    <div class="form-group row mb-3">
                        <div class="col-xl-12">
                        <label class="form-control-label">Description<span class="text-danger ml-2">*</span></label>
                        <textarea class="form-control" required oninput="resizeTextarea(this)" 
                        name="Description" id="exampleInputFirstName"><?php echo htmlspecialchars($row['Description']); ?></textarea>
                        </div>
                            <script>
                              function resizeTextarea(el) {
                                el.style.height = "auto"; // Reset height
                                el.style.height = (el.scrollHeight) + "px"; // Set new height
                              }
                            </script>                    
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
    <h6 class="m-0 font-weight-bold text-primary">All Announcements</h6>
    <div class="ml-auto" style="width: 250px;">
        <input type="text" id="searchInput" class="form-control" placeholder="Search for Announcements...">
    </div>
</div>
                <div class="table-responsive p-3">
                  <table class="table align-items-center table-flush table-hover" id="dataTableHover">
                    <thead class="thead-light">
                      <tr>
                        <th>#</th>
                        <th>Subject</th>
                        <th>Notified Course</th>
                        <th>Description</th>
                        <th>Edit</th>
                        <th>Delete</th>
                      </tr>
                    </thead>
                   
                    <tbody>

                  <?php
                      $query = "SELECT * FROM Announcements ORDER BY ID ASC";
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
                                <td>".$rows['Subject']."</td>
                                <td>".$rows['NotifiedCourse']."</td>
                                <td>".$rows['Description']."</td>
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
<!-- jQuery (needed for Select2) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <!-- Select2 JS -->
  <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

  <!-- Page level custom scripts -->
  <script>
    $(document).ready(function () {
      $('#dataTable').DataTable(); // ID From dataTable 
      $('#dataTableHover').DataTable(); // ID From dataTable with Hover
    });
  </script>


<script>
    $(document).ready(function() {
        $('#courseSelect').select2({
            placeholder: "Select courses...",
            allowClear: true,
            tags: true
        });
    });
</script>

<script>
    document.getElementById('searchInput').addEventListener('keyup', function() {
      let filter = this.value.toUpperCase();
      let rows = document.querySelector("tbody").rows;
      for (let row of rows) {
        let text = row.innerText.toUpperCase();
        row.style.display = text.includes(filter) ? "" : "none";
      }
    });
  </script>
</body>

</html>