<?php
session_start();
require_once "assets/common.php";
require_once "assets/dbconn.php";
if (!isset($_SESSION['user_id'])) {
    $_SESSION['msg'] = "You must log in to book an appointment.";
    header("Location: login.php");
    exit;
} elseif($_SERVER["REQUEST_METHOD"] === "POST") {
    $tmp = $_POST["appt_date"] . ' ' . $_POST["appt_time"];
    $epoch_time = strtotime($tmp);
    if(appt_update(dbconnect_insert(), $_SESSION['apptid'], $epoch_time, $tmp)){
        $_SESSION['usermessage'] = "Appointment booked successfully.";
        unset($_SESSION['apptid']);
    }
}
if (!isset($_SESSION['edit_book_id'])) {
    $_SESSION['msg'] = "ERROR: No appointment selected to edit.";
    unset($_SESSION['apptid']);
    header("Location: bookings.php");
    exit;
}
$book_id_to_edit = $_SESSION['edit_book_id'];
$user_id = $_SESSION['user_id'];
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $new_date = $_POST["appt_date"];
        $new_time = $_POST["appt_time"];
        $new_staff_id = $_POST["staff"];
        $tmp = $new_date . ' ' . $new_time;
        $new_epoch_time = strtotime($tmp);
        if ($new_epoch_time === false || $new_epoch_time < time()) {
            $_SESSION['msg'] = "ERROR: Please select a valid date and time in the future.";
        } else {
            if (appt_update(dbconnect_insert(), $book_id_to_edit, $user_id, $new_staff_id, $new_epoch_time)) {
                audit_write($user_id, 'BOOK_UPDATE', "User updated booking ID: $book_id_to_edit");
                unset($_SESSION['edit_book_id']);
                $_SESSION['msg'] = "SUCCESS: Your booking has been updated!";
                header("location: bookings.php");
                exit;
            } else {
                $_SESSION['msg'] = "ERROR: Booking update failed!";
            }
        }
    } catch (Exception $e) {
        $_SESSION['msg'] = "ERROR: " . $e->getMessage();
    }
    header("Location: alterbooking.php");
    exit;
}
try {
    $staff_list = staff_getter(dbconnect_insert());
    $appt = appt_fetch(dbconnect_insert(), $book_id_to_edit, $user_id);
    if (!$appt) {
        unset($_SESSION['edit_book_id']);
        $_SESSION['msg'] = "ERROR: Could not find that appointment.";
        header("Location: bookings.php");
        exit;
    }
    $appt_date_val = date('Y-m-d', $appt['appointment_date']);
    $appt_time_val = date('H:i', $appt['appointment_date']);
    $appt_staff_id = $appt['staff_id'];
} catch (Exception $e) {
    $staff_list = [];
    $_SESSION['msg'] = "ERROR: Could not load data. " . $e->getMessage();
}
$message = user_message();
echo "<!DOCTYPE html>";
echo "<html>";
echo "<head>";
echo "    <title>Change Appointment - Primary Oaks</title>";
echo "    <link rel='stylesheet' href='css/styles.css'>";
echo "</head>";
echo "<body>";
require_once 'assets/topbar.php';
require_once 'assets/nav.php';
require_once 'assets/content.php';
echo "<h1>Change Your Appointment</h1>";
echo "<hr class='divider'>";
echo $message;
echo "<form action='alterbooking.php' method='POST'>";
echo "    <label for='appt_date'>Appointment Date:</label><br>";
echo "    <input type='date' id='appt_date' name='appt_date' value='" . htmlspecialchars($appt_date_val) . "' required><br><br>";
echo "    <label for='appt_time'>Appointment Time:</label><br>";
echo "    <input type='time' id='appt_time' name='appt_time' step='600' value='" . htmlspecialchars($appt_time_val) . "' required><br><br>";
echo "    <label for='staff'>Select Clinician:</label><br>";
echo "    <select id='staff' name='staff' required>";
echo "        <option value=''>--Please select a staff member--</option>";
foreach ($staff_list as $staff_member) {
    if ($staff_member['role'] == "doc") { $role = "Doctor"; }
    else if ($staff_member['role'] == "nur") { $role = "Nurse"; }
    else { $role = "Clinician"; }
    $selected = ($staff_member['staff_id'] == $appt_staff_id) ? 'selected' : '';
    echo "        <option value='" . htmlspecialchars($staff_member['staff_id']) . "' " . $selected . ">";
    echo "            " . htmlspecialchars($role . ' ' . $staff_member['first_name'] . ' ' . $staff_member['last_name'] . ' (Room ' . $staff_member['room'] . ')');
    echo "        </option>";
}
echo "    </select><br><br>";
echo "    <button type='submit'>Update Appointment</button>";
echo "    <a href='bookings.php' class='cancel-link'>Cancel</a>";
echo "</form>";
echo "</div>";
echo "</body>";
echo "</html>";