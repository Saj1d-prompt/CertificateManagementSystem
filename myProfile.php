<?php
session_start();
include('db.php');

// Security check: Ensure user is a logged-in student
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: index.php");
    exit();
}
$user_id = (int)$_SESSION['user_id'];

$result = $conn->query("SELECT * FROM student_records WHERE user_id = $user_id ORDER BY exam_year ASC");


$personal_info = null;
$academic_records = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        if ($personal_info === null) {
            $personal_info = [
                'student_name' => $row['student_name'],
                'father_name' => $row['father_name'],
                'mother_name' => $row['mother_name'],
                'date_of_birth' => $row['date_of_birth']
            ];
        }
        $academic_records[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile | Student Dashboard</title>
    <link rel="stylesheet" href="myProfile.css"> 
</head>
<body>
    <header>
        <img src="./images/logo1.png" alt="">
        <nav>
            <a href="student_dashboard.php">Home</a>
            <a href="certificate_application.php">Certificate Application</a>
            <a href="reissueCertificate.php">Reissue Application</a>
            <a href="correctionApplication.php">Correction Application</a>
            <a href="applicationHistory.php">Application History</a>
            <a href="myProfile.php">My Profile</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>

    <main class="main-container">
        <div class="profile-header">
            <h1>My Official Profile</h1>
        </div>

        <?php if ($personal_info){ ?>
            <div class="profile-section">
                <div class="section-title">
                    <h3>Personal Information</h3>
                </div>
                <div class="info-grid">
                    <div class="info-item">
                        <label>Full Name</label>
                        <div class="value"><?php echo $personal_info['student_name']; ?></div>
                    </div>
                    <div class="info-item">
                        <label>Date of Birth</label>
                        <div class="value"><?php echo date('d M, Y', strtotime($personal_info['date_of_birth'])); ?></div>
                    </div>
                    <div class="info-item">
                        <label>Father's Name</label>
                        <div class="value"><?php echo $personal_info['father_name']; ?></div>
                    </div>
                    <div class="info-item">
                        <label>Mother's Name</label>
                        <div class="value"><?php echo $personal_info['mother_name']; ?></div>
                    </div>
                </div>
            </div>

            <div class="profile-section">
                <div class="section-title">
                    <h3>Academic Records</h3>
                </div>
                
                <div class="records-container">
                    <?php foreach ($academic_records as $record){ ?>
                        <div class="record-card">
                            <div class="card-header">
                                <h4><?php echo $record['exam_type']; ?></h4>
                                <span class="year-badge"><?php echo $record['exam_year']; ?></span>
                            </div>
                            <div class="card-body">
                                <div class="card-row">
                                    <span>Board:</span>
                                    <strong><?php echo $record['board']; ?></strong>
                                </div>
                                <div class="card-row">
                                    <span>Roll Number:</span>
                                    <strong><?php echo $record['roll_number']; ?></strong>
                                </div>
                                <div class="card-row">
                                    <span>Registration No:</span>
                                    <strong><?php echo $record['registration_number']; ?></strong>
                                </div>
                                <div class="card-row highlight">
                                    <span>GPA:</span>
                                    <strong><?php echo $record['gpa']; ?></strong>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>

        <?php }else{ ?>
            <div class="empty-state">
                <h3>No Records Found</h3>
                <p>You have not linked your academic records yet.</p>
            </div>
        <?php } ?>

    </main>
</body>
</html>