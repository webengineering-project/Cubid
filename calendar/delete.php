<?php
include '../backend/auth.php';
include '../backend/database.php';
include '../backend/calendar.php';
include '../backend/render.php';
include '../backend/mail.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    global $database;

    $id = $_POST['id'];
    $weekstart = $_POST['weekstart'];

    $event = getEventById($id);

    if (isset($_POST['send-email-checkbox'])) {
        sendEventDeletionMail($id, "Test");
    }

    $stmt = $database->prepare("DELETE FROM events WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {

        $stmt = $database->prepare("DELETE FROM user_event_mappings WHERE id = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            header("Location: /calendar?weekStart=" . $weekstart);
        } else {
            echo "nope";
        }
    }
    $stmt->close();
}
?>