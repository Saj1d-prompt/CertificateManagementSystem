<?php
session_start();
include('db.php'); 

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$records_sql = "SELECT DISTINCT 
                    sr.id, 
                    sr.exam_type, 
                    sr.exam_year, 
                    sr.gpa
                FROM student_records sr
                JOIN certificates c ON sr.id = c.student_record_id
                WHERE sr.user_id = $user_id AND c.status = 'approved' AND c.type = 'new'";

$records_result = $conn->query($records_sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reissue Application | Certificate Management System</title>
    <link rel="stylesheet" href="certificate_application.css">
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

    <div class="container">
        <h2>Certificate Reissue Application</h2>
        
        <form action="reissueProcess.php" method="POST" enctype="multipart/form-data">
            
            <label>Select the certificate you wish to reissue</label>
            <div class="exam_type">
                <?php if ($records_result && $records_result->num_rows > 0){ ?>
                    <?php while($row = $records_result->fetch_assoc()){ ?>
                        <label>
                            <input type="radio" name="student_record_id" value="<?php echo $row['id']; ?>" required>
                            <span>
                                <strong><?php echo $row['exam_type']; ?></strong> 
                                
                            </span>
                        </label>
                    <?php } ?>
                <?php }else{ ?>
                    <p>You have no approved certificates available for reissue.</p>
                <?php } ?>
            </div>

            <label for="details"> Reason for Reissue</label>
            <textarea name="details" id="details" rows="5" placeholder="e.g., Original certificate was lost, Original certificate was damaged." required></textarea>

            <label>Supporting Document</label>
            <div class="uploadFile" onclick="document.getElementById('support_doc').click();">
                <input type="file" id="support_doc" name="support_doc" accept=".pdf,.jpg,.jpeg,.png" required>
                <span class="file-upload-label">Click here to choose a file</span> <br>
                <span id="file-name">No file selected</span>
            </div>
            
            <button type="submit" name="apply_reissue" <?php if ($records_result->num_rows === 0) echo 'disabled'; ?>>
                Submit 
            </button>

        </form>
    </div>

    <script>
        document.getElementById('support_doc').onchange = function () {
            var fileName = this.files[0] ? this.files[0].name : "No file selected";
            document.getElementById('file-name').textContent = fileName;
        };
    </script>
</body>
</html>
