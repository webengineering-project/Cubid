<!DOCTYPE html>
<html lang="en">
<head>
    <title>User Login</title>
    <link rel="stylesheet" href="../components/navbar.css"/>
    <link rel="stylesheet" href="login.css"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
</head>
<body>
<?php
include("../components/navbar.php");
?>
<div class="wrapper">
    <div class="userForm">
        <h2>Anmelden</h2>
        <form action="login.php" method="POST">
            <!--<label for="username">Username:</label>-->
            <input type="text" name="username" required="required" placeholder="Username"/>
            <br/>
            <!--<label for="password">Password:</label>-->
            <input type="password" name="password" placeholder="Passwort"/>
            <br/>
            <input class="button" type="submit" value="Anmelden"/>
        </form>
    </div>
    <?php
    if ((isset($_GET["error"])) && ($_GET['error'] == 'authenticationFailed')) {
        echo '<script type="text/javascript">sweetAlert("Fehler","Falscher Benutzername oder falsches Passwort!","error")</script>';
    }
    ?>
</div>
</body>
</html>
