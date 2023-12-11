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

$insert = false;

require_once "../../config/connection_db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $room_name = validate($_POST["name"]);
    $room_description = validate($_POST["description"]);
    $room_price = validate($_POST["price"]);
    $room_dimension = validate($_POST["dimension"]);
    $hotel_id = validate($_POST["hotel"]);

    $sql = "INSERT INTO `room` (`hotel_id`, `room_name`, `room_price`, `room_dimension`, `room_description`) VALUES ('$hotel_id', '$room_name', '$room_price', '$room_dimension', '$room_description')";
    $result = mysqli_query($connection, $sql) or die("Query Failed");

    $insert = true;
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
    <title>Rooms - Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <script src="../../assets/js/menuToggle.js"></script>
    <link rel="stylesheet" href="../../assets/css/styles.css">

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

        .message {
            background-color: #ffffe0;
            width: 100%;
            padding: 25px;
        }
    </style>
</head>

<body>
    <?php
    include_once '../../admin/adminHeader.php';
    if ($insert) {
        echo '<div class="message" role="alert">
                <strong>Success!</strong> Your record has been inserted successfully.
            </div>';
    }
    ?>
    <div class="container">
        <div class="wrapper">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                <div class="form-group">
                    <label for="hotel" class="form-label">Hotel</label>
                    <select id="hotel" name="hotel" required>
                        <option value="" selected disabled>None</option>
                        <?php
                        $query = "SELECT * FROM `hotel`";
                        $result = mysqli_query($connection, $query);
                        while ($row = mysqli_fetch_assoc($result)) {
                        ?>
                            <option value="<?php echo $row['hotel_id']; ?>"><?php echo $row['hotel_name']; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="name" class="form-label">Room Name</label>
                    <input type="text" id="name" class="form-control" name="name" placeholder="Room Name" autocomplete="off" required>
                </div>
                <div class="form-group">
                    <label for="description" class="form-label">Description</label>
                    <textarea rows="5" type="text" id="description" class="form-control" name="description" placeholder="Description" autocomplete="off" required></textarea>
                </div>
                <div class="form-group">
                    <label for="price" class="form-label">Price</label>
                    <input type="text" id="price" class="form-control" name="price" placeholder="Price" autocomplete="off" required>
                </div>
                <div class="form-group">
                    <label for="dimension" class="form-label">Dimension</label>
                    <input type="text" id="dimension" class="form-control" name="dimension" placeholder="Dimension in Sq Ft." autocomplete="off" required>
                </div>
                <?php
                if (!mysqli_num_rows($result) > 0) {
                    echo "<input disabled style='background-color: gray; color: white; pointer-events: none;' class='submitBtn' type='submit' value='Submit'>";
                } else {
                    echo "<input class='submitBtn' type='submit' value='Submit'>";
                }
                ?>
            </form>
        </div>
    </div>
    <?php
    include_once '../../admin/adminFooter.php';
    ?>
</body>

</html>