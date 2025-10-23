<?php
session_start();
require_once '../assets/dbconn.php';
require_once '../assets/staff_common.php'; // Use the new staff common file

// This page is for admins. We should add an admin login check here later,
// but for now, we'll just check for a basic user login.
if (!isset($_SESSION['user_id'])) {
    $_SESSION['msg'] = "You must be logged in to manage staff.";
    header("Location: login.php");
    exit;
}

// Handle the form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Simple validation to ensure fields are not empty
    if (empty($_POST['role']) || empty($_POST['first_name']) || empty($_POST['last_name']) || empty($_POST['room'])) {
        $_SESSION['msg'] = "ERROR: All fields are required.";
    } else {
        try {
            // 1. Try to register the user
            $new_staff_id = staffreg_user(dbconnect_insert());

            if ($new_staff_id) {
                // 2. If registration is successful, audit the action
                $admin_user_id = $_SESSION['user_id'] ?? 0; // Get the admin who is logged in
                $log_desc = "Admin (User ID: $admin_user_id) added new staff: " . $_POST['first_name'] . " " . $_POST['last_name'];

                // We use a separate connection for the audit
                staff_auditor(dbconnect_insert(), $new_staff_id, "STAFF_ADD", $log_desc);

                $_SESSION['msg'] = "SUCCESS: New staff member has been registered.";
            } else {
                $_SESSION['msg'] = "ERROR: Could not register staff member.";
            }

        } catch (Exception $e) {
            $_SESSION['msg'] = "ERROR: " . $e->getMessage();
        }
    }

    // Redirect back to this same page to show the message and clear the form
    header("Location: staff_register.php");
    exit();
}

// Get any success/error messages
$message = user_message();

?>
<!DOCTYPE html>
<html>
<head>
    <title>Register Staff - Primary Oaks</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>

<?php require_once '../assets/topbar.php'; ?>
<?php require_once '../assets/nav.php'; ?>
<?php require_once '../assets/content.php'; ?>

<h1>Register New Staff Member</h1>
<hr>

<!-- Display any error/success messages here -->
<?php echo $message; ?>

<p>Use this form to add a new clinician to the booking system.</p>

<!-- This form now matches your 'staff' table -->
<form action="staff_register.php" method="POST">

    <label for="role">Role (e.g., 'doc' or 'nur'):</label><br>
    <input type="text" id="role" name="role" maxlength="3" required><br><br>

    <label for="first_name">First Name:</label><br>
    <input type="text" id="first_name" name="first_name" required><br><br>

    <label for="last_name">Last Name:</label><br>
    <input type="text" id="last_name" name="last_name" required><br><br>

    <label for="room">Room Number:</label><br>
    <input type="text" id="room" name="room" maxlength="4" required><br><br>

    <button type="submit">Register Staff Member</button>
</form>

</div> <!-- Closes the content div from content.php -->

</body>
</html>
