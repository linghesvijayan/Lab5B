<?php
// Database connection settings
$servername = "localhost";
$username = "root";      // change if needed
$password = "";          // change if needed
$dbname = "lab_5b";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize message variable
$message = "";

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $matric = htmlspecialchars(trim($_POST['matric']));
    $name = htmlspecialchars(trim($_POST['name']));
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Basic validation
    if (!empty($matric) && !empty($name) && !empty($password) && !empty($role)) {

        // Hash the password for security
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Optional: check if matric already exists
        $checkSql = "SELECT * FROM users WHERE matric = ?";
        $checkStmt = $conn->prepare($checkSql);
        $checkStmt->bind_param("s", $matric);
        $checkStmt->execute();
        $checkStmt->store_result();

        if ($checkStmt->num_rows > 0) {
            $message = "Matric already registered!";
        } else {
            // Prepare SQL statement
            $sql = "INSERT INTO users (matric, name, password, role) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssss", $matric, $name, $hashed_password, $role);

            if ($stmt->execute()) {
                $message = "success"; // trigger JS popup
            } else {
                $message = "Error: " . $stmt->error;
            }

            $stmt->close();
        }

        $checkStmt->close();
    } else {
        $message = "Please fill all fields!";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Registration</title>
    <script>
        function showMessage(msg) {
            if(msg === "success") {
                alert("Registration successful!");
            } else if(msg) {
                alert(msg);
            }
        }
    </script>
</head>
<body>
    <h2>Registration Form</h2>

    <?php
    // Call JS popup if message exists
    if (!empty($message)) {
        echo "<script>showMessage('{$message}');</script>";
    }
    ?>

    <form action="" method="post">
        <label for="matric">Matric:</label>
        <input type="text" id="matric" name="matric" required><br><br>

        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required><br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>

        <label for="role">Role:</label>
        <select id="role" name="role" required>
            <option value="">Please select</option>
            <option value="admin">Admin</option>
            <option value="student">Student</option>
            <option value="teacher">Teacher</option>
        </select><br><br>

        <input type="submit" value="Submit">
    </form>
</body>
</html>
