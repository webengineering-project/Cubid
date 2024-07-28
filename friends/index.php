<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include '../backend/auth.php';
include '../backend/render.php';
include '../components/profile_menu.php';

// Fetch user data
$userData = getProfile();
?>

<html>
<head>
    <title>Mein Profil</title>
    <link rel="stylesheet" href="../components/navbar.css"/>
    <link rel="stylesheet" href="friends.css"/>
    <link rel="stylesheet" href="../css/settings_nav.css"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script src="friends.js"></script>
</head>
<body>
<?php include '../components/navbar.php'; ?>
<div class="wrapper">
    <div id="profile">
        <?php
        echo renderProfileMenu($userData);
        ?>
        <div id="content">
            <?php
            $data = new SimpleXMLElement('<data></data>');
            $friendsXML = $data->addChild('friends');
            $friendRequestXML = $data->addChild('friend_requests');

            $email = $_SESSION['email'];

            $userData = getUserByEmail($email);
            $userSettings = getUserSettings($email);

            $friends = getAllFriends($email);
            foreach ($friends as $friend) {
                $newFriend = $friendsXML->addChild('friend');

                $friendEmail = $friend['friend'];
                $userOfFriend = getUserByEmail($friendEmail);
                $userNameOfFriend = $userOfFriend['username'];
                $profileOfFriend = getProfileByEmail($friendEmail);
                $imageOfFriend = $profileOfFriend['image'];

                $newFriend->addChild("email", $friendEmail);
                $newFriend->addChild("image", $imageOfFriend);
                $newFriend->addChild("username", $userNameOfFriend);
            }

            $requests = getFriendRequests($email);
            foreach ($requests as $request) {
                $newRequest = $friendRequestXML->addChild('request');
                $requestMail = $request;

                $newRequest->addChild('email', $requestMail);
            }

            echo render('friends.xsl', $data->asXML());
            ?>
        </div>
    </div>
</div>
</body>
</html>