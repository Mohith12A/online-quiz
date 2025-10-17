<?php
$servername = "localhost";  // MySQL server (Workbench uses localhost)
$username = "root";         // Default username for MySQL
$password = "";             // Default password for MySQL (empty in XAMPP)
$dbname = "quiz_platform";  // Name of the database you created

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
