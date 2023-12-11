<?php
require_once "../../config/connection_db.php";

if (!isset($_GET['id'])) {
    header("location:  ../Hotels/viewRooms.php");
}

$sql = "DELETE FROM `room` WHERE room_id='" . $_GET["id"] . "'";

mysqli_query($connection, $sql) or die("Query Failed");
header("location: ../Rooms/viewRooms.php");

mysqli_close($connection);
