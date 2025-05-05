<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_email']) && !isset($_SESSION['admin_email'])) {
    header("Location: Login.php");
    exit();
}

// Determine username and profile image
$username = isset($_SESSION['user_email']) ? $_SESSION['user_email'] : $_SESSION['admin_email'];
$profile_image = isset($_SESSION['user_image']) ? $_SESSION['user_image'] : 'assets/img/default-profile.png';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Librow | Home</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/logo librow.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">
</head>

<body>

  <!-- ======= Header ======= -->
  <header id="header" class="fixed-top">
    <div class="container d-flex align-items-center justify-content-lg-between">
      <h1 class="logo me-auto me-lg-0"><img class="logo" src="assets/img/librow.png" alt="LOGO"></h1>
      <nav id="navbar" class="navbar order-last order-lg-0">
        <ul>
          <li><a class="nav-link scrollto" href="#hero">Home</a></li>
          <li><a class="nav-link scrollto" href="#about">About</a></li>
          <li><a class="nav-link scrollto" href="#services">Services</a></li>
          <li><a class="nav-link scrollto" href="#team">Team</a></li>
          <li><a class="nav-link scrollto" href="Book.php">Books</a></li>
          <li><a class="nav-link scrollto" href="#contact">Contact</a></li>
        </ul>
        <i class="bi bi-list mobile-nav-toggle"></i>
      </nav>
      <div class="d-flex align-items-center">
        <div class="user-info me-3 d-flex align-items-center">
          <i class="bi bi-person" style="color: #fff; font-size: 20px; margin-right: 8px;"></i>
          <span class="username" style="color: #fff; font-family: 'Raleway', sans-serif; font-size: 15px; font-weight: 600;"><?php echo htmlspecialchars($username); ?></span>
        </div>
        <a href="Login.php" class="get-started-btn scrollto">Logout </a>
      </div>
    </div>
  </header><!-- End Header -->

  <!-- ======= Hero Section ======= -->
  <section id="hero" class="d-flex align-items-center justify-content-center">
    <div class="container" data-aos="fade-up">
      <div class="row justify-content-center" data-aos="fade-up" data-aos-delay="150">
        <div class="col-xl-6 col-lg-8">
          <h1>Librow<span>.</span></h1>
          <h2>Subscribe. Read. Enjoy.</h2>
        </div>
      </div>
      <div class="row gy-4 mt-5 justify-content-center" data-aos="zoom-in" data-aos-delay="250">
        <div class="col-xl-2 col-md-4">
          <div class="icon-box">
            <i class="ri-calendar-todo-line"></i>
            <h3><a href="Book.php">Book Now</a></h3>
          </div>
        </div>
      </div>
    </div>
  </section>

  <main id="main">

    <!-- ======= About Section ======= -->
    <section id="about" class="about">
      <div class="container" data-aos="fade-up">
        <div class="section-title">
          <h2>About</h2>
          <p>About Us</p>
        </div>
        <div class="row">
          <div class="col-lg-6 order-1 order-lg-2" data-aos="fade-left" data-aos-delay="100">
            <br><br><img src="assets/img/about_Img.jpg" class="img-fluid" alt="">
          </div>
          <div class="col-lg-6 pt-4 pt-lg-0 order-2 order-lg-1 content" data-aos="fade-right" data-aos-delay="100">
            <h3>What is Librow?</h3>
            <p class="fst-italic">
              Librow is a library management system that helps libraries of all sizes automate their workflows and provide a better experience for their patrons. Librow is a cloud-based system, so it can be accessed from anywhere with an internet connection.
            </p>
            <ul>
              <li><i class="ri-check-double-line"></i> Solve and find solutions regarding the problem of book availability, where some manually check if a book is available in storage or on shelves.</li>
              <li><i class="ri-check-double-line"></i> Provide an easy solution where readers can browse and find the desired book they want to acquire or read.</li>
              <li><i class="ri-check-double-line"></i> Help people easily access our books, offering both physical and eBook copies for borrowing.</li>
            </ul>
            <p>
              An online book lender and viewing project that serves as a central database for all books in stock, including their title, author, and price. The goal is to create a website that serves as a central bookstore where users can choose a book and check its price.
            </p>
          </div>
        </div>
      </div>
    </section><!-- End About Section -->

    <!-- ======= Features Section ======= -->
    <section id="features" class="features">
      <div class="container" data-aos="fade-up">
        <div class="row">
          <div class="image col-lg-6" style='background-image: url("assets/img/about_img2.jpg");' data-aos="fade-right"></div>
          <div class="col-lg-6" data-aos="fade-left" data-aos-delay="100">
            <div class="icon-box mt-5 mt-lg-0" data-aos="zoom-in" data-aos-delay="150">
              <i class="bx bx-receipt"></i>
              <h4>Business Owners</h4>
              <p>The goal is to introduce library companies to online shopping. Business owners can conduct online transactions by collaborating with e-money/e-cash companies.</p>
            </div>
            <div class="icon-box mt-5" data-aos="zoom-in" data-aos-delay="150">
              <i class="bx bx-cube-alt"></i>
              <h4>Future IT Students</h4>
              <p>Aspiring website designers can use this project to build straightforward websites with the same purpose, strengthening their skills for transaction-based systems.</p>
            </div>
            <div class="icon-box mt-5" data-aos="zoom-in" data-aos-delay="150">
              <i class="bx bx-images"></i>
              <h4>Employees</h4>
              <p>Employees will work fewer hours, reducing their workload and stress. Keeping distance from customers helps prevent communicable diseases, especially during pandemics.</p>
            </div>
            <div class="icon-box mt-5" data-aos="zoom-in" data-aos-delay="150">
              <i class="bx bx-shield"></i>
              <h4>Customers</h4>
              <p>This study provides customers with a better, more secure, and trustworthy transaction, saving time and effort compared to manual processes.</p>
            </div>
          </div>
        </div>
      </div>
    </section><!-- End Features Section -->

    <!-- ======= Services Section ======= -->
    <section id="services" class="services">
      <div class="container" data-aos="fade-up">
        <div class="section-title">
          <h2>Services</h2>
          <p>Check our Services</p>
        </div>
        <div class="row">
          <div class="col-lg-4 col-md-6 d-flex align-items-stretch mt-4" data-aos="zoom-in" data-aos-delay="100">
            <div class="icon-box">
              <div class="icon"><i class="bx bx-world"></i></div>
              <h4><a href="">Access Our Website</a></h4>
              <p>View our website on any platform to access our vast collection of programming books, available in digital copies.</p>
            </div>
          </div>
          <div class="col-lg-4 col-md-6 d-flex align-items-stretch mt-4" data-aos="zoom-in" data-aos-delay="200">
            <div class="icon-box">
              <div class="icon"><i class="bx bx-slideshow"></i></div>
              <h4><a href="">Online Book Viewer</a></h4>
              <p>Access our online books through our viewer for easy reading anywhere.</p>
            </div>
          </div>
          <div class="col-lg-4 col-md-6 d-flex align-items-stretch mt-4" data-aos="zoom-in" data-aos-delay="300">
            <div class="icon-box">
              <div class="icon"><i class="bx bx-arch"></i></div>
              <h4><a href="">Easy Search</a></h4>
              <p>Easily search for the book you want using our search function in our shop.</p>
            </div>
          </div>
        </div>
      </div>
    </section><!-- End Services Section -->

    <!-- ======= Cta Section ======= -->
    <section id="cta" class="cta">
      <div class="container" data-aos="zoom-in">
        <div class="text-center">
          <h3>Call To Action</h3>
          <p>If you are looking to save time, improve patron service, and make better decisions about your book collection, consider using our book management system services.</p>
          <a class="cta-btn" href="Book.php">Browse Now!</a>
        </div>
      </div>
    </section><!-- End Cta Section -->

    <!-- ======= Counts Section ======= -->
    <section id="counts" class="counts">
      <div class="container" data-aos="fade-up">
        <div class="row no-gutters">
          <div class="image col-xl-5 d-flex align-items-stretch justify-content-center justify-content-lg-start" data-aos="fade-right" data-aos-delay="100"></div>
          <div class="col-xl-7 ps-4 ps-lg-5 pe-4 pe-lg-1 d-flex align-items-stretch" data-aos="fade-left" data-aos-delay="100">
            <div class="content d-flex flex-column justify-content-center">
              <h3>Librow: Online Book Lender and Viewing System</h3>
              <p>Librow, formed in 2023, is an online book supplier located in Quezon City, aiming to provide a hassle-free experience for readers.</p>
              <div class="row">
                <div class="col-md-6 d-md-flex align-items-md-stretch">
                  <div class="count-box">
                    <i class="bi bi-emoji-smile"></i>
                    <span data-purecounter-start="0" data-purecounter-end="50" data-purecounter-duration="2" class="purecounter"></span>
                    <p><strong>Happy Readers</strong> Our current readers.</p>
                  </div>
                </div>
                <div class="col-md-6 d-md-flex align-items-md-stretch">
                  <div class="count-box">
                    <i class="bi bi-journal-richtext"></i>
                    <span data-purecounter-start="0" data-purecounter-end="10" data-purecounter-duration="2" class="purecounter"></span>
                    <p><strong>Books</strong> Currently available books in our library.</p>
                  </div>
                </div>
                <div class="col-md-6 d-md-flex align-items-md-stretch">
                  <div class="count-box">
                    <i class="bi bi-clock"></i>
                    <span data-purecounter-start="0" data-purecounter-end="1" data-purecounter-duration="4" class="purecounter"></span>
                    <p><strong>Years of Experience</strong> Years we have been operating.</p>
                  </div>
                </div>
                <div class="col-md-6 d-md-flex align-items-md-stretch">
                  <div class="count-box">
                    <i class="bi bi-award"></i>
                    <span data-purecounter-start="0" data-purecounter-end="15" data-purecounter-duration="4" class="purecounter"></span>
                    <p><strong>Achievements</strong> Achievements earned as a company.</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section><!-- End Counts Section -->

    <!-- ======= Testimonials Section ======= -->
    <section id="testimonials" class="testimonials">
      <div class="container" data-aos="zoom-in">
        <div class="testimonials-slider swiper" data-aos="fade-up" data-aos-delay="100">
          <div class="swiper-wrapper">
            <div class="swiper-slide">
              <div class="testimonial-item">
                <img src="assets/img/team/pic.jpg" class="testimonial-img" alt="">
                <h3>John Ezra B. Bugao</h3>
                <h4>CEO & Leader</h4>
                <p>
                  <i class="bx bxs-quote-alt-left quote-icon-left"></i>
                  I'm passionate about technology and excited to learn more about the field.
                  <i class="bx bxs-quote-alt-right quote-icon-right"></i>
                </p>
              </div>
            </div>
            <div class="swiper-slide">
              <div class="testimonial-item">
                <img src="assets/img/team/pic2.png" class="testimonial-img" alt="">
                <h3>Joaquin Miguel Lisondra</h3>
                <h4>Member</h4>
                <p>
                  <i class="bx bxs-quote-alt-left quote-icon-left"></i>
                  I'm new to the field, but learning quickly and eager to apply my skills.
                  <i class="bx bxs-quote-alt-right quote-icon-right"></i>
                </p>
              </div>
            </div>
            <div class="swiper-slide">
              <div class="testimonial-item">
                <img src="assets/img/team/pic4.png" class="testimonial-img" alt="">
                <h3>Kurt Justin T. De Guzman</h3>
                <h4>Member</h4>
                <p>
                  <i class="bx bxs-quote-alt-left quote-icon-left"></i>
                  I'm always up for a challenge and not afraid to learn new things.
                  <i class="bx bxs-quote-alt-right quote-icon-right"></i>
                </p>
              </div>
            </div>
            <div class="swiper-slide">
              <div class="testimonial-item">
                <img src="assets/img/team/pic5.jpg" class="testimonial-img" alt="">
                <h3>Lance Niros V. De Castro</h3>
                <h4>Member</h4>
                <p>
                  <i class="bx bxs-quote-alt-left quote-icon-left"></i>
                  I'm passionate about using technology to make a positive impact.
                  <i class="bx bxs-quote-alt-right quote-icon-right"></i>
                </p>
              </div>
            </div>
            <div class="swiper-slide">
              <div class="testimonial-item">
                <img src="assets/img/team/pic3.jpg" class="testimonial-img" alt="">
                <h3>Lance Reigner M. Rebenque</h3>
                <h4>Member</h4>
                <p>
                  <i class="bx bxs-quote-alt-left quote-icon-left"></i>
                  I'm fascinated by technology and always looking for new ways to use it.
                  <i class="bx bxs-quote-alt-right quote-icon-right"></i>
                </p>
              </div>
            </div>
          </div>
          <div class="swiper-pagination"></div>
        </div>
      </div>
    </section><!-- End Testimonials Section -->

    <!-- ======= Team Section ======= -->
    <section id="team" class="team">
      <div class="container" data-aos="fade-up">
        <div class="section-title">
          <h2>Team</h2>
          <p>Check our Team</p>
        </div>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/svg-with-js.min.css" integrity="sha512-W3ZfgmZ5g1rCPFiCbOb+tn7g7sQWOQCB1AkDqrBG1Yp3iDjY9KYFh/k1AWxrt85LX5BRazEAuv+5DV2YZwghag==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <section class="team-section py-5">
          <div class="container">
            <div class="row justify-content-center">
              <div class="col-12 col-md-6">
                <div class="card border-0 shadow-lg pt-5 my-5 position-relative">
                  <div class="card-body p-4">
                    <div class="member-profile position-absolute w-100 text-center">
                      <img class="rounded-circle mx-auto d-inline-block shadow-sm" src="assets/img/team/pic.jpg" alt="">
                    </div>
                    <div class="card-text pt-1">
                      <h5 class="member-name mb-0 text-center text-primary font-weight-bold">John Ezra B. Bugao</h5>
                      <div class="mb-3 text-center">Leader</div>
                      <div>Hi, I'm John Ezra B. Bugao, an aspiring IT student passionate about web development.</div>
                    </div>
                  </div>
                  <div class="card-footer theme-bg-primary border-0 text-center">
                    <ul class="social-list list-inline mb-0 mx-auto">
                      <li class="list-inline-item"><a class="text-dark" href="#"><i class="fab fa-twitter fa-fw"></i></a></li>
                      <li class="list-inline-item"><a class="text-dark" href="http://www.facebook.com/johnezra.bugao"><i class="fab fa-facebook-f fa-fw"></i></a></li>
                      <li class="list-inline-item"><a class="text-dark" href="#"><i class="fab fa-instagram fa-fw"></i></a></li>
                    </ul>
                  </div>
                </div>
              </div>
              <div class="col-12 col-md-6">
                <div class="card border-0 shadow-lg pt-5 my-5 position-relative">
                  <div class="card-body p-4">
                    <div class="member-profile position-absolute w-100 text-center">
                      <img class="rounded-circle mx-auto d-inline-block shadow-sm" src="assets/img/team/pic2.png" alt="">
                    </div>
                    <div class="card-text pt-1">
                      <h5 class="member-name mb-0 text-center text-primary font-weight-bold">Joaquin Miguel Lisondra</h5>
                      <div class="mb-3 text-center">Member</div>
                      <div>I'm Joaquin Miguel Lisondra, a Second-Year IT student interested in AI and machine learning.</div>
                    </div>
                  </div>
                  <div class="card-footer theme-bg-primary border-0 text-center">
                    <ul class="social-list list-inline mb-0 mx-auto">
                      <li class="list-inline-item"><a class="text-dark" href="#"><i class="fab fa-twitter fa-fw"></i></a></li>
                      <li class="list-inline-item"><a class="text-dark" href="http://www.facebook.com/ella.louise.ong"><i class="fab fa-facebook-f fa-fw"></i></a></li>
                      <li class="list-inline-item"><a class="text-dark" href="#"><i class="fab fa-instagram fa-fw"></i></a></li>
                    </ul>
                  </div>
                </div>
              </div>
              <div class="col-12 col-md-6 col-lg-4">
                <div class="card border-0 shadow-lg pt-5 my-5 position-relative">
                  <div class="card-body p-4">
                    <div class="member-profile position-absolute w-100 text-center">
                      <img class="rounded-circle mx-auto d-inline-block shadow-sm" src="assets/img/team/pic4.png" alt="">
                    </div>
                    <div class="card-text pt-1">
                      <h5 class="member-name mb-0 text-center text-primary font-weight-bold">Kurt Justin T. De Guzman</h5>
                      <div class="mb-3 text-center">Member</div>
                      <div>I'm Kurt Justin T. De Guzman, an IT student interested in cybersecurity.</div>
                    </div>
                  </div>
                  <div class="card-footer theme-bg-primary border-0 text-center">
                    <ul class="social-list list-inline mb-0 mx-auto">
                      <li class="list-inline-item"><a class="text-dark" href="#"><i class="fab fa-twitter fa-fw"></i></a></li>
                      <li class="list-inline-item"><a class="text-dark" href="http://www.facebook.com/deguzmankurtjustin"><i class="fab fa-facebook-f fa-fw"></i></a></li>
                      <li class="list-inline-item"><a class="text-dark" href="#"><i class="fab fa-instagram fa-fw"></i></a></li>
                    </ul>
                  </div>
                </div>
              </div>
              <div class="col-12 col-md-6 col-lg-4">
                <div class="card border-0 shadow-lg pt-5 my-5 position-relative">
                  <div class="card-body p-4">
                    <div class="member-profile position-absolute w-100 text-center">
                      <img class="rounded-circle mx-auto d-inline-block shadow-sm" src="assets/img/team/pic5.jpg" alt="">
                    </div>
                    <div class="card-text pt-1">
                      <h5 class="member-name mb-0 text-center text-primary font-weight-bold">John Mark Fernandez</h5>
                      <div class="mb-3 text-center">Member</div>
                      <div>I'm John Mark Fernandez, passionate about using social media to connect people.</div>
                    </div>
                  </div>
                  <div class="card-footer theme-bg-primary border-0 text-center">
                    <ul class="social-list list-inline mb-0 mx-auto">
                      <li class="list-inline-item"><a class="text-dark" href="#"><i class="fab fa-twitter fa-fw"></i></a></li>
                      <li class="list-inline-item"><a class="text-dark" href="http://www.facebook.com/lance.decastro.100"><i class="fab fa-facebook-f fa-fw"></i></a></li>
                      <li class="list-inline-item"><a class="text-dark" href="#"><i class="fab fa-instagram fa-fw"></i></a></li>
                    </ul>
                  </div>
                </div>
              </div>
              <div class="col-12 col-md-6 col-lg-4">
                <div class="card border-0 shadow-lg pt-5 my-5 position-relative">
                  <div class="card-body p-4">
                    <div class="member-profile position-absolute w-100 text-center">
                      <img class="rounded-circle mx-auto d-inline-block shadow-sm" src="assets/img/team/pic3.png" alt="">
                    </div>
                    <div class="card-text pt-1">
                      <h5 class="member-name mb-0 text-center text-primary font-weight-bold">Jhilou Lian Carpizo</h5>
                      <div class="mb-3 text-center">Member</div>
                      <div>I'm Jhilou Lian Carpizo, an IT student interested in game development.</div>
                    </div>
                  </div>
                  <div class="card-footer theme-bg-primary border-0 text-center">
                    <ul class="social-list list-inline mb-0 mx-auto">
                      <li class="list-inline-item"><a class="text-dark" href="#"><i class="fab fa-twitter fa-fw"></i></a></li>
                      <li class="list-inline-item"><a class="text-dark" href="http://www.facebook.com/reBAEnque"><i class="fab fa-facebook-f fa-fw"></i></a></li>
                      <li class="list-inline-item"><a class="text-dark" href="#"><i class="fab fa-instagram fa-fw"></i></a></li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
      </div>
    </section><!-- End Team Section -->

    <!-- ======= Contact Section ======= -->
    <section id="contact" class="contact">
      <div class="container" data-aos="fade-up">
        <div class="section-title">
          <h2>Contact</h2>
          <p>Contact Us</p>
        </div>
        <div>
          <iframe style="border:0; width: 100%; height: 270px;" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3860.620994345644!2d121.0480948!3d14.620653599999995!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397b7bf0a362b19%3A0xb7ef71e25faabd8!2sAurora%20Blvd%2C%20Quezon%20City%2C%20Metro%20Manila!5e0!3m2!1sen!2sph!4v1697432936386!5m2!1sen!2sph" frameborder="0" allowfullscreen></iframe>
        </div>
        <div class="row mt-5">
          <div class="col-lg-4">
            <div class="info">
              <div class="address">
                <i class="bi bi-geo-alt"></i>
                <h4>Location:</h4>
                <p>Aurora Boulevard, Quezon City</p>
              </div>
              <div class="email">
                <i class="bi bi-envelope"></i>
                <h4>Email:</h4>
                <p>librow@gmail.com</p>
              </div>
              <div class="phone">
                <i class="bi bi-phone"></i>
                <h4>Call:</h4>
                <p>+0906 5743 8321</p>
              </div>
            </div>
          </div>
          <div class="col-lg-8 mt-5 mt-lg-0">
            <form action="forms/contact.php" method="post" role="form" class="php-email-form">
              <div class="row">
                <div class="col-md-6 form-group">
                  <input type="text" name="name" class="form-control" id="name" placeholder="Your Name" required>
                </div>
                <div class="col-md-6 form-group mt-3 mt-md-0">
                  <input type="email" class="form-control" name="email" id="email" placeholder="Your Email" required>
                </div>
              </div>
              <div class="form-group mt-3">
                <input type="text" class="form-control" name="subject" id="subject" placeholder="Subject" required>
              </div>
              <div class="form-group mt-3">
                <textarea class="form-control" name="message" rows="5" placeholder="Message" required></textarea>
              </div>
              <div class="my-3">
                <div class="loading">Loading</div>
                <div class="error-message"></div>
                <div class="sent-message">Your message has been sent. Thank you!</div>
              </div>
              <div class="text-center"><button type="submit">Send Message</button></div>
            </form>
          </div>
        </div>
      </div>
    </section><!-- End Contact Section -->

  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <footer id="footer">
    <div class="footer-top">
      <div class="container">
        <div class="row">
          <div class="col-lg-3 col-md-6">
            <div class="footer-info">
              <a href="Home.php" class="logo me-auto me-lg-0"><img src="assets/img/librow.png" alt="" class="app-logo"></a>
              <p>
                Aurora Boulevard<br>
                Quezon City<br><br>
                <strong>Phone:</strong> +0906 5743 8321<br>
                <strong>Email:</strong> librow@gmail.com<br>
              </p>
              <div class="social-links mt-3">
                <a href="#" class="twitter"><i class="bx bxl-twitter"></i></a>
                <a href="#" class="facebook"><i class="bx bxl-facebook"></i></a>
                <a href="#" class="instagram"><i class="bx bxl-instagram"></i></a>
                <a href="#" class="google-plus"><i class="bx bxl-skype"></i></a>
                <a href="#" class="linkedin"><i class="bx bxl-linkedin"></i></a>
              </div>
            </div>
          </div>
          <div class="col-lg-2 col-md-6 footer-links">
            <h4>Useful Links</h4>
            <ul>
              <li><i class="bx bx-chevron-right"></i> <a href="#hero">Home</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="#about">About us</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="#services">Services</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="#">Terms of service</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="#">Privacy policy</a></li>
            </ul>
          </div>
          <div class="col-lg-3 col-md-6 footer-links">
            <h4>Our Services</h4>
            <ul>
              <li><i class="bx bx-chevron-right"></i> <a href="#">Web Design</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="#">Web Development</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="#">Graphic Design</a></li>
            </ul>
          </div>
          <div class="col-lg-4 col-md-6 footer-newsletter">
            <h4>Our Newsletter</h4>
            <p>Keep up to date with our daily book releases through our website.</p>
            <form action="" method="post">
              <input type="email" name="email"><input type="submit" value="Subscribe">
            </form>
          </div>
        </div>
      </div>
    </div>
    <div class="container">
      <div class="copyright">
        &copy; Copyright <strong><span>Synix</span></strong>. All Rights Reserved
      </div>
      <div class="credits">
        Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
      </div>
    </div>
  </footer><!-- End Footer -->

  <div id="preloader"></div>
  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>

</body>

</html>