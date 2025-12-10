<?php
session_start();
include('db.php'); 

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

$application_id = $_POST['application_id'];
$action = $_POST['action'];
$admin_id = $_SESSION['user_id']; 
$reason = NULL;

$new_status = '';
if ($action === 'approve') {
    $new_status = 'approved';
    $sql = "UPDATE certificates SET status = '$new_status', admin_id = '$admin_id' WHERE id = '$application_id'";
    $conn->query($sql);
} elseif ($action === 'reject') {
    $new_status = 'rejected';
    if (!isset($_POST['rejection_reason']) || empty($_POST['rejection_reason'])) {
        $_SESSION['message'] = "A reason is required to reject an application.";
        $_SESSION['message_type'] = "error";
        header("Location: viewCertificateApplication.php");
        exit();
    }
    $reason = $_POST['rejection_reason'];
    $sql = "UPDATE certificates SET status = '$new_status', admin_id = '$admin_id' , reason = '$reason' WHERE id = '$application_id'";
    $conn->query($sql);
}



$checkQuery = "SELECT type FROM certificates WHERE id = '$application_id'";
$result = $conn->query($checkQuery);



if ($result && $row = $result->fetch_assoc()) {
    $application_Type = $row['type'];

    if ($application_Type === 'new') {
        header("Location: viewCertificateApplication.php");
    } elseif ($application_Type === 'reissue') {
        header("Location: viewReissue.php");
    } else {
        header("Location: viewCorrectionApplication.php");
    }
} else {
    header("Location: admin_dashboard.php");
}
$conn->close();
exit();
?>
