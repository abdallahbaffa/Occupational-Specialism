<?php

session_start();
require_once "../assets/common.php";
require_once "../assets/dbconn.php";

if($_SERVER["REQUEST_METHOD"] == "POST"){
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

$staff_getter();


echo "<input type='time' name='appt_time' step='600'>";

echo "<select name='staff'>";

foreach ($staff as $staff) {

    if ($staff['role'] = "doc"){
        $role = "Doctor";

    }
}