<?php // This open the php code section
session_start(); // connects to the session for session inforamtion.
require_once "assets/common.php";
require_once "assets/dbconn.php";

if (isset($_SESSION['userid'])) { // checks to see if already logged in
    $_SESSION['usermessage'] = "ERROR: You have already logged in!"; // redirects them with an errory message if they are.
    header("Location: index.php");
    exit; // this is needed to ensure that no other code executes.
} elseif ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usr = login(dbconnect_insert(), $_POST["email"]);
    if ($usr && password_verify($_POST["password"], $usr["password"])) { // verifies the password is matched
        $_SESSION["userid"] = $usr["user_id"];
        $_SESSION["usermessage"] = "SUCCESS: You have successfully logged in!";
        header("Location: index.php");
        exit;
    } else {
        $_SESSION['usermessage'] = "ERROR: Email or password invalid!";
        header("Location: login.php");
        exit;
    }
}

function login($conn, $email){
    try {
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email"); // prepares the statement
        $stmt->bindParam(':email', $email); // binds the parameter
        $stmt->execute(); // executes the statement
        $result = $stmt->fetch(PDO::FETCH_ASSOC); // fetches the result as an associative array
        $conn = null; // closes the connection
        if($result){ // checks if result is returned
            return $result; // returns the result if true
        } else { // if no result
            return false; // returns false
        }
    } catch(PDOException $e) { // if error
        error_log("Database error in login: " . $e->getMessage());
        $_SESSION['usermessage'] = "Database Error: " . $e->getMessage();
        header("Location: index.php");
        exit;
    }
}

echo "<!DOCTYPE html>"; # essential html line to dictate the page type
echo "<html>"; # opens the html content of the page
echo "<head>"; # opens the head section
echo "<title> Version 1</title>"; # sets the title of the page (web browser tab)
echo "<link rel='stylesheet' type='text/css' href='css/styles.css' />"; # links to the external style sheet
echo "</head>"; # closes the head section of the page
echo "<body>"; # opens the body for the main content of the page.
echo "<div class='container'>";
require_once "assets/topbar.php";
require_once "assets/nav.php";
echo "<div class='content'>";
echo "<br>";
echo "<h2> Primary Oaks - User Login System</h2>"; # sets a h2 heading as a welcome
echo "<p class='content'> Please Enter the needed credentials below! </p>";
echo "<form method='post' action='login.php'>"; # opens the form, setting the action to run the code on this page, using the post method
echo "<br>";
echo "<input type='email' name='email' placeholder='Email' required/>"; # sets the email field
echo "<br>";
echo "<input type='password' name='password' placeholder='Password' required/>"; # sets the password field
echo "<br>";
echo "<input type='submit' name='submit' value='Login' />"; # sets the submit button
echo "<br>";
echo "</form>";
echo "<br>";
echo usermessage();
echo "</div>";
echo "</div>";
echo "</body>";
echo "</html>";
?>