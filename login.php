<?php
session_start();
if (!isset($_SESSION['matric'])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "lab_5b");

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $matric = $_POST['matric'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE matric = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $matric);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['matric'] = $user['matric'];
            $_SESSION['accessLevel'] = $user['accessLevel'];
            header("Location: view_users.php");
            exit();
        } else {
            $message = "Invalid password!";
        }
    } else {
        $message = "User not found!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f5f0e6; /* cream */
        }

        .header {
            background-color: #6f4e37; /* brown */
            color: white;
            padding: 20px;
            text-align: center;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: calc(100vh - 80px);
        }

        .form-box {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            width: 350px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            border-radius: 4px;
            border: 1px solid #ccc;
        }

        input[type="submit"] {
            background-color: #6f4e37;
            color: white;
            border: none;
            cursor: pointer;
            font-weight: bold;
        }

        input[type="submit"]:hover {
            background-color: #5a3e2b;
        }

        .error {
            color: red;
            margin-bottom: 15px;
            text-align: center;
        }

        .register-link {
            text-align: center;
            margin-top: 10px;
        }
    </style>
</head>

<body>

<div class="header">
    <h2>Login</h2>
</div>

<div class="container">
    <div class="form-box">

        <?php if ($message) echo "<p class='error'>$message</p>"; ?>

        <form method="post">
            <label>Matric</label>
            <input type="text" name="matric" required>

            <label>Password</label>
            <input type="password" name="password" required>

            <input type="submit" value="Login">
        </form>

        <div class="register-link">
            <p>Not registered? <a href="registration.html">Register here</a></p>
        </div>

    </div>
</div>

</body>
</html>
