<?php
session_start();
include '../backend/auth.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $selectedTags = $_POST['selectedTags'];
    if($selectedTags == ""){

        updateOwnedGames($selectedTags);
    }else {
        $tagsArray = preg_split('/\d+/', $selectedTags, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
        updateOwnedGames($tagsArray);

    }
    Header("Location: ../profile?profile=editProfile");
}
?>