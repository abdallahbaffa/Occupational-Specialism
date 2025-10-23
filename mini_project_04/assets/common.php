<?php
// assets/common.php
// This file contains common functions used across the application.

// Function to handle session-based messages (existing)
function user_message() {
    if (isset($_SESSION["msg"])) {
        $message = $_SESSION["msg"];
        unset($_SESSION["msg"]);
        // Add some basic HTML formatting for the message if needed
        // return "<div class='message'>" . htmlspecialchars($message, ENT_QUOTES, 'UTF-8') . "</div>";
        return "<div class='message'>{$message}</div>"; // Using echo'd version from original register.php
    }
    return "";
}

// New function: audit_write - Logs an event to the audits table (Adapted from gconsole)
function audit_write($userid, $code, $long_desc) {
    // Requires the database connection function (path is relative to common.php location: assets/)
    require_once 'dbconn.php';

    try {
        // Get database connection using your existing function
        $conn = dbconnect_insert(); // Calls your function from dbconn.php

        // Checks if the required user ID is numeric/valid and if code/desc are not empty.
        // gconsole version used $userid <= 0, we'll use $userid < 0 as 0 is valid for attempts before login
        if (!is_numeric($userid) || $userid < 0 || empty($code) || empty($long_desc)) {
            // Throw a general exception if data is invalid BEFORE hitting the database
            throw new Exception("Audit data missing or invalid. UserID: $userid, Code: $code, Desc: $long_desc");
        }

        // 1. SQL Statement and Preparation (Same as gconsole)
        $sql = "INSERT INTO audits (user_id, date, code, long_desc) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        // 2. Date/Time Capture (gconsole used date('Y-m-d'), but we need DATETIME)
        $date = date('Y-m-d H:i:s'); // Use full datetime

        // 3. Binding Parameters (Same as gconsole, but using full datetime)
        $stmt->bindParam(1, $userid, PDO::PARAM_INT);
        $stmt->bindParam(2, $date, PDO::PARAM_STR); // Bind the full datetime string
        $stmt->bindParam(3, $code, PDO::PARAM_STR);
        $stmt->bindParam(4, $long_desc, PDO::PARAM_STR);

        // 4. Execution (Same as gconsole)
        $stmt->execute();

        // 5. Connection Management (Close the connection properly)
        $conn = null; // Close the connection properly

        return true; // Indicate success (optional, as exceptions are thrown on failure)

    } catch (PDOException $e) {
        // Handles database errors (e.g., table/column mismatch)
        error_log("Audit Database Error: " . $e->getMessage());
        // Optionally, throw an exception for the calling script to handle if needed.
        // For now, we'll just log it and return false/continue silently like before.
        // throw new Exception("Audit Database Error: " . $e->getMessage());
        return false; // Indicate failure
    } catch (Exception $e) {
        // Handles validation errors (thrown above) or other runtime errors
        error_log("Audit Runtime Error: " . $e->getMessage());
        // Optionally, throw an exception for the calling script to handle if needed.
        // For now, we'll just log it and return false/continue silently like before.
        // throw new Exception("Audit Runtime Error: " . $e->getMessage());
        return false; // Indicate failure
    }
}

function staff_getter($conn){
    //Function to get all the staff suitable for an appointment.

    $sql = "SELECT staffid, role, first_name, last_name, room FROM staff WHERE role != ? ORDER BY role DESC";
    //get all staff from database where role NOT equal to "adm" - this is admin role, none bookable.
    $stmt = $conn->prepare($sql);
    $exclude_role = "adm";

    $stmt->bindParam(1, $exclude_role);

    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC); //fetches every single record/row from our database tha matches, that is what "fetchALL" does, that match the conditions.
    $conn = null;
    return $result;
}


function commit_booking($conn, $epoch){
    $sql = "INSERT INTO book (userid, staffid, appountmentdate, bookedon) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql); //prepare to sql

    $stmt->bindParam(1, $_SESSION["userid"]); //bind parameters for security.
    //Hash the password.
    $stmt->bindParam(2, $_POST["staff"]);
    $stmt->bindParam(3, $epoch);
    $stmt->bindParam(4, time());

    $stmt->execute(); // run the query to insert.
    $conn = null; //closes the connection so cant be abused.
    return true; // Registration successful.
}


function appt_getter($conn){
    //function  to get all the staff suitable for an appointment

    $sql = "SELECT b.bookid, b.appointmentdate, b.bookedon, s.role, s.fname, s.sname, s.room FROM book b JOIN staff s ON b.staffid = s.staffid WHERE b.userid = ? ORDER BY b.appointmentdate ASC";
    $stmt = $conn->prepare($sql);

    $stmt->bindParam(1, $_SESSION["userid"]);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $conn = null;
    if($result){
        return $result;
    } else {
        return false;
    }
}

?>