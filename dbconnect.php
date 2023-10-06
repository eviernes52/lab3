<?php

$host = "localhost";
$dbusername = "root";
$dbpassword = "root";
$dbname = "lab3";
$dbport = 3306;

$conn = new mysqli($host, $dbusername, $dbpassword, $dbname, $dbport);

// Tests for connection
if (mysqli_connect_error()) {
    die('Connect Error (' . mysqli_connect_error() . ')');
} else {
    session_start();

    $logout = $_GET["logout"];
    if (isset($_POST["logout"])) {
        session_destroy();
        header("Location: lab3.html");
        $conn->close();
    }

    if (isset($_POST["loginbutton"])) {
        $toInsertUsername = $_POST["username"];
        $toInsertPassword = $_POST["password"];

        // Use a prepared statement with placeholders
        $selectQuery = "SELECT username FROM user WHERE username = ? AND password = ?";
        $stmt = $conn->prepare($selectQuery);
        $stmt->bind_param("ss", $toInsertUsername, $toInsertPassword);
        $stmt->execute();

        $stmt->store_result();
        $numresults = $stmt->num_rows;

        if ($numresults > 0) {
            $_SESSION['usernameCorrect'] = true;
            $_SESSION['loggedInUsername'] = $toInsertUsername;
            header("Location: mainpage.php");
        } else {
            echo "Invalid username or password";
            echo '<br><br><a href="lab3.html">Try Again</a>';
        }
        $stmt->close();
        $conn->close();
    }


    if (isset($_POST["registerbutton"])) {
        $toInsertUsername = $_POST["username"];
        $toInsertPassword = $_POST["password"];
        $confirmPassword = $_POST["confirmPassword"];
        $toInsertFirstName = $_POST["firstName"];
        $toInsertLastName = $_POST["lastName"];
        $toInsertEmail = $_POST["email"];

        // Check if any field is left blank
        if (empty($toInsertUsername) || empty($toInsertPassword) || empty($confirmPassword) || empty($toInsertFirstName) || empty($toInsertLastName) || empty($toInsertEmail)) {
            echo "All fields are required.";
            echo '<br><br><a href="lab3.html">Try Again</a>';
        } else {
            // Check if email is already in use using a prepared statement
            $emailCheckQuery = "SELECT email FROM user WHERE email = ?";
            $stmtEmailCheck = $conn->prepare($emailCheckQuery);
            $stmtEmailCheck->bind_param("s", $toInsertEmail);
            $stmtEmailCheck->execute();
            $stmtEmailCheck->store_result();
            $numEmailResults = $stmtEmailCheck->num_rows;
            $stmtEmailCheck->close();

            if ($numEmailResults > 0) {
                echo "Email address is already in use.";
                echo '<br><br><a href="lab3.html">Try Again</a>';
            } elseif ($toInsertPassword !== $confirmPassword) {
                echo "Passwords do not match. Please try again.";
            } else {
                // Check if username is already in use using a prepared statement
                $selectQuery = "SELECT username FROM user WHERE username = ?";
                $stmt = $conn->prepare($selectQuery);
                $stmt->bind_param("s", $toInsertUsername);
                $stmt->execute();
                $stmt->store_result();
                $numresults = $stmt->num_rows;

                if ($numresults > 0) {
                    echo ("Someone already has that username " . $toInsertUsername);
                    echo '<br><br><a href="lab3.html">Try Again</a>';
                } else {
                    // Insert new user using a prepared statement
                    $insertQuery = "INSERT INTO user (username, password, firstname, lastname, email) VALUES (?, ?, ?, ?, ?)";
                    $stmt = $conn->prepare($insertQuery);
                    $stmt->bind_param("sssss", $toInsertUsername, $toInsertPassword, $toInsertFirstName, $toInsertLastName, $toInsertEmail);
                    $stmt->execute();
                    echo "New account added successfully";
                    echo '<br><br><a href="lab3.html">Back to home page</a>';
                }
            }
            $conn->close();
        }
    }
}
