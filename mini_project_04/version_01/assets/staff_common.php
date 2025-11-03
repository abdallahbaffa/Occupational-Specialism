<?php
// assets/staff_common.php
// This file contains functions specifically for staff management.

/**
 * Displays and clears a session message.
 * We add this here so staff pages don't need to include the user 'common.php'
 */
function user_message() {
    if (isset($_SESSION["msg"])) {
        $message = $_SESSION["msg"];
        unset($_SESSION["msg"]);
        return "<div class='message'>{$message}</div>";
    }
    return "";
}

/**
 * Inserts a new staff member into the database.
 * Matches the columns in your 'staff' table.
 */
function staffreg_user($conn) {
    // SQL uses the columns from your primary_oaks.sql file
    $sql = "INSERT INTO staff (role, first_name, last_name, room) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    // Bind parameters from the POST data
    $stmt->bindParam(1, $_POST['role']);
    $stmt->bindParam(2, $_POST['first_name']);
    $stmt->bindParam(3, $_POST['last_name']);
    $stmt->bindParam(4, $_POST['room']);

    $stmt->execute();

    // Get the ID of the new staff member we just created
    $new_staff_id = $conn->lastInsertId();

    $conn = null;
    return $new_staff_id; // Return the new ID so we can audit it
}

/**
 * Logs an action to the 'staff_audits' table.
 * Matches your 'staff_common.php' and database schema.
 */
function staff_auditor($conn, $staffid, $code, $long_desc) {
    try {
        if (!is_numeric($staffid) || $staffid <= 0 || empty($code) || empty($long_desc)) {
            throw new Exception("Staff audit data missing or invalid.");
        }

        // SQL matches your 'staff_audits' table
        $sql = "INSERT INTO staff_audits (staff_id, date, code, long_desc) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        $date = date('Y-m-d H:i:s'); // Use full datetime

        $stmt->bindParam(1, $staffid);
        $stmt->bindParam(2, $date);
        $stmt->bindParam(3, $code);
        $stmt->bindParam(4, $long_desc);

        $stmt->execute();
        $conn = null;
        return true;

    } catch (Exception $e) {
        error_log("Staff Audit Error: " . $e->getMessage());
        return false;
    }
}
?>