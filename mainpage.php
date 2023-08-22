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
    <?php
    if (isset($_SESSION['countTracker'])) {
        echo "Current Count: " . $_SESSION['countTracker'] . "<br> <br>";
        // Clear the session variable after displaying the count if needed
        // unset($_SESSION['updatedCount']);
    }
    ?>
    <form name="Button" method="post" action="dbconnect.php">
        <button type="submit" name="increment">increment</button>
    </form>
    <form name="Logout" method="post" action="dbconnect.php" <br><br>
        <input type="hidden" name="logout" value="true">
        <button type="submit" name="logout">Logout</button>
    </form>
</body>

</html>