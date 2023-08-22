<?php

$host = "localhost";
$dbusername = "root";
$dbpassword = "root";
$dbname = "lab3";
$dbport = 3306;

$conn = new mysqli($host, $dbusername, $dbpassword, $dbname, $dbport);

//tests for connection
if (mysqli_connect_error()) {
    die('Connect Error (' . mysqli_connect_error() . ')');
} else {
    //echo ("Connection successful <br>");
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
            //we need count right after log in is confirmed to retrive and use count
            $selectCountQuery = "SELECT count FROM user WHERE username = ?";
            $stmtSelectCount = $conn->prepare($selectCountQuery);
            $stmtSelectCount->bind_param("s", $toInsertUsername);
            $stmtSelectCount->execute();
            $stmtSelectCount->bind_result($currentCount);
            $stmtSelectCount->fetch();
            $stmtSelectCount->close();
            //create that session for count
            $_SESSION['countTracker'] = $currentCount;
            //take back to mainpage.php
            header("Location: mainpage.php");
        } else {
            echo "Invalid username or password";
            echo '<br><br><a href="lab3.html">Try Again</a>';
        }
        $conn->close();
    }
    //-------------------------------------The PHP code to register a user should be inside an if--------------------------------------
    if (isset($_POST["registerbutton"])) {
        //if user not taken
        $toInsertUsername = $_POST["username"];
        $toInsertPassword = $_POST["password"];
        $selectQuery = "SELECT username From user WHERE username = ?";
        $stmt = $conn->prepare($selectQuery);
        $stmt->bind_param("s", $toInsertUsername);
        //insertion of user is compelte at '?' character
        $stmt->execute();
        $res = $stmt->get_result(); //get result of query
        $numresults = mysqli_num_rows($res); //OOP or reg function synatx usable
        //check if user already taken
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
    }

    //increment count if button is clicked
    if (isset($_POST["increment"])) {
        $selectUserIdQuery = "SELECT userid FROM user WHERE username = ?"; // USE USERID REMEMBER
        $stmtSelect = $conn->prepare($selectUserIdQuery);
        $stmtSelect->bind_param("s", $_SESSION['loggedInUsername']);
        $stmtSelect->execute();
        $result = $stmtSelect->get_result(); //here's the actual result 
        $row = $result->fetch_assoc();      //needs row to be fetched similar to log 
        $userId = $row['userid'];           //now userid is stored in variable which cna be used for count
        $stmtSelect->close();

        // Now we update count for the userid that is logged in
        $updateQuery = "UPDATE user SET count = count + 1 WHERE userid = ?";    // UPDATE COUNT where userid = logged in essentially any userid all unique
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("i", $userId);
        // Execute the prepared statement
        $stmt->execute();
        // Here I output data of row
        header("Location: mainpage.php");
        //trying to get count for unique identifier (userid)
        //count statement is done
        $stmt->close();
        //------------------------------When the user logs in, the current count should immediately show (not just after incrementing it)------------------------------------
        // Now we select the count for the userid that is logged in
        $selectQuery = "SELECT count FROM user WHERE userid = ?";
        $selectStmt = $conn->prepare($selectQuery);
        $selectStmt->bind_param("i", $userId);  //time for integer 
        $selectStmt->execute();
        $selectStmt->bind_result($updatedCount);    //bind result to variable
        $selectStmt->fetch();
        $selectStmt->close();
        $_SESSION['countTracker'] = $updatedCount;  //set session variable to updated count "countTracker"
        // Update the session count
    }
    $conn->close();
}
