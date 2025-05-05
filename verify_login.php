<?php
session_start();
include "Database.php";
include "Admin/admin_account/connection.php";

// Load Composer autoloader and PHPMailer
if (!file_exists('vendor/autoload.php')) {
    die("Error: Composer autoloader not found. Please run 'composer install' in the project directory to install required dependencies (e.g., otphp/otphp, league/oauth2-client, and phpmailer/phpmailer).");
}
require_once 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (!isset($_SESSION['user_email']) && !isset($_SESSION['admin_email'])) {
    echo "<script>alert('Unauthorized access. Please log in first.'); window.location.href='Login.php';</script>";
    exit();
}

$email = $_SESSION['user_email'] ?? $_SESSION['admin_email'];
$redirectUrl = $_SESSION['redirect_after_verify'] ?? 'Home.php';

if (isset($_POST['verify'])) {
    $inputCode = trim($_POST['code']);
    $storedCode = $_SESSION['verification_code'] ?? '';

    if ($inputCode === $storedCode) {
        unset($_SESSION['verification_code']); // Clear code after successful verification
        header("Location: $redirectUrl");
        exit();
    } else {
        echo "<script>alert('Invalid verification code. Please try again.'); window.location.href='verify_login.php';</script>";
    }
}

if (isset($_POST['resend'])) {
    $name = 'User'; // Default name, can be fetched from database if needed
    $query = "SELECT Name FROM accounts WHERE email = ? UNION SELECT Name FROM adminaccounts WHERE email = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ss", $email, $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if ($row = mysqli_fetch_assoc($result)) {
        $name = $row['Name'];
    }

    // Resend verification code
    $verificationCode = sprintf("%06d", mt_rand(100000, 999999));
    $_SESSION['verification_code'] = $verificationCode;

    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'kolipojohn@gmail.com'; // Replace with your Gmail address
        $mail->Password = 'btig wrnh vcgu jlyb'; // Replace with your Gmail app-specific password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('kolipojohn@gmail.com', 'Librow Security');
        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->Subject = 'Login Verification Code';
        $mail->Body = "Dear $name,<br><br>Your verification code for login is: <strong>$verificationCode</strong><br>Please enter this code to complete your login.<br><br>Best regards,<br>Librow Security Team";
        $mail->AltBody = "Dear $name,\n\nYour verification code for login is: $verificationCode\nPlease enter this code to complete your login.\n\nBest regards,\nLibrow Security Team";

        $mail->send();
        echo "<script>alert('Verification code resent. Please check your email.'); window.location.href='verify_login.php';</script>";
    } catch (Exception $e) {
        echo "<script>alert('Failed to resend verification email: " . $e->getMessage() . "'); window.location.href='verify_login.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="assetacc/account_style.css">
    <title>Verify Login</title>
    <style>
        .container {
            max-width: 400px;
            margin: 50px auto;
        }
        .form.verify {
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .input-field {
            margin-bottom: 15px;
            position: relative;
        }
        .input-field input {
            width: 100%;
            padding: 10px 10px 10px 40px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .input-field .icon {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #666;
            font-size: 18px;
        }
        .input-field.button {
            display: flex;
            justify-content: center;
        }
        .input-field.button input {
            background: #eba027;
            color: #fff;
            border: none;
            cursor: pointer;
            padding: 10px;
            width: 100%;
            max-width: 300px;
            margin: 0 auto;
        }
        .input-field.button input:hover {
            background: #d78e0f;
        }
        .resend-link {
            text-align: center;
            margin-top: 10px;
        }
        .resend-link input {
            background: #eba027;
            color: #fff;
            border: none;
            cursor: pointer;
            padding: 8px 15px;
            border-radius: 4px;
            text-decoration: none;
        }
        .resend-link input:hover {
            background: #d78e0f;
        }
        .back-arrow {
            position: absolute;
            right: 95%;
            top: 10px;
            font-size: 30px;
            color: #000000;
            cursor: pointer;
            text-decoration: none;
        }
        .back-arrow:hover {
            color: #333333;
        }
        .form-header {
            position: relative;
            padding-top: 50px; /* Maintains top spacing */
            padding-bottom: 20px; /* Added bottom spacing for the arrow */
        }
    </style>
</head>
<body>
<div class="container">
    <div class="forms">
        <div class="form verify">
            <span class="title">Verify Login</span>
            <div class="form-header">
                <a href="Login.php" class="back-arrow">
                    <i class="uil uil-arrow-left"></i>
                </a>
                <p>Please enter the 6-digit code sent to your email (<?php echo htmlspecialchars($email); ?>).</p>
            </div>
            <form action="" method="POST">
                <div class="input-field">
                    <input type="text" placeholder="Enter verification code" name="code" required maxlength="6">
                    <i class="uil uil-shield-check icon"></i>
                </div>
                <div class="input-field button">
                    <input type="submit" value="Verify" name="verify">
                </div>
                <div class="resend-link">
                    <form action="" method="POST">
                        <input type="submit" value="Resend Code" name="resend">
                    </form>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>