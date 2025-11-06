<?php // This open the php code section
session_start(); // connects to the session for session inforamtion.
require_once "assets/common.php"; // Include common functions, including the login function
require_once "assets/dbconn.php"; // Include database connection functions

// Check if already logged in
if (isset($_SESSION['userid'])) {
    $_SESSION['usermessage'] = "ERROR: You have already logged in!";
    header("Location: index.php");
    exit;
}

// Process login if form is submitted via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Attempt to get user data from the database
    $usr = login(dbconnect_insert(), $email); // Use dbconnect_insert as per original version_02
    if ($usr && password_verify($password, $usr["password"])) { // Verify password hash
        // Login successful
        $_SESSION["userid"] = $usr["userid"]; // Use 'userid' key as returned by the login function
        $_SESSION['usermessage'] = "SUCCESS: User Successfully Logged In";
        // Log the successful login
        audtitor(dbconnect_insert(), $_SESSION["userid"], "log", "User has successfully logged in");
        header("Location: index.php");
        exit;
    } elseif (!$usr) {
        // User not found
        $_SESSION['usermessage'] = "ERROR: User not found";
        // Log the failed login attempt (user not found)
        // We don't have the user ID here, so we can't log the specific user, but we could log the email attempt if needed.
        // For now, we just log the attempt without a specific user ID.
        audtitor(dbconnect_insert(), 0, "flo", "User login attempt failed - User not found - Email: " . $email);
        header("Location: login.php");
        exit;
    } else {
        // Password incorrect
        $_SESSION['usermessage'] = "ERROR: User login password does not match";
        // Log the failed login attempt (incorrect password)
        // Use the user ID returned from the login function attempt
        if (isset($usr["userid"])) {
            audtitor(dbconnect_insert(), $usr["userid"], "flo", "User has unsuccessfully logged in - Incorrect password");
        }
        header("Location: login.php");
        exit;
    }
}

// --- HTML Output ---
echo "<!DOCTYPE html>";
echo "<html>";
echo "<head>";
echo "<title>Version 2</title>";
echo "<link rel='stylesheet' type='text/css' href='css/styles.css' />";
echo "</head>";
echo "<body>";
echo "<div class='container'>";
require_once "assets/topbar.php";
require_once "assets/nav.php";
echo "<div class='content'>";
echo "<br>";
echo "<h2> Primary Oaks - User Login System</h2>";
echo "<p class='content'> Please Enter the needed credentials below! </p>";
echo "<form method='post' action=''>";
echo "<br>";
echo "<input type='email' name='email' placeholder='E-mail Address' required/>";
echo "<br>";
echo "<input type='password' name='password' placeholder='Password' required/>";
echo "<br>";
echo "<input type='submit' name='submit' value='Login' />";
echo "</form>";
echo "<br>";
echo usermessage(); // Display any messages set during the process
echo "</div>";
echo "</div>";
echo "</body>";
echo "</html>";
?>