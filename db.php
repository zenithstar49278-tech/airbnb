<?php
// db.php - Database connection file
$servername = "localhost"; // Assuming localhost, change if needed
$username = "ubpkik01jujna";
$password = "f0ahnf2qsque";
$dbname = "dbhhumfy3imhgo";

// Create connection using mysqli (pro level with error handling)
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset to UTF-8 for proper encoding
$conn->set_charset("utf8mb4");
?>
