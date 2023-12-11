<?php
require_once "../../config/connection_db.php";

if (!isset($_GET['id'])) {
    header("location:  ../Hotels/viewHotels.php");
}

$sql = "DELETE FROM `hotel` WHERE hotel_id='" . $_GET["id"] . "'";

mysqli_query($connection, $sql) or die("Query Failed");
header("location: ../Hotels/viewHotels.php");

mysqli_close($connection);
