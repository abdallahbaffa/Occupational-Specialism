<?php
session_start();
require_once "assets/common.php";
require_once "assets/dbconn.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") { //SHOULD ALWAYS BE TRIPLE '='!
    if (!only_user(dbconnect_insert(), $_POST["user_name"])) {
        if (reg_user(dbconnect_insert(), $_POST)) {
            auditor(dbconnect_insert(), getnewuserid(dbconnect_insert(), $_POST["user_name"]), "reg", "New user registered");
            $_SESSION["usermessage"] = "USER WAS CREATED SUCCESSFULLY.";
            header("Location: index.php");
            exit;
        } else {
            $_SESSION["usermessage"] = "ERROR: USER REGISTRATION FAILED.";
        }
    } else {
        $_SESSION["usermessage"] = "USERNAME CANNOT BE USED.";
    }
}

echo "<!DOCTYPE html>";
echo "<html>";
echo "<head>";
echo "<title>";
echo "User Register Page";
echo "</title>";
echo "<link rel='stylesheet' href='css/styles.css'>";
echo "</head>";
echo "<body>";
require_once "assets/topbar.php";
require_once "assets/nav.php";
echo "<div class='container'>";
echo "<div id='content'>";

echo "<h2 id='passcheck' align='center'>";
echo "<u>";
echo "User Register Page";
echo "</u>";
echo "</h2>";
echo "<br>";

echo "<h1> User Page Registration </h1>";
echo "<p id='intro'>Welcome to the user registration page! Wanna register?</p>";

echo "<form method='post' action=''>";

// Label and Input for Username
echo "<label for='user_name'>Username:</label>";
echo "<input type='text' name='user_name' id='user_name' placeholder='Enter your desired username...' required>";
echo "<br>";

// Label and Input for Password
echo "<label for='password'>Password:</label>";
echo "<input type='password' name='password' id='password' placeholder='Choose a secure password...' required>";
echo "<br>";

// Label and Input for Sign-Up Date
echo "<label for='sign_up_date'>Sign-Up Date:</label>";
echo "<input type='date' name='sign_up_date' id='sign_up_date' required>";
echo "<br>";

// Label and Input for Date of Birth
echo "<label for='date_of_birth'>Date of Birth:</label>";
echo "<input type='date' name='date_of_birth' id='date_of_birth' required>";
echo "<br>";

// Label and Input for Country
echo "<label for='country'>Country:</label>";
echo "<input type='text' name='country' id='country' placeholder='Enter your country of residence...' required>";
echo "<br>";

echo "<input type='submit' value='Register'>";
echo "</form>";
echo "<br>";
echo "<br>";
echo user_message();

echo "</div>"; // Closes div id='content'
echo "</div>"; // Closes div class='container'
echo "</body>";
echo "</html>";
?>