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
    <title>Rooms - HRS</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <script src="assets/js/menuToggle.js"></script>
    <link rel="stylesheet" href="assets/css/styles.css">

    <style>
        .table {
            border-collapse: collapse;
            width: 80%;
            margin: 25px auto;
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
            font-size: large;
            font-weight: 500;
            background-color: #006633;
            color: white;
            padding: 14px 20px;
            margin-left: 8px;
            margin-right: 8px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .submitBtn:hover {
            background-color: #ffb85c;
            color: #000000;
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

        .form-label {
            font-size: large;
            font-weight: 600;
        }

        .form-group {
            margin-top: 30px;
        }

        .wrapper {
            display: flex;
            flex-direction: row;
            margin: auto;
            width: 80%;
            justify-content: flex-end;
        }

        .form-control {
            text-align: left;
            font-size: 18px;
            padding: 14px 18px;
            margin: 10px 0;
            border: 1px solid #cccccc;
            border-radius: 6px;
            box-sizing: border-box;
        }
    </style>
</head>

<body>
    <?php
    include_once 'assets/header.php';
    ?>

    <div class="wrapper">
        <form action="" method="GET">
            <div class="form-group">
                <input type="text" id="search" class="form-control" name="search" autocomplete="off" value="<?php if (isset($_GET['search'])) { echo $_GET['search'];} ?>" placeholder="Search Room">
                <input class='submitBtn' type='submit' value='Search'>
            </div>
        </form>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>Sr. No</th>
                <th>Room Name</th>
                <th>Hotel Name</th>
                <th>Description</th>
                <th>Price</th>
                <th>Dimension</th>
                <th>Actions</th>
            </tr>
        </thead>

        <tbody>
            <?php
            if (isset($_GET['search'])) {
                $sno = 0;

                $filterData = $_GET['search'];
                $query = "SELECT * from `room` JOIN `hotel` ON `room`.`hotel_id` = `hotel`.`hotel_id` WHERE CONCAT(room_name, hotel_name, room_price, room_dimension) LIKE '%$filterData%'";
                $query_run = mysqli_query($connection, $query);

                if (mysqli_num_rows($query_run) > 0) {
                    foreach ($query_run as $items) {
                        $sno = $sno + 1;
            ?>
                        <tr>
                            <td><?php echo $sno ?></td>
                            <td><?php echo $items['room_name']; ?></td>
                            <td><?php echo $items['hotel_name']; ?></td>
                            <td style="word-break: break-all;"><?php echo $items['room_description']; ?></td>
                            <td><?php echo $items['room_price']; ?>$</td>
                            <td><?php echo $items['room_dimension']; ?> Sq. Ft</td>
                            <td><input class='submitBtn' type='submit' value='Check In'></td>
                        </tr>
                    <?php
                    }
                } else {
                    ?>
                    <tr>
                        <td colspan="7">No Record Found</td>
                    </tr>
                <?php
                }
            } else {
                ?>
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
                        <td><a href="addReservation.php?id=<?php echo $row['room_id']; ?>"><input class='submitBtn' value="Check In" type='submit'></a></td>
                    </tr>
            <?php
                }
                mysqli_close($connection);
            }
            ?>
        </tbody>
    </table>
    <?php
    include_once 'assets/footer.php';
    ?>
</body>

</html>