<?php
// Starte die Session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Überprüfe, ob eine Session für den Benutzer besteht
if(isset($_SESSION['username'])) {
    // Session Variablen löschen
    session_unset();

    // Session zerstören
    session_destroy();
}

// Weiterleitung zur index.php
header("Location: /login");
exit;
