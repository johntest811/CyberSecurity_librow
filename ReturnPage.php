<?php
require_once ("connection.php");
?>

<!DOCTYPE html>

<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <title>Librow | Return Page</title>
  <link rel="icon" type="image/x-icon" href="shopassets/favicon.ico">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  <link href="ReturnAssets/Return.css" rel="stylesheet">
  <link href="shopassets/shopstyles.css" rel="stylesheet">


  <style>
  .center-form {
  margin: 0 auto;
  margin-left: 5%;
  margin-right: 5%;
  max-width: 100%; 
}
</style>

</head>

<body>
 
  
        <!-- Main Nav Bar -->
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
          <div class="container px-4 px-lg-5">
              <a class="logo me-auto me-lg-0"><img src="assets/img/librow.png" alt="" class="shop-logo"></a>
              <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
              <div class="collapse navbar-collapse" id="navbarSupportedContent">
                  <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
                      <li class="nav-item"><a class="nav-link" href="HomeLogin.html">Home</a></li>
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

                   <!-- Display the user's name -->
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

<header class="bg-dark py-5">
  <div class="container px-4 px-lg-5 my-5">
      <div class="text-center text-white">
          <h1 class="display-4 fw-bolder">Return</h1>
      </div>
  </div>
</header>

<section class="py-5">
          
  
<section class="py-5">
          
          <!-- Input Information Form aka TextBoxes -->
   
         <div class="billing_details">
           <div class="row">
             <div class="col-lg-8">
               <h3>Address Details</h3>
               <form class="row contact_form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" novalidate="novalidate">
                 <div class="col-md-6 form-group p_star">
                   <input type="text" class="form-control" id="firstname" name="firstname" placeholder="First Name">
                 </div>
                 <div class="col-md-6 form-group p_star">
                   <input type="text" class="form-control" id="lastname" name="lastname" placeholder="Last Name">
                 </div>          
                 <div class="col-md-6 form-group p_star">
                   <input type="text" class="form-control" id="number" name="number" placeholder="Phone Number">
                 </div>
                 <div class="col-md-6 form-group p_star">
                   <input type="text" class="form-control" id="email" name="email" placeholder="Email Address">
                 </div>          
                 <div class="col-md-12 form-group p_star">
                   <input type="text" class="form-control" id="add1" name="address" placeholder="Address">
                 </div>           
                 <div class="col-md-12 form-group p_star">
                   <input type="text" class="form-control" id="city" name="city" placeholder="Town/City">
                 </div>
         
                 <div class="col-md-12 form-group">
                   <input type="text" class="form-control" id="zip" name="zip" placeholder="Postcode/ZIP">
                 </div>
   
                 <div class="col-md-12 form-group">
                   
                   <div class="creat_account">                                 
                     <input type="checkbox" id="f-option3" name="selector">
                     <label for="f-option3">Ship to a different address?</label>
                   </div>
                    
   
                   <h4>Book Name</h4>
                <div class="col-md-12 form-group">
                  <input type="text" class="form-control" id="booknumber" name="booknumber" placeholder="Book Name / Number">
                </div>

                <h4>Additional Details</h4>
                <textarea class="form-control" name="addReq" id="addReq" rows="1" placeholder="Any Request?"></textarea>
                 </div>

                 <!-- BUTTON -->
                 <button type="submit" class="btn btn-warning" name="insert">Proceed to Return / Ship</button>

                 <h4>Wan't to go to our physical store? Find us here!</h4>
                 <div>
                   <iframe style="border:0; width: 100%; height: 270px;" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3860.620994345644!2d121.0480948!3d14.620653599999995!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397b7bf0a362b19%3A0xb7ef71e25faabd8!2sAurora%20Blvd%2C%20Quezon%20City%2C%20Metro%20Manila!5e0!3m2!1sen!2sph!4v1697432936386!5m2!1sen!2sph" frameborder="0" allowfullscreen></iframe>    
                 </div>
                 
            
                 <?php

if(isset($_POST["insert"]))
{
    $query = "INSERT INTO returnpage VALUES (NULL, '$_POST[firstname]','$_POST[lastname]','$_POST[number]','$_POST[email]','$_POST[address]','$_POST[city]','$_POST[zip]','$_POST[booknumber]','$_POST[addReq]')";

    if(mysqli_query($link, $query))
    {
        // Success pop-up
        echo "<script>
                alert('Data sent successfully!');
              </script>";
    }
    else
    {
        // Failure pop-up
        echo "<script>
                alert('Failed to send data!');
              </script>";
    }
}

?>
                        
               </form>
             </div>

          <!-- Total Order of Value  -->
          <div class="col-lg-4">
            <div class="order_box">
              <h2>Ship to you</h2>
              <ul class="list">
                <li>
                  <a href="#">Product
                    <span>Total</span>
                  </a>
                </li>
                <li>
                  <a href="#">C++ Programming Language
                    <span class="last">₱0</span>
                  </a>
                </li>
              </ul>
              <ul class="list list_2">
                <li>
                  <a href="#">Subtotal
                    <span>₱0</span>
                  </a>
                </li>
                <li>
                  <a href="#">Shipping
                    <span>Flat rate: ₱50.00</span>
                  </a>
                </li>
                <li>
                  <a href="#">Total
                    <span>₱50.00</span>
                  </a>
                </li>
              </ul>
              <div class="payment_item">
                <div class="radion_btn">
                  <input type="radio" id="f-option5" name="selector">
                  <label for="f-option5">Ship Back</label>
                  <div class="check"></div>
                </div>
                <div class="payment_item active">
                  <div class="radion_btn">
                    <input type="radio" id="f-option6" name="selector">
                    <label for="f-option6">Return To Physical Store</label>
                    <img src="img/product/single-product/card.jpg" alt="">
                    <div class="check"></div>
                  </div>          
                </div>
                <p>
                  Please ensure that you entered you corrent address to verify that you booked a pick-up before clicking the ship button.
                </p>
              </div>         
              <div class="creat_account">
                <input type="checkbox" id="f-option4" name="selector">
                <label for="f-option4">I’ve read and accept the </label>
                <a href="#">terms & conditions*</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    
    </section>
   
</div>

<!-- Footer -->
<footer class="py-5 bg-dark">
  <div class="container"><p class="m-0 text-center text-white">Copyright &copy; Synix</p></div>
</footer>
<!-- Bootstrap core JS-->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Core theme JS-->
<script src="bookingasset/BookingJs.js"></script>
</body>

</html>
