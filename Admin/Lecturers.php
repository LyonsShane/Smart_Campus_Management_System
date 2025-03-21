
<?php 
error_reporting(0);
include '../Includes/dbcon.php';
include '../Includes/session.php';

//------------------------SAVE--------------------------------------------------

if(isset($_POST['save'])){
    
  $firstName=$_POST['firstName'];
  $lastName=$_POST['lastName'];
  $emailAddress=$_POST['emailAddress'];
  $phoneNo=$_POST['phoneNo'];
  $Address=$_POST['Address'];
  $Gender=$_POST['Gender'];
  $AssignedClassesArray = $_POST['AssignedClasses']; // Get selected class IDs
  $AssignedClassesNames = [];

foreach ($AssignedClassesArray as $classId) {
    $classQuery = mysqli_query($conn, "SELECT ClassName FROM classes WHERE Id='$classId'");
    $classRow = mysqli_fetch_assoc($classQuery);
    $AssignedClassesNames[] = $classRow['ClassName'];
}

$AssignedClasses = implode(', ', $AssignedClassesNames); // Convert array to comma-separated string

  $dateCreated = date("Y-m-d");
  $AssignedCourse = $_POST['AssignedCourse'];
   
    $query=mysqli_query($conn,"select * from lecturers where emailAddress ='$emailAddress'");
    $ret=mysqli_fetch_array($query);

    $sampPass = "Lecturer123";
    $sampPass_2 = md5($sampPass);

    if($ret > 0){ 

        $statusMsg = "<div id='statusMsg' class='alert alert-danger' style='margin-right:700px;'>This Email Address Already Exists!</div>";
    }
    else{

    $query=mysqli_query($conn,"INSERT into lecturers(firstName,lastName,emailAddress,password,phoneNo,Address,Gender,AssignedCourse,AssignedClasses,dateCreated) 
    value('$firstName','$lastName','$emailAddress','$sampPass_2','$phoneNo','$Address','$Gender','$AssignedCourse','$AssignedClasses','$dateCreated')");

    if ($query) {
                
                $statusMsg = "<div id='statusMsg' class='alert alert-success'  style='margin-right:700px;'>Lecturer Created Successfully!</div>";
    }
            else
            {
                $statusMsg = "<div id='statusMsg' class='alert alert-danger' style='margin-right:700px;'>An error Occurred!</div>";
            }
    }
  }


//--------------------EDIT------------------------------------------------------------

 if (isset($_GET['Id']) && isset($_GET['action']) && $_GET['action'] == "edit")
	{
        $Id= $_GET['Id'];

        $query=mysqli_query($conn,"select * from lecturers where Id ='$Id'");
        $row=mysqli_fetch_array($query);

        //------------UPDATE-----------------------------

        if(isset($_POST['update'])){
    
             $firstName=$_POST['firstName'];
              $lastName=$_POST['lastName'];
              $emailAddress=$_POST['emailAddress'];
              $phoneNo=$_POST['phoneNo'];
              $Address=$_POST['Address'];
              $Gender=$_POST['Gender'];
              $AssignedClassesArray = $_POST['AssignedClasses'];
$AssignedClassesNames = [];

foreach ($AssignedClassesArray as $classId) {
    $classQuery = mysqli_query($conn, "SELECT ClassName FROM classes WHERE Id='$classId'");
    $classRow = mysqli_fetch_assoc($classQuery);
    $AssignedClassesNames[] = $classRow['ClassName'];
}

$AssignedClasses = implode(', ', $AssignedClassesNames); // Convert array to string

              $dateCreated = date("Y-m-d");
              $AssignedCourse=$_POST['AssignedCourse'];

    $query=mysqli_query($conn,"update lecturers set firstName='$firstName', lastName='$lastName',
    emailAddress='$emailAddress',phoneNo='$phoneNo', Address='$Address', Gender='$Gender', AssignedCourse='$AssignedCourse', AssignedClasses='$AssignedClasses'
    where Id='$Id'");
            if ($query) {
                
                echo "<script type = \"text/javascript\">
                window.location = (\"Lecturers.php\")
                </script>"; 
            }
            else
            {
                $statusMsg = "<div id='statusMsg' class='alert alert-danger' style='margin-right:700px;'>An error Occurred!</div>";
            }
        }
    }


//--------------------------------DELETE------------------------------------------------------------------

if (isset($_GET['Id']) && isset($_GET['action']) && $_GET['action'] == "delete")
	{
        $Id= $_GET['Id'];

        $query = mysqli_query($conn,"DELETE FROM lecturers WHERE Id='$Id'");

        if ($query == TRUE) {
                
                 echo "<script type = \"text/javascript\">
                window.location = (\"Lecturers.php\")
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
  <!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
<style>
/* Match Select2 styling with Bootstrap inputs */
.select2-container .select2-selection--multiple {
    height: calc(3rem + 2px) !important; /* Match input height */
    border: 1px solid #d1d3e2 !important; /* Match border */
    border-radius: 4px !important; /* Rounded corners */
    padding: 4px 6px !important; /* Padding similar to inputs */
}

.select2-container--default .select2-selection--multiple .select2-selection__choice {
    background-color: #4e73df !important; /* Match theme color */
    color: #fff !important;
    border-radius: 4px;
    padding: 3px 6px;
}

.select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
    color: #fff !important;
    margin-right: 4px;
}

/* Ensure consistent width */
.select2-container {
    width: 100% !important;
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
            <h1 class="h3 mb-0 text-gray-800">Lecturers</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Lecturers</li>
            </ol>
          </div>

          <div class="row">
            <div class="col-lg-12">
              <!-- Form Basic -->
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Create Lecturer</h6>
                    <?php echo $statusMsg; ?>
                </div>
                <div class="card-body">
                  <form method="post">
                   <div class="form-group row mb-3">
                        <div class="col-xl-6">
                        <label class="form-control-label">Firstname<span class="text-danger ml-2">*</span></label>
                        <input type="text" class="form-control" required name="firstName" value="<?php echo $row['firstName'];?>" id="exampleInputFirstName">
                        </div>
                        <br>
                        <div class="col-xl-6">
                        <label class="form-control-label">Lastname<span class="text-danger ml-2">*</span></label>
                      <input type="text" class="form-control" required name="lastName" value="<?php echo $row['lastName'];?>" id="exampleInputFirstName" >
                        </div>
                    </div>
                     <div class="form-group row mb-3">
                        <div class="col-xl-6">
                        <label class="form-control-label">Email Address<span class="text-danger ml-2">*</span></label>
                        <input type="email" class="form-control" required name="emailAddress" value="<?php echo $row['emailAddress'];?>" id="exampleInputFirstName" >
                        </div>
                        <br>
                        <div class="col-xl-6">
                        <label class="form-control-label">Phone No<span class="text-danger ml-2">*</span></label>
                      <input type="text" class="form-control" name="phoneNo" value="<?php echo $row['phoneNo'];?>" id="exampleInputFirstName" >
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <div class="col-xl-6">
                        <label class="form-control-label">Address<span class="text-danger ml-2">*</span></label>
                        <input type="text" class="form-control" required name="Address" value="<?php echo $row['Address'];?>" id="exampleInputFirstName" >
                        </div>
                        <br>
                        <div class="col-xl-6">
                        <label class="form-control-label">Gender<span class="text-danger ml-2">*</span></label>
                        <select required name="Gender" class="form-control">
                        <option value="" disabled <?php if(empty($row['Gender'])) echo 'selected'; ?>>--Select Gender--</option>
                        <option value="Male" <?php if($row['Gender'] == 'Male') echo 'selected'; ?>>Male</option>
                        <option value="Female" <?php if($row['Gender'] == 'Female') echo 'selected'; ?>>Female</option>
                    </select>


                      
                        </div>
                    </div>

                    <div class="form-group row mb-3">

                    <div class="col-xl-6">
                        <label class="form-control-label">Select Assigned Course<span class="text-danger ml-2">*</span></label>
                        <select required name="AssignedCourse" class="form-control mb-3">
                        <option value="">--Select Course--</option>
                        <?php
                        $qry = "SELECT * FROM courses ORDER BY Id ASC";
                        $result = $conn->query($qry);
                        while ($rows = $result->fetch_assoc()) {
                            $selected = ($row['AssignedCourse'] == $rows['CourseName']) ? 'selected' : '';
                            echo '<option value="'.$rows['CourseName'].'" '.$selected.'>'.$rows['CourseName'].'</option>';
                        }
                        ?>
                    </select>

                    </div>

                        <div class="col-xl-6">
                        <label class="form-control-label">Select Assigned Modules<span class="text-danger ml-2">*</span></label>
                        <br>
                        <select required name="AssignedClasses[]" id="assignedClasses" class="form-control mb-3" multiple>
                            <?php
                            $qry = "SELECT * FROM classes ORDER BY ClassName ASC";
                            $result = $conn->query($qry);
                            while ($rows = $result->fetch_assoc()) {
                                echo $selectedClasses = explode(', ', $row['AssignedClasses']); // Convert stored names back to array
                                echo '<option value="'.$rows['Id'].'" '.(in_array($rows['ClassName'], $selectedClasses) ? 'selected' : '').'>'.$rows['ClassName'].'</option>';
                            }
                            ?>
                        </select>

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
    <h6 class="m-0 font-weight-bold text-primary">All Lecturers</h6>
    <div class="ml-auto" style="width: 250px;">
        <input type="text" id="searchInput" class="form-control" placeholder="Search for lecturers...">
    </div>
</div>

                <div class="table-responsive p-3">
                  <table class="table align-items-center table-flush table-hover" id="dataTableHover">
                    <thead class="thead-light">
                      <tr>
                        <th>#</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email Address</th>
                        <th>Phone No</th>
                        <th>Address</th>
                        <th>Gender</th>
                        <th>Assigned Course</th>
                        <th>Assigned Modules</th>
                        <th>Date Created</th>
                        <th>Edit</th>
                        <th>Delete</th>
                      </tr>
                    </thead>
                   
                    <tbody>

                  <?php
                      $query = "SELECT * FROM lecturers ORDER BY ID ASC";
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
                                <td>".$rows['firstName']."</td>
                                <td>".$rows['lastName']."</td>
                                <td>".$rows['emailAddress']."</td>
                                <td>".$rows['phoneNo']."</td>
                                <td>".$rows['Address']."</td>
                                <td>".$rows['Gender']."</td>
                                <td>".$rows['AssignedCourse']."</td>
                                <td>".$rows['AssignedClasses']."</td>
                                <td>".$rows['dateCreated']."</td>
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
<!-- jQuery & Select2 -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    $('#assignedClasses').select2({
        placeholder: "Select Modules...",
        allowClear: true
    });
});
</script>


  <!-- Page level custom scripts -->
  <script>
    $(document).ready(function () {
      $('#dataTable').DataTable(); // ID From dataTable 
      $('#dataTableHover').DataTable(); // ID From dataTable with Hover
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