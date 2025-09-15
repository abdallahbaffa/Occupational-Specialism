<?php

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

echo "</div>";

echo "</div>";

echo "</body>";

echo "</html>";
?>