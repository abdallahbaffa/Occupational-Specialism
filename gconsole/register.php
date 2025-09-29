<?php

session_start();

require_once "assets/common.php";
require_once "assets/db-con.php";


if($_SERVER["REQUEST_METHOD"] === "POST") { //SHOULD ALWAYS BE TRIPLE '='!
    if(!only_user(dbconnect_insert(), $_POST["username"])) {

        if(reg_user(dbconnect_insert(), $_POST["username"])) {
            $_SESSION["username"] = "User was created successfully";
        } else {
            $_SESSION["username"] = "ERROR: User registration failed.";
        }
    }

    $_SESSION["usermessage"] = "The result of the only user : " .only_user(dbconnect_insert(), $_POST["username"]);
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

    echo "<form method='post' action=''>";

    echo "<label for='name'>Username</label>";
    echo "<input type='text' name='name' id='name' placeholder='Enter username here...' required>";
    echo "<br>";

    echo "<label for='name'>Password</label>";
    echo "<input type='password' name='password' placeholder='Enter password here...' required>";
    echo "<br>";

    echo "<label for='dateofbirth'>Date of Birth</label>";
    echo "<input type='text' name='dob' placeholder='When were you born?'>";
    echo "<br>";

    echo "<label for='country'>Country</label>";
    echo "<input type='text' name='country' placeholder='Country you live in?'>";
    echo "<br>";

    echo "<input type='submit' value='Submit'>";

    echo "</form>";

    echo "<br>";
    echo "<br>";

    echo "</div>";
    echo user_message();
    echo "</div>";
    echo "</body>";
    echo "</html>";

?>