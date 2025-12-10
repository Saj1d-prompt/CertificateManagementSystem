<?php
session_start();
include('db.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

$type = "all";

if (isset($_GET['type'])) {
    $type = $_GET['type'];
}

$sql = "
    SELECT c.id, u.name AS student_name, c.type AS application_type, c.applied_at AS date, c.status 
    FROM certificates c 
    JOIN users u ON c.user_id = u.id
    UNION ALL
    SELECT cr.id, u.name AS student_name, 'Correction' AS application_type, cr.requested_at AS date, cr.status 
    FROM correction_requests cr 
    JOIN users u ON cr.user_id = u.id
";

if ($type == "all") {
    $sql = "SELECT * FROM ($sql) AS all_apps ORDER BY date DESC";
} else {
    $sql = "SELECT * FROM ($sql) AS all_apps WHERE application_type = '$type' ORDER BY date DESC";
}
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>All Applications | certificate Management System</title>
    <link rel="stylesheet" href="viewApplications.css">
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
    <h1>All Applications</h1>

    <form method="GET" class="filter-container">
        <label>Filter by Type:</label>
        <select name="type">
            <option value="all">All</option>
            <option value="new">New</option>
            <option value="reissue">Reissue</option>
            <option value="Correction">Correction</option>
        </select>
        <button type="submit">Apply</button>
    </form>

    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Student Name</th>
                    <th>Application Type</th>
                    <th>Date Submitted</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['student_name'] . "</td>";
                        echo "<td>" . $row['application_type'] . "</td>";
                        echo "<td>" . date('d M, Y', strtotime($row['date'])) . "</td>";
                        echo "<td>" . $row['status'] . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No applications found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</main>
</body>
</html>
