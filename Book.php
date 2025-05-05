<?php
    include "admin/admin_library/connectionLibrary.php";
    $query = "SELECT title, author, bookstock, image FROM librow.books, accounts";
    $result = mysqli_query($link, $query);
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
                            <span class="badge bg-dark text-white ms-1 rounded-pill">0</span>
                        </button>
                    </form>
  

<!-- Display the user's name in the User button -->
<form class="d-flex">
    <button class="btn btn-outline-dark">
        <i class="bi bi-person"></i>
        User: <b>Welcome</b>
    </button>
</form>
<form class="d-flex" action="account.php">
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
    // Start the session
 
    // add book
    // Check if the "addBook" button was clicked
    if (isset($_POST["addBook"])) {
        $title = $_POST["title"];
        $author = $_POST["author"];
        $bookstock = $_POST["bookstock"];
        $imagePath = $_POST["imagePath"];

        // Create an array to store the book information
        $book = array(
            "title" => $title,
            "author" => $author,
            "bookstock" => $bookstock,
            "imagePath" => $imagePath
        );

        // Check if the "bookList" session variable already exists
        if (isset($_SESSION["bookList"])) {
            // Append the new book to the existing list
            $_SESSION["bookList"][] = $book;
        } else {
            // Create a new list with the current book
            $_SESSION["bookList"] = array($book);
        }
    }

    // Check if the "deleteBook" button was clicked
    if (isset($_POST["deleteBook"])) {
        // Get the index of the book to be deleted
        $index = $_POST["bookIndex"];

        // Check if the book index is valid
        if (isset($_SESSION["bookList"][$index])) {
            // Remove the book from the list
            unset($_SESSION["bookList"][$index]);

            // Reset the array keys to maintain continuity
            $_SESSION["bookList"] = array_values($_SESSION["bookList"]);
        }
    }
?>

<!-- Display the selected books -->
<h1>Selected Books</h1>
<?php
    // Check if the bookList session variable exists and is not empty
    if (isset($_SESSION["bookList"]) && !empty($_SESSION["bookList"])) {
        // Loop through the bookList to display the books
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

            // Add a form to delete the book
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
