<?php
include "connectionadmin.php";
$id=$_GET["id"];
mysqli_query($link, "delete from adminaccounts where id=$id");


?>

<script type="text/javascript">

window.location="admin.php";

</script>