<?php
session_start();
require_once '../assets/dbconn.php';
require_once '../assets/common.php'; // We use this for the audit_write function

$feedback = ""; // This will hold our error messages

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Log that an attempt was made
    audit_write(0, 'REG_ATTEMPT', 'Registration attempt initiated from IP: ' . $_SERVER['REMOTE_ADDR']);

    // --- 1. Get and Sanitize ALL fields from the form ---

    // Use filter_input and trim for safety and consistency
    $email_raw = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
    $password_raw = filter_input(INPUT_POST, "password", FILTER_UNSAFE_RAW);
    $confirmPassword_raw = filter_input(INPUT_POST, "confirmPassword", FILTER_UNSAFE_RAW);

    $first_name_raw = filter_input(INPUT_POST, "first_name", FILTER_UNSAFE_RAW);
    $last_name_raw = filter_input(INPUT_POST, "last_name", FILTER_UNSAFE_RAW);
    $dob_raw = filter_input(INPUT_POST, "date_of_birth", FILTER_UNSAFE_RAW);
    $phone_raw = filter_input(INPUT_POST, "phone_number", FILTER_UNSAFE_RAW);
    $addr1_raw = filter_input(INPUT_POST, "address_line_1", FILTER_UNSAFE_RAW);
    $addr2_raw = filter_input(INPUT_POST, "address_line_2", FILTER_UNSAFE_RAW); // Optional
    $postcode_raw = filter_input(INPUT_POST, "postcode", FILTER_UNSAFE_RAW);
    $county_raw = filter_input(INPUT_POST, "county", FILTER_UNSAFE_RAW);

    // Prepare variables for database (trimming whitespace)
    $email = trim($email_raw);
    $first_name = trim($first_name_raw);
    $last_name = trim($last_name_raw);
    $date_of_birth = trim($dob_raw);
    $phone_number = trim($phone_raw);
    $address_line_1 = trim($addr1_raw);
    $address_line_2 = trim($addr2_raw); // Can be empty
    $postcode = trim($postcode_raw);
    $county = trim($county_raw);


    // --- 2. Validation Checks ---

    // Check for empty required fields
    if (empty($email) || empty($password_raw) || empty($first_name) || empty($last_name) || empty($date_of_birth) || empty($phone_number) || empty($address_line_1) || empty($postcode) || empty($county)) {
        $feedback .= "All fields (except Address Line 2) are required.<br>";
    }

    // Check if passwords match
    if ($password_raw !== $confirmPassword_raw) {
        $feedback .= "Passwords do not match.<br>";
    }

    // Password strength checks (only if other checks have passed so far)
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

    // --- 3. Database Interaction (If all checks passed) ---
    if (empty($feedback)) {
        try {
            $pdo = dbconnect_insert();

            // Check if email already exists
            $stmt = $pdo->prepare("SELECT user_id FROM users WHERE email_address = ?");
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                audit_write(0, 'REG_FAILED', 'Registration failed for email ' . $email . ' - Email already exists. IP: ' . $_SERVER['REMOTE_ADDR']);
                $feedback = "Registration failed. Please try again.";
            } else {
                // Email is unique, proceed with insertion

                // Hash the password securely
                $password_hash = password_hash($password_raw, PASSWORD_BCRYPT);

                // FIXED: The SQL query now includes ALL required columns
                $sql = "INSERT INTO users (
                            email_address, password_hash, first_name, last_name, 
                            date_of_birth, phone_number, address_line_1, address_line_2, 
                            postcode, county
                        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

                $stmt = $pdo->prepare($sql);

                // FIXED: Execute with all 10 parameters
                $stmt->execute([
                        $email, $password_hash, $first_name, $last_name,
                        $date_of_birth, $phone_number, $address_line_1, $address_line_2,
                        $postcode, $county
                ]);

                // Get the new user's ID for logging
                $new_user_id = $pdo->lastInsertId();
                audit_write($new_user_id, 'REG_SUCCESS', 'New user registered successfully with email: ' . $email . '. Name: ' . $first_name . ' ' . $last_name);

                // Set success message and redirect to login
                $_SESSION['msg'] = "✅ Registration successful! You can now log in.";
                header("Location: login.php");
                exit();
            }
        } catch (Exception $e) {
            // Log the detailed database error
            audit_write(0, 'REG_FAILED', 'Registration failed due to a database error for email ' . $email . '. Error: ' . $e->getMessage() . '. IP: ' . $_SERVER['REMOTE_ADDR']);
            // Show a generic message to the user
            $feedback = "An unexpected error occurred. Please try again.";
        }
    } else {
        // Log validation failures
        audit_write(0, 'REG_FAILED', 'Registration failed for email ' . $email . ' - Validation errors: ' . str_replace('<br>', ' ', $feedback) . '. IP: ' . $_SERVER['REMOTE_ADDR']);
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register - Primary Oaks Surgery</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
<?php require_once '../assets/topbar.php'; ?>
<?php require_once '../assets/nav.php'; ?>
<?php require_once '../assets/content.php'; ?>

<h1>Create Your Account</h1>
<hr>

<!-- Display Success/Error Messages -->
<?php if (isset($_SESSION['msg'])): // Check for success message from redirect ?>
    <div class="feedback-success"><?= $_SESSION['msg'] ?></div>
    <?php unset($_SESSION['msg']); // Clear message after showing ?>
<?php elseif ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($feedback)): // Show errors on POST fail ?>
    <div class="feedback-error">❌ **Registration Failed!** Please correct the following issues:<br><br><?= $feedback ?></div><br>
<?php endif; ?>

<!--
    FIXED: The form now includes all required fields.
    The 'name' attributes match the database columns.
    We use htmlspecialchars on 'value' to safely re-populate the form on error.
-->
<form action="register.php" method="POST">

    <label for="first_name">First Name:</label><br>
    <input type="text" id="first_name" name="first_name" value="<?= htmlspecialchars($_POST['first_name'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required><br><br>

    <label for="last_name">Last Name:</label><br>
    <input type="text" id="last_name" name="last_name" value="<?= htmlspecialchars($_POST['last_name'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required><br><br>

    <label for="email">Email Address:</label><br>
    <input type="email" id="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required><br><br>

    <label for="date_of_birth">Date of Birth:</label><br>
    <input type="date" id="date_of_birth" name="date_of_birth" value="<?= htmlspecialchars($_POST['date_of_birth'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required><br><br>

    <label for="phone_number">Phone Number:</label><br>
    <input type="tel" id="phone_number" name="phone_number" value="<?= htmlspecialchars($_POST['phone_number'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required><br><br>

    <label for="address_line_1">Address Line 1:</label><br>
    <input type="text" id="address_line_1" name="address_line_1" value="<?= htmlspecialchars($_POST['address_line_1'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required><br><br>

    <label for="address_line_2">Address Line 2 (Optional):</label><br>
    <input type="text" id="address_line_2" name="address_line_2" value="<?= htmlspecialchars($_POST['address_line_2'] ?? '', ENT_QUOTES, 'UTF-8') ?>"><br><br>

    <label for="postcode">Postcode:</label><br>
    <input type="text" id="postcode" name="postcode" value="<?= htmlspecialchars($_POST['postcode'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required><br><br>

    <label for="county">County:</label><br>
    <input type="text" id="county" name="county" value="<?= htmlspecialchars($_POST['county'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required><br><br>

    <hr style="border-color: #059669; margin: 20px 0;">

    <label for="password">Password:</label><br>
    <input type="password" id="password" name="password" required><br><br>

    <label for="confirmPassword">Confirm Password:</label><br>
    <input type="password" id="confirmPassword" name="confirmPassword" required><br><br>

    <button type="submit">Create Account</button>
</form>

<h2>Password Requirements</h2>
<ul class="rules">
    <li>Must be <strong>greater than 8</strong> characters.</li>
    <li>Must contain at least <strong>one upper case</strong> character.</li>
    <li>Must contain at least <strong>one lower case</strong> character.</li>
    <li>Must contain at least <strong>one number</strong>.</li>
    <li>Must contain at least <strong>one special character</strong>.</li>
    <li>The word <strong>“password”</strong> cannot be part of the password.</li>
    <li>The <strong>first</strong> character cannot be a number or special character.</li>
    <li>The <strong>last</strong> character cannot be a special character.</li>
</ul><br>

</div> <!-- Closes the content div -->
</body>
</html>
