<?php
session_start();
require_once 'assets/common.php';
require_once 'assets/dbconn.php';
if (!isset($_SESSION['user_id'])) {
    $_SESSION['msg'] = "You must log in first to see your bookings.";
    header("Location: login.php");
    exit;
}
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $book_id = $_POST['book_id'] ?? 0;
    $user_id = $_SESSION['user_id'];
    $action = $_POST['action'] ?? '';
    try {
        if ($action == 'delete' && $book_id > 0) {
            appt_cancel(dbconnect_insert(), $book_id, $user_id);
            audit_write($user_id, 'BOOK_CANCEL', "User cancelled booking ID: $book_id");
            $_SESSION['msg'] = "SUCCESS: Your appointment has been cancelled.";
        } elseif ($action == 'change' && $book_id > 0) {
            $_SESSION['edit_book_id'] = $book_id;
            header("Location: alterbooking.php");
            exit;
        }
    } catch (Exception $e) {
        $_SESSION['msg'] = "ERROR: " . $e->getMessage();
    }
    header("Location: bookings.php");
    exit;
}
$message = user_message();
try {
    $appts = appt_getter(dbconnect_insert());
} catch (Exception $e) {
    $appts = false;
    $_SESSION['msg'] = "ERROR: Could not load appointments. " . $e->getMessage();
    $message = user_message();
}
echo "<!DOCTYPE html>";
echo "<html>";
echo "<head>";
echo "    <title>My Bookings - Primary Oaks</title>";
echo "    <link rel='stylesheet' href='css/styles.css'>";
echo "</head>";
echo "<body>";
require_once 'assets/topbar.php';
require_once 'assets/nav.php';
require_once 'assets/content.php';
echo "<h1>My Bookings</h1>";
echo "<hr class='divider'>";
echo $message;
echo "<p class='content'>Below are your upcoming appointments.</p>";
if (!$appts) {
    echo "<p>You have no appointments booked.</p>";
} else {
    echo "<table class='appointment-table'>";
    echo "    <thead>";
    echo "    <tr>";
    echo "        <th>Appointment Date</th>";
    echo "        <th>Clinician</th>";
    echo "        <th>Room</th>";
    echo "        <th class='action-cell'>Actions</th>";
    echo "    </tr>";
    echo "    </thead>";
    echo "    <tbody>";
    foreach ($appts as $appt) {
        if ($appt['role'] == "doc") { $role = "Doctor"; }
        else if ($appt['role'] == "nur") { $role = "Nurse"; }
        else { $role = "Clinician"; }
        echo "        <tr>";
        echo "            <form action='bookings.php' method='POST'>";
        echo "                <td>" . date('M d, Y @ h:i A', $appt['appointment_date']) . "</td>";
        echo "                <td>" . htmlspecialchars($role . " " . $appt['first_name'] . " " . $appt['last_name']) . "</td>";
        echo "                <td>" . htmlspecialchars($appt['room']) . "</td>";
        echo "                <td class='action-cell'>";
        echo "                    <input type='hidden' name='book_id' value='" . $appt['book_id'] . "'>";
        echo "                    <button type='submit' name='action' value='change' class='action-btn btn-change'>Change</button>";
        echo "                    <button type='submit' name='action' value='delete' class='action-btn btn-delete' onclick='return confirm(\"Are you sure you want to cancel this appointment?\");'>Cancel</button>";
        echo "                </td>";
        echo "            </form>";
        echo "        </tr>";
    }
    echo "    </tbody>";
    echo "</table>";
}
echo "</div>";
echo "</body>";
echo "</html>";