
<?php
include "connectionadmin.php";

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">


<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<link href="admin.css" rel="stylesheet">
<script src="admin.js"></script>


<style>
/* New */
tr {
  border: 1px solid #ddd;
  padding: 8px;
}

th {
  background-color: #333;
  color: white;
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
    
    <!-- Buttom For Form -->
    <button class="open-button" onclick="openForm()">Create Admin</button>

    <!-- Main Form of the button -->
	<div class="form-popup" id="myForm">
<form action="" class="form-container" name=form1 method="post" enctype="multipart/form-data">
	<h1>New Book</h1>

	<label for="book"><b>Admin Username</b></label>
		<input type="text" placeholder="Enter Username" name="email" required>
			
	<label for="stock"><b>Admin Password</b></label>
  <input type="password" class="password" placeholder="Enter your password" name="Password" required>


	<button type="submit" class="btn" name= "insert">Add</button>

	<button type="button" class="btn cancel" onclick="closeForm()">Close</button>
		</form>
	</div>




  <div class="container">
    <h1>Please input your user name and password for your admin account</h1>
    <p>Please fill in this form to create an account.</p>
<form action="admin.php">
    <div class="button-container">
    <button type="submit" name= "update" class="registerbtn">Update</button>
    </div>
</form>

<table class="table table-bordered">
<div class="container-fluid">
<div class="tables">
<div class="col-lg-12">

<table class="table table-bordered">
<div class="col-md-6 col-sm-12">
      <div class="pr-1">
    <thead>
        <tr>
            <th>Id</th>
            <th>User Name</th>
            <th>Password</th>
            <th>Edit</th>
            <th>Delete</th>
            
        </tr>
    </thead>
</div>
</div>
</div>
</div>
</div>

<tbody>

<?php
$res=mysqli_query($link,"select * from adminaccounts");
while($row=mysqli_fetch_array($res))
{

    echo "<tr>";
    echo "<td>"; echo $row["Id"]; echo "</td>";
    echo "<td>"; echo $row["email"]; echo "</td>";
    echo "<td>"; echo $row["Password"]; echo "</td>";
    echo "<td>"; ?> <a href="editadmin.php?id=<?php echo $row["Id"]; ?>"><button type="button" class="btn-success">Edit</button></a> <?php echo "</td>";
    echo "<td>"; ?> <a href="deleteadmin.php?id=<?php echo $row["Id"]; ?>"><button type="button" class="btn-danger">Delete</button></a> <?php echo "</td>";

    echo "</tr>";

}
?>
    </tbody>
</table>


<?php

if(isset($_POST["insert"]))
{

  mysqli_query($link, "insert into adminaccounts values(NULL, '$_POST[email]','$_POST[Password]')");
     ?>
        <script type="text/javascript">
        window.location.href=window.location.href;
        </script>
        <?php
 
}

?>



</body>
</html>
