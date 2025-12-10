<?php
session_start();
include('db.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: index.php");
    exit();
}
$user_id = $_SESSION['user_id'];

$new_count_Query = $conn->query("SELECT COUNT(*) AS count FROM certificates 
                    WHERE user_id = $user_id AND type = 'new'");
$new_count = $new_count_Query->fetch_assoc()['count'];

$reissue_count_Query = $conn->query("SELECT COUNT(*) AS count FROM certificates 
                    WHERE user_id =  $user_id AND type = 'reissue'");
$reissue_count = $reissue_count_Query->fetch_assoc()['count'];

$correction_count_Query = $conn->query("SELECT COUNT(*) AS count FROM correction_requests 
                    WHERE user_id = $user_id");
$correction_count = $correction_count_Query->fetch_assoc()['count'];

$records_result = $conn->query("SELECT exam_type, roll_number, registration_number, exam_year, board, gpa 
                    FROM student_records WHERE user_id = $user_id ORDER BY exam_type ASC");

$activity_sql = "
    (SELECT 'New Certificate' AS type, applied_at AS date, status FROM certificates WHERE user_id = $user_id AND type = 'new')
    UNION ALL
    (SELECT 'Reissue' AS type, applied_at AS date, status FROM certificates WHERE user_id = $user_id AND type = 'reissue')
    UNION ALL
    (SELECT 'Correction' AS type, requested_at AS date, status FROM correction_requests WHERE user_id = $user_id)
    ORDER BY date DESC
    LIMIT 5";
$activity_result = $conn->query($activity_sql);


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard | Certificate Management System</title>
    <link rel="stylesheet" href="student_dashboard.css">
</head>
<body>
    <header>
        <img src="./images/logo1.png" alt="logo">
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

    <main class="container">
        <div class="dashboard-header">
            <h1>Welcome, <?php echo $_SESSION['name']; ?>!</h1>
        </div>

        <div class="count-section">
            <div class="count-box">
                <h3><?php echo $new_count; ?></h3>
                <p>New Certificate Applications</p>
            </div>
            <div class="count-box">
                <h3><?php echo $reissue_count; ?></h3>
                <p>Reissue Requests</p>
            </div>
            <div class="count-box">
                <h3><?php echo $correction_count; ?></h3>
                <p>Correction Requests</p>
            </div>
        </div>

        <div>
            <h2 class = "featureHead">Quick Actions</h2>
            <div class="feature-section">
                <a href="certificate_application.php" class="feature-box">
                    <h4>Apply for New Certificate</h4>
                    <p>Submit an application for your original SSC or HSC certificate.</p>
                </a>
                <a href="reissueCertificate.php" class="feature-box">
                    <h4>Request a Reissue</h4>
                    <p>Apply for a reissue if your certificate was lost or damaged.</p>
                </a>
                <a href="correctionApplication.php" class="feature-box">
                    <h4>Request a Correction</h4>
                    <p>Submit a request to correct any errors on your official record.</p>
                </a>
            </div>
        </div>

        <div class="record-section">
            <div class="info-box">
                <h2>Recent Activity</h2>
                <div>
                    <table>
                        <thead>
                            <tr>
                                <th>Application Type</th>
                                <th>Date Submitted</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($activity_result && $activity_result->num_rows > 0){ ?>
                                <?php while($activity = $activity_result->fetch_assoc()){ ?>
                                    <tr>
                                        <td><?php echo $activity['type']; ?></td>
                                        <td><?php echo date('d M, Y', strtotime($activity['date'])); ?></td>
                                        <td><span class="status status-<?php echo strtolower($activity['status']); ?>"><?php echo $activity['status']; ?></span></td>
                                    </tr>
                                <?php } ?>
                            <?php }else{ ?>
                                <tr>
                                    <td colspan="3">No recent activity found.</td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="info-box">
                <h2>Your Academic Records</h2>
                <div class="records">
                    <?php if ($records_result && $records_result->num_rows > 0){ ?>
                        <?php while($record = $records_result->fetch_assoc()){ ?>
                            <div class="record-item">
                                <h4><?php echo $record['exam_type']; ?> Details</h4>
                                <ul>
                                    <li><strong>Roll:</strong> <?php echo $record['roll_number']; ?></li>
                                    <li><strong>Registration:</strong> <?php echo $record['registration_number']; ?></li>
                                    <li><strong>Year:</strong> <?php echo $record['exam_year']; ?></li>
                                    <li><strong>Board:</strong> <?php echo $record['board']; ?></li>
                                    <li><strong>GPA:</strong> <?php echo $record['gpa']; ?></li>
                                </ul>
                            </div>
                        <?php } ?>
                    <?php }else{ ?>
                        <p>Your academic records have not been linked yet.</p>
                    <?php } ?>
                </div>
            </div>
        </div>
    </main>
</body>
</html>

