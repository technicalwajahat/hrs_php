<?php
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: ../login.php");
    exit;
}
?>

<header>
    <div class="logoheader">
        <h1>Admin Panel</h1>
        <a class="logoutBtn" role="button" href="/HRS/logout.php">Logout</a>
        <i class="fa-solid fa-bars"></i>
    </div>
    <div class="header">
        <nav>
            <ul>
                <li><a href="/HRS/admin/admin.php">Home</a></li>
                <li><a href="/HRS/admin/Hotels/viewHotels.php">Hotels</a></li>
                <li><a href="/HRS/admin/Rooms/viewRooms.php">Rooms</a></li>
                <li><a href="/HRS/admin/Services/viewServices.php">Services</a></li>
                <li><a href="/HRS/admin/viewReservations.php">Reservations</a></li>
                <li><a href="/HRS/admin/history.php">Payments</a></li>
                <li class="logout"><a href="/HRS/logout.php">Logout</a></li>
            </ul>
        </nav>
    </div>
</header>

<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: Poppins, sans-serif;
    }

    /* Header Section */

    .logoheader {
        background-color: #006633;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1% 3%;
    }

    .logoheader h1 {
        color: #ffffff;
        font-size: 24px;
    }

    .logoutBtn {
        text-decoration: none;
        color: #ffffff;
        border: none;
        background: none;
        font-size: medium;
        font-weight: 600;
    }

    .logoutBtn:hover {
        color: #ffb85c;
    }

    .logoheader i {
        display: none;
        color: white;
        cursor: pointer;
    }

    .header {
        background-color: #e8fff4;
        padding: 20px 30px;
    }

    nav ul li {
        display: inline-block;
        list-style: none;
        margin: 0px 20px;
    }

    .logout {
        visibility: hidden;
    }

    nav ul li a {
        text-decoration: none;
        color: #006633;
        font-size: 14px;
        font-weight: 600;
        text-transform: uppercase;
    }

    nav ul li a:hover {
        color: #ff9100;
    }

    /* Media Queries : Header */

    @media (max-width:798px) {
        body.show {
            overflow-y: hidden;
        }

        .logoheader {
            padding: 2% 4%;
        }

        .logoheader h1 {
            font-size: 20px;
        }

        .logoutBtn {
            display: none;
        }

        .logoheader img {
            display: none;
        }

        .logoheader i {
            size: 14px;
            display: block;
        }

        .header {
            padding: 0 0;
        }

        nav ul {
            background: #e8fff4;
            position: fixed;
            width: 100%;
            left: -100%;
            height: 100vh;
            text-align: center;
            transition: all 0.3s;
        }

        .logout {
            visibility: visible;
        }

        nav ul li {
            display: block;
            margin: 30px 0;
        }

        nav ul li a {
            font-size: 16px;
        }

        nav ul.show {
            left: 0;
            z-index: 2;
        }
    }
</style>