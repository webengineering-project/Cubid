<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include '../backend/auth.php';
include '../backend/database.php';
include '../backend/render.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    global $database;

    $mail = $_SESSION['email'];
    $pictureUrl = $_POST["pictureUrl"];

    $stmt = $database -> prepare("UPDATE profile SET image=? WHERE email=?");
    $stmt -> bind_param("ss", $pictureUrl, $mail);

    if($stmt -> execute()){
        header("Location: /profile?profile=editProfile");
    }
}
?>