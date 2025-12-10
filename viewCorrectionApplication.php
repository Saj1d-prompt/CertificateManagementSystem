<?php
session_start();
include('db.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

$sql_requests = "SELECT 
                    cr.id AS request_id,
                    cr.requested_at,
                    cr.proof_document,
                    u.name AS student_name,
                    sr.exam_type,
                    sr.exam_year
                 FROM correction_requests cr
                 JOIN users u ON cr.user_id = u.id
                 JOIN certificates cert ON cr.certificate_id = cert.id
                 JOIN student_records sr ON cert.student_record_id = sr.id
                 WHERE cr.status = 'pending'
                 ORDER BY cr.requested_at ASC";

$requests_result = $conn->query($sql_requests);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Correction Certificate | Certificate Management System</title>
    <link rel="stylesheet" href="viewCorrection.css">
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
        <h1>Pending Correction Requests</h1>

        <div class="table">
            <table>
                <thead>
                    <tr>
                        <th>Student Name</th>
                        <th>Certificate</th>
                        <th>Year</th>
                        <th>Correction Requested On</th>
                        <th>Corrected Value</th>
                        <th>Proof</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($requests_result && $requests_result->num_rows > 0){ ?>
                        <?php while($request = $requests_result->fetch_assoc()){ ?>
                            <tr>
                                <td><?php echo $request['student_name']; ?></td>
                                <td><?php echo $request['exam_type'];?></td>
                                <td><?php echo $request['exam_year'];?></td>
                                <td>
                                    <ul class="correction-list">
                                        <?php
                                        $items_sql = "SELECT field_name FROM correction_items WHERE request_id = " . $request['request_id'];
                                        $items_result = $conn->query($items_sql);
                                        while($item = $items_result->fetch_assoc()) {
                                            echo $item['field_name'];
                                        }
                                        ?>
                                    </ul>
                                </td>
                                <td>
                                    <ul class="correction-list">
                                        <?php
                                        $items_sql = "SELECT new_value FROM correction_items WHERE request_id = " . $request['request_id'];
                                        $items_result = $conn->query($items_sql);
                                        while($item = $items_result->fetch_assoc()) {

                                            echo  $item['new_value'];
                                        }
                                        ?>
                                    </ul>
                                </td>
                                <td>
                                    <?php

                                        $filePath = $request['proof_document'];
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
                                    <form action="correctionStatus.php" method="POST" class="action-form">
                                        <input type="hidden" name="request_id" value="<?php echo $request['request_id']; ?>">
                                        <button type="submit" name="action" value="approve" class="btn-approve">Approve</button>
                                    </form>
                                    <button 
                                        type="button" class="btn-reject"
                                        onclick="openRejectModal(<?php echo $request['request_id']; ?>, '<?php echo $request['student_name']; ?>')">
                                        Reject
                                    </button>
                                </td>
                            </tr>
                        <?php } ?>
                    <?php } else{ ?>
                        <tr>
                            <td colspan="7">No pending correction requests found.</td>
                        </tr>
                    <?php }; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div id="rejectModal" class="modal-overlay">
        <div class="modal-content">
            <h3>Reason for Rejection</h3>
            <p>Please provide a reason for rejecting the request from <strong id="studentNameToReject"></strong>.</p>
            
            <form action="correctionStatus.php" method="POST">
                <input type="hidden" id="rejectRequestId" name="request_id">
                <input type="hidden" name="action" value="reject">
                
                <div class="form-group">
                    <label for="rejection_reason">Rejection Reason:</label>
                    <textarea id="rejection_reason" name="rejection_reason" rows="4" required placeholder="e.g., Proof document is not valid, Information provided is incomplete..."></textarea>
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
            document.getElementById('rejectRequestId').value = id;
            document.getElementById('studentNameToReject').textContent = name;
            document.getElementById('rejectModal').style.display = 'flex';
        }

        function closeRejectModal() {
            document.getElementById('rejectModal').style.display = 'none';
        }
    </script>
</body>
</html>

