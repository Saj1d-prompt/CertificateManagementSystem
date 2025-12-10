<?php
session_start();
include('db.php'); 

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

$request_id = $_POST['request_id'];
$action = $_POST['action'];
$admin_id = $_SESSION['user_id']; 
$reason = NULL;

$new_status = '';
if ($action === 'approve') {
    $new_status = 'approved';
    $sql = "UPDATE correction_requests SET status = '$new_status', admin_id = '$admin_id' WHERE id = '$request_id'";
    $conn->query($sql);
} elseif ($action === 'reject') {
    $new_status = 'rejected';
    if (!isset($_POST['rejection_reason']) || empty($_POST['rejection_reason'])) {
        $_SESSION['message'] = "A reason is required to reject an application.";
        $_SESSION['message_type'] = "error";
        header("Location: viewCorrectionApplication.php");
        exit();
    }
    $reason = $_POST['rejection_reason'];
    $sql = "UPDATE correction_requests SET status = '$new_status', admin_id = '$admin_id' , reason = '$reason' WHERE id = '$request_id'";
    $conn->query($sql);
    
}

$sql = "UPDATE correction_requests SET status = '$new_status', admin_id = '$admin_id' WHERE id = '$request_id'";
$conn->query($sql);

header("Location: viewCorrectionApplication.php");
$conn->close();
exit();
?>
