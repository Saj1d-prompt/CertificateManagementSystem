<?php
session_start();
include('db.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}
$admin_name = $_SESSION['name'];

$total_students_Query = $conn->Query("SELECT COUNT(*) AS count FROM users WHERE role = 'student'");
$total_students = $total_students_Query->fetch_assoc()['count'];

$pending_new_query = $conn->query("SELECT COUNT(*) AS count FROM certificates WHERE status = 'pending' AND type = 'new'");
$pending_new = $pending_new_query->fetch_assoc()['count'];

$pending_reissue_Query = $conn->query("SELECT COUNT(*) AS count FROM certificates WHERE status = 'pending' AND type = 'reissue'");
$pending_reissue = $pending_reissue_Query->fetch_assoc()['count'];

$pending_corrects_query = $conn->query("SELECT COUNT(*) AS count FROM correction_requests WHERE status = 'pending'");
$pending_corrects = $pending_corrects_query->fetch_assoc()['count'];


$activity_sql = "
    (SELECT 
        c.id, 
        u.name, 
        (CASE c.type WHEN 'new' THEN 'New' WHEN 'reissue' THEN 'Reissue' END) AS application_type, 
        c.updated_at AS date,
        c.status
    FROM certificates c 
    JOIN users u ON c.user_id = u.id 
    WHERE c.status IN ('approved', 'rejected'))
    UNION ALL
    (SELECT 
        cr.id, 
        u.name, 
        'Correction' AS application_type, 
        cr.requested_at AS date, -- Note: using requested_at as updated_at is not available for corrections
        cr.status 
    FROM correction_requests cr 
    JOIN users u ON cr.user_id = u.id 
    WHERE cr.status IN ('approved', 'rejected'))
    ORDER BY date DESC
    LIMIT 5";
$activity_result = $conn->query($activity_sql);

$new_students_result = $conn->query("SELECT name, email, created_at FROM users WHERE role='student' ORDER BY created_at DESC LIMIT 5");


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Certificate Management System</title>
    <link rel="stylesheet" href="admin_dashboard.css">
</head>
<body>
    <header>
        <img src="./images/logo1.png" alt="">
        <nav>
            <a href="admin_dashboard.php">Home</a>
            <a href="viewCertificateApplication.php">View Certificate Applications</a>
            <a href="viewReissue.php">View Reissue Applications</a>
            <a href="viewCorrectionApplication.php">View Correction Applications</a>
            <a href="studentListView.php">Student List</a>
            <a href="viewApplications.php">View All Applications</a>
            <a href="dataAnalysis.php">Data Analysis</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>

    <main class="container">
        <div class="header">
            <h1>Admin Dashboard</h1>
            <p>Welcome, <?php echo $admin_name; ?></p>
        </div>

        <div class="stats-section">
            <div class="stat-box">
                <h3><?php echo $total_students; ?></h3>
                <p>Total Students</p>
            </div>
            <div class="stat-box">
                <h3><?php echo $pending_new; ?></h3>
                <p>Pending New Applications</p>
            </div>
            <div class="stat-box">
                <h3><?php echo $pending_reissue; ?></h3>
                <p>Pending Reissue Requests</p>
            </div>
            <div class="stat-box">
                <h3><?php echo $pending_corrects; ?></h3>
                <p>Pending Correction Requests</p>
            </div>
        </div>

        <!-- <div class="actions">
            <h2>Management Links</h2>
            <div class="actions-section">
                <a href="viewCertificateApplication.php" class="action-box">
                    <h4>Manage New Applications</h4>
                    <p>View and process all pending new certificate requests.</p>
                </a>
                <a href="viewReissue.php" class="action-box">
                    <h4>Manage Reissue Requests</h4>
                    <p>Review and process all pending reissue requests.</p>
                </a>
                <a href="viewCorrectionApplication.php" class="action-box">
                    <h4>Manage Corrections</h4>
                    <p>Review and process all pending correction requests.</p>
                </a>
                <a href="studentListView.php" class="action-box">
                    <h4>View All Students</h4>
                    <p>View a complete list of all registered student records.</p>
                </a>
            </div>
        </div> -->

        <div class="records">
            <div class="info">
                <h2>Recent Processed Activities</h2>
                <div>
                    <table>
                        <thead>
                            <tr>
                                <th>Student Name</th>
                                <th>Application Type</th>
                                <th>Date Processed</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($activity_result && $activity_result->num_rows > 0){ ?>
                                <?php while($activity = $activity_result->fetch_assoc()){ ?>
                                    <tr>
                                        <td><?php echo $activity['name']; ?></td>
                                        <td><?php echo $activity['application_type']; ?></td>
                                        <td><?php echo date('d M, Y', strtotime($activity['date'])); ?></td>
                                    </tr>
                                <?php } ?>
                            <?php }else{ ?>
                                <tr>
                                    <td colspan="3">No recently processed activities found.</td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="info">
                <h2>Newly Registered Students</h2>
                <div class="user-list">
                    <?php if ($new_students_result && $new_students_result->num_rows > 0){ ?>
                        <?php while($student = $new_students_result->fetch_assoc()){ ?>
                            <div class="user-item">
                                <div class="user-details">
                                    <h4><?php echo $student['name']; ?></h4>
                                    <p><?php echo $student['email']; ?></p>
                                </div>
                                <span class="user-date">Joined: <?php echo date('d M, Y', strtotime($student['created_at'])); ?></span>
                            </div>
                        <?php } ?>
                    <?php }else{ ?>
                        <p>No new students have registered recently.</p>
                    <?php } ?>
                </div>
            </div>
        </div>
    </main>
</body>
</html>

