<?php
require_once "config\connection_db.php";

$checkPassword = false;
$err_Message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = validate($_POST["name"]);
    $username = validate($_POST["username"]);
    $address = validate($_POST["address"]);
    $food = validate($_POST["food"]);
    $password = validate($_POST["password"]);
    $confirmPassword = validate($_POST["confirmpassword"]);

    $sql_u = "SELECT * FROM `customer` WHERE email='$username'";
    $res_u = mysqli_query($connection, $sql_u);

    if ($password != $confirmPassword) {
        $checkPassword = true;
        $err_Message = "Password not matched";
    } else {
        if (mysqli_num_rows($res_u) > 0) {
            $checkPassword = true;
            $err_Message = "Username already exist";
        } else {
            $encryptPassword = md5($password);

            $sql = "INSERT INTO `customer` (`name`, `address`, `food_preferences`, `email`, `password`) VALUES ('$name', '$address', '$food', '$username', '$encryptPassword')";

            $result = mysqli_query($connection, $sql) or die("Query Failed");
            header("location: userLogin.php");
        }
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
    <title>Registration - HRS</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
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
            margin-top: 40px;
            border: #006633 1px solid;
            border-radius: 24px;
            width: 80%;
            padding: 25px;
        }

        .logoheader {
            background-color: #006633;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5% 3%;
        }

        .logoheader h1 {
            color: #ffffff;
        }

        .form-label {
            font-size: large;
            font-weight: 600;
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

        .message {
            background-color: #ffedf0;
            width: 100%;
            padding: 25px;
        }
    </style>
</head>

<body>
    <div class="logoheader">
        <h1>Hotel Reservation System - Register Now!</h1>
    </div>
    <?php
    if ($checkPassword) {
        echo '<div class="message" role="alert">
        <strong>Invalid! </strong>' . "$err_Message" . '</div>';
    }
    ?>

    <div class="container">
        <div class="wrapper">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                <div class="form-group">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" id="name" class="form-control" name="name" placeholder="Enter Full Name" autocomplete="off" required>
                </div>
                <div class="form-group">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" id="username" class="form-control" name="username" placeholder="Username" autocomplete="off" required>
                </div>
                <div class="form-group">
                    <label for="address" class="form-label">Address</label>
                    <input type="text" id="address" class="form-control" name="address" placeholder="Enter Address" autocomplete="off" required>
                </div>
                <div class="form-group">
                    <label for="food" class="form-label">Food Preferences</label>
                    <select id="food" name="food" required>
                        <option value="" selected disabled>None</option>
                        <option>Vegetarian</option>
                        <option>Non - Vegetarian</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" id="password" class="form-control" name="password" placeholder="*********" autocomplete="off" required>
                </div>
                <div class="form-group">
                    <label for="confirmpassword" class="form-label">Confirm Password</label>
                    <input type="password" id="confirmpassword" class="form-control" name="confirmpassword" placeholder="*********" autocomplete="off" required>
                </div>
                <input class="submitBtn" type="submit" value="Register">
                <div class="form-label" style="text-align: center;">
                    <label class="form-label">Already have an Account? <a style="text-decoration: none;" href="userLogin.php"><b style="color:#006633;">Sign In</b></a></label>
                </div>
            </form>
        </div>
    </div>
    <?php
    include_once 'assets/footer.php';
    ?>
</body>

</html>