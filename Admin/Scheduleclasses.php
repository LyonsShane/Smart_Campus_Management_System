
<?php 
error_reporting(0);
include '../Includes/dbcon.php';
include '../Includes/session.php';

//------------------------SAVE--------------------------------------------------

if(isset($_POST['save'])){
    
  $Course=$_POST['Course'];
  $Batch=$_POST['Batch'];
  $Class=$_POST['Class'];
  $Lecturer=$_POST['Lecturer'];
  $Date=$_POST['Date'];
  $Time=$_POST['Time'];
  $LectureHall=$_POST['LectureHall'];
   

    $query=mysqli_query($conn,"INSERT into scheduleclasses(Course,Batch,Class,Lecturer,Date,Time,LectureHall) 
    value('$Course','$Batch','$Class','$Lecturer','$Date','$Time','$LectureHall')");

    if ($query) {
                
                $statusMessage = "<div id='statusMessage' class='alert alert-success'  style='margin-right:700px;'>Class Scheduled Successfully!</div>";
    }
            else
            {
                $statusMessage = "<div id='statusMessage' class='alert alert-danger' style='margin-right:700px;'>An error Occurred!</div>";
            }
    }

//--------------------EDIT------------------------------------------------------------

 if (isset($_GET['Id']) && isset($_GET['action']) && $_GET['action'] == "edit")
	{
        $Id= $_GET['Id'];

        $query=mysqli_query($conn,"select * from scheduleclasses where Id ='$Id'");
        $row=mysqli_fetch_array($query);

        if(isset($_POST['update'])){
    
          $Course=$_POST['Course'];
          $Batch=$_POST['Batch'];
          $Class=$_POST['Class'];
          $Lecturer=$_POST['Lecturer'];
          $Date=$_POST['Date'];
          $Time=$_POST['Time'];
          $LectureHall=$_POST['LectureHall'];


    $query=mysqli_query($conn,"update scheduleclasses set Course='$Course', Batch='$Batch',
    Class='$Class',Lecturer='$Lecturer', Date='$Date', Time='$Time', LectureHall='$LectureHall'
    where Id='$Id'");
            if ($query) {
                
                echo "<script type = \"text/javascript\">
                window.location = (\"Scheduleclasses.php\")
                </script>"; 
            }
            else
            {
                $statusMessage = "<div id='statusMessage' class='alert alert-danger' style='margin-right:700px;'>An error Occurred!</div>";
            }
        }
    }


//--------------------------------DELETE------------------------------------------------------------------

if (isset($_GET['Id']) && isset($_GET['action']) && $_GET['action'] == "delete")
	{
        $Id= $_GET['Id'];

        $query = mysqli_query($conn,"DELETE FROM scheduleclasses WHERE Id='$Id'");

        if ($query == TRUE) {
                
                 echo "<script type = \"text/javascript\">
                window.location = (\"Scheduleclasses.php\")
                </script>"; 
            
        }
        else{

            $statusMessage = "<div id='statusMessage' class='alert alert-danger' style='margin-right:700px;'>An error Occurred!</div>"; 
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
  <!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />


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
            <h1 class="h3 mb-0 text-gray-800">Schedule Classes</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Schedule Classes</li>
            </ol>
          </div>

          <div class="row">
            <div class="col-lg-12">
              <!-- Form Basic -->
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Create Schedule Class</h6>
                    <?php echo $statusMessage; ?>
                </div>
                <div class="card-body">
                  <form method="post">
                   <div class="form-group row mb-3">
                        <div class="col-xl-6">
                        <label class="form-control-label">Course<span class="text-danger ml-2">*</span></label>
                        <select required name="Course" class="form-control mb-3">
                            <option value="">--Select Course--</option>
                            <?php
                            $qry = "SELECT * FROM courses ORDER BY Id ASC";
                            $result = $conn->query($qry);
                            while ($rows = $result->fetch_assoc()) {
                                $selected = ($row['Course'] == $rows['CourseName']) ? 'selected' : ''; 
                                echo '<option value="'.$rows['CourseName'].'" '.$selected.'>'.$rows['CourseName'].'</option>';
                            }
                            ?>
                        </select>
  
                        </div>
                        <br>
                        <div class="col-xl-6">
                        <label class="form-control-label">Batch<span class="text-danger ml-2">*</span></label>
                        <select required name="Batch" class="form-control mb-3">
                            <option value="">--Select Batch--</option>
                            <?php
                            $qry = "SELECT * FROM batches ORDER BY Id ASC";
                            $result = $conn->query($qry);
                            while ($rows = $result->fetch_assoc()) {
                                $selected = ($row['Batch'] == $rows['BatchName']) ? 'selected' : ''; 
                                echo '<option value="'.$rows['BatchName'].'" '.$selected.'>'.$rows['BatchName'].'</option>';
                            }
                            ?>
                        </select>

                        </div>
                    </div>
                     <div class="form-group row mb-3">
                        <div class="col-xl-6">
                        <label class="form-control-label">Module<span class="text-danger ml-2">*</span></label>
                        <select required name="Class" class="form-control mb-3">
                            <option value="">--Select Module--</option>
                            <?php
                            $qry = "SELECT * FROM classes ORDER BY Id ASC";
                            $result = $conn->query($qry);
                            while ($rows = $result->fetch_assoc()) {
                                $selected = ($row['Class'] == $rows['ClassName']) ? 'selected' : ''; 
                                echo '<option value="'.$rows['ClassName'].'" '.$selected.'>'.$rows['ClassName'].'</option>';
                            }
                            ?>
                        </select>

                        </div>
                        <br>
                        <div class="col-xl-6">
                        <label class="form-control-label">Lecturer<span class="text-danger ml-2">*</span></label>
                        <select required name="Lecturer" class="form-control mb-3">
                            <option value="">--Select Lecturer--</option>
                            <?php
                            $qry = "SELECT * FROM lecturers ORDER BY Id ASC";
                            $result = $conn->query($qry);
                            while ($rows = $result->fetch_assoc()) {
                                $selected = ($row['Lecturer'] == $rows['firstName']) ? 'selected' : ''; 
                                echo '<option value="'.$rows['firstName'].'" '.$selected.'>'.$rows['firstName'].'</option>';
                            }
                            ?>
                        </select>

                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <div class="col-xl-6">
                        <label class="form-control-label">Date<span class="text-danger ml-2">*</span></label>
                        <input type="Date" class="form-control" required name="Date" value="<?php echo $row['Date'];?>" id="exampleInputFirstName" >
                        </div>
                        <br>
                        <div class="col-xl-6">
                        <label class="form-control-label">Time<span class="text-danger ml-2">*</span></label>
                      <input type="Time" class="form-control" name="Time" value="<?php echo $row['Time'];?>" id="exampleInputFirstName" >
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <div class="col-xl-6">
                        <label class="form-control-label">Lecture Hall<span class="text-danger ml-2">*</span></label>
                        <input type="text" class="form-control" required name="LectureHall" value="<?php echo $row['LectureHall'];?>" id="exampleInputFirstName" >
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
    <h6 class="m-0 font-weight-bold text-primary">All Scheduled Classes</h6>
    <div class="ml-auto" style="width: 250px;">
        <input type="text" id="searchInput" class="form-control" placeholder="Search for Scheduled Classes...">
    </div>
</div>
                <div class="table-responsive p-3">
                  <table class="table align-items-center table-flush table-hover" id="dataTableHover">
                    <thead class="thead-light">
                      <tr>
                        <th>#</th>
                        <th>Course</th>
                        <th>Batch</th>
                        <th>Module</th>
                        <th>Lecturer</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>LectureHall</th>
                        <th>Edit</th>
                        <th>Delete</th>
                      </tr>
                    </thead>
                   
                    <tbody>

                  <?php
                      $query = "SELECT * FROM scheduleclasses ORDER BY ID ASC";
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
                                <td>".$rows['Batch']."</td>
                                <td>".$rows['Class']."</td>
                                <td>".$rows['Lecturer']."</td>
                                <td>".$rows['Date']."</td>
                                <td>".$rows['Time']."</td>
                                <td>".$rows['LectureHall']."</td>
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
        placeholder: "Select classes...",
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