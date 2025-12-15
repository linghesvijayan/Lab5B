<?php
session_start();
if (!isset($_SESSION['matric'])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "Lab_5b");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$matric = $_GET['matric'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $accessLevel = $_POST['accessLevel'];

    $sql = "UPDATE users SET name=?, accessLevel=? WHERE matric=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $name, $accessLevel, $matric);
    $stmt->execute();

    header("Location: view_users.php");
    exit();
}

$result = $conn->query("SELECT * FROM users WHERE matric='$matric'");
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update User</title>
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

        input, select {
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
    </style>
</head>

<body>

<div class="header">
    <h2>Update User</h2>
</div>

<div class="container">
    <div class="form-box">
        <form method="post">

            <label>Name</label>
            <input type="text" name="name" 
                   value="<?php echo $user['name']; ?>" required>

            <label>Access Level</label>
            <select name="accessLevel" required>
                <option value="admin" 
                    <?php if ($user['accessLevel'] == 'admin') echo 'selected'; ?>>
                    Admin
                </option>
                <option value="student" 
                    <?php if ($user['accessLevel'] == 'student') echo 'selected'; ?>>
                    Student
                </option>
            </select>

            <input type="submit" value="Update">
        </form>
    </div>
</div>

</body>
</html>
