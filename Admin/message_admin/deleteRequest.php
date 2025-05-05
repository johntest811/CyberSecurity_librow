<?php
include "connectionRequest.php";
$id=$_GET["id"];
mysqli_query($link, "delete from returnpage where id=$id");


?>

<script type="text/javascript">

window.location="adminMessage.php";

</script>