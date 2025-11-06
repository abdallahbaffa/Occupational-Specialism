<?php // This open the php code section
session_start();
require_once "assets/common.php"; // Include common functions, including onlyuser, reg_user, getnewuserid, audtitor, pwd_checker
require_once "assets/dbconn.php"; // Include database connection functions

// Check if already logged in
if (isset($_SESSION['userid'])) {
    $_SESSION['usermessage'] = "ERROR: You have already logged in!";
    header("Location: index.php");
    exit;
}

// Process registration if form is submitted via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if passwords match
    if ($_POST['password'] != $_POST['password_confirm']) {
        $_SESSION['usermessage'] = "ERROR: Passwords do not match!";
        header("Location: register.php");
        exit;
    }

    // --- NEW: Check Password Complexity ---
    $passwordRules = pwd_checker($_POST['password']);
    $passwordValid = true;
    $errorMessage = "Password failed validation: ";
    foreach ($passwordRules as $rule) {
        if (str_contains($rule, "Fail")) {
            $passwordValid = false;
            $errorMessage .= $rule . " ";
        }
    }

    if (!$passwordValid) {
        $_SESSION['usermessage'] = "ERROR: " . trim($errorMessage);
        header("Location: register.php");
        exit;
    }
    // --- END NEW: Check Password Complexity ---

    try {
        // Check if user already exists using the function from common.php
        if (onlyuser(dbconnect_select(), $_POST['email'])) { // Use dbconnect_select for checking existence
            // If email is unique, proceed with registration using the function from common.php
            if (reg_user(dbconnect_insert())) { // Use dbconnect_insert for the actual registration
                $_SESSION['usermessage'] = "SUCCESS: YOU have been registered!";
                // Log the registration using the function from common.php
                // Get the new user ID after registration
                $newUserId = getnewuserid(dbconnect_select(), $_POST['email']); // Use dbconnect_select to fetch the ID
                if ($newUserId) {
                    audtitor(dbconnect_insert(), $newUserId, "reg", "Registration of new user"); // Use dbconnect_insert for logging
                }
                header("Location: login.php");
                exit;
            } else {
                // Registration failed in the function
                $_SESSION['usermessage'] = "ERROR: Registration failed due to a database issue.";
                header("Location: register.php");
                exit;
            }
        } else {
            // Email already exists, handled by onlyuser function which sets a message
            $_SESSION['usermessage'] = "ERROR: Email is already in use!";
            header("Location: register.php");
            exit;
        }
    } catch (PDOException $e) {
        $_SESSION['usermessage'] = "ERROR: Database error occurred - " . $e->getMessage();
        header("Location: register.php");
        exit;
    } catch (Exception $e) {
        $_SESSION['usermessage'] = "ERROR: An unexpected error occurred - " . $e->getMessage();
        header("Location: register.php");
        exit;
    }
}

// --- HTML Output ---
echo "<!DOCTYPE html>";
echo "<html>";
echo "<head>";
echo "<title> version 2</title>";
echo "<link rel='stylesheet' type='text/css' href='css/styles.css' />";
echo "</head>";
echo "<body>";
echo "<div class='container'>";
require_once "assets/topbar.php";
require_once "assets/nav.php";
echo "<div class='content'>";
echo "<br>";
echo "<h2> Primary Oaks - User registration system</h2>";
echo "<p class='content'> Please complete the below form to register for our system </p>";
echo "<form method='post' action=''>"; // Form posts to itself
echo "<br>";
echo "<input type='email' name='email' placeholder='E-mail Address' required/>";
echo "<br>";
echo "<input type='password' name='password' placeholder='Password' required/>";
echo "<br>";
echo "<input type='password' name='password_confirm' placeholder='Confirm Password' required/>";
echo "<br>";
echo "<input type='text' name='fname' placeholder='Firstname' required/>";
echo "<br>";
echo "<input type='text' name='sname' placeholder='Surname' required/>";
echo "<br>";
echo "<input type='date' name='dob' value='". date('Y-m-d')."' required/>"; // Pre-fills today's date
echo "<br>";
echo "<input type='text' name='addressln1' placeholder='Address Line 1' required/>";
echo "<br>";
echo "<input type='text' name='addressln2' placeholder='Address Line 2' />";
echo "<br>";
echo "<input type='text' name='postcode' placeholder='Postcode' required/>";
echo "<br>";
echo "<input type='text' name='county' placeholder='County' required/>";
echo "<br>";
echo "<input type='text' name='phone' placeholder='Phone Number' required/>";
echo "<br>";
echo "<input type='submit' name='submit' value='Register' />";
echo "<br>";
echo "</form>";
echo "<br>";

// --- NEW: Display Password Rules ---
echo "<div class='rules'>"; // Assuming you have CSS for this class
echo "<h3>Password Requirements:</h3>";
echo "<ul>";
echo "<li id='rule1'>Rule 1 - Must be at least 8 characters long</li>";
echo "<li id='rule2'>Rule 2 - Must contain an uppercase letter</li>";
echo "<li id='rule3'>Rule 3 - Must contain a lowercase letter</li>";
echo "<li id='rule4'>Rule 4 - Must contain a special character</li>";
echo "<li id='rule5'>Rule 5 - Must contain a number</li>";
echo "<li id='rule6'>Rule 6 - Cannot start with a special character</li>";
echo "<li id='rule7'>Rule 7 - Cannot end with a special character</li>";
echo "<li id='rule8'>Rule 8 - Cannot contain the word 'password'</li>";
echo "<li id='rule9'>Rule 9 - Cannot start with a number</li>";
echo "</ul>";
echo "</div>";
// --- END NEW: Display Password Rules ---

echo usermessage(); // Display any messages set during the process
echo "</div>";
echo "</div>";
echo "</body>";
echo "</html>";
?>