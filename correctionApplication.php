<?php
session_start();
include('db.php'); 

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$records_sql = "SELECT
                    c.id AS certificate_id,
                    sr.exam_type, 
                    sr.exam_year
                FROM certificates c
                JOIN student_records sr ON c.student_record_id = sr.id
                WHERE c.user_id = $user_id AND c.status = 'approved' AND c.type = 'new'
                GROUP BY sr.exam_type";

$records_result = $conn->query($records_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Correction Request | Certificate Management System</title>
    <link rel="stylesheet" href="correctionApplication.css">
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

    <div class="container">
        <h2>Correction Request Application</h2>
        
        <form action="correctionProcess.php" method="POST" enctype="multipart/form-data">
            
            <label>Select the certificate you wish to correct</label>
            
            <div class="selection_box">
                <?php if ($records_result && $records_result->num_rows > 0){ ?>
                    <?php while($row = $records_result->fetch_assoc()){ ?>
                        <label class="radio-label">
                            <input type="radio" name="certificate_id" value="<?php echo $row['certificate_id']; ?>" required>
                            <span><?php echo $row['exam_type']; ?></span>
                        </label>
                    <?php } ?>
                <?php }else{ ?>
                    <p>You have no approved certificates available for correction.</p>
                <?php } ?>
            </div>

            <label>Check the box for each item you need to correct</label>
            <div class="correction-fields">
                <div class="correction-item">
                    <label class="checkbox-label"><input type="checkbox" name="fields_to_correct[]" value="student_name" onclick="toggleInput(this)"> Correct My Name</label>
                    <input type="text" name="corrected_student_name" class="correction-input" placeholder="Enter your correct name">
                </div>
                <div class="correction-item">
                    <label class="checkbox-label"><input type="checkbox" name="fields_to_correct[]" value="father_name" onclick="toggleInput(this)"> Correct Father's Name</label>
                    <input type="text" name="corrected_father_name" class="correction-input" placeholder="Enter correct father's name">
                </div>
                <div class="correction-item">
                    <label class="checkbox-label"><input type="checkbox" name="fields_to_correct[]" value="mother_name" onclick="toggleInput(this)"> Correct Mother's Name</label>
                    <input type="text" name="corrected_mother_name" class="correction-input" placeholder="Enter correct mother's name">
                </div>
                <div class="correction-item">
                    <label class="checkbox-label"><input type="checkbox" name="fields_to_correct[]" value="date_of_birth" onclick="toggleInput(this)"> Correct Date of Birth</label>
                    <input type="date" name="corrected_date_of_birth" class="correction-input">
                </div>
            </div>

            <label>Upload Mandatory Supporting Document</label>
            <div class="uploadFile" onclick="document.getElementById('proof_doc').click();">
                <input type="file" id="proof_doc" name="proof_doc" accept=".pdf,.jpg,.jpeg,.png" required>
                <span class="file-upload-label">Click here to choose a file</span> <br>
                <span id="file-name">No file selected</span>
            </div>
            
            <button type="submit" name="apply_correction">
                Submit 
            </button>
        </form>
    </div>

    <script>
        document.getElementById('proof_doc').onchange = function () {
            var fileName = this.files[0] ? this.files[0].name : "No file selected";
            document.getElementById('file-name').textContent = fileName;
        };

        function toggleInput(checkbox) {
            const inputField = checkbox.parentElement.nextElementSibling;
            if (checkbox.checked) {
                inputField.style.display = 'block';
                inputField.required = true;
            } else {
                inputField.style.display = 'none';
                inputField.required = false;
                inputField.value = '';  
            }
        }
    </script>
</body>
</html>

