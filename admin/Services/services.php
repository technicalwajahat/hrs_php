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
$err_Message = false;

require_once "../../config/connection_db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $service = validate($_POST["service"]);

    $sql_s = "SELECT * from `service` WHERE `service_name` = '$service'";
    $res_s = mysqli_query($connection, $sql_s);

    if (mysqli_num_rows($res_s) > 0) {
        $err_Message = true;
    } else {
        $sql = "INSERT INTO `service` (`service_name`) VALUES ('$service')";
        $result = mysqli_query($connection, $sql) or die("Query Failed");

        $insert = true;
    }
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
    <title>Services - Admin</title>
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
                $(".message1").hide();
                $(".message2").hide();
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
            content: ' - Service';
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

        .submitBtn:hover {
            background-color: #ffb85c;
            color: #000000;
        }

        .message1 {
            background-color: #ffffe0;
            width: 100%;
            padding: 25px;
        }

        .message2 {
            background-color: #ffedf0;
            width: 100%;
            padding: 25px;
        }
    </style>
</head>

<body>
    <?php
    include_once '../../admin/adminHeader.php';
    if ($insert) {
        echo '<div class="message1" role="alert">
                <strong>Success!</strong> Your record has been inserted successfully.
            </div>';
    }

    if ($err_Message) {
        echo '<div class="message2" role="alert">
                <strong>Invalid!</strong> Service Already Exist.
            </div>';
    }
    ?>
    <div class="container">
        <div class="wrapper">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                <div class="form-group">
                    <label for="service" class="form-label">Service</label>
                    <input type="text" id="service" class="form-control" name="service" placeholder="Service Name" autocomplete="off" required>
                </div>
                <input class="submitBtn" type="submit" value="Add Service">
            </form>
        </div>
    </div>
    <?php
    include_once '../../admin/adminFooter.php';
    ?>
</body>

</html>