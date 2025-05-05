<?php
include "connectionLibrary.php";
$id=$_GET["id"];
mysqli_query($link, "delete from books where id=$id");


?>

<script type="text/javascript">

window.location="library.php";

</script>