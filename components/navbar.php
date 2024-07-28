<div id="container">
    <div id="nav">
        <div id="navLeft" class="navSide">
            <div class="navItem">
                <a href="/">Home</a>
            </div>
            <div class="navItem">
                <a href="/user_search">Mitglieder</a>
            </div>
            <?php if (isset($_SESSION["username"])) { ?>
            <div class="navItem">
                <a href="/calendar">Kalender</a>
            </div>
            <?php } ?>
            <div class="navItem">
                <a href="/games">Spiele</a>
            </div>
        </div>
        <div id="navRight" class="navSide">
            <?php if (isset($_SESSION["username"])) { ?>
                <div class="navItem">
                    <a class="highlight"><img src="../assets/icons/arrow-down-s-line.svg"/><?php echo $_SESSION["username"]; ?></a>
                    <div class="dropdown">
                        <a href="/profile">Profil</a>
                        <a href="/settings" class="friends_link">Einstellungen
                            <?php
                            if (count_one_way_friends($_SESSION['email']) > 0) {
                                echo '<div class="notification bg-blue">' . count_one_way_friends($_SESSION['email']) . '</div>';
                            }
                            ?>
                        </a>
                        <a href="/logout/">Logout</a>
                    </div>
                </div>
            <?php } else { ?>
                <div class="navItem">
                    <a href="/login/">Anmelden</a>
                </div>
                <div class="navItem">
                    <a href="/register/">Registrieren</a>
                </div>
            <?php } ?>
        </div>
    </div>
</div>
