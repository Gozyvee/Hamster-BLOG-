<?php
// Define database credentials
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'cms');

// Connect to database using MySQLi object-oriented approach
$connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check for connection errors
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}
?>