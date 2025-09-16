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

echo "<title>Session Work</title>";
// Links to the external CSS file, 'styles.css', which handles the website's design
echo "<link rel='stylesheet' href='css/styles.css'>";

echo "</head>";

echo "<body>";

echo "<div class='container'>";

require_once "assets/nav.php";

require_once "assets/topbar.php";

echo "<div id='content'>";

echo usr_msg();

echo "<h2>Session Work</h2>";

# To take user input, store it in session and output it elsewhere.

echo "<form method='post' action=''>"; /*If not declaration of action, then it will reload the page*/

echo "<input type='text' name='message' placeholder='Message?' required>";

echo "<input type='submit' value='Submit'>";

echo "</div>";

echo "</div>";

echo "</body>";

echo "</html>";
?>