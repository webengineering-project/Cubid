<?php
function renderProfileMenu($userData)
{
    ob_start();
    ?>
    <?php if (!isset($_GET['profile']) || ($_GET['profile'] == "editProfile" || $_GET['profile'] == "self")): ?>
    <div id="profile_menu">
        <div class="profile_info">
            <img src="<?php echo $userData['image']; ?>" alt="Profile Image" class="profile_image">
            <span><?php echo $userData['first_name'] . ' ' . $userData['last_name']; ?></span>
        </div>
        <ul>
            <li <?php if ((isset($_GET['profile']) && $_GET['profile'] != "editProfile")) {
                echo 'class="active"';
            } ?>>
                <a href="/profile?profile=self">Profil</a>
            </li>
            <li <?php if (isset($_GET['profile']) && ($_GET['profile']) == "editProfile") {
                echo 'class="active"';
            } ?>>
                <a href="/profile?profile=editProfile">Profil bearbeiten</a>
            </li>
            <li <?php if (strpos($_SERVER['REQUEST_URI'], '/settings') !== false) {
                echo 'class="active"';
            } ?>>
                <a href="/settings">Einstellungen</a>
            </li>
            <li <?php if (strpos($_SERVER['REQUEST_URI'], '/friends') !== false) {
                echo 'class="active"';
            } ?>>
                <a href="/friends" class="friends_link">Freunde verwalten
                    <?php
                    if (count_one_way_friends($_SESSION['email']) > 0) {
                        echo '<div class="notification">' . count_one_way_friends($_SESSION['email']) . '</div>';
                    }
                    ?>
                </a>
            </li>
            <li>
                <a href="/logout/">Abmelden</a>
            </li>
        </ul>
    </div>
<?php endif; ?>
    <?php
    return ob_get_clean();
}

?>
