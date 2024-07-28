<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include '../backend/auth.php';
include '../components/navbar.php';
include '../backend/database.php';
include '../backend/calendar.php';
include '../backend/render.php';
include '../backend/mail.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    global $database;

    $title = $_POST['title'];
    $description = $_POST['description'];
    $location = $_POST['location'];
    $start_time = $_POST['start-time'];
    $end_time = $_POST['end-time'];
    $is_private = isset($_POST['is-private']) ? 1 : 0;
    $attendees = $_POST['send-email'] ?? [];
    $game = $_POST['game-filter'] ?? 0;
    $date = $_POST['date'];

    //replace : to - in times
    $start_time = str_replace(':', '-', $start_time);
    $end_time = str_replace(':', '-', $end_time);

    $currentUser = getCurrentUser();
    if ($currentUser) {
        $organizer = $currentUser['email'];
    } else {
        die("No user logged in");
    }

    $stmt = $database->prepare("INSERT INTO events (organizer, title, description, date, begin, end, location, color, private, game) VALUES (?, ?, ?, ?,?, ?, ?, 1, ?, ?)");
    $stmt->bind_param("sssssssii", $organizer, $title, $description, $date, $start_time, $end_time, $location, $is_private, $game);

    if ($stmt->execute()) {
        $event_id = $stmt->insert_id;

        $stmt = $database->prepare("INSERT INTO user_event_mappings (email, id) VALUES (?, ?)");
        if ($stmt) {
            foreach ($attendees as $attendee_email) {
                $stmt->bind_param("si", $attendee_email, $event_id);
                $stmt->execute();
            }
        }

        echo '<script type="text/javascript">
                window.location.href = window.location.href;
              </script>';
    } else {
        $error = htmlspecialchars($stmt->error, ENT_QUOTES);
        echo '<script type="text/javascript">sweetAlert("Error","' . $error . '","error");</script>';
    }
    $stmt->close();

    if (isset($_POST['send-email-checkbox'])){
        sendEventCreationMail($event_id, getCurrentUser(), $title, $description, $location, $start_time, $end_time, $is_private, $game, $date);
    }
}

if ($_SERVER["REQUEST_METHOD"] == "UPDATE"){
    global $database;

    $title = $_POST['title'];
    $description = $_POST['description'];
    $location = $_POST['location'];
    $start_time = $_POST['start-time'];
    $end_time = $_POST['end-time'];
    $is_private = isset($_POST['is-private']) ? 1 : 0;
    $attendees = $_POST['send-email'] ?? [];
    $game = $_POST['game-filter'];
    $date = $_POST['date'];
    $id = $_POST['id'];

    //replace : to - in times
    $start_time = str_replace(':', '-', $start_time);
    $end_time = str_replace(':', '-', $end_time);

    $currentUser = getCurrentUser();
    if ($currentUser) {
        $organizer = $currentUser['email'];
    } else {
        die("No user logged in");
    }

    $stmt = $database->prepare("UPDATE events SET organizer = ?, title = ?, description = ?, date = ?, begin = ?, end = ?, location = ?, color = 1, private = ?, game = ? WHERE id = ?");
    $stmt->bind_param("sssssssiis", $organizer, $title, $description, $date, $start_time, $end_time, $location, $is_private, $game, $id);

/*    if ($stmt->execute()) {
        $event_id = $stmt->insert_id;

        $stmt = $database->prepare("DELETE FROM user_event_mappings WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $stmt = $database->prepare("INSERT INTO user_event_mappings (email, id) VALUES (?, ?)");
        if ($stmt) {
            foreach ($attendees as $attendee_email) {
                $stmt->bind_param("si", $attendee_email, $event_id);
                $stmt->execute();
            }
        }

        echo '<script type="text/javascript">
                window.location.href = window.location.href;
              </script>';
    } else {
        $error = htmlspecialchars($stmt->error, ENT_QUOTES);
        echo '<script type="text/javascript">sweetAlert("Error","' . $error . '","error");</script>';
    }*/

    $stmt->close();


}

$weekStart = Null;
if (isset($_GET['weekStart'])) {
    $weekStart = $_GET['weekStart'];
}

if (isset($_SESSION['username'])) {
    $xsl = new DOMDocument();
    $xsl->load('calendar.xsl');

    $data = new SimpleXMLElement('<data></data>');

    $users = $data->addChild('users');
    $events_today = $data->addChild('events_today');

    $todayEvents = getAllEventsByUserToday(getUserData("email"));
    foreach ($todayEvents as $event) {
        $newEvent = $events_today->addChild('event');
        $newEvent->addChild('id', $event['id']);
        $newEvent->addChild('organizer', $event['organizer']);
        $newEvent->addChild('title', $event['title']);
        $newEvent->addChild('description', $event['description']);
        $newEvent->addChild('date', $event['date']);
        $newEvent->addChild('begin', $event['begin']);
        $newEvent->addChild('end', $event['end']);
        $newEvent->addChild('location', $event['location']);
        $newEvent->addChild('color', $event['color']);
        $newEvent->addChild('private', $event['private']);
        $newEvent->addChild('game', $event['game']);
    }

    $dbGames = getAllGames();
    $games = $data->addChild('games');
    foreach ($dbGames as $game) {
        $newGame = $games->addChild('game');
        $newGame->addChild('id', $game['id']);
        $newGame->addChild('name', $game['name']);
    }

    $dbUsers = getAllUsers();
    foreach ($dbUsers as $user) {
        $newUser = $users->addChild('user');
        $newUser->addChild('username', $user['username']);
        $newUser->addChild('email', $user['email']);
    }

    $events = $data->addChild('events');
    $email = getUserData("email");
    if ($weekStart == Null) {
        $weekStart = getFirstWeekday();
    }
    $dbEvents = getEventsByUserAndWeekStart($email, $weekStart);


    foreach ($dbEvents as $day => $eventsOfDay) {
        $dayElement = $events->addChild('day');
        $dayElement->addAttribute('name', $day); // Assuming $day is the day name

        foreach ($eventsOfDay as $event) {
            $eventElement = $dayElement->addChild('event');
            $eventElement->addChild('id', $event["id"]);
            $eventElement->addChild('organizer', $event["organizer"]);
            $eventElement->addChild('title', $event["title"]);
            $eventElement->addChild('description', $event["description"]);
            $eventElement->addChild('date', $event["date"]);
            $eventElement->addChild('begin', $event["begin"]);
            $eventElement->addChild('end', $event['end']);
            $eventElement->addChild('location', $event['location']);
            $eventElement->addChild('color', $event['color']);
            $eventElement->addChild('private', $event['private']);
            $eventElement->addChild('game', $event['game']);
            if ($event['organizer'] == getUserData("email")) {
                $eventElement->addChild('isowner', "true");
            } else {
                $eventElement->addChild('isowner', "false");
            }
        }

    }

    echo render('calendar.xsl', $data->asXML());

    /*$proc = new XSLTProcessor();
    $proc->importStyleSheet($xsl);

    $result = $proc->transformToXml($data);

    echo $result; */


} else {
    header("Location: ../login");
}

function getFirstWeekday(): string
{
    $date = date('Y-m-d');

    $datetime = new DateTime($date);

    $weekday = $datetime->format('N');

    $diff = $weekday - 1;

    $datetime->modify(" - $diff days");

    return $datetime->format('Y-m-d');
}
