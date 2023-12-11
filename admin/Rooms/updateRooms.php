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

if (!isset($_GET['id'])) {
    header('location: viewRooms.php');
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = validate($_POST["id"]);
    $room_name = validate($_POST["name"]);
    $room_description = validate($_POST["description"]);
    $room_price = validate($_POST["price"]);
    $room_dimension = validate($_POST["dimension"]);
    $hotel_id = validate($_POST["hotel"]);

    $sql = "UPDATE `room` SET `hotel_id` = '$hotel_id', `room_name` = '$room_name', `room_price` = '$room_price', `room_dimension` = '$room_dimension', `room_description` = '$room_description' WHERE `room_id` = '$id'";

    $result = mysqli_query($connection, $sql);
    header("location: ../Rooms/viewRooms.php") or die("Query Failed");
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
    <title>Hotels - Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <script src="../../assets/js/menuToggle.js"></script>
    <link rel="stylesheet" href="../../assets/css/styles.css">
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
            content: ' - Update Room';
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

        .submitBtn:hover {
            background-color: #ffb85c;
            color: #000000;
        }
    </style>
</head>

<body>
    <?php
    include_once '../../admin/adminHeader.php';

    $id = $_GET['id'];
    $selection = "SELECT * FROM `room` WHERE room_id = '$id'";
    $query_run = mysqli_query($connection, $selection);

    if (mysqli_num_rows($query_run) > 0) {
        while ($row = mysqli_fetch_assoc($query_run)) {
    ?>
            <div class="container">
                <div class="wrapper">
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                        <div class="form-group">
                            <label for="hotel" class="form-label">Hotel</label>
                            <?php
                            $sql1 = "SELECT * FROM `hotel`";
                            $result1 = mysqli_query($connection, $sql1);

                            if (mysqli_num_rows($result1) > 0) {
                                echo '<select id="hotel" name="hotel" required>';
                                while ($row1 = mysqli_fetch_assoc($result1)) {
                                    if ($row1['hotel_id'] == $row['hotel_id']) {
                                        $select = "selected";
                                    } else {
                                        $select = "";
                                    }
                                    echo "<option {$select} value='{$row1['hotel_id']}'>{$row1['hotel_name']}</option>";
                                }
                                echo "</select>";
                            }
                            ?>
                        </div>
                        <div class="form-group">
                            <label for="name" class="form-label">Room Name</label>
                            <input type="hidden" class="form-control" name="id" autocomplete="off" value="<?php echo $id; ?>">
                            <input type="text" id="name" class="form-control" name="name" placeholder="Room Name" autocomplete="off" value="<?php echo $row['room_name']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="description" class="form-label">Description</label>
                            <textarea rows="5" type="text" id="description" class="form-control" name="description" placeholder="Description" autocomplete="off" required><?php echo $row['room_description']; ?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="price" class="form-label">Price</label>
                            <input type="text" id="price" class="form-control" name="price" placeholder="Price" autocomplete="off" value="<?php echo $row['room_price']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="dimension" class="form-label">Dimension</label>
                            <input type="text" id="dimension" class="form-control" name="dimension" placeholder="Dimension in Sq Ft." autocomplete="off" value="<?php echo $row['room_dimension']; ?>" required>
                        </div>
                        <input class="submitBtn" type="submit" value="Update">
                    </form>
                </div>
            </div>
    <?php
        }
    }
    include_once '../../admin/adminFooter.php';
    ?>
</body>

</html>