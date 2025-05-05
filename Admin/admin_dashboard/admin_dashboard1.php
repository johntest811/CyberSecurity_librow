<?php 
    include "connectionDash.php";
    $query = "SELECT * FROM accounts, books";
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <title>Admin Dashboard</title>
    <link rel="icon" type="image/x-icon" href="shopassets/favicon.ico" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <link rel="icon" type="image/x-icon" href="shopassets/favicon.ico" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="dashboard.css">
    
</head>

<body>

    <div class="navbar">
        <img src="librow.png" alt="Logo">
    </div>


    <footer>
        <div class="main-footer">
            <div class="social-media">
                <div class="social-icon facebook-icon">
                    <i class="fab fa-facebook"></i>
                    <span id="facebook-likes">500</span> Likes
                </div>
                <div class="social-icon linkedin-icon">
                    <i class="fab fa-linkedin"></i>
                    <span id="linkedin-likes">200</span> Likes
                </div>
                <div class="social-icon twitter-icon">
                    <i class="fab fa-twitter"></i>
                    <span id="twitter-likes">300</span> Likes
                </div>
                <div class="social-icon instagram-icon">
                    <i class="fab fa-instagram"></i>
                    <span id="instagram-likes">400</span> Likes
                </div>
            </div>
        </div>





    <!-- Action Buttons -->
    <div class="action-buttons">
        <button href="#" class="btn btn-primary">Edit</button>
        <button href="#" class="btn btn-danger">Delete</button>
    </div>

    <!-- Table Section -->
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Books</th>
                    <th>Stocks</th>
                    <th>Users</th>
                </tr>
            </thead>
            <tbody>
                    <?php
                       $res=mysqli_query($link, 'select * from accounts, books');
                       while($row=mysqli_fetch_array($res))
                        {
                            echo "<tr>";
                            echo "<td>"; echo $row["Id"]; echo "</td>";
                            echo "<td>"; echo $row["title"]; echo "</td>";
                            echo "<td>"; echo $row["bookstock"]; echo "</td>";
                            echo "<td>"; echo $row["Name"]; echo "</td>";
                            echo "</tr>";
                        }  
                    ?>
            </tbody>
        </table>
    </div>

    <div class="sidenav">
        <a href="http://localhost/librow_MainPage/admin/admin_dashboard/admin_dashboard1.php#">Dashboard</a>
        <a href="http://localhost/librow_MainPage/admin/admin_library/library.php">Library</a>
        <a href="http://localhost/Librow_MainPage/admin/admin_account/Accounts.php#">User Accounts</a>
        <a href="http://localhost/Librow_MainPage/admin/message_admin/adminMessage.php">Return Messages</a>
        <a href="http://localhost/Librow_MainPage/admin/admin/admin.php#">Admin Accounts</a>
        <a href="http://localhost/Librow_MainPage/account.php">Logout</a>
    </div>

    <form action="">
        <!-- Add Icons Button -->
        <div class="icon">
            <i class="fas fa-plus"></i>
        </div>

        <!-- Add Icons Modal -->
        <div id="myModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <ul class="icons-list">
                    <li><i class="fab fa-500px"></i></li>
                    <li><i class="fab fa-accessible-icon"></i></li>
                    <li><i class="fab fa-accusoft"></i></li>
                    <!-- Add More Icons -->
                </ul>
            </div>
        </div>
    </form>
		<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>

   

        <div class="copyright-footer">
            &copy; 2023 Librow. All rights reserved.
        </div>
    </footer>


    <script src="admin_script.js"></script>
    <script src="https://kit.fontawesome.com/your-fontawesome-kit.js" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script>
        document.getElementById('facebook-likes').innerText = getRandomLikes();
        document.getElementById('linkedin-likes').innerText = getRandomLikes();
        document.getElementById('twitter-likes').innerText = getRandomLikes();
        document.getElementById('instagram-likes').innerText = getRandomLikes();

        function getRandomLikes() {
            return Math.floor(Math.random() * 1000) + 1;
        }
    </script>
</body>

</html>
