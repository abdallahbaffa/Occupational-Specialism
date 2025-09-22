<?php

session_start();

require_once "assets/common.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $_SESSION["msg"] = $_POST["message"];
}

echo "<!DOCTYPE html>";
// Start of the HTML document
echo "<html>";

echo "<head>";

echo "<title>Password Rater</title>";
// Links to the external CSS file, 'styles.css', which handles the website's design
echo "<link rel='stylesheet' href='css/styles.css'>";

echo "</head>";

echo "<body>";

require_once "assets/topbar.php";
require_once "assets/nav.php";

echo "<div class='container'>";



echo "<div id='content'>";

# content goes here

echo "<h2 id='passcheck' align='center'><u>The Password Checker</u></h2>"; # Header to show on the website.
echo "<br>";
echo "<p id='ptext'>Below is a form in which you can test your password strength:</p>";
echo "<br>";
echo "<h3 id='remember' align='center'>The number of characters is greater than 8 
• At least one upper case character 
• At least one lower case character 
• At least one special character 
• At least one number is present 
• The first character cannot be a special character  
• The last character cannot be the special character 
• The word “password” cannot be part of the password 
• The first character cannot be a number</h3>";

echo "</div>";

echo "</body>";

echo "</html>";
?>