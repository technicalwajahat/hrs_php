<?php
session_start();

unset($_SESSION['userloggedin']);
unset($_SESSION['username']);

header("location: userLogin.php");
exit;
