<?php
include '../backend/auth.php';
include '../backend/database.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["email"])) {
        echo $_POST["email"];

        declineFriendRequest($_POST["email"], $_SESSION['email']);
        header("Location: /friends");
    }
}

?>
