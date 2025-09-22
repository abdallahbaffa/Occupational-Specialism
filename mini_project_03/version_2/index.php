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

echo "<h2 align='center'><u>Importance of Passwords</u></h2>"; # Header to show on the website.
echo "<br>";
echo "<p id='ptext' align='center'>Strong passwords are vital because they act as your first line of defense against cyberattacks, protecting your personal information, financial data and accounts from unauthorized access and identity theft.</p>";

echo "<br>";
echo "<h3 id='h3headings' align='center'><u>What makes a strong passowrd?</u></h3>";
echo "<p id='ptext' align='center'>A strong password is long (14+ characters), complex (a mix of uppercase letters, lowercase letters, numbers, and symbols), unique (not reused across different accounts), and random (not using personal information, dictionary words, or obvious patterns).
Here is an example: v<7F9_|GJdBFq2</p>";
echo "<br>";
echo "<p id='p2text' align='center'>As you can see, making your passwords strong is vital, and so, this website has a password checker so that you can ensure the safety of your passwords! It is located on the navbar.</p>";
echo "</div>";

echo "</div>";

echo "</body>";

echo "</html>";
?>