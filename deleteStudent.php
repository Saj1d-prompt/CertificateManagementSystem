<?php
session_start();
include('db.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    $_SESSION['message'] = "You do not have permission to perform this action.";
    $_SESSION['message_type'] = "error";
    header("Location: studentListView.php"); 
    exit();
}

if (!isset($_POST['user_id'])) {
    $_SESSION['message'] = "Invalid request. No user specified.";
    $_SESSION['message_type'] = "error";
    header("Location: studentListView.php"); 
    exit();
}

$user_id_to_delete = $_POST['user_id'];

if ($user_id_to_delete === $_SESSION['user_id']) {
    $_SESSION['message'] = "Error: You cannot delete your own account.";
    $_SESSION['message_type'] = "error";
    header("Location: studentListView.php"); 
    exit();
}


$del = $conn->query("DELETE FROM users WHERE id = $user_id_to_delete AND role = 'student'");


if ($del) {
    if ($conn->affected_rows > 0) {
        $_SESSION['message'] = "Student account and all associated records have been permanently deleted.";
        $_SESSION['message_type'] = "success";
    } else {
        $_SESSION['message'] = "Error: Could not find the specified student account to delete.";
        $_SESSION['message_type'] = "error";
    }
} else {
    $_SESSION['message'] = "Database error: " . $conn->error;
    $_SESSION['message_type'] = "error";
}

$conn->close();

header("Location: studentListView.php");
exit();
?>