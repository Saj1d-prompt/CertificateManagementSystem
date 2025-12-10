<?php
session_start();
include('db.php');

if (!isset($_SESSION['user_id']) || !isset($_POST['link_records'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$student_name = $_POST['student_name'];
$father_name = $_POST['father_name'];
$mother_name = $_POST['mother_name'];
$date_of_birth = $_POST['date_of_birth'];

if (isset($_POST['ssc_roll']) && !empty($_POST['ssc_roll'])) {

    $ssc_roll = $_POST['ssc_roll'];
    $ssc_reg = $_POST['ssc_reg'];
    $ssc_year = $_POST['ssc_year'];
    $ssc_board = $_POST['ssc_board'];
    $ssc_gpa = $_POST['ssc_gpa'];
    
    $ssc_sql = "INSERT INTO student_records (user_id, exam_type, roll_number, registration_number, exam_year, board, student_name, father_name, mother_name, date_of_birth, gpa) 
                VALUES ($user_id, 'SSC', '$ssc_roll', '$ssc_reg', $ssc_year, '$ssc_board', '$student_name', '$father_name', '$mother_name', '$date_of_birth', '$ssc_gpa')";

    
    if (!$conn->query($ssc_sql)) {
        die("Error saving SSC record: " . $conn->error);
    }
}


if (isset($_POST['hsc_roll']) && !empty($_POST['hsc_roll'])) {

    $hsc_roll = $_POST['hsc_roll'];
    $hsc_reg = $_POST['hsc_reg'];
    $hsc_year = $_POST['hsc_year'];
    $hsc_board = $_POST['hsc_board'];
    $hsc_gpa = $_POST['hsc_gpa'];

    $hsc_sql = "INSERT INTO student_records (user_id, exam_type, roll_number, registration_number, exam_year, board, student_name, father_name, mother_name, date_of_birth, gpa) 
                VALUES ($user_id, 'HSC', '$hsc_roll', '$hsc_reg', $hsc_year, '$hsc_board', '$student_name', '$father_name', '$mother_name', '$date_of_birth', '$hsc_gpa')";

    if (!$conn->query($hsc_sql)) {
        die("Error saving HSC record: " . $conn->error);
    }
}

$conn->close();

header("Location: student_dashboard.php");
exit();

?>
