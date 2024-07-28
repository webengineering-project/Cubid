<?php
require '../phpmailer/Exception.php';
require '../phpmailer/PHPMailer.php';
require '../phpmailer/SMTP.php';


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function sendMail($to, $subject, $message)
{
    try {
        $mail = new PHPMailer(true);

        $mail->isSMTP();
        $mail->Host = 'smtp.strato.de';
        $mail->SMTPAuth = true;
        $mail->Username = 'events@cubid.io';
        $mail->Password = 'QLRSWtcL29e5fjq3M9Z2GJWV';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('events@cubid.io', 'Cubid.io Kalender');
        $mail->addAddress($to);

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $message;
        $mail->AltBody = strip_tags($message);

        $mail->send();
    } catch (Exception $e) {
    }
}

function sendEventCreationMail($event_id, $current_user, $title, $description, $location, $start_time, $end_time, $is_private, $game, $date)
{
    $subject = "Zu Termin eingeladen: " . $title;
    sendEventMail($subject, $event_id, $current_user, $title, $description, $location, $start_time, $end_time, $is_private, $game, $date);
}

function sendEventUpdateMail($event_id, $current_user, $title, $description, $location, $start_time, $end_time, $is_private, $game, $date)
{
    $subject = "Termin aktualisiert: " . $title;
    sendEventMail($subject, $event_id, $current_user, $title, $description, $location, $start_time, $end_time, $is_private, $game, $date);
}

function sendEventDeletionMail($event_id, $title){
    $subject = "Termin gelöscht: " . $title;
    $attendee_emails = getAttendees($event_id);
    foreach ($attendee_emails as $attendee_email) {
        sendMail($attendee_email, $subject, "Der Termin " . $title . " wurde gelöscht.");
    }
}

function sendEventMail($subject, $event_id, $current_user, $title, $description, $location, $start_time, $end_time, $is_private, $game, $date)
{
    global $database;

    $stmt = $database->prepare("SELECT email FROM user_event_mappings WHERE id=?");
    $stmt->bind_param("i", $event_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();

    $attendee_emails = [];
    while ($row = $result->fetch_assoc()) {
        $attendee_emails[] = $row['email'];
    }
    $message = "Titel: " . $title . "<br>";
    $message .= "Veranstalter: " . $current_user['email'] . "<br>";
    $message .= "Datum: " . $date . "<br>";
    $message .= "Beschreibung: " . $description . "<br>";
    $message .= "Ort: " . $location . "<br>";
    $message .= "Von: " . $start_time . "<br>";
    $message .= "Bis: " . $end_time . "<br>";
    $message .= "Teilnehmer: " . implode(", ", $attendee_emails) . "<br>";
    $message .= "Spiel: " . getGameNamebyID($game) . "<br>";

    foreach ($attendee_emails as $attendee_email) {
        sendMail($attendee_email, $subject, $message);
    }
}

function getGameNameByID($gameID){
    global $database;

    $stmt = $database->prepare("SELECT name FROM games WHERE id = ?");
    $stmt->bind_param("i", $gameID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        return $result->fetch_assoc()['name'];
    } else {
        return null;
    }
}

?>