<?php
require_once 'database.php';

// Function to add a new calendar event
function addEvent($title, $description, $attendees, $startDateTime, $endDateTime, $ownerUsername, $isPrivate)
{
    global $database;

    // Prepare and execute the query to insert the event
    $stmt = $database->prepare("INSERT INTO events (title, description, attendees, start_date_time, end_date_time, owner_username, isPrivate) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssi", $title, $description, $attendees, $startDateTime, $endDateTime, $ownerUsername, $isPrivate);

    //TODO: add all atendees to user_event_mappings


    if ($stmt->execute()) {
        return true; // Event added successfully
    } else {
        return false; // Failed to add event
    }
}

// Function to delete an existing calendar event
function deleteEvent($eventId, $ownerUsername)
{
    global $database;

    // Prepare and execute the query to delete the event
    $stmt = $database->prepare("DELETE FROM events WHERE id = ? AND owner_username = ?");
    $stmt->bind_param("is", $eventId, $ownerUsername);

    if ($stmt->execute()) {
        return true; // Event deleted successfully
    } else {
        return false; // Failed to delete event
    }
}

// Function to update an existing calendar event
function updateEvent($eventId, $title, $description, $attendees, $startDateTime, $endDateTime, $ownerUsername, $isPrivate)
{
    global $database;

    // Prepare and execute the query to update the event
    $stmt = $database->prepare("UPDATE events SET title=?, description=?, attendees=?, start_date_time=?, end_date_time=?, isPrivate=? WHERE id=? AND owner_username=?");
    $stmt->bind_param("ssssssis", $title, $description, $attendees, $startDateTime, $endDateTime, $isPrivate, $eventId, $ownerUsername);

    if ($stmt->execute()) {
        return true; // Event updated successfully
    } else {
        return false; // Failed to update event
    }
}

// Function to retrieve all calendar events for a specific owner
function getAllEventsByOrganizer($ownerEmail)
{
    global $database;

    // Prepare the SQL query to select events where the organizer matches the given email
    $stmt = $database->prepare("SELECT * FROM events WHERE organizer = ?");
    $stmt->bind_param("s", $ownerEmail);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $events = array();
        while ($row = $result->fetch_assoc()) {
            $events[] = $row;
        }
        return $events;
    } else {
        return array(); // No events found
    }
}


// Function to retrieve all calendar events where a user is included either as owner or attendee
function getAllEventsByUser($email): array
{
    global $database;

    // Array to hold all unique events
    $events = [];
    // Associative array to track unique event IDs
    $eventIds = [];

    // Prepare the SQL query to select events where the user is the organizer or participant
    $stmt = $database->prepare("
        SELECT events.* 
        FROM events 
        LEFT JOIN user_event_mappings ON events.id = user_event_mappings.id 
        WHERE events.organizer = ? OR user_event_mappings.email = ? ORDER BY events.begin ASC
    ");
    if (!$stmt) {
        die("Error preparing statement: " . $database->error);
    }

    $stmt->bind_param("ss", $email, $email);
    if (!$stmt->execute()) {
        die("Error executing statement: " . $stmt->error);
    }

    $result = $stmt->get_result();
    if (!$result) {
        die("Error getting result: " . $stmt->error);
    }

    while ($row = $result->fetch_assoc()) {
        if (!isset($eventIds[$row['id']])) {
            $events[] = $row;
            $eventIds[$row['id']] = true;
        }
    }

    return $events;
}


function getTodayEventsByUser($email)
{
    $allEvents = getAllEventsByUser($email);
    $today = date('Y-m-d');

    $todayEvents = array_filter($allEvents, function ($event) use ($today) {
        return $event['date'] === $today;
    });

    return $todayEvents;
}


function getEventsByUserAndWeekStart($email, $weekStart)
{
    $weeklyEvents = [
        'Monday' => [],
        'Tuesday' => [],
        'Wednesday' => [],
        'Thursday' => [],
        'Friday' => [],
        'Saturday' => [],
        'Sunday' => []
    ];

    $allEvents = getAllEventsByUser($email);

    $dates = getWeekDates($weekStart);

    foreach ($allEvents as $event) {
        foreach ($dates as $date) {
            $eventDate = $event['date'];
            if ($eventDate == $date) {
                $eventDay = (new DateTime($eventDate))->format('l');
                $weeklyEvents[$eventDay][] = $event;
            }
        }
    }

    return $weeklyEvents;
}

function getAllEventsByUserToday($email)
{
    $today = date('Y-m-d');
    $allEvents = getAllEventsByUser($email);

    $todayEvents = array_filter($allEvents, function ($event) use ($today) {
        return $event['date'] === $today;
    });

    return $todayEvents;
}

function getEventByID($eventID)
{
    global $database;

    $stmt = $database->prepare("SELECT * FROM events WHERE id = ?");
    $stmt->bind_param("i", $eventID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return null;
    }
}


function getAttendees($eventId)
{
    global $database;

    $stmt = $database->prepare("SELECT email FROM user_event_mappings WHERE id = ?");
    $stmt->bind_param("i", $eventId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $attendees = array();
        while ($row = $result->fetch_assoc()) {
            $attendees[] = $row['email'];
        }
        return $attendees;
    } else {
        return array();
    }
}


function getCalendarWeek($date)
{
    $dateTime = new DateTime($date);
    return $dateTime->format("W");
}

function getWeekDates($startDate)
{
    $dates = [];
    $currentDate = strtotime($startDate);

    for ($i = 0; $i < 7; $i++) {
        $dates[] = date('Y-m-d', $currentDate);
        $currentDate = strtotime('+1 day', $currentDate);
    }
    return $dates;
}

?>