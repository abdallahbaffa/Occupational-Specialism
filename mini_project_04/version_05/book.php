<?php // This open the php code section
session_start();  // connects to session to get important session data
require_once "assets/common.php";  // connects to the common functions
require_once "assets/dbconn.php";  // brings in the database connection

// Check if user is logged in
if (!isset($_SESSION['userid'])) {
    $_SESSION['usermessage'] = "ERROR: You are not logged in!";
    header("Location: login.php");
    exit;
}

// Handle form submission for booking
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate that a staff member was selected
    if (!isset($_POST['admin']) || empty($_POST['admin'])) {
        $_SESSION['usermessage'] = "ERROR: Please select a staff member.";
        header("Location: book.php");
        exit;
    }

    try {
        $tmp = $_POST["appt_date"] . ' ' . $_POST["appt_time"];
        $epoch_time = strtotime($tmp);

        if (commit_booking(dbconnect_insert(), $epoch_time)) {
            audtitor(dbconnect_insert(), $_SESSION['userid'], "APB", "Booked an appointment");
            $_SESSION['usermessage'] = "SUCCESS: Your booking has been made!";
            header("Location: bookings.php");
            exit;
        } else {
            $_SESSION['usermessage'] = "ERROR: Booking has failed!";
            header("Location: book.php");
            exit;
        }
    } catch (PDOException $e) {
        $_SESSION['usermessage'] = "ERROR: " . $e->getMessage();
        header("Location: book.php");
        exit;
    } catch (Exception $e) {
        $_SESSION['usermessage'] = "ERROR: " . $e->getMessage();
        header("Location: book.php");
        exit;
    }
}

echo "<!DOCTYPE html>";
echo "<html>";
echo "<head>";
echo "<title> Version 3</title>";
echo "<link rel='stylesheet' type='text/css' href='css/styles.css' />";
echo "</head>";
echo "<body>";
echo "<div class='container'>";
require_once "assets/topbar.php";
require_once "assets/nav.php";
echo "<div class='content'>";
echo "<br>";
echo "<h2> Primary Oaks - Appointment Booking System</h2>";
echo "<br>";
echo usermessage();
echo "<br>";

echo "<form action='' method='post'>";
echo "<label for='appt_time'> Appointment Time:</label>";
echo "<input type='time' name='appt_time' required>";
echo "<br>";

echo "<label for='appt_date'> Appointment Date:</label>";
echo "<input type='date' name='appt_date' required>";
echo "<br>";

echo "<select name='admin' required>"; // Added 'required' attribute
echo "<option value='' disabled selected>Select a Staff Member</option>"; // Default option

try {
    $staff = staf_geter(dbconnect_select());
} catch (PDOException $e) {
    echo "ERROR fetching staff: " . $e->getMessage();
    header("Location: index.php");
    exit;
} catch (Exception $e) {
    echo "ERROR fetching staff: " . $e->getMessage();
    header("Location: index.php");
    exit;
}

foreach ($staff as $staf) {
    // Use comparison operator (==) instead of assignment operator (=)
    if ($staf['role'] == "doc") {
        $role = "Doctor";
    } else if ($staf['role'] == "nur") {
        $role = "Nurse";
    } else {
        // Skip staff members who are not doctors or nurses (e.g., admins)
        continue;
    }
    // Corrected the HTML for the option tag - added quotes around the value
    echo "<option value='" . $staf['staffid'] . "'>" . $role . " " . $staf['sname'] . " " . $staf['fname'] . " Room " . $staf['room'] . "</option>";
}
echo "</select>";
echo "<br>";

echo "<input type='submit' name='submit' value='Book Appointment' />";
echo "</form>";

echo "</div>";
echo "</div>";
echo "</body>";
echo "</html>";