<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include '../backend/auth.php';
include '../backend/database.php';
include '../backend/render.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    global $database;

    $mail = $_SESSION['email'];
    $stmt = $database -> prepare("UPDATE profile SET image='https://cdn.pixabay.com/photo/2020/07/01/12/58/icon-5359553_1280.png' WHERE email=?");
    $stmt -> bind_param("s", $mail);

    if($stmt -> execute()){
        header("Location: /profile?profile=editProfile");
    }
}
?>