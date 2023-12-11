<?php

$site_key = "6LfgmEcgAAAAAGwc1apA3s_1DAiTXB9Ry-liWny1";
$secret_key = "6LfgmEcgAAAAAGp0Weyz2JeHMxDZI8-PD6tjjobm";

$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$db = "hrs_db";

$connection = new mysqli($db_host, $db_user, $db_pass, $db) or die("Connection failed: %s\n" . $connection->error);

function CloseConn($connection)
{
    $connection->close();
}
