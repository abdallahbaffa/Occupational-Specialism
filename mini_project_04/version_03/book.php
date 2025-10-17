<?php

session_start();
require_once "../assets/common.php";
require_once "../assets/dbconn.php";

if($_SERVER["REQUEST_METHOD"] == "POST") { //Should always be on top, rest of the page should not load before this happens.

    try {

        $tmp = $_POST["appt_date"] . ' ' . $_POST["appt_time"]; //Don't try to pass stuff like that, because server overload chances Get in proper format first
        $epoch_time = strtotime($tmp); //Then pass the format as the parameter.
        if(commit_booking(dbconnect_insert(), $epoch_time)) {
            $_SESSION['usermessage'] = "SUCCESS: YOUR Booking hs been made!";
            header("location: book.php");
            exit;
        } else {
            $_SESSION['usermessage'] = "ERROR: Booking has failed!";
        }

    } catch (PDOException $e) {
        $_SESSION['usermessage'] = "ERROR: " . $e->getMessage();
    } catch (Exception $e) {
        $_SESSION['usermessage'] = "ERROR: " . $e->getMessage();
    }
}

    $tmp = $_POST["appt_date"]. ' ' . $_POST["appt_time"]; //Don't try to pass stuff like that, because server overload chances Get in proper format first
    $epoch_time = strtotime($tmp); //Then pass the format as the parameter.

    echo $epoch_time;
    echo time();

    try{
        book_appointment(dbconnect_insert());
        $_SESSION['usermessage'] = "SUCCESS: You have booked your appointment.". $_POST['date']. " at ". $_POST['time'];
        header("location: book.php");
        exit;
    } catch(PDOException $e){
        $_SESSION['usermessage'] = $e->getMessage();
        header("location: book.php");
        exit;
    } catch(Exception $e){
        $_SESSION['usermessage'] = $e->getMessage();
        header("location: book.php");
        exit;
}

echo "<!DOCTYPE html>";

echo "<html>";

    echo "<head>";

    echo "<title>Version 3</title>";
    echo "<link rel='stylesheet' type='text/css' href='/style.css'/>";

echo "</head>";

echo "<body>";

echo "<div class='container'>";

require_once "assets/topbar.php";

require_once "assets/nav.php";

echo

$staff = staff_getter(dbconnect_insert());

echo "<label for ='appt_time'> Appointment Time: </label>";
echo "<input type='time' name='appt_time' required>";

echo "<br>";
echo "<label for ='appt_date'> Appointment Date: </label>";
echo "<input type='date' name='appt_date' required>";

echo "<input type='time' name='appt_time' step='600'>";

echo "<select name='staff'>";

foreach ($staff as $staff) {

    if ($staff['role'] = "doc") {
        $role = "Doctor";
    } else if ($staff['role'] = "nur") {
        $role = "Nurse";
    }


}