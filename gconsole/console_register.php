<?php // This open the php code section

session_start();

require_once("assets/common.php");
require_once("assets/dbconn.php");

if (!isset($_SESSION['user'])){
    $_SESSION['usermessage'] = "ERROR: You are not logged in.";
    header("Location: login.php");
    exit; //Stop further execution.
}

if ($_SERVER["REQUEST_METHOD"] === "POST"){
    try{
        new_console(dbconnect_insert(), $_POST); /*Calling subroutine and one of the paramerters is calling another subroutine, and if the conncetion is succefful we return the conncetion to the database.*/
        $_SESSION['usermessage'] = "SUCCESS: Console Created!";
    } catch(PDOException $e){
        $_SESSION['usermessage'] = $e->getMessage();
    }
}

echo "<!DOCTYPE html>";  # essential html line to dictate the page type

echo "<html>";  # opens the html content of the page

echo "<head>";  # opens the head section

echo "<title> GConsole</title>";  # sets the title of the page (web browser tab)
echo "<link rel='stylesheet' type='text/css' href='css/styles.css' />";  # links to the external style sheet

echo "</head>";  # closes the head section of the page

echo "<body>";  # opens the body for the main content of the page.

echo "<div class='container'>";

require_once "assets/topbar.php";
require_once "assets/nav.php";

echo "<div id='content'>";
echo "<h2 id='passcheck' align='center'>";
echo "<u>";
echo "Console Register Page";
echo "</u>";
echo "</h2>";
echo "<br>";


echo "<div class='content'>";


echo "<h1> Game New Console Registration </h1>";

echo "<br>";

echo "<p id='intro'>Welcome to the home of tracking the consoles you own.</p>";


echo "<br>";
echo user_message();
echo "<br>";


echo "<form method='post' action=''>";

echo "<input type='text' name='manufacturer' placeholder='Manufacturer'>";
echo "<br>";
echo "<input type='text' name='console_name' placeholder='Console Name'>";
echo "<br>";
echo "<input type='text' name='release' placeholder='Release Date'>";
echo "<br>";
echo "<input type='text' name='controller_number' placeholder='Number of Controllers'>";
echo "<br>";
echo "<input type='text' name='bit' placeholder='Number of Bits.'>";
echo "<br>";
echo "<input type='submit' name='submit' value='Register'>";

echo "</div>";

echo "</div>";

echo "</body>";

echo "</html>";
?>