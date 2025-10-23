<?php
session_start();
require_once '../assets/common.php';
require_once '../assets/dbconn.php';

// 1. Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['msg'] = "You must log in first to see your bookings.";
    header("Location: login.php");
    exit;
}

// --- NEW: Handle form submissions (Cancel or Change) ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $book_id = $_POST['book_id'] ?? 0;
    $user_id = $_SESSION['user_id'];
    $action = $_POST['action'] ?? '';

    try {
        if ($action == 'delete' && $book_id > 0) {
            // --- Handle Cancel ---
            cancel_appt(dbconnect_insert(), $book_id, $user_id);
            audit_write($user_id, 'BOOK_CANCEL', "User cancelled booking ID: $book_id");
            $_SESSION['msg'] = "SUCCESS: Your appointment has been cancelled.";

        } elseif ($action == 'change' && $book_id > 0) {
            // --- Handle Change ---
            // Store the ID of the appointment we want to edit in the session
            $_SESSION['edit_book_id'] = $book_id;
            // Redirect to the new alterbooking.php page
            header("Location: alterbooking.php");
            exit;
        }
    } catch (Exception $e) {
        $_SESSION['msg'] = "ERROR: " . $e->getMessage();
    }

    // Redirect back to this page to show the message
    header("Location: bookings.php");
    exit;
}
// --- End of POST Handle ---


// 2. Get any messages (e.g., "SUCCESS: Your appointment has been cancelled.")
$message = user_message();

// 3. Get appointments from database
try {
    $appts = appt_getter(dbconnect_insert());
} catch (Exception $e) {
    $appts = false;
    $_SESSION['msg'] = "ERROR: Could not load appointments. " . $e->getMessage();
    $message = user_message();
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>My Bookings - Primary Oaks</title>
    <link rel="stylesheet" href="../css/styles.css">
    <style>
        /* Add some style for the buttons */
        .action-btn {
            padding: 5px 10px;
            margin: 2px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .btn-delete { background-color: #DC2626; color: white; }
        .btn-change { background-color: #059669; color: white; }
    </style>
</head>
<body>

<?php require_once '../assets/topbar.php'; ?>
<?php require_once '../assets/nav.php'; ?>
<?php require_once '../assets/content.php'; ?>

<h1>My Bookings</h1>
<hr>

<?php echo $message; ?>

<p class='content'>Below are your upcoming appointments.</p>

<?php if (!$appts): ?>
    <p>You have no appointments booked.</p>
<?php else: ?>
    <table id='bookings' style="width:100%; border-collapse: collapse;">
        <thead style="background-color: #f4f4f4;">
        <tr>
            <th style="padding: 8px; border: 1px solid #ddd;">Appointment Date</th>
            <th style="padding: 8px; border: 1px solid #ddd;">Clinician</th>
            <th style="padding: 8px; border: 1px solid #ddd;">Room</th>
            <th style="padding: 8px; border: 1px solid #ddd;">Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($appts as $appt): ?>
            <?php
            if ($appt['role'] == "doc") { $role = "Doctor"; }
            else if ($appt['role'] == "nur") { $role = "Nurse"; }
            else { $role = "Clinician"; }
            ?>
            <tr>
                <!-- We wrap each row's cells in a form -->
                <form action="bookings.php" method="POST">
                    <td style="padding: 8px; border: 1px solid #ddd;"><?php echo date('M d, Y @ h:i A', $appt['appointment_date']); ?></td>
                    <td style="padding: 8px; border: 1px solid #ddd;"><?php echo htmlspecialchars($role . " " . $appt['first_name'] . " " . $appt['last_name']); ?></td>
                    <td style="padding: 8px; border: 1px solid #ddd;"><?php echo htmlspecialchars($appt['room']); ?></td>

                    <!-- NEW: Action buttons cell -->
                    <td style="padding: 8px; border: 1px solid #ddd; text-align: center;">
                        <!-- Hidden input to send the 'book_id' -->
                        <input type="hidden" name="book_id" value="<?php echo $appt['book_id']; ?>">

                        <!-- Submit buttons with 'name' and 'value' to tell our PHP logic what to do -->
                        <button type="submit" name="action" value="change" class="action-btn btn-change">Change</button>
                        <button type="submit" name="action" value="delete" class="action-btn btn-delete" onclick="return confirm('Are you sure you want to cancel this appointment?');">Cancel</button>
                    </td>
                </form>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

</div> <!-- Closes the content div -->
</body>
</html>

