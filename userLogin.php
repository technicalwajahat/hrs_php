<?php
session_start();

if (isset($_SESSION["userloggedin"]) && $_SESSION["userloggedin"] === true) {
    header("location: index.php");
    exit;
}

require_once "config\connection_db.php";

$login_error = NULL;
$reCAPTCHA_error = NULL;

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = validate($_POST["username"]);
    $password = md5(validate($_POST["password"]));

    $secretKey = $secret_key;
    $responseKey = $_POST["g-recaptcha-response"];
    $userIP = $_SERVER["REMOTE_ADDR"];

    $url = "https://www.google.com/recaptcha/api/siteverify?secret=$secretKey&response=$responseKey&remoteip=$userIP";
    $response = file_get_contents($url);
    $json = json_decode($response);

    if ($json->success == "true") {
        if ($stmt = $connection->prepare('SELECT `user_id`, `password` FROM `customer` WHERE email = ?')) {
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $stmt->bind_result($id, $hash_password);
                $stmt->fetch();
                if ($password === $hash_password) {
                    session_regenerate_id();
                    $_SESSION["userloggedin"] = true;
                    $_SESSION['username'] = $username;

                    $_SESSION['timestamp'] = time();
                    header("location: index.php");
                    exit;
                } else {
                    $login_error = 'Invalid Credentials!';
                }
            } else {
                $login_error =  'Invalid Credentials!';
            }
        }
    } else {
        $reCAPTCHA_error = "Check 'I'm not a robot'";
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login - HRS</title>
    <link rel="stylesheet" href="assets/css/styles.css">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>

    <script>
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    </script>

    <style>
        body {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background-image: url('/assets/imgs/BgLogin.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            overflow: hidden;
        }

        .login {
            width: 355px;
            padding: 25px;
            margin: 25% auto;
            height: auto;
            border-radius: 12px;
            border: 1px solid #006633;
            background-color: #e8fff4;
        }

        .form-control:focus {
            background-color: #ffffff;
            border-color: #006633;
            outline: 0;
            box-shadow: none;
        }

        .headingLogin {
            text-align: center;
            background-color: #006633;
            padding-top: 14px;
            padding-bottom: 14px;
            border-radius: 8px;
            margin-bottom: 14px;
        }

        .headingLogin h2 {
            color: white;
            font-weight: 600;
            font-size: 26px;
            margin: 0px;
        }
    </style>
</head>

<body>
    <div class="login">
        <div class="headingLogin">
            <h2>User Login</h2>
        </div>
        <?php
        if ($login_error) {
            echo '<div class="alert alert-danger" role="alert">
                <strong>Error: </strong> ' . $login_error . '
            </div>';
        }

        if ($reCAPTCHA_error) {
            echo '<div class="alert alert-danger" role="alert">
                <strong>Error: </strong> ' . $reCAPTCHA_error . '
            </div>';
        }
        ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <div class="form-group mb-3">
                <label class="form-label" for="username">Username</label>
                <input type="text" name="username" id="username" class="form-control" required autocomplete="off" placeholder="Username" />
            </div>
            <div class="form-group  mb-4">
                <label class="form-label" for="pass">Password</label>
                <input type="password" id="pass" name="password" class="form-control" required autocomplete="off" placeholder="**********" />
            </div>
            <div id="recaptcha" class="g-recaptcha" data-sitekey="<?php echo $site_key; ?>"></div>
            <input type="submit" value="Sign in" class="btn btn-success mt-4 mb-4 w-100"></button>
            <div class="form-label" style="text-align: center;">
                <label class="form-label mb-0 mt-0 fs-6">Dont have an Account? <a style="text-decoration: none;" href="registration.php"><b style="color:#006633;">Sign Up</b></a></label>
            </div>
        </form>
    </div>

</body>

</html>