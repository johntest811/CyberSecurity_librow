<?php
require_once 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


include "Admin/admin_account/connection.php";

function startSecureSession() {

    ini_set('session.use_only_cookies', 1);
    ini_set('session.cookie_httponly', 1);
    ini_set('session.cookie_secure', 1); 
    session_start();


    if (!isset($_SESSION['token'])) {
        $_SESSION['token'] = bin2hex(random_bytes(32));
    }
}

function logActivity($conn, $activity, $success = 1) {
    $ip = $_SERVER['REMOTE_ADDR'];
    $email = $_SESSION['user_email'] ?? $_SESSION['admin_email'] ?? 'unknown';
    $query = "INSERT INTO activity_log (ip_address, email, activity, success, activity_time) VALUES (?, ?, ?, ?, NOW())";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "sssi", $ip, $email, $activity, $success);
    mysqli_stmt_execute($stmt);
}

function checkSession() {
    global $conn;
    if (!isset($_SESSION['token']) || (!isset($_SESSION['user_email']) && !isset($_SESSION['admin_email']))) {
        session_destroy();
        header("Location: Login.php");
        exit();
    }

    // Check ng session inactivity 
    $inactivity_limit = 900;
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $inactivity_limit)) {
        $username = $_SESSION['user_email'] ?? $_SESSION['admin_email'];
        logActivity($conn, "Session expired due to inactivity for $username");
        
        // Send nag session expired email
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'kolipojohn@gmail.com';
            $mail->Password = 'btig wrnh vcgu jlyb';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('kolipojohn@gmail.com', 'Librow Security');
            $mail->addAddress($username);
            $mail->isHTML(true);
            $mail->Subject = 'Session Expired Notification';
            $mail->Body = "Dear User,<br><br>Your session has expired due to inactivity.<br><br>Best regards,<br>Librow Security Team";
            $mail->send();
        } catch (Exception $e) {
            error_log('Failed to send session expired email: ' . $e->getMessage());
        }
        
        session_destroy();
        header("Location: Login.php?message=session_expired");
        exit();
    }
    $_SESSION['last_activity'] = time();
}

function sendSuspiciousActivityEmail($email, $name, $ip) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'kolipojohn@gmail.com';
        $mail->Password = 'btig wrnh vcgu jlyb';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('kolipojohn@gmail.com', 'Librow Security');
        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->Subject = 'Suspicious Activity Detected';
        $mail->Body = "Dear $name,<br><br>We detected a login attempt from a new IP address ($ip).<br><br>Best regards,<br>Librow Security Team";
        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}

function checkNewIP($conn, $email, $ip) {
    $query = "SELECT COUNT(*) as count FROM activity_log WHERE email = ? AND ip_address = ? AND activity LIKE 'Login%'";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ss", $email, $ip);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    return $row['count'] == 0;
}
?>