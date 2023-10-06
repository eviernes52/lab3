<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Logged In</title>
    <?php session_start(); ?>
</head>

<body>
    <h1>Welcome <?php echo $_SESSION['loggedInUsername']; ?>
    </h1>
    <form name="Logout" method="post" action="dbconnect.php" <br><br>
        <input type="hidden" name="logout" value="true">
        <button type="submit" name="logout">Logout</button>
    </form>
</body>

</html>