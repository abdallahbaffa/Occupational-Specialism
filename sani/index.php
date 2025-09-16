<?php

session_start();

require_once "assets/common.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $_SESSION["msg"] = $_POST["message"];
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $_SESSION["mail"] = $_POST["email"];
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $_SESSION["url"] = $_POST["url"];
}

echo "<!DOCTYPE html>";
// Start of the HTML document
echo "<html>";

echo "<head>";

echo "<title>Sanitisation</title>";
// Links to the external CSS file, 'styles.css', which handles the website's design
echo "<link rel='stylesheet' href='css/styles.css'>";

echo "</head>";

echo "<body>";

echo "<div class='container'>";

require_once "assets/nav.php";

require_once "assets/topbar.php";

echo "<div id='content'>";

echo usr_msg(); #GIVE IT BACK TO THE USER
echo usr_mail();
echo usr_url();

echo "<h2>I like to Sanitize!!</h2>";

echo "<form method='post' action=''>"; /*If not declaration of action, then it will reload the page*/
echo "<input type='text' name='message' placeholder='Message?' required>";
echo "<input type='submit' value='Submit'>";

echo "<br>";

echo "<form method='post' action=''>";
echo "<input type='email' name='mail' placeholder='Email?' required>";
echo "<input type='submit' value='Submit'>";

echo "<br>";

echo "<form method='post' action=''>";
echo "<input type='url' name='url' placeholder='URL?' required>";
echo "<input type='submit' value='Submit'>";

echo "</div>";

echo "</div>";

echo "</body>";

echo "</html>";
?>