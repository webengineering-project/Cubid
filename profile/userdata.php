<?php

function userdataWithFriendData(): SimpleXMLElement
{

    $userdata = new SimpleXMLElement('<userdata></userdata>');

    $users = $userdata->addChild('users');
    $current = $userdata->addChild('current');
    $games = $userdata->addChild('games');
    $currentGame = $current->addChild('game');
    $friendsDataXml = $current->addChild('friends');


    $sessionUser = $users->addChild('sessionuser');
    $sessionUser ->addChild('username',$_SESSION['username']);
    $sessionUser ->addChild('email',$_SESSION['email']);

    $dbUsers = getAllUsers();
    foreach ($dbUsers as $user) {
        $newUser = $users->addChild('user');
        $newUser->addChild('username', $user['username']);
        $newUser->addChild('email', $user['email']);
    }

    $user = getCurrentUser();
    $current->addChild('name', $user['username']);
    $current->addChild('email', $user['email']);

    $profile = getProfile();
    $current->addChild('location', $profile['location']);
    $current->addChild('last_name', $profile['last_name']);
    $current->addChild('first_name', $profile['first_name']);
    $current->addChild('email', $profile['email']);
    $current->addChild('about_me', $profile['about_me']);
    $current->addChild('image', $profile['image']);

    $globalGames = getAllGames();
    foreach ($globalGames as $game) {
        $games->addChild('id', $game['id'] );
        $games->addChild('name', $game['name']);
        $games->addChild('description', $game['description']);
    }

    $currentGames = getCurrentGames();
    foreach ($currentGames as $game) {
        //$newGame = $current->addChild('game');
        $currentGame->addChild('name', $game['name']);
        $currentGame->addChild('id', $game['id']);
    }

    $friends = getAllFriends($_SESSION['email']);
    foreach ($friends as $friend) {
        $friendData = getProfileByEmail($friend["friend"]);
        $friendData2 = getUserByEmail($friend["friend"]);

        $newFriend = $friendsDataXml->addChild('friend');
        $newFriend->addChild('email', $friend['friend']);
        $newFriend->addChild('username', $friendData2["username"]);
        $newFriend->addChild('image', $friendData['image']);
    }

    return $userdata;
}

function userdata($usernmae): SimpleXMLElement
{

    $userdata = new SimpleXMLElement('<userdata></userdata>');

    $users = $userdata->addChild('users');
    $current = $userdata->addChild('current');
    $games = $userdata->addChild('games');
    $currentGame = $current->addChild('game');

    $friendsDataXml = $current->addChild('friends');

    $dbUsers = getAllUsers();
    foreach ($dbUsers as $user) {
        $newUser = $users->addChild('user');
        $newUser->addChild('username', $user['username']);
        $newUser->addChild('email', $user['email']);
    }

    $user = getCurrentUser();
    $current->addChild('name', $user['username']);
    $current->addChild('email', $user['email']);

    $profile = getProfile();
    $current->addChild('location', $profile['location']);
    $current->addChild('last_name', $profile['last_name']);
    $current->addChild('first_name', $profile['first_name']);
    $current->addChild('email', $profile['email']);
    $current->addChild('about_me', $profile['about_me']);
    $current->addChild('image', $profile['image']);

    $globalGames = getAllGames();
    foreach ($globalGames as $game) {
        $games->addChild('id', $game['id'] );
        $games->addChild('name', $game['name']);
        $games->addChild('description', $game['description']);
    }

    $currentGames = getCurrentGames();
    foreach ($currentGames as $game) {
        //$newGame = $current->addChild('game');
        $currentGame->addChild('name', $game['name']);
        $currentGame->addChild('id', $game['id']);
    }

    return $userdata;
}


function userDataByEmail($email): SimpleXMLElement
{
    $userdata = new SimpleXMLElement('<userdata></userdata>');

    $users = $userdata->addChild('users');
    $games = $userdata->addChild('games');
    $current = $userdata->addChild('current');
    $currentGame = $current->addChild('game');
    $friendsDataXml = $current->addChild('friends');

    // Set SessionUser
    $sessionUser = $users->addChild('sessionuser');
    $sessionUser ->addChild('username',$_SESSION['username']);
    $sessionUser ->addChild('email',$_SESSION['email']);
    // Fetch user data by email
    $user = getUserByEmail($email);

    if ($user) {
        $newUser = $users->addChild('user');
        $newUser->addChild('username', $user['username']);
        $newUser->addChild('email', $user['email']);
    }

    // Fetch profile data by email
    $profile = getProfileByEmail($email);

    if ($profile) {
        $current = $userdata->addChild('current');
        $current->addChild('name', $user['username']);
        $current->addChild('email', $profile['email']);
        $current->addChild('location', $profile['location']);
        $current->addChild('last_name', $profile['last_name']);
        $current->addChild('first_name', $profile['first_name']);
        $current->addChild('about_me', $profile['about_me']);
        $current->addChild('image', $profile['image']);
    }

    $userSettings = getUserSettings($email);
    $current->addChild('receive_friend_requests', $userSettings['receive_friend_requests']);


    $globalGames = getAllGames();
    foreach ($globalGames as $game) {
        $games->addChild('id', $game['id'] );
        $games->addChild('name', $game['name']);
        $games->addChild('description', $game['description']);
    }

    $currentGames = getGamesOfProfile($email);
    foreach ($currentGames as $game) {
        //$newGame = $current->addChild('game');
        $currentGame->addChild('name', $game['name']);
        $currentGame->addChild('id', $game['id']);
    }

    $friends = getAllFriends($email);

    foreach ($friends as $friend) {
        $friendData = getProfileByEmail($friend["friend"]);
        $friendData2 = getUserByEmail($friend["friend"]);

        $newFriend = $friendsDataXml->addChild('friend');
        $newFriend->addChild('email', $friend['friend']);
        $newFriend->addChild('username', $friendData2["username"]);
        $newFriend->addChild('image', $friendData['image']);
    }


    $pendingRequest = pendingFriendRequest($_SESSION["email"], $email);
    $current->addChild('pendingRequest', $pendingRequest ? 'true' : 'false');

    return $userdata;
}
