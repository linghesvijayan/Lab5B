<?php
$servername = "localhost";
$username = "root"; 
$password = "";     
$dbname = "lab_5b"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$matric = $_POST['matric'];
$name = $_POST['name'];
$password = $_POST['password'];
$accessLevel = $_POST['role'];

// Hash the password (good practice)
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Insert data into users table
$sql = "INSERT INTO users (matric, name, password, accessLevel) VALUES (?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $matric, $name, $hashed_password, $accessLevel);

if ($stmt->execute()) {
    header("Location: login.php?success=1");
    

} else {
    echo "Error: " . $stmt->error;
}

// Close connections
$stmt->close();
$conn->close();
?>
