<?php
session_start();
include '../backend/auth.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $result = registerUser($username, $email, $password);

    if ($result) {
        Header("Location: /profile?registration=success");
    } else {
        header("Location: /register?error=usernameExists");
    }
    exit();

}
