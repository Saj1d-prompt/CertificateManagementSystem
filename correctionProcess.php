<?php
session_start();
include('db.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student' || !isset($_POST['apply_correction'])) {
    header("Location: index.php");
    exit();
}

$proof_path_to_save = NULL;
if (isset($_FILES['proof_doc']) && $_FILES['proof_doc']['error'] == 0) {
    $upload_dir = 'uploads/proofs/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }
    $file_ext = strtolower(pathinfo($_FILES['proof_doc']['name'], PATHINFO_EXTENSION));
    $allowed_ext = ['jpg', 'jpeg', 'png', 'pdf'];
    if (in_array($file_ext, $allowed_ext)) {
        $unique_file_name = uniqid('proof_', true) . '.' . $file_ext;
        $target_path = $upload_dir . $unique_file_name;
        if (move_uploaded_file($_FILES['proof_doc']['tmp_name'], $target_path)) {
            $proof_path_to_save = $target_path;
        } else {
            die("Error: Could not move the uploaded proof file.");
        }
    } else {
        die("Error: Invalid proof file type.");
    }
} else {
    die("Error: A mandatory proof document is required.");
}

$user_id = $_SESSION['user_id'];
$certificate_id = $_POST['certificate_id'];
$proof_path = $conn->real_escape_string($proof_path_to_save);

$sql = "INSERT INTO correction_requests (certificate_id, user_id, proof_document) 
                VALUES ('$certificate_id', '$user_id', '$proof_path')";

if ($conn->query($sql)) {
    $request_id = $conn->insert_id;
    if (!empty($_POST['fields_to_correct']) && is_array($_POST['fields_to_correct'])) {
        $fields = $_POST['fields_to_correct'];

        for ($i = 0; $i < count($fields); $i++) {
            $field = $fields[$i];
            $key = 'corrected_' . $field;

            if (!empty($_POST[$key])) {
               
                $field_name = $conn->real_escape_string($field);
                $new_value = $conn->real_escape_string($_POST[$key]);
                
                $sql_item = "INSERT INTO correction_items (request_id, field_name, new_value) 
                             VALUES ($request_id, '$field_name', '$new_value')";

                if (!$conn->query($sql_item)) {
                    die("Error saving correction item: " . $conn->error);
                }
            }
        }
    }

    header("Location: correctionApplication.php");
    exit();

} else {
    die("Error creating main request: " . $conn->error);
}
$conn->close();
?>
