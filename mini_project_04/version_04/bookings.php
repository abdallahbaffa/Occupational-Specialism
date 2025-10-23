<?php

session_start();

require_once '../assets/common.php';
require_once '../assets/dbconn.php';

if (!isset($_SESSION['user'])) {
    $_SESSION['msg'] = "You must log in first";
}


echo usermessage();

echo "<p class='content'>Below are your bookings </p>";
$appts = appt_getter(dbconnect_select());
if (!$appts){
    echo "No appts found";
} else {
    echo "<table id = 'bookings'>";

    foreach ($appts as $appt) {
        if ($appt['role'] = "doc") {
            $role = "Doctor";
        } else if ($appt['role'] = "nur") {
            $role = "Nurse";
        }
    }

    echo "<tr>";
    echo "<td> Date:" . date('M d, Y @ h:i A', $appt['appointmentdate']) . "</td>"; //It is an epoch time formatting.
    echo "<td> Made on: " . date('M d, Y @ h:i A', $appt['bookedon']) . "</td>";
    echo "<td> With: " . $role . " " . $appt['first_name'] . " " . $appt['last_name'] . "</td>";
    echo "<td> in: " . $appt['room'] . "</td>";
    echo "</tr>";

}

echo "</table>";