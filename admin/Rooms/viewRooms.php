<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: /HRS/login.php");
    exit;
}

if (isset($_SESSION["loggedin"])) {
    if ((time() - $_SESSION['timestamp']) > 1800) {
        header("location: /HRS/logout.php");
    }
}

require_once "../../config/connection_db.php";

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="refresh" content="1800" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rooms - Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <script src="../../assets/js/menuToggle.js"></script>
    <link rel="stylesheet" href="../../assets/css/styles.css">

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

        .edit1 {
            color: gray;
            text-decoration: none;
        }

        .delete1 {
            color: gray;
            text-decoration: none;
        }
    </style>
</head>

<body>
    <?php
    include_once '../../admin/adminHeader.php';
    ?>

    <table class="table">
        <thead>
            <tr>
                <th>Sr. No</th>
                <th>Room Name</th>
                <th>Hotel Name</th>
                <th>Description</th>
                <th>Price</th>
                <th>Dimension</th>
                <th colspan="2">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql_query = "SELECT * FROM `room` JOIN `hotel` WHERE `room`.`hotel_id` = `hotel`.`hotel_id`";
            $sno = 0;
            $result = mysqli_query($connection, $sql_query);
            while ($row = mysqli_fetch_array($result)) {
                $sno = $sno + 1;
            ?>
                <tr>
                    <td><?php echo $sno ?></td>
                    <td><?php echo $row['room_name']; ?></td>
                    <td><?php echo $row['hotel_name']; ?></td>
                    <td style="word-break: break-all;"><?php echo $row['room_description']; ?></td>
                    <td><?php echo $row['room_price']; ?> $</td>
                    <td><?php echo $row['room_dimension']; ?> Sq. Ft</td>

                    <?php
                    $query = "SELECT * FROM `reservation`";
                    $res = mysqli_query($connection, $query);

                    if (mysqli_num_rows($res) > 0) {
                    ?>
                        <td><a class="edit1" href="#"><i class="fa-sharp fa-solid fa-pen"></i></a>&emsp;<a class="delete1" href="#"><i class="fa-solid fa-trash"></i></a></td>
                    <?php } else { ?>
                        <td><a class="edit" href="updateRooms.php?id=<?php echo $row['room_id']; ?>"><i class="fa-sharp fa-solid fa-pen"></i></a>&emsp;<a class="delete" href="deleteRooms.php?id=<?php echo $row['room_id']; ?>"><i class="fa-solid fa-trash"></i></a></td>
                    <?php
                    }
                    ?>
                </tr>
            <?php
            }
            mysqli_close($connection);
            ?>
        </tbody>
    </table>
    <?php
    include_once '../../admin/adminFooter.php';
    ?>
</body>

</html>