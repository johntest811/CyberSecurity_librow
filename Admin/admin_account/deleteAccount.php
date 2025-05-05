<?php
include "connection.php";
$id=$_GET["id"];
mysqli_query($link, "delete from accounts where id=$id");


?>

<script type="text/javascript">

window.location="Accounts.php";

</script>