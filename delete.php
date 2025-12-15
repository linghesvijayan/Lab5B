<?php
session_start();
if (!isset($_SESSION['matric'])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "Lab_5b");

$matric = $_GET['matric'];

$sql = "DELETE FROM users WHERE matric=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $matric);
$stmt->execute();

header("Location: view_users.php");
exit();
