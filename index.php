<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

error_reporting(E_ALL);
ini_set('display_errors', 1);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cubid</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="index.css">
    <link rel="stylesheet" href="components/navbar.css">
</head>
<body>
<?php
include("backend/auth.php");
include("components/navbar.php");
?>
<div class="hero">
    <div class="hero-text">
        <h1>Cubid.io</h1>
        <p>Deine zuverlässige Plattform für einen entspannten Abend</p>
    </div>
</div>
<div class="wrapper">
    <div class="homeCards">
        <div class="homeCard">
            <h2>Social Connection</h2>
            <p>Entdecke eine Welt voller neuer Freundschaften und gemeinsamer Gaming-Erlebnisse.</p>
        </div>
        <div class="homeCard">
            <h2>Explore Together</h2>
            <p>Gemeinsam neue Spiele entdecken und in einer freundlichen Umgebung mit anderen Gamern interagieren.</p>
        </div>
        <div class="homeCard">
            <h2>Community Hub</h2>
            <p>Trete einer lebendigen Gemeinschaft von Gamern bei und genieße den Austausch von Tipps, Tricks und
                Erlebnissen.</p>
        </div>
    </div>
</div>

</body>
</html>
