<?php
session_start();
if (!isset($_SESSION['matric'])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "lab_5b");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT matric, name, accessLevel FROM users";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>User List</title>
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
            margin-top: 40px;
        }

        table {
            border-collapse: collapse;
            width: 70%;
            background-color: white;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
        }

        th, td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #d2b48c; /* light brown */
        }

        a {
            color: #6f4e37;
            text-decoration: none;
            font-weight: bold;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

<div class="header">
    <h2>User List</h2>
</div>

<div class="container">
    <table>
        <tr>
            <th>Matric</th>
            <th>Name</th>
            <th>Access Level</th>
            <th>Action</th>
        </tr>

        <?php if ($result->num_rows > 0) { ?>
            <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $row['matric']; ?></td>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['accessLevel']; ?></td>
                <td>
                    <a href="update.php?matric=<?php echo $row['matric']; ?>">Update</a> |
                    <a href="delete.php?matric=<?php echo $row['matric']; ?>" 
                       onclick="return confirm('Are you sure?')">Delete</a>
                </td>
            </tr>
            <?php } ?>
        <?php } else { ?>
            <tr>
                <td colspan="4">No data found</td>
            </tr>
        <?php } ?>
    </table>
</div>

</body>
</html>
