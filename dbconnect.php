<?php

$host = "localhost";
$dbusername = "root";
$dbpassword = "root";
$dbname = "lab3";
$dbport = 3306;

$conn = new mysqli($host, $dbusername, $dbpassword, $dbname, $dbcount, $dbport);


//tests for connection
if (mysqli_connect_error()) {
    die('Connect Error (' . mysqli_connect_error() . ')');
} else {
    echo ("Connection successful <br>");
    // after this point, we can use $conn to refer to the connection
    session_start();

    $logout = $_GET["logout"];
    if (isset($_POST["logout"])) {
        session_destroy(); // Destroy the session
        header("Location: lab3.html"); // Redirect to index page
        $conn->close();
    }

    if (isset($_POST["loginbutton"])) {
        $toInsertUsername = $_POST["username"];
        $toInsertPassword = $_POST["password"];
        $selectQuery = "SELECT username From user WHERE username = ? AND password = ?";
        $stmt = $conn->prepare($selectQuery);
        $stmt->bind_param("ss", $toInsertUsername, $toInsertPassword);
        //insertion of user is compelte at '?' character
        $stmt->execute();
        $res = $stmt->get_result(); //get result of query
        $numresults = mysqli_num_rows($res); //OOP or reg function synatx usable
        if ($numresults > 0) { //if user already taken "elon is in there"
            $_SESSION['usernameCorrect'] = true;
            $_SESSION['loggedInUsername'] = $toInsertUsername;
            header("Location: mainpage.php");
        } else {
            echo "Invalid username or password";
            echo '<br><br><a href="lab3.html">Try Again</a>';
        }
        $conn->close();
    }

    //if user not taken
    $toInsertUsername = $_GET["username"];
    $toInsertPassword = $_GET["password"];

    $selectQuery = "SELECT username From user WHERE username = ?";
    $stmt = $conn->prepare($selectQuery);
    $stmt->bind_param("s", $toInsertUsername);
    //insertion of user is compelte at '?' character
    $stmt->execute();
    $res = $stmt->get_result(); //get result of query
    $numresults = mysqli_num_rows($res); //OOP or reg function synatx usable



    if ($numresults > 0) { //if user already taken "elon is in there"
        echo ("Someone already has that username " . $toInsertUsername);
        echo '<br><br><a href="lab3.html">Try Again</a>';
    } else {
        $insertQuery = "INSERT INTO user (username, password) VALUES (?, ?)";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param("ss", $toInsertUsername, $toInsertPassword); //ss for string string
        $stmt->execute();
        echo "New account added successfully";
        echo '<br><br><a href="lab3.html">Back to home page</a>';
    }

    if (isset($_POST["increment"])) {
        // Get the user's ID based on the username
        $selectUserIdQuery = "SELECT userid FROM user WHERE username = ?";
        $stmtSelect = $conn->prepare($selectUserIdQuery);
        $stmtSelect->bind_param("s", $_SESSION['loggedInUsername']);
        $stmtSelect->execute();
        $result = $stmtSelect->get_result();
        $row = $result->fetch_assoc();
        $userId = $row['userid'];
        $stmtSelect->close();

        // Prepare a SQL statement to update the count for the logged-in user
        $updateQuery = "UPDATE user SET count = count + 1 WHERE userid = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("i", $userId);
        // Execute the prepared statement
        $stmt->execute();
        $stmt->close();

        // Update the session count
        $_SESSION['count'] = $_SESSION['count'] + 1;
    }

    $conn->close();
}
