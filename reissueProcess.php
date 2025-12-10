<?php
session_start();
include('db.php');

$user_id = $_SESSION['user_id'];
$student_record_id = $_POST['student_record_id'];
$details = $_POST['details'];
$type = 'reissue';

if (isset($_POST['apply_reissue'])) {

    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
        header("Location: index.php");
        exit();
    }

    $file_path_to_save = NULL;
    if (isset($_FILES['support_doc']) && $_FILES['support_doc']['error'] == 0) {
        $upload_dir = 'uploads/';
        if (!is_dir($upload_dir)) { mkdir($upload_dir, 0755, true); }

        $file_name = uniqid('reissue_', true) . '.' . strtolower(pathinfo($_FILES['support_doc']['name'], PATHINFO_EXTENSION));
        $target_path = $upload_dir . $file_name;

        if (move_uploaded_file($_FILES['support_doc']['tmp_name'], $target_path)) {
            $file_path_to_save = $target_path;
        } else {
            die("Error: Could not move the uploaded file.");
        }
    } else {
        die("Error: A supporting document is required for a reissue request.");
    }
     
    $sql = "INSERT INTO certificates (user_id, student_record_id, type, details, proof_document_path) 
        VALUES ('$user_id', '$student_record_id', '$type', '$details', '$file_path_to_save')";

    if ($conn->query($sql)) {
        header("Location: student_dashboard.php");
        exit();
    } else {
        die("Database Error: " . $conn->error);
    }
} 
else {
    header("Location: index.php");
    exit();
}
?>
