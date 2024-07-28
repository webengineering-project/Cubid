<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include '../backend/auth.php';
include '../backend/render.php';
include '../components/profile_menu.php';
include 'userdata.php';

// Fetch user data
$userData = getProfile();
?>

<html lang="de">
<head>
    <title>Mein Profil</title>
    <link rel="stylesheet" href="../components/navbar.css"/>
    <link rel="stylesheet" href="../css/settings_nav.css"/>
    <link rel="stylesheet" href="../css/event_form.css"/>
    <link rel="stylesheet" href="profile.css"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script src="modal.js"></script>
    <script src="profilePicture.js"></script>
</head>
<body>
<?php include '../components/navbar.php'; ?>
<div class="wrapper">
    <div id="profile">
        <?php
         $menu = renderProfileMenu($userData);
         echo $menu;
        ?>
        <div id="profile_content">
            <?php
            if (isset($_SESSION['username'])) {
                if ((isset($_GET['registration'])) && ($_GET['registration'] == 'success')) {
                    echo '<script type="text/javascript">sweetAlert("Erfolgreich","Dein Konto wurde erfolgreich angelegt. Du wurdest automatisch angemeldet. Viel Spaß!","success")</script>';
                }
                $userdata = userdataWithFriendData();

                if (isset($_GET['profile'])) {
                    $profile = $_GET['profile'];

                    if ($profile == "editProfile") {
                        echo render('profileEdit.xsl', $userdata->asXML());
                    } elseif ($profile == "self") {
                        echo render('profile.xsl', $userdata->asXML());
                    } else {
                        $profile = getProfileByEmail($_GET['profile']);
                        $profileXML = userDataByEmail($_GET['profile']);
                        if ($userData) {
                            echo render('profile.xsl', $profileXML->asXML());
                        } else {
                            header("Location: /user_search");
                            exit;
                        }
                    }
                } else {
                    echo render('profile.xsl', $userdata->asXML());
                }
            } else {
                ?>
                <meta http-equiv="refresh" content="0; URL=../login">
            <?php
            }
            ?>
        </div>
    </div>
</div>
</body>
</html>
