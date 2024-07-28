<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include '../backend/auth.php';
include '../components/navbar.php';
include '../backend/database.php';
include '../backend/calendar.php';

$xsl = new DOMDocument();
$xsl->load('user_search.xsl');

$data = new SimpleXMLElement('<data></data>');

$users = $data->addChild('users');
$games = $data->addChild('games');

$dbUsers = getAllUsers();
foreach ($dbUsers as $user) {
    $newUser = $users->addChild('user');
    $profile = getProfileByEmail($user['email']);

    $newUser->addChild('username', $user['username']);
    $newUser->addChild('email', $user['email']);
    $newUser->addChild('image', $profile['image']);
    $newUser->addChild('location', $profile['location']);
    $newUser->addChild('about_me', $profile['about_me']);

    $userGamesData = $newUser->addChild('user_games');
    $userGames = getGamesOfProfile($user['email']);
    foreach ($userGames as $game) {
        $newGame = $userGamesData->addChild('user_game');
        $newGame->addChild("id", $game["id"]);
        $newGame->addChild("name", $game["name"]);
    }

}

$globalGames = getAllGames();
foreach ($globalGames as $game) {
    $newGame = $games->addChild('game');
    $newGame->addChild('id', $game['id']);
    $newGame->addChild('name', $game['name']);
    $newGame->addChild('description', $game['description']);
}

$proc = new XSLTProcessor();
$proc->importStyleSheet($xsl);

$result = $proc->transformToXml($data);

echo $result;
