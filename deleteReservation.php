<?php
require_once "config/connection_db.php";

if (!isset($_GET['id'])) {
    header('location: viewReservation.php');
}

$sql = "DELETE FROM `reservation` WHERE reservation_id='" . $_GET["id"] . "'";

mysqli_query($connection, $sql) or die("Query Failed");
header("location: viewReservation.php");

mysqli_close($connection);
