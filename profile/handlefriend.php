<?php
session_start();
include '../backend/auth.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if(isset($_POST["addFriend"])){
        $addFriend = $_POST["addFriend"];
        //echo ("SMOKE1");
        addFriendRequest($_SESSION['email'], $addFriend);
        Header("Location: ../profile?profile=" . $addFriend);
    }else if (isset($_POST["removeFriend"])){
        $removeFriend = $_POST["removeFriend"];
        unfriend($_SESSION['email'], $removeFriend);
        Header("Location: ../profile?profile=" . $removeFriend);
    }else if (isset($_POST["revokeFriendRequest"])) {
        $revokeFriendRequest = $_POST["revokeFriendRequest"];
        unfriend($_SESSION['email'], $revokeFriendRequest);
        Header("Location: ../profile?profile=" . $revokeFriendRequest);
    } else {
        echo("ERROR 500");
    }
}
