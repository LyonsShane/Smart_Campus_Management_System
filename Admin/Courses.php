<?php
error_reporting(0);
include '../Includes/dbcon.php';
include '../Includes/session.php';

//------------------------SAVE--------------------------------------------------
if (isset($_POST['save'])) {
    
    $CourseName = mysqli_real_escape_string($conn, $_POST['CourseName']);
    $Description = mysqli_real_escape_string($conn, $_POST['Description']);
    
    
    // Check if the class already exists
    $checkQuery = mysqli_query($conn, "SELECT * FROM courses WHERE CourseName = '$CourseName'");
    if (mysqli_num_rows($checkQuery) > 0) {
        $statusMsg = "<div id='statusMsg' class='alert alert-danger' style='margin-right:700px;'>This Course Already Exists!</div>";
    } else {
        // Insert into database
        $query = mysqli_query($conn, "INSERT INTO courses (CourseName, Description) VALUES ('$CourseName', '$Description')");
        
        if ($query) {
            $statusMsg = "<div id='statusMsg' class='alert alert-success' style='margin-right:700px;'>Course Created Successfully!</div>";
        } else {
            $statusMsg = "<div id='statusMsg' class='alert alert-danger' style='margin-right:700px;'>An error Occurred!</div>";
        }
    }
}


//---------------------------------------EDIT-------------------------------------------------------------


//--------------------EDIT------------------------------------------------------------

if (isset($_GET['Id']) && isset($_GET['action']) && $_GET['action'] == "edit") {
    $Id = $_GET['Id'];
    
    $query = mysqli_query($conn, "select * from courses where Id ='$Id'");
    $row = mysqli_fetch_array($query);
    
    //------------UPDATE-----------------------------
    
    if (isset($_POST['update'])) {
        
        
        $CourseName = $_POST['CourseName'];
        $Description = $_POST['Description'];
        
        $query = mysqli_query($conn, "update courses set CourseName='$CourseName', Description = '$Description' where Id='$Id'");
        
        if ($query) {
            
            echo "<script type = \"text/javascript\">
                window.location = (\"Courses.php\")
                </script>";
        } else {
            $statusMsg = "<div id='statusMsg' class='alert alert-danger' style='margin-right:700px;'>An error Occurred!</div>";
        }
    }
}


//--------------------------------DELETE------------------------------------------------------------------

if (isset($_GET['Id']) && isset($_GET['action']) && $_GET['action'] == "delete") {
    $Id = $_GET['Id'];
    
    $query = mysqli_query($conn, "DELETE FROM courses WHERE Id='$Id'");
    
    if ($query == TRUE) {
        
        echo "<script type = \"text/javascript\">
                window.location = (\"Courses.php\")
                </script>";
    } else {
        
        $statusMsg = "<div id='statusMsg' class='alert alert-danger' style='margin-right:700px;'>An error Occurred!</div>";
    }
    
}


?>

<script>
    setTimeout(function () {
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
    <?php include 'includes/title.php'; ?>
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="css/ruang-admin.min.css" rel="stylesheet">
</head>

<body id="page-top">
<div id="wrapper">
    <!-- Sidebar -->
    <?php include "Includes/sidebar.php"; ?>
    <!-- Sidebar -->
    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <!-- TopBar -->
            <?php include "Includes/topbar.php"; ?>
            <!-- Topbar -->

            <!-- Container Fluid-->
            <div class="container-fluid" id="container-wrapper">
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">Courses</h1>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="./">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Courses</li>
                    </ol>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <!-- Form Basic -->
                        <div class="card mb-4">
                            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                <h6 class="m-0 font-weight-bold text-primary">Create a Course</h6>
                                <div id="statusMessage">
                                    <?php echo $statusMsg; ?>
                                </div>
                            </div>
                            <div class="card-body">
                                <form method="post">
                                    <div class="form-group row mb-3">
                                        <div class="col-xl-12">
                                            <label class="form-control-label">Course Name<span class="text-danger ml-2">*</span></label>

                                            <input type="text" required class="form-control" name="CourseName"
                                                   value="<?php echo $row['CourseName']; ?>" id="exampleInputFirstName"
                                                   placeholder="">
                                        </div>


                                        <div class="col-xl-12">
                                            <br>
                                            <label class="form-control-label">Description<span class="text-danger ml-2">*</span></label>
                                            <textarea class="form-control" required oninput="resizeTextarea(this)"
                                                      name="Description"
                                                      id="exampleInputFirstName"><?php echo htmlspecialchars($row['Description']); ?></textarea>
                                        </div>
                                        <script>
                                            function resizeTextarea(el) {
                                                el.style.height = "auto"; // Reset height
                                                el.style.height = (el.scrollHeight) + "px"; // Set new height
                                            }
                                        </script>


                                    </div>
                                    <?php
                                    if (isset($Id)) {
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
                                        <h6 class="m-0 font-weight-bold text-primary">All Courses</h6>
                                    </div>
                                    <div class="table-responsive p-3">
                                        <table class="table align-items-center table-flush table-hover"
                                               id="dataTableHover">
                                            <thead class="thead-light">
                                            <tr>
                                                <th>#</th>
                                                <th>Course Name</th>
                                                <th>Discription</th>
                                                <th>Edit</th>
                                                <th>Delete</th>
                                            </tr>
                                            </thead>

                                            <tbody>
                                            
                                            <?php
                                            $query = "SELECT * FROM courses ORDER BY ID ASC";
                                            
                                            $rs = $conn->query($query);
                                            $num = $rs->num_rows;
                                            $sn = 0;
                                            if ($num > 0) {
                                                while ($rows = $rs->fetch_assoc()) {
                                                    
                                                    $sn = $sn + 1;
                                                    echo "
                              <tr>
                                <td>" . $sn . "</td>
                                <td>" . $rows['CourseName'] . "</td>
                                <td>" . $rows['Description'] . "</td>
                                <td><a href='?action=edit&Id=" . $rows['Id'] . "'><i class='fas fa-fw fa-edit'></i>Edit</a></td>
                                <td><a href='?action=delete&Id=" . $rows['Id'] . "'><i class='fas fa-fw fa-trash'></i>Delete</a></td>
                              </tr>";
                                                }
                                            } else {
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
            <?php include "Includes/footer.php"; ?>
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