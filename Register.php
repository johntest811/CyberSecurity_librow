<?php
session_start();
include "Database.php";
include "Admin/admin_account/connection.php";

// Check if Composer's autoloader exists
if (!file_exists('vendor/autoload.php')) {
    die("Error: Composer autoloader not found. Please run 'composer install' in the project directory to install required dependencies (e.g., otphp/otphp, league/oauth2-client, and phpmailer/phpmailer).");
}

require_once 'vendor/autoload.php';
use OTPHP\TOTP;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Handle AJAX request for sending OTP
if (isset($_POST['action']) && $_POST['action'] === 'send_otp') {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $response = ['success' => false, 'message' => ''];

    if (empty($email)) {
        $response['message'] = 'Please enter an email address.';
    } else {
        $otp = rand(100000, 999999);
        $_SESSION['otp'] = $otp;
        $_SESSION['otp_email'] = $email;
        $_SESSION['otp_time'] = time();
        $_SESSION['otp_requested'] = true;
        $_SESSION['otp_cooldown'] = time();

        if (sendOTP($email, $otp)) {
            $response['success'] = true;
            $response['message'] = "OTP has been sent to $email. Please check your inbox.";
        } else {
            $response['message'] = 'Failed to send OTP. Please try again.';
        }
    }
    echo json_encode($response);
    exit();
}

function sendOTP($email, $otp) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'kolipojohn@gmail.com'; // Dito natin nilalagay yung email na mag se-send ng messages
        $mail->Password = 'btig wrnh vcgu jlyb'; // Application password ng gmail 
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('kolipojohn@gmail.com', 'Librow Registration');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Your OTP for Registration';
        $mail->Body    = "Your OTP for registration is: <b>$otp</b>. It is valid for 10 minutes.";
        $mail->AltBody = "Your OTP for registration is: $otp. It is valid for 10 minutes.";

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}

// Dito mag Insert sa Database 
if (isset($_POST["insert"])) {
    $name = mysqli_real_escape_string($conn, $_POST['Name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['Password'];
    $conpassword = $_POST['ConPassword'];
    $otp = $_POST['otp'] ?? '';

    if (empty($name) || empty($email) || empty($password) || empty($conpassword) || empty($otp)) {
        echo "<script>alert('All fields are required.');</script>";
    } elseif ($password !== $conpassword) {
        echo "<script>alert('The passwords do not match.');</script>";
    } elseif (!isset($_SESSION['otp_requested']) || !isset($_SESSION['otp']) || $_SESSION['otp_email'] !== $email || $_SESSION['otp'] != $otp || (time() - $_SESSION['otp_time'] > 600)) {
        echo "<script>alert('Invalid or expired OTP. Please request a new one.');</script>";
    } else {
        $checkQuery = "SELECT * FROM accounts WHERE email = ?";
        $stmt = mysqli_prepare($conn, $checkQuery);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $checkResult = mysqli_stmt_get_result($stmt);
        if (mysqli_num_rows($checkResult) > 0) {
            echo "<script>alert('Email already registered. Please use a different email.');</script>";
        } else {
            //Dito nag Hash yung password
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            $encryptedPassword = openssl_encrypt($hashedPassword, 'AES-256-CBC', '8J2k9xPqW3mZ7rT4vN6bY8cL2hF5jD', 0, 'K9mW3xPqJ2rT7vN6');
            if (!class_exists('OTPHP\TOTP')) {
                $insertQuery = "INSERT INTO accounts (Name, email, Password, ConPassword) VALUES (?, ?, ?, ?)";
                $stmt = mysqli_prepare($conn, $insertQuery);
                mysqli_stmt_bind_param($stmt, "ssss", $name, $email, $encryptedPassword, $encryptedPassword);
            } else {
                $totp = TOTP::create();
                $totp_secret = $totp->getSecret();
                $insertQuery = "INSERT INTO accounts (Name, email, Password, ConPassword, totp_secret) VALUES (?, ?, ?, ?, ?)";
                $stmt = mysqli_prepare($conn, $insertQuery);
                mysqli_stmt_bind_param($stmt, "sssss", $name, $email, $encryptedPassword, $encryptedPassword, $totp_secret);
            }
            if (mysqli_stmt_execute($stmt)) {
                unset($_SESSION['otp']);
                unset($_SESSION['otp_email']);
                unset($_SESSION['otp_time']);
                unset($_SESSION['otp_requested']);
                unset($_SESSION['otp_cooldown']);
                $message = class_exists('OTPHP\TOTP') ? "Registration Successful. Scan this QR code with your 2FA app: " . $totp->getProvisioningUri() . "." : "Registration Successful.";
                echo "<script>alert('$message'); window.location.href='Login.php';</script>";
            } else {
                echo "<script>alert('Error during registration: " . mysqli_error($conn) . "');</script>";
            }
        }
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
    <title>Secure Registration</title>
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
        .form.login {
            padding-top: 40px;
        }
        .otp-container {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 25px;
            margin-top: 30px; /* Add more space at the top of Enter OTP */
        }
        .otp-container input {
            flex: 1;
            height: 45px;
            padding: 0 35px;
            border: none;
            outline: none;
            font-size: 16px;
            font-family: "Raleway", sans-serif;
            border-bottom: 2px solid #ccc;
            border-top: 2px solid transparent;
            transition: all 0.2s ease;
        }
        .otp-container input:is(:focus, :valid) {
            border-bottom-color: #c08a54;
        }
        .otp-container button {
            height: 45px;
            padding: 0 15px;
            background-color: #eba027;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 14px; /* Smaller font size for Send OTP button text */
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .otp-container button:hover {
            background-color: #ffc451;
        }
        .otp-container button:disabled {
            background-color: #6c757d;
            cursor: not-allowed;
        }
        .otp-cooldown {
            font-size: 12px;
            color: #6c757d;
            margin-left: 10px;
        }
     
        .container {
            margin-top: 180px !important; 
            max-width: 450px !important;
        }
        .container .forms {
            height: 600px !important; 
        }
        .form .input-field {
            margin-top: 25px; 
        }
        .form .checkbox-text {
            margin-top: 25px; 
        }
        .form .button {
            margin-top: 40px;
        }
        .form .login-signup {
            margin-top: 25px; 
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
        <div class="form signup">
            <span class="title">Registration</span>
            <form action="" name="form1" method="post" enctype="multipart/form-data">
                <div class="input-field">
                    <input type="text" placeholder="Enter your name" name="Name" required>
                    <i class="uil uil-user icon"></i>
                </div>
                <div class="input-field">
                    <input type="email" placeholder="Enter your email" name="email" id="emailInput" required oninput="this.value = this.value.replace(/\s/g, '')">
                    <i class="uil uil-envelope icon"></i>
                </div>
                <div class="otp-container">
                    <input type="text" placeholder="Enter OTP" name="otp" <?php echo isset($_SESSION['otp_requested']) ? 'required' : ''; ?>>
                    <button type="button" id="sendOtpBtn">Send OTP</button>
                    <span class="otp-cooldown" id="cooldownText"></span>
                </div>
                <div class="input-field">
                    <input type="password" class="password" placeholder="Create password" name="Password" required>
                    <i class="uil uil-lock icon"></i>
                </div>
                <div class="input-field">
                    <input type="password" class="password" placeholder="Confirm password" name="ConPassword" required>
                    <i class="uil uil-lock icon"></i>
                    <i class="uil uil-eye-slash showHidePw"></i>
                </div>
                <div class="checkbox-text">
                    <div class="checkbox-content">
                        <input type="checkbox" id="termCon" required>
                        <label for="termCon" class="text">I accepted all terms and conditions</label>
                    </div>
                </div>
                <div class="input-field button">
                    <input type="submit" value="Register Now" name="insert">
                </div>
            </form>
            <div class="login-signup">
                <span class="text">Already have an account? <a href="Login.php" class="text login-link">Login Now!</a></span>
            </div>
        </div>
    </div>
</div>

<script src="assetacc/account_script.js"></script>
<script>
function startCooldown() {
    const sendOtpBtn = document.getElementById('sendOtpBtn');
    const cooldownText = document.getElementById('cooldownText');
    sendOtpBtn.disabled = true;
    let timeLeft = 10;

    cooldownText.textContent = `Wait ${timeLeft}s`;
    const timer = setInterval(() => {
        timeLeft--;
        cooldownText.textContent = `Wait ${timeLeft}s`;
        if (timeLeft <= 0) {
            clearInterval(timer);
            sendOtpBtn.disabled = false;
            cooldownText.textContent = '';
            <?php unset($_SESSION['otp_cooldown']); ?>
        }
    }, 1000);
}

document.getElementById('sendOtpBtn').addEventListener('click', function(e) {
    const emailInput = document.getElementById('emailInput').value;

    if (!emailInput) {
        alert('Please enter an email address.');
        return;
    }

    if (<?php echo isset($_SESSION['otp_cooldown']) && (time() - $_SESSION['otp_cooldown'] < 10) ? 'true' : 'false'; ?>) {
        alert('Please wait before requesting a new OTP.');
        return;
    }

    // Dito nag Send AJAX request to send OTP
    fetch('', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'action=send_otp&email=' + encodeURIComponent(emailInput)
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
        if (data.success) {
            startCooldown();
        }
    })
    .catch(error => {
        alert('An error occurred while sending OTP: ' + error);
    });
});
</script>
</body>
</html>