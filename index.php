<?php
include 'includes/head.php';

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $username = $_GET["username"];
    $password = $_GET["password"];
    // Process form data here
    // You can perform any necessary actions with the form data
    // For example, you can store it in a database, send an email, etc.
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Lab 3</title>
    <?php include 'includes/head.php'; ?>
</head>

<body>
    <form action="index.php" method="post">
        <h3>Login</h3>
        <label for="logusername">Username: <input type="text" id="logusername" name="username"><br></label><br>

        <label for="logpassword">Password: <input type="password" id="logpassword" name="password"><br></label><br>

        <button type="submit" id="Submitbutton" name="button">Submit</button>


        <h3>Register</h3>
        <label for="regusername">Username: <input type="text" id="logusername" name="username"><br></label><br>

        <label for="regpassword">Password: <input type="password" id="logpassword" name="password"><br></label><br>


        <button type="submit" id="Submitbutton" name="button">Submit</button>
    </form>
</body>

</html>