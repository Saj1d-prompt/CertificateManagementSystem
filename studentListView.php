<?php
session_start();
include('db.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

$sql = "SELECT 
            u.id AS student_id,
            u.name AS student_name,
            u.email,
            MAX(sr.father_name) AS father_name,
            MAX(sr.mother_name) AS mother_name,
            MAX(CASE WHEN sr.exam_type = 'SSC' THEN sr.gpa ELSE NULL END) AS ssc_gpa,
            MAX(CASE WHEN sr.exam_type = 'HSC' THEN sr.gpa ELSE NULL END) AS hsc_gpa
        FROM users u
        LEFT JOIN student_records sr ON u.id = sr.user_id
        WHERE u.role = 'student'
        GROUP BY u.id, u.name, u.email
        ORDER BY u.name ASC";

$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Records | Certificate Management System</title>
    <link rel="stylesheet" href="studentList.css">
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
        <h1>Official Student Information</h1>

        <div class="table">
            <table>
                <thead>
                    <tr>
                        <th>Student ID</th>
                        <th>Student Name</th>
                        <th>Email</th>
                        <th>Father's Name</th>
                        <th>Mother's Name</th>
                        <th>SSC GPA</th>
                        <th>HSC GPA</th>
                        <th> </th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result && $result->num_rows > 0){ ?>
                        <?php while($record = $result->fetch_assoc()){ ?>
                            <tr>
                                <td><?php echo $record['student_id']; ?></td>
                                <td><?php echo $record['student_name']; ?></td>
                                <td><?php echo $record['email']; ?></td>
                                <td><?php echo $record['father_name']; ?></td>
                                <td><?php echo $record['mother_name']; ?></td>
                                <td><?php echo $record['ssc_gpa']; ?></td>
                                <td><?php echo $record['hsc_gpa']; ?></td>
                                <td>
                                    <button 
                                        class="btn-delete" 
                                        onclick="openDeleteModal(<?php echo $record['student_id']; ?>, '<?php echo htmlspecialchars(addslashes($record['student_name'])); ?>')">
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        <?php } ?>
                    <?php }else{ ?>
                        <tr>
                            <td colspan="8">No student records found in the system.</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    <div id="deleteModal" class="modal-overlay">
        <div class="modal-content">
            <h3>Confirm Deletion</h3>
            <p>Are you sure you want to permanently delete the student: <strong id="userNameToDelete"></strong>?</p>
            <div class="modal-actions">
                <form action="deleteStudent.php" method="POST">
                    <input type="hidden" id="userIdToDelete" name="user_id">
                    <button type="button" class="btn-cancel" onclick="closeDeleteModal()">Cancel</button>
                    <button type="submit" class="btn-delete-confirm">Delete</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openDeleteModal(id, name) {
            document.getElementById('userIdToDelete').value = id;
            document.getElementById('userNameToDelete').textContent = name;
            document.getElementById('deleteModal').style.display = 'flex';
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').style.display = 'none';
        }
    </script>
</body>
</html>

