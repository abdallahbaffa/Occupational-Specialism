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

echo "<title>Template</title>";
// Links to the external CSS file, 'styles.css', which handles the website's design
echo "<link rel='stylesheet' href='css/styles.css'>";

echo "</head>";

echo "<body>";

echo "<div class='container'>";

require_once "assets/nav.php";

require_once "assets/topbar.php";

echo "<div id='content'>";

# content goes here

echo "<h2>Template</h2>"; # Header to show on the website.

echo "</div>";

echo "</div>";

echo "</body>";

echo "</html>";
?>