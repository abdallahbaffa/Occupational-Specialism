<?php

echo "<!DOCTYPE html>";

echo "<html>";

echo "<head>";

echo "<title>Template</title>";
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