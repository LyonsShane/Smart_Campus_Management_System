<?php
include '../Includes/dbcon.php';

if (isset($_GET['classId'])) {
    $classId = $_GET['classId'];

    // Get course ID of the selected class
    $courseQuery = mysqli_query($conn, "SELECT CourseId FROM classes WHERE Id = '$classId'");
    $courseRow = mysqli_fetch_assoc($courseQuery);
    $courseId = $courseRow['CourseId'];

    // Fetch students enrolled in the same course
    $studentsQuery = mysqli_query($conn, "SELECT Id, firstName FROM students WHERE CourseId = '$courseId'");

    echo "<table class='table table-bordered mt-3'>";
    echo "<tr><th>Student Name</th><th>Attendance</th></tr>";
    while ($student = mysqli_fetch_assoc($studentsQuery)) {
        echo "<tr>
                <td>{$student['firstName']}</td>
                <td>
                    <input type='radio' name='attendance[{$student['Id']}]' value='Present' required> Present
                    <input type='radio' name='attendance[{$student['Id']}]' value='Absent' required> Absent
                </td>
              </tr>";
    }
    echo "</table>";
}
?>
