<?php

$link = mysqli_connect("localhost", "root", "") or die (mysqli_error($link));
mysqli_select_db($link, "librow");

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>