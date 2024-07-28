<?php
// Start session (if not already started)
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include '../backend/auth.php'; // Adjust path as per your file structure
include '../backend/database.php'; // Adjust path as per your file structure
global $database;

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    // Redirect to login page if not logged in
    header("Location: /login");
    exit;
}

// Retrieve session email
$email = $_SESSION['email'];

// Retrieve form data
$username = $_POST['username']; // Always present
$password = $_POST['password']; // New password, if provided
$receive_emails = isset($_POST['receive_emails']) ? 1 : 0; // Checkbox state (1 for checked, 0 for unchecked)
$receive_friend_requests = isset($_POST['receive_friend_requests']) ? 1 : 0; // Checkbox state
$profile_visibility = $_POST['profile_visibility']; // Selected option (public, friends_only, private)

// Hash the password if provided
$hashed_password = null;
if ($password !== null) {
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
}

// Prepare update statement for users table
$updateUserQuery = "UPDATE users SET username = ?, password = ? WHERE email = ?";
$stmtUser = $database->prepare($updateUserQuery);
$stmtUser->bind_param('sss', $username, $hashed_password, $email);

// Prepare update statement for settings table
$updateSettingsQuery = "UPDATE settings SET receive_mails = ?, receive_friend_requests = ?, profile_visibility = ? WHERE email = ?";
$stmtSettings = $database->prepare($updateSettingsQuery);
$stmtSettings->bind_param('iiss', $receive_emails, $receive_friend_requests, $profile_visibility, $email);

// Execute updates within a transaction
try {
    $database->autocommit(false); // Start transaction

    // Execute user table update
    $stmtUser->execute();

    // Execute settings table update
    $stmtSettings->execute();

    // Commit transaction
    $database->commit();

    $_SESSION['username'] = $username;


    // Redirect to settings page upon successful update
    header("Location: /settings");
    exit;
} catch (Exception $e) {
    // Rollback transaction if an error occurs
    $database->rollback();

    // Handle error condition appropriately, e.g., redirect to an error page
    header("Location: /error");
    exit;
} finally {
    // Always close statements after use
    $stmtUser->close();
    $stmtSettings->close();
}

?>
