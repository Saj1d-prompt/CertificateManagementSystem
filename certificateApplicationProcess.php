<?php
session_start();
include('db.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student' || !isset($_POST['apply_new'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$student_record_id = $_POST['student_record_id'];
$details = $_POST['details'];
$type = 'new';
$file_path_to_save = NULL; 

if (isset($_FILES['support_doc']) && $_FILES['support_doc']['error'] == 0) {
    $upload_dir = 'uploads/'; // IMPORTANT: Create this directory in your project folder!
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true); // Create the directory if it doesn't exist
    }

    $file_name = basename($_FILES['support_doc']['name']);
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    $allowed_ext = ['jpg', 'jpeg', 'png', 'pdf'];

    // Check if the file type is in our allowed list
    if (in_array($file_ext, $allowed_ext)) {
        // Create a unique filename to prevent overwriting existing files
        $unique_file_name = uniqid('doc_', true) . '.' . $file_ext;
        $target_path = $upload_dir . $unique_file_name;

        // Move the file from temporary storage to your uploads directory
        if (move_uploaded_file($_FILES['support_doc']['tmp_name'], $target_path)) {
            $file_path_to_save = $target_path;
        } else {
            die("Error: Could not move the uploaded file. Please check folder permissions.");
        }
    } else {
        die("Error: Invalid file type. Only JPG, PNG, and PDF files are allowed.");
    }
} else {
    die("Error: A supporting document is required. Please upload a file.");
}

$sql = "INSERT INTO certificates (user_id, student_record_id, type, details, proof_document_path) 
        VALUES ('$user_id', '$student_record_id', '$type', '$details', '$file_path_to_save')";

if ($conn->query($sql)) {
    header("Location: student_dashboard.php");
    exit();
} else {
    die("Database Error: " . $conn->error);
}
$conn->close();
?>
