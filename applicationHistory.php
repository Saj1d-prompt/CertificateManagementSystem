<?php
session_start();
include('db.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: index.php");
    exit();
}
$user_id = $_SESSION['user_id'];

$sql = "
    SELECT * FROM (
        (SELECT 
            c.id, 
            (CASE c.type WHEN 'new' THEN 'New Certificate' WHEN 'reissue' THEN 'Reissue' END) AS application_type, 
            c.applied_at AS date, 
            c.status, 
            c.reason
        FROM certificates c 
        WHERE c.user_id = $user_id)
        UNION ALL
        (SELECT 
            cr.id, 
            'Correction' AS application_type, 
            cr.requested_at AS date, 
            cr.status, 
            cr.reason
        FROM correction_requests cr 
        WHERE cr.user_id = $user_id)
    ) AS all_applications
    ORDER BY date DESC
";

$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application History | Certificate Management System</title>
    <link rel="stylesheet" href="application_history.css"> 
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
        <h1>Your Application History</h1>
        <p>Here is a complete list of all the applications you have submitted.</p>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Application Type</th>
                        <th>Date Submitted</th>
                        <th>Current Status</th>
                        <th>Reason</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result && $result->num_rows > 0){ ?>
                        <?php while($app = $result->fetch_assoc()){ ?>
                            <tr>
                                <td>
                                    <span>
                                        <?php echo $app['application_type']; ?>
                                    </span>
                                </td>
                                <td><?php echo date('d M, Y', strtotime($app['date'])); ?></td>
                                <td>
                                    <span class="status status-<?php echo strtolower($app['status']); ?>">
                                        <?php echo $app['status']; ?>
                                    </span>
                                </td>
                                <td>
                                    <span>
                                        <?php echo $app['reason']; ?>
                                    </span>
                                </td>
                            </tr>
                        <?php } ?>
                    <?php }else{ ?>
                        <tr>
                            <td colspan="4">You have not submitted any applications yet.</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>
