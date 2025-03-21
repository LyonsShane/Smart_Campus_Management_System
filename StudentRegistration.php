<?php 
include 'Includes/dbcon.php';
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link href="img/logo/logo 1.png" rel="icon">
  <title>SCMS</title>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="css/ruang-admin.min.css" rel="stylesheet">
  
</head>

<body class="bg-gradient-login" style="background-image: url('img/logo/loral1.jpg');">
  <div class="container-login">
    <div class="row justify-content-center">
      <div class="col-xl-10 col-lg-12 col-md-9">
        <div class="card shadow-sm my-5">
          <div class="card-body p-0">
            <div class="row">
              <div class="col-lg-12">
                <div class="login-form">
                  <div class="text-center">
                    <img src="img/logo/logo 1.png" style="width:100px;height:100px"><br><br>
                    <h1 class="h4 text-gray-900 mb-4">Student Registration</h1>
                  </div>
                  
                  <form class="user" method="POST">
                  <div class="form-group row mb-3">
                        <div class="col-xl-6">
                        <label class="form-control-label">Firstname<span class="text-danger ml-2">*</span></label>
                        <input type="text" class="form-control" required name="firstName" id="exampleInputFirstName">
                        </div>
                        <br>
                        <div class="col-xl-6">
                        <label class="form-control-label">Lastname<span class="text-danger ml-2">*</span></label>
                      <input type="text" class="form-control" required name="lastName" id="exampleInputFirstName" >
                        </div>
                    </div>
                     <div class="form-group row mb-3">
                        <div class="col-xl-6">
                        <label class="form-control-label">Email Address<span class="text-danger ml-2">*</span></label>
                        <input type="email" class="form-control" required name="emailAddress" id="exampleInputFirstName" >
                        </div>
                        <br>
                        <div class="col-xl-6">
                        <label class="form-control-label">Phone No<span class="text-danger ml-2">*</span></label>
                      <input type="text" class="form-control" name="phoneNo" id="exampleInputFirstName" >
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <div class="col-xl-6">
                        <label class="form-control-label">Address<span class="text-danger ml-2">*</span></label>
                        <input type="text" class="form-control" required name="Address" id="exampleInputFirstName" >
                        </div>
                        <br>
                        <div class="col-xl-6">
                        <label class="form-control-label">Gender<span class="text-danger ml-2">*</span></label>
                        <select required name="Gender" class="form-control">
                        <option value="" disabled <?php if(empty($row['Gender'])) echo 'selected'; ?>>--Select Gender--</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>


                      
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                    
                    <div class="col-xl-6">
                        <label class="form-control-label">Select Enrolled Course<span class="text-danger ml-2">*</span></label>
                        <select required name="EnrolledCourse" class="form-control mb-3">
                        <option value="">--Select Course--</option>
                        <?php
                        $qry = "SELECT * FROM courses ORDER BY Id ASC";
                        $result = $conn->query($qry);
                        while ($rows = $result->fetch_assoc()) {
                            $selected = ($row['EnrolledCourse'] == $rows['CourseName']) ? 'selected' : '';
                            echo '<option value="'.$rows['CourseName'].'" '.$selected.'>'.$rows['CourseName'].'</option>';
                        }
                        ?>
                    </select>

                    </div>

                    </div>
                    <div class="form-group">
                        <input type="submit" class="btn btn-success btn-block" value="Register" name="Register" />
                    </div>
                    <div class="form-group row mb-3 d-flex flex-row align-items-center flex-column gap-2">
                        <p>Already have an account ?</p> <a href="Login.php"> <p>Log in</p> </a>
                    </div>
                  </form>

                  
                  <?php
                  if(isset($_POST['Register'])){

                    include 'Includes/dbcon.php';
    
                    $firstName=$_POST['firstName'];
                    $lastName=$_POST['lastName'];
                    $emailAddress=$_POST['emailAddress'];
                    $phoneNo=$_POST['phoneNo'];
                    $Address=$_POST['Address'];
                    $Gender=$_POST['Gender'];
                    $EnrolledCourse=$_POST['EnrolledCourse'];
                  
                    $dateCreated = date("Y-m-d");
                     
                      $query=mysqli_query($conn,"select * from students where emailAddress ='$emailAddress'");
                      $ret=mysqli_fetch_array($query);
                  
                      $sampPass = "Student123";
                      $sampPass_2 = md5($sampPass);
                  
                      if($ret > 0){ 
                  
                          echo "<script>alert('This Email Address Already Exists!');</script>";
                      }
                      else{

                        $query=mysqli_query($conn,"INSERT into students(firstName,lastName,emailAddress,password,phoneNo,Address,Gender,EnrolledCourse,dateCreated) 
                        value('$firstName','$lastName','$emailAddress','$sampPass_2','$phoneNo','$Address','$Gender','$EnrolledCourse','$dateCreated')");
                  
                        if ($query) {
                          echo "<script>alert('Student Successfully Registered!  Your Password Is - Student123'); window.location.href='Login.php';</script>";
exit();
                      } else {
                          echo "<script>alert('Student Registration Failed!');</script>";
                      }

                      // Close the statement
                      $query->close();
                      $conn->close();
                      }
                    }
                  ?>

                  <div class="text-center"></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="js/ruang-admin.min.js"></script>
</body>
</html>
