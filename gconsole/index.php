<?php

if(!isset($_GET["message"])) {
    session_start();
    $message = false;
} else {
    //Decode the message for display.
    $message = htmlspecialchars(urldecode($_GET["message"]));
}

require_once "assets/db-con.php";
require_once "assets/common.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $_SESSION["msg"] = $_POST["message"];
}

echo "<!DOCTYPE html>";
// Start of the HTML document
echo "<html>";

echo "<head>";

echo "<title>Games Consoles</title>";
// Links to the external CSS file, 'styles.css', which handles the website's design
echo "<link rel='stylesheet' href='css/styles.css'>";

echo "</head>";

echo "<body>";

echo "<div class='container'>";

require_once "assets/topbar.php";

require_once "assets/nav.php";

echo "<div id='content'>";

if (!$message) {
    echo user_message();
} else {
    echo $message;
}

try {
    $conn = dbconnect_insert();
        echo"success";
} catch (PDOException $e) {
    echo $e->getMessage();
}

echo "<img src='images/image-index.png'>"; # Index image picture.

echo "</div>";

echo "</div>";

echo "</body>";

echo "</html>";
?>