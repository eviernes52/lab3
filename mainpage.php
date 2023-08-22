<!DOCTYPE html>
<html lang="en">
<?php
session_start();
?>

<head>
    <meta charset="UTF-8">
    <title>Logged In</title>

</head>

<body>
    <h1>Welcome Back</h1>
    <form name="Button" method="post" action="dbconnect.php">
        <button type="submit" name="increment">increment</button>
    </form>
    <form name="Logout" method="post" action="dbconnect.php" <br>
        <input type="hidden" name="logout" value="true">
        <button type="submit" name="logout">Logout</button>
    </form>
</body>

</html>