<?php
require '../../Includes/dbcon.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message = trim($_POST['message']);
    $user_id = $_SESSION['userId'];
    $user_type = $_SESSION['userType'];
    
    if (!empty($message)) {
        
        // Insert message
        $stmt = $conn->prepare("INSERT INTO messages (user_id, user_type, message) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $user_id, $user_type, $message);
        $stmt->execute();
        $message_id = $conn->insert_id;
        
        // Insert message status for all users
        foreach (['Administrator', 'Lecturer', 'Student'] as $type) {
            if ($type === 'Administrator') {
                $query = "SELECT id FROM `tbladmin` ";
                $result = $conn->query($query);
                while ($user = $result->fetch_assoc()) {
                    $status_stmt = $conn->prepare("INSERT INTO message_status (message_id, user_type, user_id) VALUES (?, ?, ?)");
                    $status_stmt->bind_param("isi", $message_id, $type, $user['id']);
                    $status_stmt->execute();
                }
            } else if ($type === 'Lecturer') {
                $query = "SELECT id FROM `lecturers` ";
                $result = $conn->query($query);
                while ($user = $result->fetch_assoc()) {
                    $status_stmt = $conn->prepare("INSERT INTO message_status (message_id, user_type, user_id) VALUES (?, ?, ?)");
                    $status_stmt->bind_param("isi", $message_id, $type, $user['id']);
                    $status_stmt->execute();
                }
            } else if ($type === 'Student') {
                $query = "SELECT id FROM `students` ";
                $result = $conn->query($query);
                while ($user = $result->fetch_assoc()) {
                    $status_stmt = $conn->prepare("INSERT INTO message_status (message_id, user_type, user_id) VALUES (?, ?, ?)");
                    $status_stmt->bind_param("isi", $message_id, $type, $user['id']);
                    $status_stmt->execute();
                }
            }
        }
        
        echo json_encode(["status" => "success"]);
    }
}
