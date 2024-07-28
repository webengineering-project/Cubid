<?php
include '../backend/auth.php';
include '../backend/database.php';
include '../backend/calendar.php';
include '../backend/render.php';
include '../backend/mail.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    global $database;

    $event_id = $_POST['id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $location = $_POST['location'];
    $start_time = $_POST['start-time'];
    $end_time = $_POST['end-time'];
    $is_private = $_POST['is-private'] == 1 ? 1 : 0;
    $attendees = $_POST['send-email'] ?? [];
    $game = $_POST['game-filter'];
    $date = $_POST['date'];
    $weekstart = $_POST['weekstart'];

    // Update event in the database
    $stmt = $database->prepare("UPDATE events SET title=?, description=?, begin=?, end=?, date=?, location=?, private=?, game=? WHERE id=?");
    $stmt->bind_param("ssssssiii", $title, $description, $start_time, $end_time, $date, $location, $is_private, $game, $event_id);

    if ($stmt->execute()) {
        header("Location: /calendar/index.php?weekStart=" . $weekstart);
    } else {
        header("Location: /");
    }
    $stmt->close();

    if (isset($_POST['send-email-checkbox'])){
        sendEventUpdateMail($event_id, getCurrentUser(), $title, $description, $location, $start_time, $end_time, $is_private, $game, $date);
    }
}
?>