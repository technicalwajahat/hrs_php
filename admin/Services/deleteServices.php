<?php
require_once "../../config/connection_db.php";

if (!isset($_GET['id'])) {
    header('location: viewServices.php');
}

$sql = "DELETE FROM `service` WHERE service_id='" . $_GET["id"] . "'";

mysqli_query($connection, $sql) or die("Query Failed");
header("location: ../Services/viewServices.php");

mysqli_close($connection);
