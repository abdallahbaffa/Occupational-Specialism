<?php // This open the php code section
session_start();
require_once "assets/common.php";
require_once "assets/dbconn.php";

if (isset($_SESSION['userid'])) {
    $_SESSION['usermessage'] = "ERROR: You have already logged in!";
    header("Location: index.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($_POST['password'] != $_POST['password_confirm']) {
        $_SESSION['usermessage'] = "ERROR: Passwords do not match!";
        header("Location: register.php");
        exit;
    } else {
        try{
            // Pass all required fields from the form to the registration function
            if(onlyuser(dbconnect_select(),$_POST['email']) && userreg(dbconnect_insert())) {
                $_SESSION['usermessage'] = "SUCCESS: You have been registered!";
                header("Location: login.php");
                exit;
            }
        } catch(PDOException $e) {
            $_SESSION['usermessage'] = "ERROR: " . $e->getMessage();
            header("Location: register.php");
            exit;
        } catch(Exception $e) {
            $_SESSION['usermessage'] = "ERROR: " . $e->getMessage();
            header("Location: register.php");
            exit;
        }
    }
}

function onlyuser($conn, $email){
    $stmt = $conn->prepare("SELECT email FROM users WHERE email = :email"); // prepares the statement
    $stmt->bindParam(':email', $email); // binds the parameter
    $stmt->execute(); // executes the statement
    $result = $stmt->fetch(PDO::FETCH_ASSOC); // fetches the result as an associative array
    $conn = null; // closes the connection
    if($result){ // checks if result is returned
        $_SESSION['usermessage'] = "ERROR: Email is already in use!";
        header("Location: register.php");
        exit;
    } else { // if no result
        return true; // returns true if email is unique
    }
}

function userreg($conn){
    $options = ['cost' => 12]; // sets the cost for the password hashing
    $hash = password_hash($_POST['password'], PASSWORD_BCRYPT, $options); // hashes the password
    // Include all the fields expected by the version_01 users table in the INSERT statement
    $stmt = $conn->prepare("INSERT INTO users (fname, sname, email, password, dob, sign_up, addressln1, addressln2, postcode, county, phone) VALUES (:fname, :sname, :email, :password, :dob, :sign_up, :addressln1, :addressln2, :postcode, :county, :phone)"); // prepares the statement
    $stmt->bindParam(':fname', $_POST['fname']); // binds the parameter
    $stmt->bindParam(':sname', $_POST['sname']); // binds the parameter
    $stmt->bindParam(':email', $_POST['email']); // binds the parameter
    $stmt->bindParam(':password', $hash); // binds the parameter (hashed)
    $stmt->bindParam(':dob', $_POST['dob']); // binds the date of birth parameter
    $stmt->bindParam(':sign_up', date('Y-m-d')); // binds the current date for sign_up
    $stmt->bindParam(':addressln1', $_POST['addressln1']); // binds the address line 1 parameter
    $stmt->bindParam(':addressln2', $_POST['addressln2']); // binds the address line 2 parameter
    $stmt->bindParam(':postcode', $_POST['postcode']); // binds the postcode parameter
    $stmt->bindParam(':county', $_POST['county']); // binds the county parameter
    $stmt->bindParam(':phone', $_POST['phone']); // binds the phone parameter
    $result = $stmt->execute(); // executes the statement
    $conn = null; // closes the connection
    return $result; // returns the result of the execution
}

echo "<!DOCTYPE html>"; # essential html line to dictate the page type
echo "<html>"; # opens the html content of the page
echo "<head>"; # opens the head section
echo "<title> Version 1</title>"; # sets the title of the page (web browser tab)
echo "<link rel='stylesheet' type='text/css' href='css/styles.css' />"; # links to the external style sheet
echo "</head>"; # closes the head section of the page
echo "<body>"; # opens the body for the main content of the page.
echo "<div class='container'>";
require_once "assets/topbar.php";
require_once "assets/nav.php";
echo "<div class='content'>";
echo "<br>";
echo "<h2> Primary Oaks - User Registration</h2>"; # sets a h2 heading as a welcome
echo "<p class='content'> Please Enter the needed credentials below! </p>";
echo "<form method='post' action='register.php'>"; # opens the form, setting the action to run the code on this page, using the post method
echo "<br>";
echo "<input type='text' name='fname' placeholder='First Name' required/>"; # sets the first name field
echo "<br>";
echo "<input type='text' name='sname' placeholder='Surname' required/>"; # sets the surname field
echo "<br>";
echo "<input type='email' name='email' placeholder='Email' required/>"; # sets the email field
echo "<br>";
echo "<input type='password' name='password' placeholder='Password' required/>"; # sets the password field
echo "<br>";
echo "<input type='password' name='password_confirm' placeholder='Confirm Password' required/>"; # sets the confirm password field
echo "<br>";
echo "<input type='date' name='dob' placeholder='Date of Birth (YYYY-MM-DD)' required/>"; # adds the date of birth field
echo "<br>";
echo "<input type='text' name='addressln1' placeholder='Address Line 1' required/>"; # adds address line 1
echo "<br>";
echo "<input type='text' name='addressln2' placeholder='Address Line 2' required/>"; # adds address line 2
echo "<br>";
echo "<input type='text' name='postcode' placeholder='Postcode' required/>"; # adds postcode
echo "<br>";
echo "<input type='text' name='county' placeholder='County' required/>"; # adds county
echo "<br>";
echo "<input type='text' name='phone' placeholder='Phone Number' required/>"; # adds phone number
echo "<br>";
echo "<input type='submit' name='submit' value='Register' />"; # sets the submit button
echo "<br>";
echo "</form>";
echo "<br>";
echo usermessage();
echo "</div>";
echo "</div>";
echo "</body>";
echo "</html>";
?>