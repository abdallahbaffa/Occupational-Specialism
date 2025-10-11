<?php // This open the php code section

session_start();
require_once("assets/dbconn.php");
require_once("assets/common.php");

if (!isset($_SESSION['user'])) {
    $_SESSION['usermessage'] = "ERROR: You are not logged in.";
    header("Location: login.php");
    exit; //Stop further execution.
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        new_console(dbconnect_insert(), $_POST); /*Calling subroutine and one of the paramerters is calling another subroutine, and if the conncetion is succefful we return the conncetion to the database.*/
        $_SESSION['usermessage'] = "SUCCESS: Console Created!";
    } catch (PDOException $e) {
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

// Label and Input for Manufacturer
echo "<label for='manufacturer'>Manufacturer:</label>";
echo "<input type='text' name='manufacturer' id='manufacturer' placeholder='e.g., Nintendo, Sony, Microsoft...' required>";
echo "<br>";

// Label and Input for Console Name
echo "<label for='console_name'>Console Name:</label>";
echo "<input type='text' name='console_name' id='console_name' placeholder='e.g., Switch, PlayStation 5...' required>";
echo "<br>";

// Label and Input for Release Date
echo "<label for='release_date'>Release Date:</label>";
echo "<input type='date' name='release_date' id='release_date' required>";
echo "<br>";

// Label and Input for Number of Controllers
echo "<label for='controller_number'>Number of Controllers:</label>";
echo "<input type='number' name='controller_number' id='controller_number' min='1' placeholder='e.g., 2, 4...' required>";
echo "<br>";

// Label and Input for Bit Type
echo "<label for='bit'>Bit Type (e.g., 8, 16, 32, 64, 128):</label>";
echo "<input type='number' name='bit' id='bit' min='1' placeholder='e.g., 8, 16, 64...' required>";
echo "<br>";

echo "<input type='submit' name='submit' value='Register Console'>";
echo "</form>";

echo "</div>"; // Closes div class='content'

echo "</div>"; // Closes div id='content'
echo "</div>"; // Closes div class='container'

echo "</body>";

echo "</html>";
?>