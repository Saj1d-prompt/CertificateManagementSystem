<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Information | Certificate Management System</title>
    <link rel="stylesheet" href="studentInfo.css">
</head>
<body>
    <div class="container">
        <h2>Welcome to Certificate Management System</h2>
        <h3>Link Your Academic Records</h3>
        <p>Please select which records you would like to add. This is a one-time mandatory step.</p>

        <form action="studentInfoProcess.php" method="POST">
            
            <div class="record-selection">
                <label>
                    <input type="checkbox" id="ssc_check" onchange="toggleForms()">
                    SSC record
                </label>
                <label>
                    <input type="checkbox" id="hsc_check" onchange="toggleForms()">
                    HSC record
                </label>
            </div>

            <div id="ssc_form" class="form-section">
                <h3>SSC Information</h3>
                <label for="ssc_roll">Roll Number:</label>
                <input type="text" name="ssc_roll" placeholder="SSC Roll Number"><br>
                <label for="ssc_reg">Registration No:</label>
                <input type="text" name="ssc_reg" placeholder="SSC Registration Number"><br>
                <label for="ssc_year">Exam Year:</label>
                <input type="number" name="ssc_year" placeholder="e.g., 2022"><br>
                <label for="ssc_board">Board:</label>
                <input type="text" name="ssc_board" placeholder="e.g., Dhaka"><br>
                <label for="ssc_gpa">GPA:</label>
                <input type="text" name="ssc_gpa" placeholder="e.g., 5.00"><br>
            </div>

            <div id="hsc_form" class="form-section">
                <h3>HSC Information</h3>
                <label for="hsc_roll">Roll Number:</label>
                <input type="text" name="hsc_roll" placeholder="HSC Roll Number"><br>
                <label for="hsc_reg">Registration No:</label>
                <input type="text" name="hsc_reg" placeholder="HSC Registration Number"><br>
                <label for="hsc_year">Exam Year:</label>
                <input type="number" name="hsc_year" placeholder="e.g., 2024"><br>
                <label for="hsc_board">Board:</label>
                <input type="text" name="hsc_board" placeholder="e.g., Dhaka"><br>
                <label for="hsc_gpa">GPA:</label>
                <input type="text" name="hsc_gpa" placeholder="e.g., 5.00"><br>
            </div>

            <h3>Personal Information</h3>
            <p>This information must match your official documents.</p>
            <label for="student_name">Full Name (as on certificate):</label>
            <input type="text" name="student_name" required><br>
            <label for="father_name">Father's Name:</label>
            <input type="text" name="father_name" required><br>
            <label for="mother_name">Mother's Name:</label>
            <input type="text" name="mother_name" required><br>
            <label for="date_of_birth">Date of Birth:</label>
            <input type="date" name="date_of_birth" required><br>
            
            <button type="submit" name="link_records">Save and Continue</button>
        </form>
    </div>

    <script src="studentInfo.js"></script>
</body>
</html>