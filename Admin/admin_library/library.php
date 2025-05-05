
<?php
include "connectionLibrary.php";

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
<link href="library.css" rel="stylesheet">
<script src="library.js"></script>


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
    <button class="open-button" onclick="openForm()">Add Book</button>

    <!-- Main Form of the button -->
	<div class="form-popup" id="myForm">
<form action="" class="form-container" name=form1 method="post" enctype="multipart/form-data">
	<h1>New Book</h1>

	<label for="book"><b>Book Title</b></label>
		<input type="text" placeholder="Enter Book Title" name="title" required>
			
	<label for="stock"><b>Book Author</b></label>
		<input type="text" placeholder="Enter Book Author" name="author" required>

	<label for="price"><b>Book Stocks</b></label>
			<input type="text" placeholder="Enter Book Quantity" name="bookstock" required>

    <div class="form-group">
    <label for="psw-repeat"><b>Book Cover/Image</b></label>
    <input type="file" class="form-control" placeholder="Repeat Contact" id="psw-repeat" name="f1">
    </div>

	<button type="submit" class="btn" name= "insert">Add</button>

	<button type="button" class="btn cancel" onclick="closeForm()">Close</button>
		</form>
	</div>




  <div class="container">
    <h1>Books Available</h1>
    <p>Please fill in this form to create an account.</p>
<form action="">
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
            <th>Images</th>
            <th>Title</th>
            <th>Author</th>
            <th>Book Stock</th>         
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
$res=mysqli_query($link,"select * from books");
while($row=mysqli_fetch_array($res))
{

    echo "<tr>";
    echo "<td>"; echo $row["Id"]; echo "</td>";
    echo "<td>"; ?> <img src="<?php echo $row["image"]; ?>" height="210" width="200"> <?php echo "</td>";
    echo "<td>"; echo $row["title"]; echo "</td>";
    echo "<td>"; echo $row["author"]; echo "</td>";
    echo "<td>"; echo $row["bookstock"]; echo "</td>";
    echo "<td>"; ?> <a href="editLibrary.php?id=<?php echo $row["Id"]; ?>"><button type="button" class="btn-success">Edit</button></a> <?php echo "</td>";
    echo "<td>"; ?> <a href="deleteLibrary.php?id=<?php echo $row["Id"]; ?>"><button type="button" class="btn-danger">Delete</button></a> <?php echo "</td>";

    echo "</tr>";

}
?>
    </tbody>
</table>



<?php
if(isset($_POST["insert"]))
{
   
  $tm=md5(time());
  $fnm=$_FILES["f1"]["name"];
  $dst="./images/".$tm.$fnm;
  $dst1="images/".$tm.$fnm;
  
  move_uploaded_file($_FILES["f1"]["tmp_name"],$dst);

  mysqli_query($link,"insert into books values(NULL, '$_POST[title]','$_POST[author]','$_POST[bookstock]','$dst1')");
     ?>
        <script type="text/javascript">
        window.location.href=window.location.href;
        </script>
        <?php
}

?>



</body>
</html>
