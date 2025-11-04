<?php
session_start();
require_once 'assets/dbconn.php';
require_once 'assets/common.php';
$feedback = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    audit_write(0, 'REG_ATTEMPT', 'Registration attempt initiated from IP: ' . $_SERVER['REMOTE_ADDR']);
    $email_raw = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
    $password_raw = filter_input(INPUT_POST, "password", FILTER_UNSAFE_RAW);
    $confirmPassword_raw = filter_input(INPUT_POST, "confirmPassword", FILTER_UNSAFE_RAW);
    $first_name_raw = filter_input(INPUT_POST, "first_name", FILTER_UNSAFE_RAW);
    $last_name_raw = filter_input(INPUT_POST, "last_name", FILTER_UNSAFE_RAW);
    $dob_raw = filter_input(INPUT_POST, "date_of_birth", FILTER_UNSAFE_RAW);
    $phone_raw = filter_input(INPUT_POST, "phone_number", FILTER_UNSAFE_RAW);
    $addr1_raw = filter_input(INPUT_POST, "address_line_1", FILTER_UNSAFE_RAW);
    $addr2_raw = filter_input(INPUT_POST, "address_line_2", FILTER_UNSAFE_RAW);
    $postcode_raw = filter_input(INPUT_POST, "postcode", FILTER_UNSAFE_RAW);
    $county_raw = filter_input(INPUT_POST, "county", FILTER_UNSAFE_RAW);
    $email = trim($email_raw);
    $first_name = trim($first_name_raw);
    $last_name = trim($last_name_raw);
    $date_of_birth = trim($dob_raw);
    $phone_number = trim($phone_raw);
    $address_line_1 = trim($addr1_raw);
    $address_line_2 = trim($addr2_raw);
    $postcode = trim($postcode_raw);
    $county = trim($county_raw);
    if (empty($email) || empty($password_raw) || empty($first_name) || empty($last_name) || empty($date_of_birth) || empty($phone_number) || empty($address_line_1) || empty($postcode) || empty($county)) {
        $feedback .= "All fields (except Address Line 2) are required.<br>";
    }
    if ($password_raw !== $confirmPassword_raw) {
        $feedback .= "Passwords do not match.<br>";
    }
    if (empty($feedback) && !empty($password_raw)) {
        if (stripos($password_raw, 'password') !== false) {
            $feedback .= "Password cannot contain the word 'password'.<br>";
        }
        if (strlen($password_raw) < 9) {
            $feedback .= "Password must be greater than 8 characters.<br>";
        }
        if (!preg_match('/[A-Z]/', $password_raw)) {
            $feedback .= "Password must contain at least one uppercase letter.<br>";
        }
        if (!preg_match('/[a-z]/', $password_raw)) {
            $feedback .= "Password must contain at least one lowercase letter.<br>";
        }
        if (!preg_match('/[0-9]/', $password_raw)) {
            $feedback .= "Password must contain at least one number.<br>";
        }
        if (!preg_match('/[^a-zA-Z0-9]/', $password_raw)) {
            $feedback .= "Password must contain at least one special character.<br>";
        }
        if (isset($password_raw[0])) {
            if (is_numeric($password_raw[0])) {
                $feedback .= "First character cannot be a number.<br>";
            } elseif (!ctype_alnum($password_raw[0])) {
                $feedback .= "First character cannot be a special character.<br>";
            }
        }
        $len = strlen($password_raw);
        if ($len > 0 && !ctype_alnum($password_raw[$len - 1])) {
            $feedback .= "Last character cannot be a special character.<br>";
        }
    }
    if (empty($feedback)) {
        try {
            $pdo = dbconnect_insert();
            $stmt = $pdo->prepare("SELECT user_id FROM users WHERE email_address = ?");
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                audit_write(0, 'REG_FAILED', 'Registration failed for email ' . $email . ' - Email already exists. IP: ' . $_SERVER['REMOTE_ADDR']);
                $feedback = "Registration failed. Please try again.";
            } else {
                $password_hash = password_hash($password_raw, PASSWORD_BCRYPT);
                $sql = "INSERT INTO users (
                            email_address, password_hash, first_name, last_name, 
                            date_of_birth, phone_number, address_line_1, address_line_2, 
                            postcode, county
                        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                        $email, $password_hash, $first_name, $last_name,
                        $date_of_birth, $phone_number, $address_line_1, $address_line_2,
                        $postcode, $county
                ]);
                $new_user_id = $pdo->lastInsertId();
                audit_write($new_user_id, 'REG_SUCCESS', 'New user registered successfully with email: ' . $email . '. Name: ' . $first_name . ' ' . $last_name);
                $_SESSION['msg'] = "✅ Registration successful! You can now log in.";
                header("Location: login.php");
                exit();
            }
        } catch (Exception $e) {
            audit_write(0, 'REG_FAILED', 'Registration failed due to a database error for email ' . $email . '. Error: ' . $e->getMessage() . '. IP: ' . $_SERVER['REMOTE_ADDR']);
            $feedback = "An unexpected error occurred. Please try again.";
        }
    } else {
        audit_write(0, 'REG_FAILED', 'Registration failed for email ' . $email . ' - Validation errors: ' . str_replace('<br>', ' ', $feedback) . '. IP: ' . $_SERVER['REMOTE_ADDR']);
    }
}
echo "<!DOCTYPE html>";
echo "<html>";
echo "<head>";
echo "    <title>Register - Primary Oaks Surgery</title>";
echo "    <link rel='stylesheet' href='css/styles.css'>";
echo "</head>";
echo "<body>";
require_once 'assets/topbar.php';
require_once 'assets/nav.php';
require_once 'assets/content.php';
echo "<h1>Create Your Account</h1>";
echo "<hr class='divider'>";
if (isset($_SESSION['msg'])) {
    echo "    <div class='feedback-success'>" . $_SESSION['msg'] . "</div>";
    unset($_SESSION['msg']);
} elseif ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($feedback)) {
    echo "    <div class='feedback-error'>❌ **Registration Failed!** Please correct the following issues:<br><br>" . $feedback . "</div><br>";
}
echo "<form action='register.php' method='POST'>";
echo "    <label for='first_name'>First Name:</label><br>";
echo "    <input type='text' id='first_name' name='first_name' value='" . htmlspecialchars($_POST['first_name'] ?? '', ENT_QUOTES, 'UTF-8') . "' required><br><br>";
echo "    <label for='last_name'>Last Name:</label><br>";
echo "    <input type='text' id='last_name' name='last_name' value='" . htmlspecialchars($_POST['last_name'] ?? '', ENT_QUOTES, 'UTF-8') . "' required><br><br>";
echo "    <label for='email'>Email Address:</label><br>";
echo "    <input type='email' id='email' name='email' value='" . htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES, 'UTF-8') . "' required><br><br>";
echo "    <label for='date_of_birth'>Date of Birth:</label><br>";
echo "    <input type='date' id='date_of_birth' name='date_of_birth' value='" . htmlspecialchars($_POST['date_of_birth'] ?? '', ENT_QUOTES, 'UTF-8') . "' required><br><br>";
echo "    <label for='phone_number'>Phone Number:</label><br>";
echo "    <input type='tel' id='phone_number' name='phone_number' value='" . htmlspecialchars($_POST['phone_number'] ?? '', ENT_QUOTES, 'UTF-8') . "' required><br><br>";
echo "    <label for='address_line_1'>Address Line 1:</label><br>";
echo "    <input type='text' id='address_line_1' name='address_line_1' value='" . htmlspecialchars($_POST['address_line_1'] ?? '', ENT_QUOTES, 'UTF-8') . "' required><br><br>";
echo "    <label for='address_line_2'>Address Line 2 (Optional):</label><br>";
echo "    <input type='text' id='address_line_2' name='address_line_2' value='" . htmlspecialchars($_POST['address_line_2'] ?? '', ENT_QUOTES, 'UTF-8') . "'><br><br>";
echo "    <label for='postcode'>Postcode:</label><br>";
echo "    <input type='text' id='postcode' name='postcode' value='" . htmlspecialchars($_POST['postcode'] ?? '', ENT_QUOTES, 'UTF-8') . "' required><br><br>";
echo "    <label for='county'>County:</label><br>";
echo "    <input type='text' id='county' name='county' value='" . htmlspecialchars($_POST['county'] ?? '', ENT_QUOTES, 'UTF-8') . "' required><br><br>";
echo "    <hr class='divider'>";
echo "    <label for='password'>Password:</label><br>";
echo "    <input type='password' id='password' name='password' required><br><br>";
echo "    <label for='confirmPassword'>Confirm Password:</label><br>";
echo "    <input type='password' id='confirmPassword' name='confirmPassword' required><br><br>";
echo "    <button type='submit'>Create Account</button>";
echo "</form>";
echo "<h2>Password Requirements</h2>";
echo "<ul class='rules'>";
echo "    <li>Must be <strong>greater than 8</strong> characters.</li>";
echo "    <li>Must contain at least <strong>one upper case</strong> character.</li>";
echo "    <li>Must contain at least <strong>one lower case</strong> character.</li>";
echo "    <li>Must contain at least <strong>one number</strong>.</li>";
echo "    <li>Must contain at least <strong>one special character</strong>.</li>";
echo "    <li>The word <strong>“password”</strong> cannot be part of the password.</li>";
echo "    <li>The <strong>first</strong> character cannot be a number or special character.</li>";
echo "    <li>The <strong>last</strong> character cannot be a special character.</li>";
echo "</ul><br>";
echo "</div>";
echo "</body>";
echo "</html>";