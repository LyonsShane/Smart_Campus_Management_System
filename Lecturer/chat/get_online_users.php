<?php
require '../../Includes/dbcon.php';

function getOnlineUsers($conn, $table, $type) {
    $users = [];
    $query = "SELECT firstName, lastName FROM `$table` WHERE last_seen >= NOW() - INTERVAL 30 SECOND";
    $result = $conn->query($query);
    while ($row = $result->fetch_assoc()) {
        $users[] = ["name" => $row['firstName'] . ' ' . $row['lastName'], "type" => $type];
    }
    return $users;
}

$onlineUsers = array_merge(
    getOnlineUsers($conn, 'tbladmin', 'Administrator'),
    getOnlineUsers($conn, 'lecturers', 'Lecturer'),
    getOnlineUsers($conn, 'students', 'Student')
);

echo json_encode($onlineUsers);
