<?php
session_start();
include('db.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

$sql = "SELECT 
            c.id AS application_id,
            c.type AS application_type,
            c.details,
            c.applied_at,
            c.proof_document_path,
            u.name AS student_name,
            sr.exam_type,
            sr.exam_year
        FROM certificates c
        JOIN users u ON c.user_id = u.id
        JOIN student_records sr ON c.student_record_id = sr.id
        WHERE c.status = 'pending' AND c.type = 'reissue'
        ORDER BY c.applied_at DESC";

$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reissue Certificate | Certificate Management System</title>
    <link rel="stylesheet" href="viewCertificateApplication.css">
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

    <div class="container">
        <h1>Pending Reissue Applications</h1>

        <div class="table">
            <table>
                <thead>
                    <tr>
                        <th>Student Name</th>
                        <th>Certificate For</th>
                        <th>Year</th>
                        <th>Applied On</th>
                        <th>Reason For Reissue</th>
                        <th>Document</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result && $result->num_rows > 0){ ?>
                        <?php while($app = $result->fetch_assoc()){ ?>
                            <tr>
                                <td><?php echo $app['student_name']; ?></td>
                                <td><?php echo $app['exam_type']; ?></td>
                                <td><?php echo $app['exam_year']; ?></td>
                                <td><?php echo date('d M, Y', strtotime($app['applied_at'])); ?></td>
                                <td><?php echo $app['details']; ?></td>
                                <td>
                                    <?php
                                        $filePath = $app['proof_document_path'];
                                        $fileExtension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
                                        $imageExtensions = ['jpg', 'jpeg', 'png'];

                                        if (in_array($fileExtension, $imageExtensions)) {
                                            echo '<a href="' . $filePath . '" target="_blank"><img src="' . $filePath . '" alt="Proof" class="proof"></a>';
                                        } else {
                                            echo '<a href="' . $filePath . '" target="_blank" class="view-doc">View Document</a>';
                                        }
                                    ?>
                                </td>
                                
                                <td>
                                    <form action="certificateApplicationStatus.php" method="POST" class="action-form">
                                        <input type="hidden" name="application_id" value="<?php echo $app['application_id']; ?>">
                                        <button type="submit" name="action" value="approve" class="btn-approve">Approve</button>
                                    </form>
                                    <button 
                                        type="button" class="btn-reject" onclick="openRejectModal(<?php echo $app['application_id']; ?>, '<?php echo $app['student_name']; ?>')">
                                        Reject
                                    </button>
                                </td>
                                
                            </tr>
                        <?php } ?>
                    <?php }else{ ?>
                        <tr>
                            <td colspan="7">No pending applications found.</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    <div id="rejectModal" class="modal-overlay">
        <div class="modal-content">
            <h3>Reason for Rejection</h3>
            <p>Please provide a reason for rejecting the application for <strong id="studentNameToReject"></strong>.</p>
            
            <form action="certificateApplicationStatus.php" method="POST">
                <input type="hidden" id="rejectApplicationId" name="application_id">
                <input type="hidden" name="action" value="reject">
                
                <div class="form-group">
                    <label for="rejection_reason">Rejection Reason:</label>
                    <textarea id="rejection_reason" name="rejection_reason" rows="4" required placeholder="e.g., Supporting document is not clear, Admit card does not match record..."></textarea>
                </div>

                <div class="modal-actions">
                    <button type="button" class="btn-cancel" onclick="closeRejectModal()">Cancel</button>
                    <button type="submit" class="btn-delete-confirm">Submit Rejection</button>
                </div>
            </form>
        </div>
    </div>
    <script>
        function openRejectModal(id, name) {
            document.getElementById('rejectApplicationId').value = id;
            document.getElementById('studentNameToReject').textContent = name;
            document.getElementById('rejectModal').style.display = 'flex';
        }

        function closeRejectModal() {
            document.getElementById('rejectModal').style.display = 'none';
        }
    </script>
</body>
</html>

