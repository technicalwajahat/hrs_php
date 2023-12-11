<?php
session_start();

if (!isset($_SESSION["userloggedin"]) || $_SESSION["userloggedin"] !== true) {
    header("location: /HRS/userLogin.php");
    exit;
}

if (isset($_SESSION["loggedin"])) {
    if ((time() - $_SESSION['timestamp']) > 1800) {
        header("location: /HRS/userLogout.php");
    }
}

$insert = false;

require_once "config/connection_db.php";

if (!isset($_GET['id'])) {
    header("location: viewRoom.php");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $rid = validate($_POST['id']);
    $uid = validate($_POST['uid']);
    $checkin = validate($_POST["checkdate"]);
    $date = date('Y-m-d', strtotime($checkin));

    $sql = "INSERT INTO `reservation` (`user_id`, `room_id`, `check_in`) VALUES ('$uid', '$rid', '$date')";
    $result = mysqli_query($connection, $sql) or die("Query Failed");

    header('location: viewRoom.php');
}

function validate($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="refresh" content="1800" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservation - HRS</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <script src="assets/js/menuToggle.js"></script>
    <link rel="stylesheet" href="assets/css/styles.css">

    <script>
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }

        $(document).ready(function() {
            setTimeout(function() {
                $(".message").hide();
            }, 5000);
        });
    </script>

    <style>
        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            min-height: calc(100vh - 146.63px - 61.33px);
        }

        .wrapper {
            width: 80%;
        }

        .logoheader h1:after {
            content: ' - Room';
        }

        .form-label {
            font-size: large;
            font-weight: 600;
        }

        .wrapper .form-group:nth-child(1) {
            margin-top: 30px;
        }

        .form-control,
        select {
            text-align: left;
            width: 100%;
            font-size: 18px;
            padding: 14px 18px;
            margin: 10px 0;
            display: inline-block;
            border: 1px solid #cccccc;
            border-radius: 6px;
            box-sizing: border-box;
        }

        .submitBtn {
            width: 100%;
            font-size: larger;
            font-weight: 600;
            background-color: #006633;
            color: white;
            padding: 14px 20px;
            margin: 12px 0;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        textarea {
            resize: none;
        }

        .submitBtn:hover {
            background-color: #ffb85c;
            color: #000000;
        }

        .table {
            border-collapse: collapse;
            width: 80%;
            margin: 20px auto;
            border-radius: 5px 5px 0 0;
            text-align: center;
            overflow: hidden;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
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
    </style>
</head>

<body>
    <?php
    include_once 'assets/header.php';
    $id = $_GET['id'];
    $u_name = $_SESSION["username"];

    $sql_r = "SELECT * FROM `room` JOIN `hotel` ON `room`.`hotel_id` = `hotel`.`hotel_id` WHERE `room_id` = '$id'";
    $sql_u = "SELECT * FROM `customer` WHERE `email` = '$u_name'";

    $result = mysqli_query($connection, $sql_r);
    $res_u = mysqli_query($connection, $sql_u);

    $row_u = mysqli_fetch_assoc($res_u);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
    ?>
            <div class="container">
                <div class="wrapper">
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                        <div class="form-group">
                            <label for="user" class="form-label">Customer/Employee Name</label>

                            <input type="hidden" id="user" class="form-control" name="uid" value="<?php echo $row_u['user_id']; ?>">

                            <input type="text" id="user" class="form-control" name="user" placeholder="Customer or Employee" disabled value="<?php echo $row_u['name']; ?>" autocomplete="off" required>
                        </div>
                        <div class="form-group">
                            <label for="hotel" class="form-label">Hotel</label>
                            <input type="text" id="hotel" class="form-control" name="hotel" placeholder="Hotel" autocomplete="off" disabled value="<?php echo $row['hotel_name']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="room" class="form-label">Room</label>
                            <input type="hidden" class="form-control" name="id" autocomplete="off" value="<?php echo $id; ?>">
                            <input type="text" id="room" class="form-control" name="room" placeholder="Room" autocomplete="off" disabled value="<?php echo $row['room_name']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="price" class="form-label">Price</label>
                            <input type="text" id="price" class="form-control" name="price" placeholder="Price" autocomplete="off" disabled value="<?php echo $row['room_price'], " $"; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="dimension" class="form-label">Dimension</label>
                            <input type="text" id="dimension" class="form-control" name="dimension" placeholder="Dimension" disabled value="<?php echo $row['room_dimension']; ?>" autocomplete="off" required>
                        </div>
                        <div class="form-group">
                            <label for="checkdate" class="form-label">Check-In Date</label>
                            <input type="date" id="checkdate" class="form-control" name="checkdate" min="<?php echo date("Y-m-d"); ?>" max="<?php echo date("Y-m-d", strtotime('+28 days')) ?>" autocomplete="off" required>
                        </div>
                        <input class='submitBtn' type='submit' value='Book Room'>
                    </form>
            <?php
        }
    }
            ?>

                </div>
            </div>
            <?php
            include_once 'assets/footer.php';
            ?>
</body>

</html>