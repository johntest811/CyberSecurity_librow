<?php
session_start();
include "Database.php";
include "Admin/admin_account/connection.php";
include "security.php";

// PARA SA Composer's 
if (!file_exists('vendor/autoload.php')) {
    die("Error: Composer autoloader not found. Please run 'composer install' in the project directory to install required dependencies (e.g., otphp/otphp, league/oauth2-client, and phpmailer/phpmailer).");
}

require_once 'vendor/autoload.php';
use League\OAuth2\Client\Provider\Google;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// PARA SA Google OAuth Configuration
$google = new Google([
    'clientId'     => '495978947642-tbvnfgu8cfef529abuk78ts337a7drmh.apps.googleusercontent.com',
    'clientSecret' => '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',
    'redirectUri'  => 'http://localhost/CyberSecurity_librow/Login.php',
]);

// Check PARA SA user is in cooldown
$ip = $_SERVER['REMOTE_ADDR'];
$email = isset($_SESSION['last_attempted_email']) ? $_SESSION['last_attempted_email'] : '';
$sessionKey = 'login_attempts_' . $ip . '_' . $email;
$attempts = $_SESSION[$sessionKey] ?? 0;
$cooldownRemaining = 0;
$isInCooldown = false;

if ($attempts >= 3 && isset($_SESSION['cooldown_end']) && time() < $_SESSION['cooldown_end']) {
    $cooldownRemaining = $_SESSION['cooldown_end'] - time();
    $isInCooldown = true;
}


if (isset($_GET['action']) && $_GET['action'] === 'clear_attempts' && isset($_GET['ip']) && isset($_GET['email'])) {
    $ipToClear = $_GET['ip'];
    $emailToClear = $_GET['email'];
    $query = "DELETE FROM login_attempts WHERE ip_address = ? AND email = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ss", $ipToClear, $emailToClear);
    mysqli_stmt_execute($stmt);
    echo json_encode(['status' => 'success']);
    exit();
}

if (!isset($_GET['code'])) {
    $authUrl = $google->getAuthorizationUrl([
        'access_type' => 'offline',
        'prompt' => 'consent',
        'scope' => ['https://www.googleapis.com/auth/userinfo.email', 'https://www.googleapis.com/auth/userinfo.profile']
    ]);
    $_SESSION['oauth2state'] = $google->getState();
} elseif (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {
    unset($_SESSION['oauth2state']);
    echo "<script>alert('Invalid state. Possible CSRF attack detected.'); window.location.href='Login.php';</script>";
    exit();
} else {
    try {
        $token = $google->getAccessToken('authorization_code', [
            'code' => $_GET['code']
        ]);
        $user = $google->getResourceOwner($token);
        $email = $user->getEmail();
        $name = $user->getName();

        $checkQuery = "SELECT * FROM accounts WHERE email = ?";
        $stmt = mysqli_prepare($conn, $checkQuery);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $checkResult = mysqli_stmt_get_result($stmt);
        if (mysqli_num_rows($checkResult) == 0) {
            $randomPassword = 'google_sso_' . rand(1000, 9999);
            $hashedPassword = password_hash($randomPassword, PASSWORD_BCRYPT);
            $encryptedPassword = openssl_encrypt($hashedPassword, 'AES-256-CBC', '8J2k9xPqW3mZ7rT4vN6bY8cL2hF5jD', 0, 'K9mW3xPqJ2rT7vN6');
            $insertQuery = "INSERT INTO accounts (Name, email, Password, ConPassword) VALUES (?, ?, ?, ?)";
            $insertStmt = mysqli_prepare($conn, $insertQuery);
            mysqli_stmt_bind_param($insertStmt, "ssss", $name, $email, $encryptedPassword, $encryptedPassword);
            mysqli_stmt_execute($insertStmt);
        }
        startSecureSession();
        $_SESSION['user_email'] = $email;
        $_SESSION['last_activity'] = time();

      
        if (checkNewIP($conn, $email, $ip)) {
            sendSuspiciousActivityEmail($email, $name, $ip);
        }

       
        logActivity($conn, "Google SSO Login successful for $email from IP $ip");

        // Send verification code
        $verificationCode = sprintf("%06d", mt_rand(100000, 999999));
        $_SESSION['verification_code'] = $verificationCode;
        $_SESSION['redirect_after_verify'] = 'Home.php';

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
            $mail->Subject = 'Login Verification Code';
            $mail->Body = "Dear $name,<br><br>Your verification code for login is: <strong>$verificationCode</strong><br>Please enter this code to complete your login.<br><br>Best regards,<br>Librow Security Team";
            $mail->AltBody = "Dear $name,\n\nYour verification code for login is: $verificationCode\nPlease enter this code to complete your login.\n\nBest regards,\nLibrow Security Team";

            $mail->send();
            header("Location: verify_login.php");
            exit();
        } catch (Exception $e) {
            echo "<script>alert('Failed to send verification email: " . $e->getMessage() . "'); window.location.href='Login.php';</script>";
            exit();
        }
    } catch (Exception $e) {
        echo "<script>alert('Google OAuth error: " . $e->getMessage() . "'); window.location.href='Login.php';</script>";
    }
}

function checkIntrusion($conn, $ip, $email) {
    $timeLimit = date('Y-m-d H:i:s', strtotime('-10 minutes'));
    $query = "SELECT COUNT(*) as attempts FROM login_attempts WHERE ip_address = ? AND email = ? AND attempt_time > ? AND success = 0";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "sss", $ip, $email, $timeLimit);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    return $row['attempts'];
}

function clearOldAttempts($conn, $ip, $email) {
    $timeLimit = date('Y-m-d H:i:s', strtotime('-10 minutes'));
    $query = "DELETE FROM login_attempts WHERE ip_address = ? AND email = ? AND attempt_time < ? AND success = 0";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "sss", $ip, $email, $timeLimit);
    mysqli_stmt_execute($stmt);
}

function sendIntrusionAlert($conn, $ip, $email) {
    $sessionKey = 'alert_sent_' . $ip . '_' . $email;
    if (isset($_SESSION[$sessionKey]) && $_SESSION[$sessionKey] === true) {
        return;
    }

    $query = "SELECT DISTINCT email FROM login_attempts WHERE ip_address = ? AND success = 0 AND email IS NOT NULL LIMIT 1";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $ip);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if ($row = mysqli_fetch_assoc($result)) {
        $email = $row['email'];
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'kolipojohn@gmail.com';
            $mail->Password = 'btig wrnh vcgu jlyb';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('kolipojohn@gmail.com', 'Librow Security Alert');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Security Alert: Multiple Failed Login Attempts';
            $mail->Body = "Dear User,<br><br>Our system has detected 3 failed login attempts from your IP address ($ip). This may indicate a potential security breach. For your safety, your account has been temporarily restricted for 20 seconds.<br><br>Please contact support if you did not initiate these attempts.<br><br>Best regards,<br>Librow Security Team";
            $mail->AltBody = "Dear User,\n\nOur system has detected 3 failed login attempts from your IP address ($ip). This may indicate a potential security breach. For your safety, your account has been temporarily restricted for 20 seconds.\n\nPlease contact support if you did not initiate these attempts.\n\nBest regards,\nLibrow Security Team";

            $mail->send();
            $_SESSION[$sessionKey] = true;
        } catch (Exception $e) {
            
        }
    }
}

function logAttempt($conn, $ip, $email, $success) {
    $query = "INSERT INTO login_attempts (ip_address, email, attempt_time, success) VALUES (?, ?, NOW(), ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ssi", $ip, $email, $success);
    mysqli_stmt_execute($stmt);
}

function sendVerificationCode($email, $name, $conn, $ip) {
    $verificationCode = sprintf("%06d", mt_rand(100000, 999999));
    $_SESSION['verification_code'] = $verificationCode;

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
        $mail->Subject = 'Login Verification Code';
        $mail->Body = "Dear $name,<br><br>Your verification code for login is: <strong>$verificationCode</strong><br>Please enter this code to complete your login.<br><br>Best regards,<br>Librow Security Team";
        $mail->AltBody = "Dear $name,\n\nYour verification code for login is: $verificationCode\nPlease enter this code to complete your login.\n\nBest regards,\nLibrow Security Team";

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}

if (isset($_POST["login"])) {
    $ip = $_SERVER['REMOTE_ADDR'];
    $email = mysqli_real_escape_string($conn, $_POST["email"]);
    $_SESSION['last_attempted_email'] = $email;
    $password = $_POST["Password"];

    $sessionKey = 'login_attempts_' . $ip . '_' . $email;
    $attempts = $_SESSION[$sessionKey] ?? 0;

    if ($attempts >= 3) {
        if (!isset($_SESSION['cooldown_end']) || time() >= $_SESSION['cooldown_end']) {
            $_SESSION[$sessionKey] = 0;
            unset($_SESSION['cooldown_end']);
            unset($_SESSION['alert_sent_' . $ip . '_' . $email]);
            clearOldAttempts($conn, $ip, $email);
        } else {
            $remaining = $_SESSION['cooldown_end'] - time();
            echo "<script>
                alert('Too many failed attempts. Please wait $remaining seconds before trying again.');
                window.location.href='Login.php?cooldown=$remaining';
            </script>";
            exit();
        }
    }

    // Check admin login
    $adminQuery = "SELECT * FROM adminaccounts WHERE email = ?";
    $stmt = mysqli_prepare($conn, $adminQuery);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $adminResult = mysqli_stmt_get_result($stmt);
    if (mysqli_num_rows($adminResult) > 0) {
        $admin = mysqli_fetch_assoc($adminResult);
        if (password_verify($password, $admin['Password'])) {
            startSecureSession();
            $_SESSION['admin_email'] = $email;
            $_SESSION['last_activity'] = time();
            $_SESSION['redirect_after_verify'] = 'admin/admin_dashboard/admin_dashboard1.php';

            
            if (checkNewIP($conn, $email, $ip)) {
                sendSuspiciousActivityEmail($email, $admin['Name'] ?? 'Admin', $ip);
            }

            // Log successful login
            logActivity($conn, "Admin login successful for $email from IP $ip");

            if (sendVerificationCode($email, $admin['Name'] ?? 'Admin', $conn, $ip)) {
                unset($_SESSION[$sessionKey]);
                unset($_SESSION['alert_sent_' . $ip . '_' . $email]);
                clearOldAttempts($conn, $ip, $email);
                header("Location: verify_login.php");
                exit();
            } else {
                echo "<script>alert('Failed to send verification email. Please try again.'); window.location.href='Login.php';</script>";
                exit();
            }
        }
    }

    //user login
    $userQuery = "SELECT * FROM accounts WHERE email = ?";
    $stmt = mysqli_prepare($conn, $userQuery);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $userResult = mysqli_stmt_get_result($stmt);
    if (mysqli_num_rows($userResult) > 0) {
        $user = mysqli_fetch_assoc($userResult);
        $decryptedPassword = openssl_decrypt($user['Password'], 'AES-256-CBC', '8J2k9xPqW3mZ7rT4vN6bY8cL2hF5jD', 0, 'K9mW3xPqJ2rT7vN6');
        if ($decryptedPassword && password_verify($password, $decryptedPassword)) {
            startSecureSession();
            $_SESSION['user_email'] = $email;
            $_SESSION['last_activity'] = time();
            $_SESSION['redirect_after_verify'] = 'Home.php';

         
            if (checkNewIP($conn, $email, $ip)) {
                sendSuspiciousActivityEmail($email, $user['Name'] ?? 'User', $ip);
            }

            // KAPAG SUCCESSFUL Log successful login
            logActivity($conn, "User login successful for $email from IP $ip");

            if (sendVerificationCode($email, $user['Name'] ?? 'User', $conn, $ip)) {
                unset($_SESSION[$sessionKey]);
                unset($_SESSION['alert_sent_' . $ip . '_' . $email]);
                clearOldAttempts($conn, $ip, $email);
                header("Location: verify_login.php");
                exit();
            } else {
                echo "<script>alert('Failed to send verification email. Please try again.'); window.location.href='Login.php';</script>";
                exit();
            }
        }
    }

    // KAPAG NAG Failed login 
    $_SESSION[$sessionKey] = $attempts + 1;
    logAttempt($conn, $ip, $email, 0);
    logActivity($conn, "Failed login attempt for $email from IP $ip", 0);
    if ($_SESSION[$sessionKey] >= 3) {
        $_SESSION['cooldown_end'] = time() + 20;
        sendIntrusionAlert($conn, $ip, $email);
        $remaining = 20;
        echo "<script>
            alert('Invalid login credentials. 3 failed attempts. Cooldown of 20 seconds applied.');
            window.location.href='Login.php?cooldown=$remaining';
        </script>";
    } else {
        echo "<script>alert('Invalid login credentials. " . ($attempts + 1) . "/3 attempts used.'); window.location.href='Login.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="assetacc/account_style.css">
    <title>Secure Login</title>
    <style>
        .google-login {
            background-color: #fff;
            color: #757575;
            border: 1px solid #d3d3d3;
            padding: 10px 15px;
            border-radius: 24px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            font-weight: 500;
            font-size: 14px;
            transition: background-color 0.3s;
            width: 100%;
            max-width: 300px;
            margin: 15px auto;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        }
        .google-login.disabled {
            background-color: #e0e0e0;
            color: #a0a0a0;
            border-color: #d0d0d0;
            pointer-events: none;
            cursor: not-allowed;
        }
        .google-login img {
            width: 18px;
            height: 18px;
            margin-right: 8px;
        }
        .google-login:hover {
            background-color: #f1f3f4;
            border-color: #c1c1c1;
            color: #757575;
        }
        .input-field.button {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
        }
        .input-field.button input {
            width: 100%;
            max-width: 300px;
        }
        .input-field.button input:disabled {
            background-color: #e0e0e0;
            color: #a0a0a0;
            border-color: #d0d0d0;
            cursor: not-allowed;
        }
        .form.login {
            padding-top: 40px;
        }
        #cooldownMessage {
            text-align: center;
            color: #d9534f;
            font-weight: bold;
            margin-top: 10px;
            display: none;
        }
    </style>
</head>
<body>
<header>
    <nav class="navbar">
        <a href="#" class="logo"><img src="librow.png" alt="logo"></a>
        <div class="links">
            <a href="HomeRestricted.html">Home</a>
            <a href="HomeRestricted.html#about">About</a>
            <a href="HomeRestricted.html#services">Services</a>
            <a href="HomeRestricted.html#team">Team</a>
            <a href="HomeRestricted.html#contact">Contact</a>
        </div>
    </nav>
</header>

<div class="container">
    <div class="forms">
        <div class="form login">
            <span class="title">Login</span>
            <form action="" name="form1" method="POST" enctype="multipart/form-data">
                <div class="input-field">
                    <input type="email" placeholder="Enter your email" name="email" required oninput="this.value = this.value.replace(/\s/g, '')">
                    <i class="uil uil-envelope icon"></i>
                </div>
                <div class="input-field">
                    <input type="password" class="password" placeholder="Enter your password" name="Password" required oninput="this.value = this.value.replace(/\s/g, '')">
                    <i class="uil uil-lock icon"></i>
                    <i class="uil uil-eye-slash showHidePw"></i>
                </div>
                <div class="checkbox-text">
                    <div class="checkbox-content">
                        <input type="checkbox" id="logCheck">
                        <label for="logCheck" class="text">Remember me</label>
                    </div>
                    <a href="forget_Password.php" class="text">Forgot password?</a>
                </div>
                <div class="input-field button">
                    <input type="submit" value="Login Now" name="login" id="loginButton" <?php echo $isInCooldown ? 'disabled' : ''; ?>>
                </div>
                <div class="input-field button">
                    <a href="<?php echo $authUrl; ?>" class="google-login <?php echo $isInCooldown ? 'disabled' : ''; ?>" id="googleLogin">
                        <img src="https://www.google.com/favicon.ico" alt="Google Logo"> Sign in with Google
                    </a>
                </div>
                <div id="cooldownMessage"></div>
            </form>
            <div class="login-signup">
                <span class="text">Don't have an account? <a href="Register.php" class="text signup-link">Create Now!</a></span>
            </div>
        </div>
    </div>
</div>

<script src="assetacc/account_script.js"></script>
<script>
    const urlParams = new URLSearchParams(window.location.search);
    let cooldownRemaining = <?php echo $cooldownRemaining; ?> || parseInt(urlParams.get('cooldown')) || 0;
    const cooldownMessage = document.getElementById('cooldownMessage');
    const loginButton = document.getElementById('loginButton');
    const googleLogin = document.getElementById('googleLogin');
    const ip = '<?php echo $ip; ?>';
    const email = '<?php echo addslashes($email); ?>';

    function updateCooldown() {
        if (cooldownRemaining > 0) {
            loginButton.disabled = true;
            googleLogin.classList.add('disabled');
            cooldownMessage.style.display = 'block';
            cooldownMessage.textContent = `Cooldown active. Please wait ${cooldownRemaining} seconds before trying again.`;
            cooldownRemaining--;
            setTimeout(updateCooldown, 1000);
        } else {
            loginButton.disabled = false;
            googleLogin.classList.remove('disabled');
            cooldownMessage.style.display = 'none';
            window.history.replaceState({}, document.title, window.location.pathname);

            if (ip && email) {
                fetch(`Login.php?action=clear_attempts&ip=${encodeURIComponent(ip)}&email=${encodeURIComponent(email)}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.status !== 'success') {
                            console.error('Failed to clear login attempts');
                        }
                    })
                    .catch(error => console.error('Error clearing login attempts:', error));
            }
        }
    }

    if (cooldownRemaining > 0) {
        updateCooldown();
    }

    document.querySelector('form').addEventListener('submit', function(e) {
        if (loginButton.disabled) {
            e.preventDefault();
        }
    });
</script>
</body>
</html>