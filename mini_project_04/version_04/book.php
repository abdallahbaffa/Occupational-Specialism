<?php
session_start();
require_once "../assets/common.php";
require_once "../assets/dbconn.php";

// 1. Check if user is logged in. If not, redirect to login.
if (!isset($_SESSION['user_id'])) {
    // We'll use the 'msg' session variable for error messages
    $_SESSION['msg'] = "You must log in to book an appointment.";
    header("Location: login.php");
    exit;
}

// 2. Handle form submission (POST request)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Combine date and time from form
        $tmp = $_POST["appt_date"] . ' ' . $_POST["appt_time"];
        // Convert to epoch timestamp
        $epoch_time = strtotime($tmp);

        // Check if a valid time was created and is in the future
        if ($epoch_time === false || $epoch_time < time()) {
            $_SESSION['msg'] = "ERROR: Please select a valid date and time in the future.";
        } else {
            // Call the (now fixed) commit_booking function
            if (commit_booking(dbconnect_insert(), $epoch_time)) {
                // Set a success message and redirect to bookings page
                $_SESSION['msg'] = "SUCCESS: Your booking has been made!";
                header("location: bookings.php"); // Go to bookings page to see it
                exit;
            } else {
                $_SESSION['msg'] = "ERROR: Booking has failed!";
            }
        }
    } catch (PDOException $e) {
        $_SESSION['msg'] = "ERROR: Database error. " . $e->getMessage();
    } catch (Exception $e) {
        $_SESSION['msg'] = "ERROR: " . $e->getMessage();
    }

    // If we are here, something failed. Redirect back to book.php to show the error.
    header("location: book.php");
    exit;
}

// 3. (GET Request) Prepare data for displaying the page
try {
    // Get the list of staff to populate the dropdown
    $staff_list = staff_getter(dbconnect_insert());
} catch (Exception $e) {
    // If the database fails, set staff list to empty and show an error
    $staff_list = [];
    $_SESSION['msg'] = "ERROR: Could not load staff. " . $e->getMessage();
}

// Get any session messages to display (e.g., from a failed booking)
$message = user_message(); // This function also clears the message

?>
<!DOCTYPE html>
<html>
<head>
    <title>Book Appointment - Primary Oaks</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>

<!-- Include standard layout files -->
<?php require_once '../assets/topbar.php'; ?>
<?php require_once '../assets/nav.php'; ?>
<?php require_once '../assets/content.php'; ?>

<h1>Book an Appointment</h1>
<hr>

<!-- Display any error/success messages here -->
<?php echo $message; ?>

<!-- This is the booking form. It was missing before. -->
<form action="book.php" method="POST">

    <label for="appt_date">Appointment Date:</label><br>
    <input type="date" id="appt_date" name="appt_date" required><br><br>

    <label for="appt_time">Appointment Time:</label><br>
    <!-- step="600" means 10-minute intervals, matching your original code -->
    <input type="time" id="appt_time" name="appt_time" step="600" required><br><br>

    <label for="staff">Select Clinician:</label><br>
    <select id="staff" name="staff" required>
        <option value="">--Please select a staff member--</option>
        <?php foreach ($staff_list as $staff_member): ?>
            <?php
            // 3. FIXED: Use comparison (==) not assignment (=)
            if ($staff_member['role'] == "doc") {
                $role = "Doctor";
            } else if ($staff_member['role'] == "nur") {
                $role = "Nurse";
            } else {
                $role = "Clinician"; // Fallback
            }
            ?>
            <!-- We are creating an <option> for the dropdown list -->
            <option value="<?php echo htmlspecialchars($staff_member['staff_id']); ?>">
                <?php echo htmlspecialchars($role . ' ' . $staff_member['first_name'] . ' ' . $staff_member['last_name'] . ' (Room ' . $staff_member['room'] . ')'); ?>
            </option>
        <?php endforeach; ?>
    </select><br><br>

    <button type="submit">Book Appointment</button>
</form>

</div> <!-- Closes the content div from content.php -->

</body>
</html>

