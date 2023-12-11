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

require_once "config/connection_db.php";

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="refresh" content="1800" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>History - HRS</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <script src="assets/js/menuToggle.js"></script>
    <link rel="stylesheet" href="assets/css/styles.css">

    <style>
        .table {
            border-collapse: collapse;
            width: 80%;
            margin: 50px auto;
            border-radius: 5px 5px 0 0;
            text-align: center;
            overflow: hidden;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
            min-height: calc(100vh - 146.63px - 61.33px);
        }

        .table thead tr {
            background-color: #006633;
            color: #ffffff;
            font-weight: bold;
        }

        .submitBtn {
            font-size: medium;
            font-weight: 500;
            background-color: #ff4040;
            color: white;
            padding: 12px 18px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .submitBtn:hover {
            background-color: red;
            color: white;
        }

        .table th,
        .table td {
            padding: 12px 15px;
        }

        .table tbody tr {
            border-bottom: 1px solid #dddddd;
            font-weight: 600;
        }

        .table tbody tr:nth-child(even) {
            background-color: #e8fff4;
        }

        .table tbody tr:last-of-type {
            border-bottom: 2px solid #006633;
        }

        .table tbody tr:hover {
            font-weight: 600;
            color: #006633;
        }

        .message {
            background-color: #ffffe0;
            width: 100%;
            padding: 25px;
        }

        .edit {
            color: green;
            text-decoration: none;
        }

        .delete {
            color: red;
            text-decoration: none;
        }
    </style>
</head>

<body>
    <?php
    include_once 'assets/header.php';
    ?>

    <table class="table">
        <thead>
            <tr>
                <th>Sr. No</th>
                <th>Cus/Emp Name</th>
                <th>Hotel</th>
                <th>Room</th>
                <th>Paid</th>
                <th>Check In</th>
                <th>Check Out</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $u_name = $_SESSION["username"];
            $sql_u = "SELECT * FROM `customer` WHERE `email` = '$u_name'";
            $res_u = mysqli_query($connection, $sql_u);
            $row_u = mysqli_fetch_assoc($res_u);

            $sql_query = "SELECT * FROM `billing` JOIN `reservation` ON `reservation`.`reservation_id` = `billing`.`reservation_id`
            JOIN `room` ON `room`.`room_id` = `reservation`.`room_id`
            JOIN `hotel` ON `hotel`.`hotel_id` = `room`.`hotel_id`
            JOIN `customer` ON `customer`.`user_id` = `reservation`.`user_id`
            WHERE `customer`.`user_id` = " . $row_u['user_id'] . " AND `check_out` IS NOT NULL";
            $sno = 0;
            $result = mysqli_query($connection, $sql_query);
            while ($row = mysqli_fetch_array($result)) {
                $sno = $sno + 1;
            ?>
                <tr>
                    <td><?php echo $sno; ?></td>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['hotel_name']; ?></td>
                    <td><?php echo $row['room_name']; ?></td>
                    <td><?php echo $row['total_amount']; ?> $</td>
                    <td><?php echo $row['check_in']; ?></td>
                    <td><?php echo $row['check_out']; ?></td>
                </tr>
            <?php
            }
            mysqli_close($connection);
            ?>
        </tbody>
    </table>
    <?php
    include_once 'assets/footer.php';
    ?>
</body>

</html>