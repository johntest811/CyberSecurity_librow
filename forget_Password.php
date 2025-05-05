<?php
ob_start(); // Start output buffering to prevent stray output
session_start();
include "DataBase.php"; // Updated to use the provided Database.php

// Ensure database connection is available
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    <link rel="stylesheet" href="assetacc/account_style.css">
    <title>Forgot Password</title>
    <style>
        .otp-container {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 25px;
            margin-top: 25px;
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
            font-size: 14px;
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
        .container .forms {
            height: 700px !important; /* Extended height to make background bigger */
        }
    </style>
</head>
<body>
<header>
    <nav class="navbar">
        <a href="#" class="logo">
            <img src="librow.png" alt="logo">
        </a>
        <div class="links">
            <a href="Home.php">Home</a>
            <a href="Home.html#about">About</a>
            <a href="Home.html#services">Services</a>
            <a href="Home.html#team">Team</a>
            <a href="Home.html#contact">Contact</a>
        </div>
    </nav>
</header>

<div class="container">
    <div class="forms">
        <div class="form Forgot-Password">
            <span class="title">Forgot Password</span>
            <form action="" name="form1" method="post" enctype="multipart/form-data" id="resetPasswordForm" onsubmit="return handleSubmit(event)">
                <div class="input-field">
                    <input type="email" placeholder="Enter your email" name="email" id="emailInput" required oninput="this.value = this.value.replace(/\s/g, '')">
                    <i class="uil uil-envelope icon"></i>
                </div>
                <div class="input-field">
                    <input type="password" class="password" placeholder="Enter your previous password" name="previousPassword" required>
                    <i class="uil uil-lock icon"></i>
                    <i class="uil uil-eye-slash showHidePw"></i>
                </div>
                <div class="input-field">
                    <input type="password" class="password" placeholder="Enter your new password" name="Password" required>
                    <i class="uil uil-lock icon"></i>
                    <i class="uil uil-eye-slash showHidePw"></i>
                </div>
                <div class="input-field">
                    <input type="password" class="password" placeholder="Confirm your new password" name="ConPassword" required>
                    <i class="uil uil-lock icon"></i>
                    <i class="uil uil-eye-slash showHidePw"></i>
                </div>
                <div class="otp-container">
                    <input type="text" placeholder="Enter OTP" name="otp" id="otpInput">
                    <button type="button" id="sendOtpBtn">Send OTP</button>
                    <span class="otp-cooldown" id="cooldownText"></span>
                </div>
                <div class="checkbox-text">
                    <div class="checkbox-content">
                        <input type="checkbox" id="logCheck">
                        <label for="logCheck" class="text">Remember me</label>
                    </div>
                </div>
                <div class="input-field button">
                    <input type="submit" value="Reset Password" name="update">
                </div>
                <div class="login-signup">
                    <span class="text">Remembered Password?
                        <a href="Login.php" class="text signup-link">Return to Login!</a>
                    </span>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
require_once 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Handle AJAX request for sending OTP
if (isset($_POST['action']) && $_POST['action'] === 'send_otp') {
    ob_clean(); // Clear any previous output to ensure clean JSON response
    header('Content-Type: application/json'); // Set correct content type

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

// Handle AJAX request for password reset
if (isset($_POST['action']) && $_POST['action'] === 'reset_password') {
    ob_clean();
    header('Content-Type: application/json');

    $response = ['success' => false, 'message' => ''];
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $previousPassword = $_POST['previousPassword'] ?? '';
    $password = $_POST['Password'] ?? '';
    $conpassword = $_POST['ConPassword'] ?? '';
    $otp = $_POST['otp'] ?? '';

    if (empty($previousPassword)) {
        $response['message'] = 'Previous password is required. Please try again.';
    } elseif ($password !== $conpassword) {
        $response['message'] = 'Passwords do not match. Please try again.';
    } else {
        $sql = "SELECT * FROM accounts WHERE email = ?";
        $stmt = mysqli_prepare($conn, $sql);
        if (!$stmt) {
            $response['message'] = 'Database error: ' . $conn->error;
        } else {
            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if (mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                $storedPassword = openssl_decrypt($row['Password'], 'AES-256-CBC', '8J2k9xPqW3mZ7rT4vN6bY8cL2hF5jD', 0, 'K9mW3xPqJ2rT7vN6');
                if ($storedPassword === false || $storedPassword === null) {
                    $response['message'] = 'Failed to decrypt stored password. Please contact support.';
                } elseif (!password_verify($previousPassword, $storedPassword)) {
                    $response['message'] = 'Incorrect previous password. Please try again.';
                } elseif (isset($_SESSION['otp_requested']) && !empty($otp) && (!isset($_SESSION['otp']) || $_SESSION['otp_email'] !== $email || $_SESSION['otp'] != $otp || (time() - $_SESSION['otp_time'] > 600))) {
                    $response['message'] = 'Invalid or expired OTP. Please request a new one.';
                } else {
                    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
                    $encryptedPassword = openssl_encrypt($hashedPassword, 'AES-256-CBC', '8J2k9xPqW3mZ7rT4vN6bY8cL2hF5jD', 0, 'K9mW3xPqJ2rT7vN6');
                    $sql = "UPDATE accounts SET Password = ?, ConPassword = ? WHERE email = ?";
                    $stmt = mysqli_prepare($conn, $sql);
                    if ($stmt) {
                        mysqli_stmt_bind_param($stmt, "sss", $encryptedPassword, $encryptedPassword, $email);
                        if (mysqli_stmt_execute($stmt)) {
                            unset($_SESSION['otp']);
                            unset($_SESSION['otp_email']);
                            unset($_SESSION['otp_time']);
                            unset($_SESSION['otp_requested']);
                            unset($_SESSION['otp_cooldown']);
                            $response['success'] = true;
                            $response['message'] = 'Password updated successfully.';
                        } else {
                            $response['message'] = 'Error updating password: ' . $conn->error;
                        }
                    } else {
                        $response['message'] = 'Database error: ' . $conn->error;
                    }
                }
            } else {
                $response['message'] = 'Email not found. Please check your email address.';
            }
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
        $mail->Username = 'kolipojohn@gmail.com'; // Replace with your Gmail address
        $mail->Password = 'btig wrnh vcgu jlyb'; // Replace with your Gmail app-specific password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('kolipojohn@gmail.com', 'Librow Password Reset');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Your OTP for Password Reset';
        $mail->Body    = "Your OTP for password reset is: <b>$otp</b>. It is valid for 10 minutes.";
        $mail->AltBody = "Your OTP for password reset is: $otp. It is valid for 10 minutes.";

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}

// Clear OTP session data on page load if no form submission
if (!isset($_POST['update']) && !isset($_POST['action'])) {
    unset($_SESSION['otp']);
    unset($_SESSION['otp_email']);
    unset($_SESSION['otp_time']);
    unset($_SESSION['otp_requested']);
    unset($_SESSION['otp_cooldown']);
}
?>

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

    // Send AJAX request to send OTP
    fetch('', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'action=send_otp&email=' + encodeURIComponent(emailInput)
    })
    .then(response => response.text().then(text => {
        console.log('Raw response:', text);
        return JSON.parse(text);
    }))
    .then(data => {
        alert(data.message);
        if (data.success) {
            document.getElementById('otpInput').setAttribute('required', 'required');
            startCooldown();
        }
    })
    .catch(error => {
        alert('An error occurred while sending OTP: ' + error);
    });
});

function handleSubmit(event) {
    event.preventDefault();
    const form = document.getElementById('resetPasswordForm');
    const formData = new FormData(form);

    fetch('', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams(formData).toString() + '&action=reset_password'
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            window.location.href = 'Login.php';
        } else {
            alert(data.message);
        }
    })
    .catch(error => {
        alert('An error occurred: ' + error);
    });
    return false;
}
</script>
</body>
</html>
<?php
ob_end_flush(); // Flush the output buffer at the end of the script
?>