<!DOCTYPE html>
<html>
<head>
    <title>User Registration</title>
    <link rel="stylesheet" href="../components/navbar.css"/>
    <link rel="stylesheet" href="register.css"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
</head>
<body>
<?php include '../components/navbar.php'; ?>
<div class="wrapper">
    <div class="userForm">
        <h2>Registrieren</h2>
        <form action="register.php" method="POST">
            <input type="text" name="username" required="required" placeholder="Username"/><br/>
            <input type="email" name="email" required="required" placeholder="Email"/><br/>
            <input type="password" name="password" required="required" placeholder="Passwort"/><br/>
            <input class="button" type="submit" value="Jetzt registrieren!"/>
        </form>
    </div>
    <?php
    if ((isset($_GET["error"])) && ($_GET['error'] == 'usernameExists')) {
        echo '<script type="text/javascript">sweetAlert("Fehler","Dieser Nutzer oder diese Email-Adresse existiert bereits!","error")</script>';
    }
    ?>
</div>
</body>
</html>
