<?php

session_start();

require_once "assets/common.php";

echo "<!DOCTYPE html>";

echo "<html>";

echo "<head>";

echo "<title>";
echo "Password Rater";
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
echo "The Password Checker";
echo "</u>";
echo "</h2>";

echo "<br>";

// This checks if the form has been submitted using the POST method
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $password = $_POST["password"];
    $feedback = "";

    // Rule 1: Check if the password contains the word "password"
    if (strpos($password, 'password') !== false) {
        $feedback .= "Password cannot contain the word 'password'.<br>";
    }

    // Rule 2: Check the length
    if (strlen($password) < 9) {
        $feedback .= "Password must be greater than 8 characters.<br>";
    }

    // Rule 3: Check for uppercase
    if (!preg_match('/[A-Z]/', $password)) {
        $feedback .= "Password must contain at least one uppercase letter.<br>";
    }

    // Rule 4: Check for lowercase
    if (!preg_match('/[a-z]/', $password)) {
        $feedback .= "Password must contain at least one lowercase letter.<br>";
    }

    // Rule 5: Check for a number
    if (!preg_match('/[0-9]/', $password)) {
        $feedback .= "Password must contain at least one number.<br>";
    }

    // Rule 6: Check for a special character
    if (!preg_match('/[^a-zA-Z0-9]/', $password)) {
        $feedback .= "Password must contain at least one special character.<br>";
    }

    // Rule 7: Check the first character (must not be a number)
    if (is_numeric($password[0])) {
        $feedback .= "First character cannot be a number.<br>";
    }

    // Rule 8: Check the first character (must not be a special character)
    if (!ctype_alnum($password[0])) {
        $feedback .= "First character cannot be a special character.<br>";
    }

    // Rule 9: Check the last character (must not be a special character)
    if (!ctype_alnum($password[strlen($password) - 1])) {
        $feedback .= "Last character cannot be a special character.<br>";
    }

    // Display the final feedback
    if (empty($feedback)) {
        echo "<p id='ptext' style='color:green;'>";
        echo "Password is strong!";
        echo "</p>";
    } else {
        echo "<p id='ptext' style='color:red;'>";
        echo "<strong>";
        echo "Your password needs improvement:";
        echo "</strong>";
        echo "</p>";
        echo "<p id='ptext' style='color:red;'>";
        echo "$feedback";
        echo "</p>";
    }
}

echo "<form action='pass_check.php' method='post'>";
echo "<p id='ptext'>";
echo "Enter password here:";
echo "</p>";
echo "<input type='password' name='password' placeholder='Enter password here...'>";
echo "<br>";
echo "<input type='submit' value='Check Password'>";
echo "</form>";

echo "<h3 id='remember' align='center'>The number of characters is greater than 8 
• At least one upper case character 
• At least one lower case character 
• At least one special character 
• At least one number is present 
• The first character cannot be a special character  
• The last character cannot be the special character 
• The word “password” cannot be part of the password 
• The first character cannot be a number</h3>";

echo "</div>";
echo "</div>";
echo "</body>";
echo "</html>";

?>