<?php
include('db.php');
session_start();
$msg = "";
$popupClass = "";

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    //email: admin@gmail.com
    //password: admin

    //email: sajid@gmail.com
    //password: sajid
    $res = $conn->query("SELECT * FROM users WHERE email='$email'");
    if($res && $res->num_rows == 1){
        $user = $res->fetch_assoc();
        if($user['email'] == $email && $user['password'] == $password){
            
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] == 'admin') {
                header("Location: admin_dashboard.php");
                exit();
            }

            $checkSql = "SELECT id FROM student_records WHERE user_id = $_SESSION[user_id]";
            $checkResult = $conn -> query($checkSql);

            if ($checkResult->num_rows > 0) {
                header("Location: student_dashboard.php");
            } else {
                header("Location: studentInfo.php");
            }
            exit(); 
            
        }
        else{
            $msg = "Invalid password!";
            $popupClass = "error";
        }
    }else{
        $msg = "User not found!";
        $popupClass = "error";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Certificate Management System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Certificate Management System</h2>
        <h3>Login</h3>

        <?php if ($msg !== ""){ ?>
            <div class="popup <?php echo $popupClass; ?>"><?php echo $msg; ?></div>
        <?php } ?>
        
        <form method="POST">
            <input type="email" name="email" placeholder="Email" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <button type="submit" name="login">Login</button>
        </form>

        <p class="links">
            <a href="./createAccount.php">Create an Account</a> | 
            <a href="forgetPassword.php">Forgot Password?</a>
        </p>
    </div>
</body>
</html>