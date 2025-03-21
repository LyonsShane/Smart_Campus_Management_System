<?php
require '../../Includes/dbcon.php';
session_start();

if (isset($_SESSION['userId'], $_SESSION['userType'])) {
    $user_id = $_SESSION['userId'];
    $user_type = $_SESSION['userType'];
    
    if ($user_type == "Administrator") {
        $stmt = $conn->prepare("UPDATE `tbladmin` SET last_seen = NOW() WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
    } else if ($user_type == "Lecturer") {
        $stmt = $conn->prepare("UPDATE `lecturers` SET last_seen = NOW() WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
    } else if ($user_type == "Student") {
        $stmt = $conn->prepare("UPDATE `students` SET last_seen = NOW() WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
    }
    echo json_encode(["status" => "success"]);
}
