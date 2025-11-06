<?php
// [Previous content of usermessage function remains the same]
function usermessage(){ # function to check for a user message and return echoable string
    if(isset($_SESSION['usermessage'])){ # checks to see if it is set
        if(str_contains($_SESSION['usermessage'],"ERROR")){ # if it's an error
            $msg = "<div id='usererror'>".$_SESSION['usermessage']."</div>"; # formats string appropriately
        } else { # if it's not an error
            $msg = "<div id='usermessage'>".$_SESSION['usermessage']."</div>"; # positive message given
        }
        unset($_SESSION['usermessage']); # removes the variable to prevent continued printing
        return $msg; # returns the message
    }
    // Return empty string if no message is set
    return "";
}

// --- New Functions from Normal Version 2 ---

/**
 * Checks if an email is already registered in the user table.
 * @param PDO $conn The database connection object.
 * @param string $email The email address to check.
 * @return bool True if the email is unique (not found), false otherwise.
 */
function onlyuser($conn, $email){
    $sql = "SELECT email FROM user WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(1, $email);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($result) {
        return false; // User already exists
    } else {
        return true;  // User does not exist, email is unique
    }
}

/**
 * Registers a new user in the database.
 * @param PDO $conn The database connection object (for insertion).
 * @return bool True if registration is successful, false otherwise.
 */
function reg_user($conn){
    $sql = "INSERT INTO user (email, password, fname, sname, dob, sign_up, addressln1, addressln2, postcode, county, phone) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    // Hash the password before storing
    $hashedPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $stmt->bindParam(1, $_POST['email']);
    $stmt->bindParam(2, $hashedPassword);
    $stmt->bindParam(3, $_POST['fname']);
    $stmt->bindParam(4, $_POST['sname']);
    $stmt->bindParam(5, $_POST['dob']);
    $stmt->bindParam(6, date('Y-m-d')); // Sign up date is current date
    $stmt->bindParam(7, $_POST['addressln1']);
    $stmt->bindParam(8, $_POST['addressln2']);
    $stmt->bindParam(9, $_POST['postcode']);
    $stmt->bindParam(10, $_POST['county']);
    $stmt->bindParam(11, $_POST['phone']);

    try {
        $stmt->execute();
        $conn = null; // Close connection
        return true; // Registration successful
    } catch (PDOException $e) {
        // Log the error and return false on failure
        error_log("Database error in reg_user: " . $e->getMessage());
        return false;
    }
}

/**
 * Checks if a password meets various complexity rules.
 * @param string $password The password to check.
 * @return array An array of strings describing the status of each rule.
 */
function pwd_checker($password){
    $rules = array();
    $rules["1"] = lenchecker($password);
    $rules["2"] = capchecker($password);
    $rules["3"] = lowerchecker($password);
    $rules["4"] = specialchecker($password);
    $rules["5"] = "Rule 5 - " . numchecker($password). "Your Password must contain a number";
    $rules["6"] = "Rule 6 - " . specialcheckerfirst($password[0]). "First character cannot be a special character";
    $rules["7"] = "Rule 7 - " . specialcheckerfirst($password[strlen($password)-1]). "Last character cannot be a special character"; // Fixed: Use strlen-1 for last char
    $rules["8"] = pwdcontains($password);
    $rules["9"] = "Rule 9 - " . numchecker($password[0]). "Your password cannot start with a number";
    return $rules;
}

/**
 * Checks if the password contains the word 'password' (case-insensitive).
 * @param string $password The password to check.
 * @return string A message indicating if the rule passed or failed.
 */
function pwdcontains($password){
    if(stripos($password, "password") !== false){ // Use stripos for case-insensitive search
        return "Rule 8 - Fail: Your password should not contain the word password";
    } else {
        return "Rule 8 - Pass: Your password should not contain the word password";
    }
}

/**
 * Checks if a character is a special character.
 * @param string $char The character to check.
 * @return string A message indicating if the rule passed or failed.
 */
function specialcheckerfirst($char){
    if (preg_match('/[^a-zA-Z0-9]/', $char)) {
        return "Fail: ";
    } else {
        return "Pass: ";
    }
}

/**
 * Checks if a character is a number (used for first char check).
 * @param string $char The character to check.
 * @return string A message indicating if the rule passed or failed.
 */
function numchecker($char){
    if (is_numeric($char)) { // Check if the character is numeric
        return "Fail: "; // Fails if it's a number
    } else {
        return "Pass: "; // Passes if it's not a number
    }
}

/**
 * Checks if the password contains at least one special character.
 * @param string $password The password to check.
 * @return string A message indicating if the rule passed or failed.
 */
function specialchecker($password){
    if (!preg_match('/[^a-zA-Z0-9]/', $password)) {
        return "Rule 4 - Fail: Your password must contain at least 1 Special Character";
    } else {
        return "Rule 4 - Pass: Your password must contain at least 1 Special Character";
    }
}

/**
 * Checks if the password contains at least one lowercase letter.
 * @param string $password The password to check.
 * @return string A message indicating if the rule passed or failed.
 */
function lowerchecker($password){
    if (!preg_match('/[a-z]/', $password)) {
        return "Rule 3 - Fail: Your password must contain at least 1 lowercase letter";
    } else {
        return "Rule 3 - Pass: Your password must contain at least 1 lowercase letter";
    }
}

/**
 * Checks if the password contains at least one uppercase letter.
 * @param string $password The password to check.
 * @return string A message indicating if the rule passed or failed.
 */
function capchecker($password){
    if (!preg_match('/[A-Z]/', $password)) {
        return "Rule 2 - Fail: Your password must contain at least 1 uppercase letter";
    } else {
        return "Rule 2 - Pass: Your password must contain at least 1 uppercase letter";
    }
}

/**
 * Checks if the password is at least 8 characters long.
 * @param string $password The password to check.
 * @return string A message indicating if the rule passed or failed.
 */
function lenchecker($password){
    if(strlen($password) < 8){
        return "Rule 1 - FAIL: Your password is less than 8 characters";
    } else {
        return "Rule 1 - Pass: Your password is longer than 8 characters";
    }
}

/**
 * Retrieves the userid for a given email after registration.
 * @param PDO $conn The database connection object (for selection).
 * @param string $email The email address of the newly registered user.
 * @return string|int The userid if found, or null/0 if not found.
 */
function getnewuserid($conn, $email){
    $sql = "SELECT userid FROM user WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(1, $email);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result ? $result["userid"] : null; // Return userid or null if not found
}

/**
 * Authenticates a user by email and returns their data if found.
 * @param PDO $conn The database connection object.
 * @param string $email The email address provided during login.
 * @return array|false The user data array (including userid and password hash) if found, false otherwise.
 */
function login($conn, $email){
    $sql = "SELECT userid, password FROM user WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(1, $email);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $conn = null; // Close connection
    if($result){
        return $result; // Return user data
    } else {
        return false; // User not found
    }
}

/**
 * Logs an action performed by a user into the audit table.
 * @param PDO $conn The database connection object (for insertion).
 * @param string|int $userid The ID of the user performing the action.
 * @param string $code A short code describing the action (e.g., 'log', 'reg', 'flo').
 * @param string $long A longer description of the action.
 * @return bool True if the audit entry was successful, false otherwise.
 */
function audtitor($conn, $userid, $code, $long){
    $sql = "INSERT INTO audit (date, userid, code, auditdescrip) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(1, date('Y-m-d'));
    $stmt->bindParam(2, $userid);
    $stmt->bindParam(3, $code);
    $stmt->bindParam(4, $long);

    try {
        $stmt->execute();
        $conn = null; // Close connection
        return true; // Audit successful
    } catch (PDOException $e) {
        // Log the error and return false on failure
        error_log("Database error in audtitor: " . $e->getMessage());
        return false;
    }
}
?>