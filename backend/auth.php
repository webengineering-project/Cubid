<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'database.php';

function registerUser($username, $email, $password): bool
{
    global $database;

    // Check if username or email already exist
    $stmt = $database->prepare("SELECT COUNT(*) AS count FROM users WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $existingUsersCount = $row['count'];

    if ($existingUsersCount > 0) {
        // Username or email already exists
        return false;
    } else {
        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Use prepared statement to prevent SQL injection
        $stmt = $database->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");

        $stmt->bind_param("sss", $username, $email, $hashedPassword);

        $profileCreated = insertDefaultProfileData($email);
        if (!$profileCreated) {
            return false;
        }

        $settingsCreated = insertDefaultSettings($email);
        if (!$settingsCreated) {
            return false;
        }
        if ($stmt->execute()) {

            $_SESSION['username'] = $username;
            $_SESSION['email'] = getCurrentUser()['email'];
            return true; // Registration successful
        } else {
            // Handle errors
            return false; // Registration failed
        }
    }
}


function insertDefaultProfileData($email)
{
    global $database;

    $stmt = $database->prepare("INSERT INTO profile (first_name,last_name,image,location,about_me, email) VALUES (?, ?, ?, ?, ?,?)");
    $image = "https://cdn.pixabay.com/photo/2020/07/01/12/58/icon-5359553_1280.png";
    $about_me = "Hello, im new here :)";
    $location = "Planet Earth";
    $firstname = "Your firstname";
    $lastname = "Your lastname";


    $stmt->bind_param("ssssss", $firstname, $lastname, $image, $location, $about_me, $email);

    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}

function updateProfile($firstname, $lastname, $location, $about_me)
{
    global $database;

    $email = getCurrentUser()['email'];
    $stmt = $database->prepare("UPDATE profile SET first_name=?, last_name=?, location=?, about_me=? WHERE email=?");
    $stmt->bind_param("sssss", $firstname, $lastname, $location, $about_me, $email);
    $stmt->execute();
}

function loginUser($username, $password)
{
    global $database;

    // Use prepared statement to prevent SQL injection
    $stmt = $database->prepare("SELECT password FROM users WHERE username=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $hashedPassword = $row['password'];

        if (password_verify($password, $hashedPassword)) {
            $_SESSION['username'] = $username;

            $stmt = $database->prepare("SELECT email FROM users WHERE username='$username'");
            //$stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $_SESSION['email'] = $row['email'];

            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

function getCurrentUser()
{
    global $database;

    if (isset($_SESSION['username'])) {
        $statement = $database->prepare("SELECT username, email FROM users WHERE username = ?");
        $statement->bind_param("s", $_SESSION['username']);
        $statement->execute();

        // Get the result
        $result = $statement->get_result();

        // Fetch the user data
        $user = $result->fetch_assoc();

        // Close the statement
        $statement->close();

        return $user;
    } else {
        return null;
    }
}

function getUserByEmail($email)
{
    global $database;

    $statement = $database->prepare("SELECT username, email FROM users WHERE email = ?");
    $statement->bind_param("s", $email);
    $statement->execute();

    // Get the result
    $result = $statement->get_result();

    // Fetch the user data
    $user = $result->fetch_assoc();

    // Close the statement
    $statement->close();

    return $user;
}


function getAllUsers()
{
    global $database;

    $stmt = $database->prepare("SELECT username, email FROM users");
    if (!$stmt) {
        die("Error preparing statement: " . $database->error);
    }

    $stmt->execute();
    if (!$stmt) {
        die("Error executing statement: " . $stmt->error);
    }

    $result = $stmt->get_result();
    if (!$result) {
        die("Error getting result: " . $stmt->error);
    }

    $users = array();
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }

    return $users;
}


function getUserData($param)
{
    global $database;

    $query = mysqli_query($database, "SELECT $param FROM users WHERE username = '" . $_SESSION['username'] . "'");
    $data = mysqli_fetch_array($query);
    return $data[$param];
}


function getProfile()
{
    global $database;

    if (isset($_SESSION['email'])) {
        return getProfileByEmail($_SESSION['email']);
    } else {
        return null;
    }
}

function getProfileByEmail($email)
{
    global $database;

    $statement = $database->prepare("SELECT * FROM profile WHERE email = ?");
    $statement->bind_param("s", $email);
    $statement->execute();

    // Get the result
    $result = $statement->get_result();

    // Fetch the user data
    $user = $result->fetch_assoc();

    // Close the statement
    $statement->close();

    return $user;
}

function getCurrentGames()
{
    if (isset($_SESSION['email'])) {
        return getGamesOfProfile($_SESSION['email']);
    } else {
        return null;
    }
}


function getGamesOfProfile($email)
{
    global $database;

    $statement = $database->prepare("SELECT name, id FROM user_game_mappings INNER JOIN games ON games.id = user_game_mappings.game WHERE email = ?");
    $statement->bind_param("s", $email);
    $statement->execute();

    // Get the result
    $result = $statement->get_result();
    $games = array();
    while ($row = $result->fetch_assoc()) {
        $games[] = $row;
    }
    $statement->close();
    return $games;
}

function getAllGames()
{
    global $database;
    $statement = $database->prepare("SELECT * FROM games ");
    $statement->execute();

    // Get the result
    $result = $statement->get_result();

    // Fetch the user data
    $games = array();
    while ($row = $result->fetch_assoc()) {
        $games[] = $row;
    }

    // Close the statement
    $statement->close();

    return $games;

}

function updateOwnedGames($games)
{
    global $database;
    $statement = $database->prepare("DELETE FROM user_game_mappings WHERE email = ?");
    $statement->bind_param("s", $_SESSION['email']);
    $statement->execute();
    if ($games != "") {
        foreach ($games as $game) {
            $gameID = getIdOfGame($game);
            $statement = $database->prepare("INSERT INTO user_game_mappings (game, email) VALUES (?, ?)");
            $statement->bind_param("is", $gameID, $_SESSION['email']);
            $statement->execute();
        }
    }

}

function getIdOfGame($game)
{
    global $database;
    $statement = $database->prepare("SELECT id FROM games WHERE name = ?");
    $statement->bind_param("s", $game);
    $statement->execute();
    $result = $statement->get_result();
    $gameId = $result->fetch_assoc();

    return $gameId['id'];

}

function getAllFriends2($email)
{
    global $database;

    $statement = $database->prepare("SELECT DISTINCT CASE WHEN email1 = ? THEN email2 ELSE email1 END AS friend FROM friends WHERE ? IN (email1, email2)");
    $statement->bind_param("ss", $email, $email);

    $statement->execute();

    $result = $statement->get_result();

    $friends = array();
    while ($row = $result->fetch_assoc()) {
        $friends[] = $row;
    }
    $statement->close();

    return $friends;
}

function getFriendRequests($email)
{
    global $database;

    if ($database->connect_error) {
        die("Connection failed: " . $database->connect_error);
    }

    $statement = $database->prepare("SELECT email1 AS friendmail
            FROM friends
            WHERE email2 = ? 
              AND NOT EXISTS (
                  SELECT 1 
                  FROM friends f2 
                  WHERE f2.email1 = friends.email2 
                    AND f2.email2 = friends.email1 
                    AND f2.email1 = ?
              )
        ");

    if ($statement === false) {
        die("Prepare failed: " . $database->error);
    }

    $statement->bind_param("ss", $email, $email);
    $statement->execute();

    if ($statement->error) {
        die("Execute failed: " . $statement->error);
    }

    $result = $statement->get_result();
    if ($result === false) {
        die("Get result failed: " . $statement->error);
    }

    $friendRequests = [];
    while ($row = $result->fetch_assoc()) {
        $friendRequests[] = $row['friendmail'];
    }

    $statement->close();

    return $friendRequests;
}

function addFriendRequest($email1, $email2)
{
    global $database;

    if (isFriends($email1, $email2)) {
        return false;
    }

    $statement = $database->prepare("INSERT INTO friends (email1, email2) VALUES (?, ?)");
    $statement->bind_param("ss", $email1, $email2);

    if ($statement->execute()) {
        $statement->close();
        return true;
    } else {
        $statement->close();
        return false;
    }
}

function acceptFriendRequest($email1, $email2)
{
    global $database;

    $statement = $database->prepare("INSERT INTO friends (email1, email2) VALUES (?, ?)");
    $statement->bind_param("ss", $email2, $email1);
    $statement->execute();
    $statement->close();
}

function declineFriendRequest($email1, $email2)
{
    global $database;

    $statement = $database->prepare("DELETE FROM friends WHERE email1 = ? AND email2 = ?");
    $statement->bind_param("ss", $email1, $email2);
    $statement->execute();
}

function getAllFriends($email)
{
    global $database;

    $statement = $database->prepare("SELECT DISTINCT CASE WHEN email1 = ? THEN email2 ELSE email1 END AS friend FROM friends WHERE (email1 = ? OR email2 = ?) AND EXISTS (SELECT 1 FROM friends AS reciprocal WHERE reciprocal.email1 = friends.email2 AND reciprocal.email2 = friends.email1)");
    $statement->bind_param("sss", $email, $email, $email);
    $statement->execute();
    $result = $statement->get_result();

    $friends = [];
    while ($row = $result->fetch_assoc()) {
        $friends[] = $row;
    }

    $statement->close();
    return $friends;
}

function isFriends($email1, $email2)
{
    global $database;

    $statement = $database->prepare("SELECT 1 FROM friends WHERE (email1 = ? AND email2 = ?) AND EXISTS (SELECT 1 FROM friends WHERE email1 = ? AND email2 = ?)");
    $statement->bind_param("ssss", $email1, $email2, $email2, $email1);
    $statement->execute();
    $result = $statement->get_result();

    $isFriends = $result->num_rows > 0;

    $statement->close();
    return $isFriends;
}

function unfriend($email1, $email2)
{
    global $database;
        $statement = $database->prepare("DELETE FROM friends WHERE (email1 = ? AND email2 = ?) OR ( email1 = ? AND email2 = ?)");
        $statement->bind_param("ssss", $email1, $email2, $email2, $email1);
        $statement->execute();
}

function getUserSettings($email)
{
    global $database;

    $query = "SELECT * FROM settings WHERE email = ?";
    $stmt = $database->prepare($query);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if settings exist
    if ($result->num_rows > 0) {
        $settings = $result->fetch_assoc();
    } else {
        $settings = null; // No settings found
    }

    $stmt->close();
    return $settings;
}

function insertDefaultSettings($email)
{
    global $database;

    $receive_emails = 1;
    $receive_friend_requests = 1;
    $profile_visibility = 'public';

    // Prepare query to insert default settings
    $query = "INSERT INTO settings (email, receive_mails, receive_friend_requests, profile_visibility) VALUES (?, ?, ?, ?)";
    $stmt = $database->prepare($query);
    $stmt->bind_param('siis', $email, $receive_emails, $receive_friend_requests, $profile_visibility);

    if ($stmt->execute()) {
        $stmt->close();
        return true;
    } else {
        $stmt->close();
        return false;
    }
}

function pendingFriendRequest($email1, $email2): bool
{
    global $database;

    // Prepare the SQL statement
    $stmt = $database->prepare("SELECT * FROM friends WHERE (email1 = ? AND email2 = ?) OR (email1 = ? AND email2 = ?)");

    // Bind the parameters
    $stmt->bind_param("ssss", $email1, $email2, $email2, $email1);

    // Execute the statement
    $stmt->execute();

    // Get the result
    $result = $stmt->get_result();

    // Initialize flags
    $directRequestExists = false;
    $reverseRequestExists = false;

    // Iterate over results to set flags
    while ($row = $result->fetch_assoc()) {
        if ($row['email1'] === $email1 && $row['email2'] === $email2) {
            $directRequestExists = true;
        }
        if ($row['email1'] === $email2 && $row['email2'] === $email1) {
            $reverseRequestExists = true;
        }
    }

    // Close the statement
    $stmt->close();

    // Return true only if the direct request exists and the reverse request does not exist
    return $directRequestExists && !$reverseRequestExists;
}

function count_one_way_friends($email) {
    global $database;

    // SQL-Abfrage vorbereiten
    $sql = "SELECT COUNT(*) as cnt
            FROM friends f1
            WHERE f1.email2 = ?
            AND NOT EXISTS (
                SELECT 1
                FROM friends f2
                WHERE f2.email1 = f1.email2
                AND f2.email2 = f1.email1
            )";

    // Vorbereitung der SQL-Anweisung
    $stmt = $database->prepare($sql);
    if (!$stmt) {
        die("Vorbereitung fehlgeschlagen: " . $database->error);
    }

    // Parameter binden
    $stmt->bind_param("s", $email);

    // Ausführen
    $stmt->execute();

    // Ergebnis binden
    $stmt->bind_result($count);
    $stmt->fetch();

    // Schließen der Anweisung und der Verbindung
    $stmt->close();

    return $count;
}



