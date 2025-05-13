<?php
include "Admin/admin_account/connection.php";
require_once 'security.php';
require_once 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

startSecureSession();

// Check for session timeout (15 minutes = 900 seconds)
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 900)) {
    session_unset();
    session_destroy();
    header("Location: Login.php?timeout=1");
    exit();
}
$_SESSION['last_activity'] = time();

checkSession();


$username = isset($_SESSION['user_email']) ? $_SESSION['user_email'] : $_SESSION['admin_email'];

// para sa logout
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    logActivity($link, "User logout for $username");  
    
    // Send yung logout notification email
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
    <title>Librow | Booking</title>

    <link rel="icon" type="image/x-icon" href="assets/img/logo librow.png" />

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
                <!-- <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarDropdown" href="Shop.html" role="button" data-bs-toggle="dropdown" aria-expanded="false">Select</a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="Shop.html">All Books</a></li>
                        <li><hr class="dropdown-divider" /></li>
                        <li><a class="dropdown-item" href="#!">Popular Items</a></li>
                        <li><a class="dropdown-item" href="#!">New Arrivals</a></li>
                    </ul>
                </li> -->
            </ul>

            <!-- Top Button -->
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
                </button>
            </form>

           
   

<!-- Display the user's name in the User button -->
<form class="d-flex">
    <button class="btn btn-outline-dark">
        <i class="bi bi-person"></i>
        User: <b><?php echo htmlspecialchars($username); ?></b>
    </button>
</form>
<form class="d-flex" action="Login.php" onclick="return confirm('Are you sure you want to logout?');">
    <button class="btn btn-outline-dark">
        <i class="bi bi-box-arrow-right"></i>
      <b>Logout</b>
    </button>
</form>


        </div>
    </div>
</nav>


<header class="bg-dark py-5">
    <div class="container px-4 px-lg-5 my-5">
        <div class="text-center text-white">
            <h1 class="display-4 fw-bolder">Welcome to Librow Book now!</h1>
            <p class="lead fw-normal text-white-50 mb-0"><b>Shop with Us!</b></p>
        </div>
    </div>
</header>

<!-- Search Bar -->
<form class="search-container" action="Shop.php" method="GET">
    <div class="input-group">
      
        <input type="text" class="form-control" placeholder="Search for..." name="search">
        <span class="input-group-btn">
           
            <button class="btn btn-secondary" type="submit">Search</button>
        </span>
    </div>
</form>





<!-- Book Display Section -->
<section class="py-5">
    <div class="container px-4 px-lg-5 mt-5">
        <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center">
            <?php
                
                $searchQuery = isset($_GET['search']) ? $_GET['search'] : '';

              
                $query = "SELECT DISTINCT title, author, bookstock, image FROM librow.books, accounts WHERE title LIKE '%$searchQuery%'";

          
                $result = mysqli_query($link, $query);

                // Loop through the query result to display books
                while ($row = mysqli_fetch_assoc($result)) {
                   
                    $title = $row["title"];
                    $author = $row["author"];
                    $bookstock = $row["bookstock"];
                    $imageFilename = $row["image"];
                    $imagePath = "admin/admin_library/" . $imageFilename;
                
            ?>
                <div class="col mb-5">
                    <div class="card h-100">
                        <!-- Display book image -->
                        <img src="<?php echo $imagePath; ?>" height="260" width="250" class="card-img-top" alt="Book Image">

                        <div class="small text-center text-muted mx-auto">
                        <div class="card-body">
                            <!-- Display book title and author -->
                           <h4 class="card-title"> <b><?php echo $title; ?></h4></b>
                            <p class="card-text"><h6><b>Author:</b> <?php echo $author; ?></p></h6>
                            <p class="card-text"><h6><b>Stock:</b> <?php echo $bookstock; ?></p></h6>
                        </div>

                        
                        
                           
                    
<form action="Book.php" method="POST">
    <input type="hidden" name="title" value="<?php echo $title; ?>">
    <input type="hidden" name="author" value="<?php echo $author; ?>">
    <input type="hidden" name="bookstock" value="<?php echo $bookstock; ?>">
    <input type="hidden" name="imagePath" value="<?php echo $imagePath; ?>">

    <div class="card-footer p-4 pt-0 border-top-0 bg-transparent">
        <button type="submit" class="btn btn-outline-dark mt-auto" name="addBook">Add to Book</button>
    </div>
</form>
                        </div>
                       

                    </div>
                </div>
            <?php
            }
            ?>
        </div>
    </div>
</section>


 <!-- Footer-->
 <footer class="py-5 bg-dark">
            <div class="container px-4 px-lg-5 mt-5">
                <div class="small text-center text-muted">Librow &copy; Website 2023</div>
            </div>
        </footer>

    </body>
</html>

