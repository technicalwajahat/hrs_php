<?php
session_start();

if (!isset($_SESSION["userloggedin"]) || $_SESSION["userloggedin"] !== true) {
    header("location: /HRS/userLogin.php");
    exit;
}

if (isset($_SESSION["userloggedin"])) {
    if ((time() - $_SESSION['timestamp']) > 1800) {
        header("location: /HRS/userLogout.php");
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Reservation System</title>
    <link rel="stylesheet" href="/assets/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <script src="assets/js/menuToggle.js"></script>
</head>

<body>
    <?php
    include 'assets/header.php';
    ?>

    <div class="container">
        <button class="addBtn" type="button" onclick="window.location.href='viewHotel.php'">Hotels</button>
        <button class="addBtn" type="button" onclick="window.location.href='viewRoom.php'">Rooms</button>
        <button class="addBtn" type="button" onclick="window.location.href='viewReservation.php'">Reservations</button>
        <button class="addBtn" type="button" onclick="window.location.href='history.php'">History</button>
    </div>

    <?php
    include 'assets/footer.php';
    ?>
</body>

<style>
    .container {
        display: flex;
        flex-direction: row;
        flex-wrap: wrap;
        align-items: center;
        margin: 20px 20px;
        justify-content: center;
        min-height: calc(100vh - 146.63px - 61.33px);
    }

    .addBtn {
        display: block;
        padding: 100px 50px;
        font-weight: 600;
        font-size: 25px;
        margin: 25px;
        background-color: #006633;
        color: #ffffff;
        border-radius: 10px;
        width: 30%;
        border: none;
        cursor: pointer;
    }

    .addBtn:hover {
        background-color: #ffb85c;
        color: #000000;
    }

    @media (max-width:786px) {
        .addBtn {
            width: 80%;
        }
    }
</style>

</html>