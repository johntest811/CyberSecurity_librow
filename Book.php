<?php
include "Admin/admin_account/connection.php";
require_once 'security.php';
require_once 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

startSecureSession();

// Check for session timeout 
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 900)) {
    session_unset();
    session_destroy();
    header("Location: Login.php?timeout=1");
    exit();
}
$_SESSION['last_activity'] = time();

checkSession();


$username = isset($_SESSION['user_email']) ? $_SESSION['user_email'] : $_SESSION['admin_email'];


if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    logActivity($link, "User logout for $username");  
    
 
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
        $mail->Subject = 'Logout Notification';
        $mail->Body = "Dear User,<br><br>You have been successfully logged out from your Librow account.<br><br>If this was not you, please contact support immediately.<br><br>Best regards,<br>Librow Security Team";
        $mail->AltBody = "Dear User,\n\nYou have been successfully logged out from your Librow account.\n\nIf this was not you, please contact support immediately.\n\nBest regards,\nLibrow Security Team";

        $mail->send();
    } catch (Exception $e) {
        error_log('Failed to send logout email: ' . $e->getMessage());
    }
    
    session_destroy();
    header("Location: Login.php");
    exit();
}


$query = "SELECT title, author, bookstock, image FROM librow.books";
$stmt = mysqli_prepare($link, $query);  
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Librow | Book</title>

        <link rel="icon" type="image/x-icon" href="shopassets/favicon.ico" />

        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />

        <link href="shopassets/shopstyles.css" rel="stylesheet" />
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    
    </head>
    <body>

         <!-- Main Nav Bar -->
         <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container px-4 px-lg-5">
                <a class="logo me-auto me-lg-0"><img src="assets/img/librow.png" alt="" class="shop-logo"></a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
                        <li class="nav-item"><a class="nav-link" href="Home.php">Home</a></li>
                        <li class="nav-item"><a class="nav-link" href="Booking.php">Booking</a></li>
                    </ul>
  
                 
             
                    <form class="d-flex" action="Book.php">
                        <button class="btn btn-outline-dark" type="submit">
                            <i class="bi bi-book-fill"></i>
                            Book
                            <span class="badge bg-dark text-white ms-1 rounded-pill">0</span>
                        </button>
                    </form>
                    
                    <form class="d-flex" action="ReturnPage.php">
                        <button class="btn btn-outline-dark" type="submit" >
                            <i class="bi bi-arrow-down-square-fill"></i>
                            Return
                            <span class="badge bg-dark text-white ms-1 rounded-pill">0</span>
                        </button>
                    </form>
  
                    <!-- Display the user's name -->
                    <form class="d-flex">
                        <button class="btn btn-outline-dark">
                            <i class="bi bi-person"></i>
                            User: <b><?php echo htmlspecialchars($username); ?></b>
                        </button>
                    </form>
                    <form class="d-flex" action="Home.php?action=logout" onclick="return confirm('Are you sure you want to logout?');">
                        <button class="btn btn-outline-dark">
                            <i class="bi bi-box-arrow-right"></i>
                            <b>Logout</b>
                        </button>
                    </form>
                </div>
            </div>
        </nav>

        <!-- Header-->
        <header class="bg-dark py-5">
            <div class="container px-4 px-lg-5 my-5">
                <div class="text-center text-white">
                    <h1 class="display-4 fw-bolder">Welcome to Librow Booking!</h1>
                    <p class="lead fw-normal text-white-50 mb-0">Book with us Now!</p>
                </div>
            </div>
        </header>

        <div class="container text-center">
            <div class="row justify-content-center">
                <div class="card">
                    <div class="card-body">             
                        <?php
                            // add book
                            if (isset($_POST["addBook"])) {
                                $title = $_POST["title"];
                                $author = $_POST["author"];
                                $bookstock = $_POST["bookstock"];
                                $imagePath = $_POST["imagePath"];

                                $book = array(
                                    "title" => $title,
                                    "author" => $author,
                                    "bookstock" => $bookstock,
                                    "imagePath" => $imagePath
                                );

                                if (isset($_SESSION["bookList"])) {
                                    $_SESSION["bookList"][] = $book;
                                } else {
                                    $_SESSION["bookList"] = array($book);
                                }
                            }

                            if (isset($_POST["deleteBook"])) {
                                $index = $_POST["bookIndex"];
                                if (isset($_SESSION["bookList"][$index])) {
                                    unset($_SESSION["bookList"][$index]);
                                    $_SESSION["bookList"] = array_values($_SESSION["bookList"]);
                                }
                            }
                        ?>

                        <h1>Selected Books</h1>
                        <?php
                            if (isset($_SESSION["bookList"]) && !empty($_SESSION["bookList"])) {
                                foreach ($_SESSION["bookList"] as $index => $book) {
                                    $title = $book["title"];
                                    $author = $book["author"];
                                    $bookstock = $book["bookstock"];
                                    $imagePath = $book["imagePath"];

                                    echo "<div>";
                                    echo "<img src='$imagePath' alt='Book Image' height='260' width='250'>";
                                    echo "<h3>Title: $title</h3>";
                                    echo "<h4>Author: $author</h4>";
                                    echo "<p>Stock: $bookstock</p>";

                                    echo "<form action='Book.php' method='POST'>";
                                    echo "<input type='hidden' name='bookIndex' value='$index'>";
                                    echo "<button type='submit' class='btn btn-outline-danger' name='deleteBook'>Delete</button>";
                                    echo "</form>";

                                    echo "</div>";
                                }
                            } else {
                                echo "<p>No books selected.</p>";
                            }
                        ?>
                        <br><br>
                        <h2>Please Pick Up Your Book at This location</h2>
                        <br>
                        <div>
                            <iframe style="border:0; width: 100%; height: 400px;" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3860.620994345644!2d121.0480948!3d14.620653599999995!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397b7bf0a362b19%3A0xb7ef71e25faabd8!2sAurora%20Blvd%2C%20Quezon%20City%2C%20Metro%20Manila!5e0!3m2!1sen!2sph!4v1697432936386!5m2!1sen!2sph" frameborder="0" allowfullscreen></iframe>    
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer-->
        <footer class="py-5 bg-dark">
            <div class="container"><p class="m-0 text-center text-white">Copyright &copy; Synix</p></div>
        </footer>
        <!-- Bootstrap core JS-->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Core theme JS-->
        <script src="shopassets/shopscripts.js"></script>
    </body>
</html>