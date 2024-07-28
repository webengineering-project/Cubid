<?php
session_start();
include '../backend/auth.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_SESSION['username'];
    $location = $_POST['location'];
    $firstname = $_POST['first_name'];
    $lastname = $_POST['last_name'];
    $about_me = $_POST['about_me'];
    if(!$about_me){
        $about_me = "";
    }

    //echo $email;
    updateProfile($firstname,$lastname,$location,$about_me);

    Header("Location: ../profile?profile=editProfile");
}
?>