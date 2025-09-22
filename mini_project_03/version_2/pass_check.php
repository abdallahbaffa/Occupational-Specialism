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

    // Sanitize the input using filter_input
    $password_raw = filter_input(INPUT_POST, "password", FILTER_UNSAFE_RAW);
    // Also trim whitespace
    $password_raw = trim($password_raw);
    // Escape special HTML chars so that output is safe if echoed back
    $password = htmlspecialchars($password_raw, ENT_QUOTES, 'UTF-8');

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
    if (isset($password[0]) && is_numeric($password[0])) {
        $feedback .= "First character cannot be a number.<br>";
    }

    // Rule 8: Check the first character (must not be a special character)
    if (isset($password[0]) && !ctype_alnum($password[0])) {
        $feedback .= "First character cannot be a special character.<br>";
    }

    // Rule 9: Check the last character (must not be a special character)
    $len = strlen($password);
    if ($len > 0 && !ctype_alnum($password[$len - 1])) {
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

echo "<form action='pass_check.php' method='post'>"; #Forming
echo "<p id='ptext'>";
echo "Enter password here:";
echo "</p>";
echo "<input type='password' name='password' placeholder='Enter password here...'>";
echo "<br>";
echo "<input type='submit' value='Check Password'>"; #The submit button.
echo "</form>";

echo "<ul id='remember' align='center'>"; #Unordered lists.
echo "<li>The number of characters is greater than 8.</li>";
echo "<li>At least one upper case character.</li>";
echo "<li>At least one lower case character.</li>";
echo "<li>At least one special character</li>";
echo "<li>At least one number is present </li>";
echo "<li>The first character cannot be a special character</li>";
echo "<li>The last character cannot be the special character </li>";
echo "<li>The word “password” cannot be part of the password</li>";
echo "<li>The first character cannot be a number</li>";
echo "</ul>";

echo "</div>";
echo "</div>";
echo "</body>";
echo "</html>";

?>
