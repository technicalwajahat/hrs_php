<?php
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: ../login.php");
    exit;
}
?>

<footer>
    <div class="copyright">
        <p id="copyrightText">Copyright &copy <?php echo date("Y"); ?> American University of the Middle East</p>
    </div>
</footer>

<style>
    /* Footer Section */

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: Poppins, sans-serif;
    }

    footer {
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .copyright {
        display: block;
        width: 100%;
        margin-top: 25px;
        background: #e8fff4;
        padding: 25px 0;
    }

    #copyrightText {
        font-size: 14px;
        text-align: center;
        font-weight: 500;
    }

    #copyrightText a {
        text-decoration: none;
        color: black;
    }

    #copyrightText a:hover {
        color: #ff9100;
    }

    /* Media Queries : Footer */

    @media (Max-width:1192px) {
        .copyright {
            padding: 20px 16px;
        }

        #copyrightText {
            font-size: 12px;
        }
    }
</style>