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
    <link rel="stylesheet" href="settings.css"/>
    <link rel="stylesheet" href="../profile/profile.css"/>
    <link rel="stylesheet" href="../css/settings_nav.css"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
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

            $userData = getUserByEmail($_SESSION['email']);
            $userSettings = getUserSettings($_SESSION['email']);

            $data->addChild('username', $userData['username']);
            $data->addChild('receive_mails', $userSettings['receive_mails']);
            $data->addChild('receive_friend_requests', $userSettings['receive_friend_requests']);
            $data->addChild('profile_visibility', $userSettings['profile_visibility']);

            echo render('settings.xsl', $data->asXML());
            ?>
        </div>
    </div>
</div>
</body>
</html>