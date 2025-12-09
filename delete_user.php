<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
if (!isset($_SESSION['matric'])) {
    header("Location: login.php");
    exit;
}

require 'db.php';

$matric = trim($_GET['matric'] ?? '');

if ($matric !== '') {
    $sql  = "DELETE FROM users WHERE matric = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("s", $matric);
        $stmt->execute();
        $stmt->close();
    }
}

header("Location: users.php");
exit;
