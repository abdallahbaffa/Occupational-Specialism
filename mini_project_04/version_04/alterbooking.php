<?php
session_start();
require_once "../assets/common.php";
require_once "../assets/dbconn.php";

// 1. Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['msg'] = "You must log in to book an appointment.";
    header("Location: login.php");
    exit;
}

// 2. Check if we are in the middle of editing an appointment
if (!isset($_SESSION['edit_book_id'])) {
    $_SESSION['msg'] = "ERROR: No appointment selected to edit.";
    header("Location: bookings.php");
    exit;
}

// --- Get the IDs we need ---
$book_id_to_edit = $_SESSION['edit_book_id'];
$user_id = $_SESSION['user_id'];


// 3. Handle form submission (POST request)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Get new details from form
        $new_date = $_POST["appt_date"];
        $new_time = $_POST["appt_time"];
        $new_staff_id = $_POST["staff"];

        // Combine date and time
        $tmp = $new_date . ' ' . $new_time;
        $new_epoch_time = strtotime($tmp);

        // Check if the time is valid and in the future
        if ($new_epoch_time === false || $new_epoch_time < time()) {
            $_SESSION['msg'] = "ERROR: Please select a valid date and time in the future.";
        } else {
            // Call the new update_appt function
            if (update_appt(dbconnect_insert(), $book_id_to_edit, $user_id, $new_staff_id, $new_epoch_time)) {

                audit_write($user_id, 'BOOK_UPDATE', "User updated booking ID: $book_id_to_edit");

                // Success! Clear the edit ID from session
                unset($_SESSION['edit_book_id']);

                $_SESSION['msg'] = "SUCCESS: Your booking has been updated!";
                header("location: bookings.php"); // Go back to bookings page
                exit;
            } else {
                $_SESSION['msg'] = "ERROR: Booking update failed!";
            }
        }
    } catch (Exception $e) {
        $_SESSION['msg'] = "ERROR: " . $e->getMessage();
    }

    // If we are here, something failed. Redirect back to this same page to show the error.
    header("Location: alterbooking.php");
    exit;
}

// 4. (GET Request) Prepare data for displaying the page
try {
    // Get the list of all staff for the dropdown
    $staff_list = staff_getter(dbconnect_insert());

    // Get the details of the specific appointment we are editing
    $appt = appt_fetch(dbconnect_insert(), $book_id_to_edit, $user_id);

    if (!$appt) {
        // This can happen if the appointment was cancelled or belongs to someone else
        unset($_SESSION['edit_book_id']);
        $_SESSION['msg'] = "ERROR: Could not find that appointment.";
        header("Location: bookings.php");
        exit;
    }

    // --- Pre-fill values for the form ---
    // Convert epoch time back into "Y-m-d" and "H:i"
    $appt_date_val = date('Y-m-d', $appt['appointment_date']);
    $appt_time_val = date('H:i', $appt['appointment_date']);
    $appt_staff_id = $appt['staff_id'];

} catch (Exception $e) {
    $staff_list = [];
    $_SESSION['msg'] = "ERROR: Could not load data. " . $e->getMessage();
}

// Get any session messages to display (e.g., from a failed update)
$message = user_message();

?>
<!DOCTYPE html>
<html>
<head>
    <title>Change Appointment - Primary Oaks</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>

<?php require_once '../assets/topbar.php'; ?>
<?php require_once '../assets/nav.php'; ?>
<?php require_once '../assets/content.php'; ?>

<h1>Change Your Appointment</h1>
<hr>

<?php echo $message; ?>

<!-- This form posts back to itself -->
<form action="alterbooking.php" method="POST">

    <label for="appt_date">Appointment Date:</label><br>
    <!-- We use 'value' to pre-fill the form with the current appointment date -->
    <input type="date" id="appt_date" name="appt_date" value="<?php echo htmlspecialchars($appt_date_val); ?>" required><br><br>

    <label for="appt_time">Appointment Time:</label><br>
    <!-- We use 'value' to pre-fill the form with the current appointment time -->
    <input type="time" id="appt_time" name="appt_time" step="600" value="<?php echo htmlspecialchars($appt_time_val); ?>" required><br><br>

    <label for="staff">Select Clinician:</label><br>
    <select id="staff" name="staff" required>
        <option value="">--Please select a staff member--</option>
        <?php foreach ($staff_list as $staff_member): ?>
            <?php
            if ($staff_member['role'] == "doc") { $role = "Doctor"; }
            else if ($staff_member['role'] == "nur") { $role = "Nurse"; }
            else { $role = "Clinician"; }

            // --- This is the new part ---
            // If this staff member's ID matches the one from the appointment, add 'selected'
            $selected = ($staff_member['staff_id'] == $appt_staff_id) ? 'selected' : '';
            ?>

            <option value="<?php echo htmlspecialchars($staff_member['staff_id']); ?>" <?php echo $selected; ?>>
                <?php echo htmlspecialchars($role . ' ' . $staff_member['first_name'] . ' ' . $staff_member['last_name'] . ' (Room ' . $staff_member['room'] . ')'); ?>
            </option>
        <?php endforeach; ?>
    </select><br><br>

    <button type="submit">Update Appointment</button>
    <a href="bookings.php" style="margin-left: 10px; color: #d1fae5;">Cancel</a>
</form>

</div> <!-- Closes the content div from content.php -->

</body>
</html>
