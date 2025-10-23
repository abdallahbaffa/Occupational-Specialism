<?php
// assets/common.php
// This file contains common functions used across the application.

// Function to handle session-based messages (existing).
function user_message() {
    if (isset($_SESSION["msg"])) {
        $message = $_SESSION["msg"];
        unset($_SESSION["msg"]);
        return "<div class='message'>{$message}</div>";
    }
    return "";
}


// Function to log an event to the audits table (existing).
function audit_write($userid, $code, $long_desc) {
    require_once 'dbconn.php';
    try {
        $conn = dbconnect_insert();

        if (!is_numeric($userid) || $userid < 0 || empty($code) || empty($long_desc)) {
            throw new Exception("Audit data missing or invalid.");
        }

        $sql = "INSERT INTO audits (user_id, date, code, long_desc) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $date = date('Y-m-d H:i:s');

        $stmt->bindParam(1, $userid, PDO::PARAM_INT);
        $stmt->bindParam(2, $date, PDO::PARAM_STR);
        $stmt->bindParam(3, $code, PDO::PARAM_STR);
        $stmt->bindParam(4, $long_desc, PDO::PARAM_STR);
        $stmt->execute();
        $conn = null;
        return true;
    } catch (Exception $e) {
        error_log("Audit Error: " . $e->getMessage());
        return false;
    }
}

// Function to get staff list (existing).
function staff_getter($conn){
    $sql = "SELECT staff_id, role, first_name, last_name, room FROM staff WHERE role != ? ORDER BY role DESC";
    $stmt = $conn->prepare($sql);
    $exclude_role = "adm";
    $stmt->bindParam(1, $exclude_role);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $conn = null;
    return $result;
}

// Function to create a new booking (existing).
function commit_booking($conn, $epoch){
    $sql = "INSERT INTO book (user_id, staff_id, appointment_date, booked_on) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(1, $_SESSION["user_id"]);
    $stmt->bindParam(2, $_POST["staff"]);
    $stmt->bindParam(3, $epoch);
    $stmt->bindParam(4, time());
    $stmt->execute();
    $conn = null;
    return true;
}

// Function to get all bookings for the logged-in user (existing).
function appt_getter($conn){
    $sql = "SELECT b.book_id, b.appointment_date, b.booked_on, s.role, s.first_name, s.last_name, s.room, s.staff_id 
            FROM book b 
            JOIN staff s ON b.staff_id = s.staff_id 
            WHERE b.user_id = ? 
            ORDER BY b.appointment_date ASC";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(1, $_SESSION["user_id"]);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $conn = null;
    return $result ?: false;
}

// --- NEW FUNCTIONS FOR ALTER/CANCEL ---

/**
 * NEW: Cancels (deletes) an appointment.
 * Security: It also checks the user_id to make sure you can only cancel your own appointments.
 */
function cancel_appt($conn, $book_id, $user_id) {
    $sql = "DELETE FROM book WHERE book_id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(1, $book_id);
    $stmt->bindParam(2, $user_id);
    $stmt->execute();
    $conn = null;
    return true;
}

/**
 * NEW: Fetches the details for a SINGLE appointment.
 * Used to pre-fill the "alterbooking.php" form.
 * Security: Also checks user_id.
 */
function appt_fetch($conn, $book_id, $user_id) {
    $sql = "SELECT * FROM book WHERE book_id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(1, $book_id);
    $stmt->bindParam(2, $user_id);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $conn = null;
    return $result ?: false; // Return the appointment data or false if not found
}

/**
 * NEW: Updates an existing appointment.
 * Security: Also checks user_id.
 */
function update_appt($conn, $book_id, $user_id, $staff_id, $epoch_time) {
    $sql = "UPDATE book SET staff_id = ?, appointment_date = ? WHERE book_id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(1, $staff_id);
    $stmt->bindParam(2, $epoch_time);
    $stmt->bindParam(3, $book_id);
    $stmt->bindParam(4, $user_id);
    $stmt->execute();
    $conn = null;
    return true;
}

?>

