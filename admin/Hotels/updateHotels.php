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
    header("location:  ../Hotels/viewHotels.php");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = validate($_POST["id"]);
    $hotel_name = validate($_POST["name"]);
    $hotel_address = validate($_POST["address"]);
    $hotel_city = validate($_POST["city"]);
    $hotel_contact = validate($_POST["phone"]);
    $hotel_price = validate($_POST["price"]);
    $hotel_service = validate($_POST["service"]);

    $sql = "UPDATE `hotel` SET `service_id` = '$hotel_service', `hotel_name` = '$hotel_name', `address` = '$hotel_address', `city` = '$hotel_city', `phone` = '$hotel_contact', `price` = '$hotel_price' WHERE `hotel_id` = '$id'";

    $result = mysqli_query($connection, $sql);

    header("location: ../Hotels/viewHotels.php") or die("Query Failed");
    mysqli_close($connection);
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
            content: ' - Update Hotel';
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
    $selection = "SELECT * FROM `hotel` WHERE hotel_id = '$id'";
    $query_run = mysqli_query($connection, $selection);

    if (mysqli_num_rows($query_run) > 0) {
        while ($row = mysqli_fetch_assoc($query_run)) {
    ?>
            <div class="container">
                <div class="wrapper">
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                        <div class="form-group">
                            <input type="hidden" class="form-control" name="id" autocomplete="off" value="<?php echo $id; ?>">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" id="name" class="form-control" name="name" placeholder="Hotel Name" autocomplete="off" value="<?php echo $row['hotel_name'] ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="address" class="form-label">Address</label>
                            <input type="text" id="address" class="form-control" name="address" placeholder="Hotel Address" value="<?php echo $row['address'] ?>" autocomplete="off" required>
                        </div>
                        <div class="form-group">
                            <label for="city" class="form-label">City</label>
                            <input type="text" id="city" class="form-control" name="city" placeholder="City" autocomplete="off" value="<?php echo $row['city'] ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="phone" class="form-label">Phone No</label>
                            <input type="text" id="phone" class="form-control" name="phone" placeholder="Hotel Contact No" autocomplete="off" value="<?php echo $row['phone'] ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="price" class="form-label">Price</label>
                            <input type="text" id="price" class="form-control" name="price" placeholder="Price" autocomplete="off" value="<?php echo $row['price'] ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="servcies" class="form-label">Service</label>
                            <?php
                            $sql1 = "SELECT * FROM `service`";
                            $result1 = mysqli_query($connection, $sql1);

                            if (mysqli_num_rows($result1) > 0) {
                                echo '<select id="services" name="service" required>';
                                while ($row1 = mysqli_fetch_assoc($result1)) {
                                    if ($row1['service_id'] == $row['service_id']) {
                                        $select = "selected";
                                    } else {
                                        $select = "";
                                    }
                                    echo "<option {$select} value='{$row1['service_id']}'>{$row1['service_name']}</option>";
                                }
                                echo "</select>";
                            }
                            ?>
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