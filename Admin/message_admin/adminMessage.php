<?php
include "connectionRequest.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>

<link rel="icon" type="image/x-icon" href="shopassets/favicon.ico" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<link href="Account.css" rel="stylesheet">


<style>
    .addReq {
        white-space: pre-wrap;
        word-wrap: break-word;
    }
</style>


</head>
<body>

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


<form action="" name=form1 method="post" enctype="multipart/form-data">
  <div class="container">
    <h1>Current Messages</h1>
    <p>Please fill in this form to create an account.</p>
  
    <div class="button-container">
    <button type="submit" name= "update" class="registerbtn">Update</button>
  </div>

  
    <table class="table table-bordered">
    <div class="container-fluid">
    <div class="tables">
        <div class="col-lg-12">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <div class="col-md-6 col-sm-12">
                        <div class="pr-1">
                            <thead>
                                <tr>
                                    <th>Id</th>         
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Number</th>
                                    <th>Email</th>
                                    <th>Address</th>         
                                    <th>City</th>
                                    <th>Zip</th>
                                    <th>Book Number</th>
                                    <th>Special Request</th>
                                    <th>Delete</th>
                                </tr>
                            </thead>
                        </div>
                    </div>
                    <tbody>
                    <?php
$res=mysqli_query($link,"select * from returnpage");
while($row=mysqli_fetch_array($res))
{
    echo "<tr>";
    echo "<td>"; echo $row["Id"]; echo "</td>";
    echo "<td>"; echo $row["firstname"]; echo "</td>";
    echo "<td>"; echo $row["lastname"]; echo "</td>";
    echo "<td>"; echo $row["number"]; echo "</td>";
    echo "<td>"; echo $row["email"]; echo "</td>";
    echo "<td>"; echo $row["address"]; echo "</td>";
    echo "<td>"; echo $row["city"]; echo "</td>";
    echo "<td>"; echo $row["zip"]; echo "</td>";
    echo "<td>"; echo $row["booknumber"]; echo "</td>";
    echo "<td style='word-wrap: break-word; max-width: 300px;'>"; echo $row["addReq"]; echo "</td>";
    echo "<td>"; ?> <a href="deleteRequest.php?id=<?php echo $row["Id"]; ?>"><button type="button" class="btn-danger">Delete</button></a> <?php echo "</td>";
    echo "</tr>";
}
?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</form>

</body>
</body>
</html>
