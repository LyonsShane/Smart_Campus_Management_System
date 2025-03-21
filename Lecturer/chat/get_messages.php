<?php
require '../../Includes/dbcon.php';
session_start();

$user_id = (int) $_SESSION['userId'];
$user_type = $_SESSION['userType'];

$query = "
    SELECT m.*, ms.seen,
        CASE
            WHEN m.user_type = 'Administrator' THEN (SELECT firstName FROM tbladmin WHERE id = m.user_id)
            WHEN m.user_type = 'Lecturer' THEN (SELECT firstName FROM lecturers WHERE id = m.user_id)
            WHEN m.user_type = 'Student' THEN (SELECT firstName FROM students WHERE id = m.user_id)
        END AS firstName,
        CASE
            WHEN m.user_type = 'Administrator' THEN (SELECT lastName FROM tbladmin WHERE id = m.user_id)
            WHEN m.user_type = 'Lecturer' THEN (SELECT lastName FROM lecturers WHERE id = m.user_id)
            WHEN m.user_type = 'Student' THEN (SELECT lastName FROM students WHERE id = m.user_id)
        END AS lastName
    FROM messages m
    LEFT JOIN message_status ms
    ON m.id = ms.message_id AND ms.user_id = ? AND ms.user_type = ?
    ORDER BY m.created_at ASC
";

$stmt = $conn->prepare($query);
$stmt->bind_param("is", $user_id, $user_type);
$stmt->execute();
$result = $stmt->get_result();

$messages = [];
while ($row = $result->fetch_assoc()) {
    $messages[] = $row;
}

echo json_encode($messages);
