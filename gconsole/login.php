<?php

session_start();

require_once "assets/common.php";

echo "<!DOCTYPE html>";
echo "<html>";
echo "<head>";
echo "<title>";
echo "User Login Page";
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
echo "User Login Page";
echo "</u>";
echo "</h2>";
echo "<br>";

// This checks if the form has been submitted using the POST method
if ($_SERVER["REQUEST_METHOD"] == "POST") {
}
    echo "<form action='login.php' method='post'>"; #Forming
    echo "<p id='ptext'>";
    echo "Enter username:";
    echo "</p>";
    echo "<input type='text' name='name' id='name' placeholder='Enter username here...' required>";
    echo "<br>";
    echo "<p id='ptext'>";
    echo "Enter password:";
    echo "</p>";
    echo "<input type='password' name='password' placeholder='Enter password here...' required>";
    echo "<br>";
    echo "<input type='submit' value='Login'>"; #The submit button.

    echo "</form>";


    echo "</div>";
    echo "</div>";
    echo "</body>";
    echo "</html>";
?>