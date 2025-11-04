<?php
session_start();
require_once 'assets/dbconn.php';
require_once 'assets/common.php';

$feedback = ""; // To store error messages
$email_preset = ""; // To re-fill the email field on a failed attempt

// --- 1. Check if user is ALREADY logged in ---
// If they are, just send them to the index page.
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// --- 2. Check if the form has been submitted ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Log that an attempt was made
    audit_write(0, 'LOGIN_ATTEMPT', 'Login attempt initiated from IP: ' . $_SERVER['REMOTE_ADDR']);

    // Get and sanitize email and password
    $email = trim(filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL));
    $password = filter_input(INPUT_POST, "password", FILTER_UNSAFE_RAW);
    $email_preset = htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); // For re-filling the form

    // Check for empty fields
    if (empty($email) || empty($password)) {
        $feedback = "Login failed. Please check your details and try again.";
        audit_write(0, 'LOGIN_FAILED', 'Login failed for email ' . $email . ' - Empty fields provided. IP: ' . $_SERVER['REMOTE_ADDR']);
    } else {
        // Try to find and verify the user
        try {
            $pdo = dbconnect_insert();

            // Get user data from the database
            $stmt = $pdo->prepare("SELECT user_id, first_name, last_name, password_hash FROM users WHERE email_address = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Verify the user exists AND the password is correct
            if ($user && password_verify($password, $user['password_hash'])) {

                // --- SUCCESS! ---
                // Set session variables
                $_SESSION['user_id'] = $user['user_id'];
                // We'll store their name for a nice welcome message
                $_SESSION['user_name'] = htmlspecialchars($user['first_name'] . ' ' . $user['last_name'], ENT_QUOTES, 'UTF-8');

                // Log the successful login
                audit_write($user['user_id'], 'LOGIN_SUCCESS', 'User logged in successfully with email: ' . $email);

                // FIXED: Redirect to the index page on success
                header("Location: index.php");
                exit(); // Stop the script

            } else {
                // --- FAILURE ---
                // Log the failure (generic reason for security)
                $user_id_for_audit = $user['user_id'] ?? 0;
                $reason = $user ? 'Incorrect password' : 'Email not found';
                audit_write($user_id_for_audit, 'LOGIN_FAILED', 'Login failed for email ' . $email . ' - ' . $reason . '. IP: ' . $_SERVER['REMOTE_ADDR']);

                // Show generic error message
                $feedback = "Login failed. Please check your details and try again.";
            }
        } catch (Exception $e) {
            // Database or other system error
            audit_write(0, 'LOGIN_FAILED', 'Login failed for email ' . $email . ' due to a database error. Error: ' . $e->getMessage() . '. IP: ' . $_SERVER['REMOTE_ADDR']);
            $feedback = "An unexpected error occurred. Please try again.";
        }
    }
}

// --- 3. Check for any success messages from registration ---
// This uses the function from common.php
$message = user_message();

?>
<!DOCTYPE html>
<html>
<head>
    <title>Login - Primary Oaks Surgery</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
<?php require_once 'assets/topbar.php'; ?>
<?php require_once 'assets/nav.php'; ?>
<?php require_once 'assets/content.php'; ?>

<h1>Login to Your Account</h1>
<hr>

<!--
    This logic now displays:
    1. A success message (if you just registered).
    2. An error message (if login failed).
-->
<?php if (!empty($message)): // This is for success messages (e.g., from register.php) ?>
    <div class="feedback-success"><?= $message ?></div><br>
<?php elseif (!empty($feedback)): // This is for login error messages ?>
    <div class="feedback-error"><strong>❌ Error:</strong> <?= $feedback ?></div><br>
<?php endif; ?>

<!--
    The form now pre-fills the email address on a failed attempt
    to make it easier to re-try.
-->
<form action="login.php" method="POST">
    <label for="email">Email Address:</label><br>
    <input type="email" id="email" name="email" value="<?= $email_preset ?>" required><br><br>

    <label for="password">Password:</label><br>
    <input type="password" id="password" name="password" required><br><br>

    <button type="submit">Login</button>
</form>
<br>
<p>Don't have an account? <a href="register.php">Register here</a>.</p>

</div> <!-- Closes the content div -->
</body>
</html>