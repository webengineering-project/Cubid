<?php
$database = mysqli_connect("212.132.71.205", "cubid", "", "cubid", 3306);

if ($database->connect_error) {
    die("Connection failed: " . $database->connect_error);
}

?>
