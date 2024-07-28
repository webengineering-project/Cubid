<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include '../backend/auth.php';
include '../backend/render.php';
?>

<html lang="de">
<head>
    <title>Mein Profil</title>
    <link rel="stylesheet" href="../components/navbar.css"/>
    <link rel="stylesheet" href="games.css"/>
    <script src="games.js"></script>
</head>
<body>
<?php include '../components/navbar.php'; ?>
<div class="wrapper">
    <?php

    include '../backend/database.php';
    include '../backend/calendar.php';

    $xsl = new DOMDocument();
    $xsl->load('games.xsl');

    $data = new SimpleXMLElement('<data></data>');
    $games = $data->addChild('games');

    $globalGames = getAllGames();
    foreach ($globalGames as $game) {
        $newGame = $games->addChild('game');
        $newGame->addChild('id', $game['id']);
        $newGame->addChild('name', $game['name']);
        $newGame->addChild('description', $game['description']);
        $newGame->addChild('link', $game['img']);
        $newGame->addChild('description_long', $game['description_long']);
    }

    $proc = new XSLTProcessor();
    $proc->importStyleSheet($xsl);

    $result = $proc->transformToXml($data);

    echo $result;
    ?>
</div>
</body>
</html>
