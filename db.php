<!-- db.php (for reference, if needed) -->
<?php
$servername = "localhost";
$username   = "root";
$password   = "";
$database   = "lab_7";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

