
<?php
include "connectionadmin.php";

$id=$_GET["id"];

$title="";
$author="";
$bookstock="";
$image="";

$res=mysqli_query($link, "select * from books where id=$id");

while($row=mysqli_fetch_array($res)){

$title=$row["title"];
$author=$row["author"];
$bookstock=$row["bookstock"];
$image=$row["image"];


}


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


<style>
body {
  font-family: Arial, Helvetica, sans-serif;
  background-color: gray;
}

* {
  box-sizing: border-box;
}

/* Add padding to containers */
.container {
  padding: 16px;
  background-color: white;
}

/* Full-width input fields */
input[type=text], input[type=password] {
  width: 100%;
  padding: 15px;
  margin: 5px 0 22px 0;
  display: inline-block;
  border: none;
  background: #f1f1f1;
}

input[type=text]:focus, input[type=password]:focus {
  background-color: #ddd;
  outline: none;
}

/* Overwrite default styles of hr */
hr {
  border: 1px solid #f1f1f1;
  margin-bottom: 25px;
}

/* Set a style for the submit button */
.registerbtn {
  background-color: #04AA6D;
  color: white;
  padding: 16px 20px;
  margin: 8px 0;
  border: none;
  cursor: pointer;
  width: 50%;
  opacity: 0.9;
}

.registerbtn:hover {
  opacity: 1;
}

/* Add a blue text color to links */
a {
  color: dodgerblue;
}

/* Set a grey background color and center the text of the "sign in" section */
.signin {
  background-color: #f1f1f1;
  text-align: center;
}

.button-container {
    display: flex;
    justify-content: space-between;

}

.registerbtn {
    /* Other styles... */
    margin-right: 10px;
}

.tables{
  margin-top: 60px;
}

.back-btn {
  text-align: center; /* Center the text inside the button */
  text-decoration: none; /* Remove the default underline for hyperlinks */
}

.back-btn:hover {
  opacity: 1;
}
</style>
</head>
<body>

<form action="" name=form1 method="post" enctype="multipart/form-data">
  <div class="container">
    <h1>Change Information</h1>
    <p>Please fill in this form to Edit your Account.</p>

    <img src="<?php echo $image; ?>" height="200" width="150">
   <br><br>


    <label for="psw"><b>Admin User</b></label>
    <input type="text" placeholder="Please Enter title" id="psw" name="user" value="<?php echo $title; ?>">

    <label for="psw-repeat">Author</label>
    <input type="text" placeholder="Please Enter author" id="psw-repeat" name="password" value="<?php echo $author; ?>">

    <p>By creating an account you agree to our <a href="MainPHP.php">Terms & Privacy</a>.</p>

    <div class="button-container">
    <button type="submit" name="update" class="registerbtn">Update</button>    
    <a href="http://localhost/librow_MainPage/admin/admin_library/library.php" class="registerbtn back-btn">Back</a>

</div>
  

</form>
<?php
if(isset($_POST["update"]))
{

    $tm=md5(time());
    $fnm=$_FILES["f1"]["name"];

    if($fnm=="")
    {
        mysqli_query($link,"update admin set title='$_POST[title]',author='$_POST[author]',bookstock='$_POST[bookstock]' where id=$id");
    }
    else
    {
        $dst="./images/".$tm.$fnm;
        $dst1="images/".$tm.$fnm;
        move_uploaded_file($_FILES["f1"]["tmp_name"],$dst);

        mysqli_query($link,"update admin set title='$_POST[title]',author='$_POST[author]',bookstock='$_POST[bookstock]',image='$dst1' where id=$id");
    }

    
        ?>
        <script type="text/javascript">
        window.location="admin.php";
        </script>
        
        <?php
}

?>

</body>
</html>
