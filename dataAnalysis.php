<?php
session_start();
include('db.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

$new_count = $conn->query("SELECT COUNT(*) as count FROM certificates WHERE type='new'")->fetch_assoc()['count'];
$reissue_count = $conn->query("SELECT COUNT(*) as count FROM certificates WHERE type='reissue'")->fetch_assoc()['count'];
$correction_count = $conn->query("SELECT COUNT(*) as count FROM correction_requests")->fetch_assoc()['count'];
$applicationTypesData = [
    'labels' => ['New', 'Reissue', 'Correction'],
    'data' => [$new_count, $reissue_count, $correction_count]
];
$applicationTypesDataJSON = json_encode($applicationTypesData);

$studentsOverTimeSql = "
    SELECT CAST(created_at AS DATE) as registration_date, COUNT(*) as count 
    FROM users 
    WHERE role = 'student' AND created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
    GROUP BY registration_date
    ORDER BY registration_date ASC
";
$studentsOverTimeResult = $conn->query($studentsOverTimeSql);
$studentLabels = [];
$studentData = [];
while ($row = $studentsOverTimeResult->fetch_assoc()) {
    $studentLabels[] = date('M d', strtotime($row['registration_date']));
    $studentData[] = $row['count'];
}
$studentsOverTimeData = [
    'labels' => $studentLabels,
    'data' => $studentData
];
$studentsOverTimeDataJSON = json_encode($studentsOverTimeData);

$statusSql = "
    SELECT status, COUNT(*) as count FROM (
        (SELECT status FROM certificates) 
        UNION ALL 
        (SELECT status FROM correction_requests)
    ) as all_statuses 
    GROUP BY status;
";
$statusResult = $conn->query($statusSql);
$statusLabels = [];
$statusData = [];
while ($row = $statusResult->fetch_assoc()) {
    $statusLabels[] = ucfirst($row['status']);
    $statusData[] = $row['count'];
}
$overallStatusData = [
    'labels' => $statusLabels,
    'data' => $statusData
];
$overallStatusDataJSON = json_encode($overallStatusData);

$boardSql = "
    SELECT sr.board, COUNT(*) as count FROM (
        (SELECT student_record_id FROM certificates)
        UNION ALL 
        (SELECT c.student_record_id FROM correction_requests cr JOIN certificates c ON cr.certificate_id = c.id)
    ) as app_records
    JOIN student_records sr ON app_records.student_record_id = sr.id
    GROUP BY sr.board
    ORDER BY count DESC
    LIMIT 10 -- Limit to top 10 boards for clarity
";
$boardResult = $conn->query($boardSql);
$boardLabels = [];
$boardData = [];
if($boardResult) {
    while ($row = $boardResult->fetch_assoc()) {
        $boardLabels[] = $row['board'];
        $boardData[] = $row['count'];
    }
}
$volumeByBoardData = [
    'labels' => $boardLabels,
    'data' => $boardData
];
$volumeByBoardDataJSON = json_encode($volumeByBoardData);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Analysis | Admin Panel</title>
    <link rel="stylesheet" href="dataAnalysis.css"> 
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

    <main class="admin-container">
        <h1>System Data Analysis</h1>
        
        <div class="charts-grid two-columns">
            <div class="chart-container large">
                <h3>Student Registrations (Last 30 Days)</h3>
                <canvas id="studentRegistrationsChart"></canvas>
            </div>
             <div class="chart-container large">
                <h3>Application Volume by Board (Top 10)</h3>
                 <?php if(!empty($boardLabels)): ?>
                    <canvas id="volumeByBoardChart"></canvas>
                 <?php else: ?>
                    <p class="chart-notice">Board data requires 'student_records' table with a 'board' column.</p>
                 <?php endif; ?>
            </div>
            <div class="chart-container small">
                <h3>Application Types Breakdown</h3>
                <canvas id="applicationTypesChart"></canvas>
            </div>
             <div class="chart-container small"> 
                <h3>Overall Application Status</h3>
                <canvas id="overallStatusChart"></canvas>
            </div>
        </div>
    </main>

    <script>
        const typesData = JSON.parse('<?php echo $applicationTypesDataJSON; ?>');
        const typesCtx = document.getElementById('applicationTypesChart').getContext('2d');
        new Chart(typesCtx, {
            type: 'bar',
            data: {
                labels: typesData.labels,
                datasets: [{
                    label: 'Total Applications', data: typesData.data,
                    backgroundColor: ['rgba(52, 152, 219, 0.7)', 'rgba(241, 196, 15, 0.7)', 'rgba(231, 76, 60, 0.7)'],
                    borderColor: ['#3498db', '#f1c40f', '#e74c3c'], borderWidth: 1
                }]
            },
            options: { indexAxis: 'y', scales: { x: { beginAtZero: true } }, plugins: { legend: { display: false } } }
        });

        const studentRegData = JSON.parse('<?php echo $studentsOverTimeDataJSON; ?>');
        const studentCtx = document.getElementById('studentRegistrationsChart').getContext('2d');
        new Chart(studentCtx, {
            type: 'bar',
            data: {
                labels: studentRegData.labels,
                datasets: [{
                    label: 'New Students per Day', data: studentRegData.data,
                    backgroundColor: 'rgba(155, 89, 182, 0.7)',
                    borderColor: '#9b59b6', 
                    borderWidth: 1
                }]
            },
            options: { scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }, plugins: { legend: { display: false } } }
        });

        const statusData = JSON.parse('<?php echo $overallStatusDataJSON; ?>');
        const statusCtx = document.getElementById('overallStatusChart').getContext('2d');
        new Chart(statusCtx, {
            type: 'pie',
            data: {
                labels: statusData.labels,
                datasets: [{
                    label: 'Overall Status', data: statusData.data,
                    backgroundColor: ['#f39c12', '#27ae60', '#c0392b'], 
                    hoverOffset: 4
                }]
            }
        });
        
        
        <?php if(!empty($boardLabels)){ ?>
        const boardData = JSON.parse('<?php echo $volumeByBoardDataJSON; ?>');
        const boardCtx = document.getElementById('volumeByBoardChart').getContext('2d');
        new Chart(boardCtx, {
            type: 'bar',
            data: {
                labels: boardData.labels,
                datasets: [{
                    label: 'Total Applications', data: boardData.data,
                    backgroundColor: 'rgba(41, 128, 185, 0.7)', 
                    borderColor: '#2980b9', borderWidth: 1
                }]
            },
            options: { scales: { y: { beginAtZero: true } }, plugins: { legend: { display: false } } }
        });
        <?php } ?>

    </script>
</body>
</html>

