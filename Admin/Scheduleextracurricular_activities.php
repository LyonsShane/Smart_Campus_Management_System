
<?php 
error_reporting(0);
include '../Includes/dbcon.php';
include '../Includes/session.php';

//------------------------SAVE--------------------------------------------------

if(isset($_POST['save'])){
    
  $ActivityName=$_POST['ActivityName'];
  $Date=$_POST['Date'];
  $Time=$_POST['Time'];
  $Venue=$_POST['Venue'];
  $Description=$_POST['Description'];
   

    $query=mysqli_query($conn,"INSERT into extracurricularactivities(ActivityName,Date,Time,Venue,Description) 
    value('$ActivityName','$Date','$Time','$Venue','$Description')");

if ($query) {
  $statusMsg = "<div id='statusMsg' class='alert alert-success' style='margin-right:700px;'>Extra Curricular Activity Scheduled Successfully!</div>";
} else {
  $statusMsg = "<div id='statusMsg' class='alert alert-danger' style='margin-right:700px;'>An error Occurred!</div>";
}

    }

//--------------------EDIT------------------------------------------------------------

 if (isset($_GET['Id']) && isset($_GET['action']) && $_GET['action'] == "edit")
	{
        $Id= $_GET['Id'];

        $query=mysqli_query($conn,"select * from extracurricularactivities where Id ='$Id'");
        $row=mysqli_fetch_array($query);

        //------------UPDATE-----------------------------

        if(isset($_POST['update'])){
    
          $ActivityName=$_POST['ActivityName'];
          $Date=$_POST['Date'];
          $Time=$_POST['Time'];
          $Venue=$_POST['Venue'];
          $Description=$_POST['Description'];


    $query=mysqli_query($conn,"update extracurricularactivities set ActivityName='$ActivityName', Date='$Date', Time='$Time', Venue='$Venue', Description='$Description'
    where Id='$Id'");
            if ($query) {
                
                echo "<script type = \"text/javascript\">
                window.location = (\"Scheduleextracurricular_activities.php\")
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

        $query = mysqli_query($conn,"DELETE FROM extracurricularactivities WHERE Id='$Id'");

        if ($query == TRUE) {
                
                 echo "<script type = \"text/javascript\">
                window.location = (\"Scheduleextracurricular_activities.php\")
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
            <h1 class="h3 mb-0 text-gray-800">Schedule Extracurricular Activities</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Schedule Extracurricular Activities</li>
            </ol>
          </div>

          <div class="row">
            <div class="col-lg-12">
              <!-- Form Basic -->
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Create Schedule Extracurricular Activity</h6>
                    <?php echo $statusMsg; ?>
                </div>
                <div class="card-body">
                  <form method="post">
                   <div class="form-group row mb-3">
                        <div class="col-xl-6">
                        <label class="form-control-label">Activity Name<span class="text-danger ml-2">*</span></label>
                        <input type="text" class="form-control" required name="ActivityName" value="<?php echo $row['ActivityName'];?>" id="exampleInputFirstName" >
  
                        </div>
                        <br>
                        <div class="col-xl-6">
                        <label class="form-control-label">Date<span class="text-danger ml-2">*</span></label>
                        <input type="Date" class="form-control" required name="Date" value="<?php echo $row['Date'];?>" id="exampleInputFirstName" >
                        </div>
                    </div>
                     

                    <div class="form-group row mb-3">
                        <div class="col-xl-6">
                        <label class="form-control-label">Time<span class="text-danger ml-2">*</span></label>
                      <input type="Time" class="form-control" name="Time" value="<?php echo $row['Time'];?>" id="exampleInputFirstName" >
                        </div>
                        <br>
                        <div class="col-xl-6">
                        <label class="form-control-label">Venue<span class="text-danger ml-2">*</span></label>
                        <input type="text" class="form-control" required name="Venue" value="<?php echo $row['Venue'];?>" id="exampleInputFirstName" >
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <div class="col-xl-6">
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
    <h6 class="m-0 font-weight-bold text-primary">All Scheduled Extracurricular Activities</h6>
    <div class="ml-auto" style="width: 400px;">
        <input type="text" id="searchInput" class="form-control" placeholder="Search for Scheduled Extracurricular Activities...">
    </div>
</div>
                <div class="table-responsive p-3">
                  <table class="table align-items-center table-flush table-hover" id="dataTableHover">
                    <thead class="thead-light">
                      <tr>
                        <th>#</th>
                        <th>Activity Name</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Venue</th>
                        <th>Description</th>
                        <th>Edit</th>
                        <th>Delete</th>
                      </tr>
                    </thead>
                   
                    <tbody>

                  <?php
                      $query = "SELECT * FROM extracurricularactivities ORDER BY ID ASC";
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
                                <td>".$rows['ActivityName']."</td>
                                <td>".$rows['Date']."</td>
                                <td>".$rows['Time']."</td>
                                <td>".$rows['Venue']."</td>
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