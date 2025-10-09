<?php

session_start();

require_once "assets/common.php";
require_once "assets/db-con.php";

if($_SERVER["REQUEST_METHOD"] === "POST") { //SHOULD ALWAYS BE TRIPLE '='!

    if (!only_user(dbconnect_insert(), $_POST["username"])) {

        if (reg_user(dbconnect_insert(), $_POST)) {
            auditor(dbconnect_insert(), getnewuserid(dbconnect_insert(), $_POST["username"]), "reg", "New user registered");
            $_SESSION["usermessage"] = "USER WAS CREATED SUCCESSFULLY.";
            header("location: index.php");
            exit;

        } else {
            $_SESSION["usermessage"] = "ERROR: USER REGISTRATION FAILED.";
        }
    } else {
        $_SESSION["usermessage"] = "USERNAME CANNOT BE USED.";
    }

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

echo "<h1> User Page Registration </h1>";

echo "<p id='intro'>Welcome to the user registration page! Wanna register?</p>";

    echo "<form method='post' action=''>";

    echo "<input type='text' name='username' id='name' placeholder='Enter username here...'>";
    echo "<br>";

    echo "<input type='password' name='password' placeholder='Enter password here...'>";
    echo "<br>";

    echo "<input type='text' name='signupdate' placeholder='When did you sign up?'>";
    echo "<br>";

    echo "<input type='text' name='dob' placeholder='When were you born?'>";
    echo "<br>";

    echo "<input type='text' name='country' placeholder='Country you live in?'>";
    echo "<br>";

    echo "<input type='submit' value='Register'>";

    echo "</form>";

    echo "<br>";
    echo "<br>";

    echo "</div>";

    echo user_message();

    echo "</div>";
    echo "</body>";
    echo "</html>";

?>