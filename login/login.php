<?php
include '../backend/auth.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];


    $authenticated = loginUser($username, $password);

    if ($authenticated) {
        $_SESSION["username"] = $username;
        header("Location: /");
    } else {
        header("Location: /login?error=authenticationFailed");
    }

}

