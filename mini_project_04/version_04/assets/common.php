<?php
/**
 * assets/common.php
 * Shared helpers used by multiple pages.
 * Goal: make your app behave the SAME as his, not fancier.
 * Notes:
 *  - We match his database schema (table/column names) exactly.
 *  - Where your pages expect nicer keys (e.g., first_name), we alias in SQL.
 *  - We keep responsibilities the same and avoid unnecessary changes.
 */

/////////////////////////////
// Session message helper //
/////////////////////////////

/**
 * user_message()
 * Reads a one-off message from $_SESSION["msg"], then clears it,
 * and returns a small HTML snippet your pages can echo.
 */
function user_message() {
    if (isset($_SESSION["msg"])) {                              // If a message was set earlier...
        $message = $_SESSION["msg"];                            // ...capture it
        unset($_SESSION["msg"]);                                // ...and clear it so it doesn't repeat
        return "<div class='message'>{$message}</div>";         // ...return HTML the page can echo
    }
    return "";                                                  // No message => empty string
}


////////////////////////
// AUDIT LOG WRITING //
////////////////////////

/**
 * audit_write($userid, $code, $long_desc)
 * Writes a single audit record to the `audit` table (his schema).
 * Columns (per his SQL): auditid, date (DATETIME), userid (INT), code (TEXT), auditdescrip (TEXT)
 * We keep your signature the same but map names correctly.
 */
function audit_write($userid, $code, $long_desc) {
    require_once 'dbconn.php';                                  // Get the DB connector
    try {
        $conn = dbconnect_insert();                             // Open a write-capable connection

        // Basic validation (same spirit as before, just clearer wording)
        if (!is_numeric($userid) || $userid <= 0) {
            throw new Exception("Audit user id is missing or invalid.");
        }
        if ($code === '' || $long_desc === '') {
            throw new Exception("Audit code/description cannot be empty.");
        }

        // Match his schema exactly: table `audit`, columns: date, userid, code, auditdescrip
        $sql = "INSERT INTO audit (`date`, `userid`, `code`, `auditdescrip`) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        $date = date('Y-m-d H:i:s');                            // DATETIME string (his schema expects DATETIME)

        $stmt->bindParam(1, $date,    PDO::PARAM_STR);          // Bind the formatted datetime
        $stmt->bindParam(2, $userid,  PDO::PARAM_INT);          // Bind the user id
        $stmt->bindParam(3, $code,    PDO::PARAM_STR);          // Bind the short code (e.g., REG, LGI, UPB)
        $stmt->bindParam(4, $long_desc, PDO::PARAM_STR);        // Bind the long description

        $stmt->execute();                                       // Write the row
        $conn = null;                                           // Close connection
        return true;                                            // Success
    } catch (Exception $e) {
        error_log("Audit Error: " . $e->getMessage());          // Log the reason (kept quiet to user)
        return false;                                           // Signal failure to caller
    }
}


//////////////////////////
// STAFF LIST GETTER   //
//////////////////////////

/**
 * staff_getter($conn)
 * Returns all bookable staff (exclude admin), sorted like his.
 * DB columns are: staffid, role, email, password, fname, sname, room
 * Your pages expect keys: staff_id, role, first_name, last_name, room
 * => We alias to preserve your page code (no HTML changes needed).
 */
function staff_getter($conn){
    $sql = "SELECT 
                staffid   AS staff_id,        -- alias to match your page code
                role,
                fname     AS first_name,      -- alias: fname -> first_name
                sname     AS last_name,       -- alias: sname -> last_name
                room
            FROM staff
            WHERE role != ?                   -- exclude admin role (not bookable)
            ORDER BY role DESC";              // same ordering behavior as his
    $stmt = $conn->prepare($sql);
    $exclude_role = "adm";                     // admin role code in his schema
    $stmt->bindParam(1, $exclude_role);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $conn = null;                              // Close the connection (your style)
    return $result;                            // Return the rows (empty array is fine)
}


/////////////////////////////////////
// CREATE A NEW APPOINTMENT (BOOK) //
/////////////////////////////////////

/**
 * commit_booking($conn, $epoch)
 * Creates a new row in `book` using his columns:
 *   userid, staffid, appointmentdate, bookedon, status
 * Your form fields: $_POST["staff"] holds the staff id (in v04).
 * We keep your behavior, just map columns correctly and set status 'BKD'.
 */
function commit_booking($conn, $epoch){
    $sql = "INSERT INTO book (userid, staffid, appointmentdate, bookedon, status)
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(1, $_SESSION["user_id"]);                  // Logged-in user making the booking
    $stmt->bindParam(2, $_POST["staff"]);                       // Staff to see
    $stmt->bindParam(3, $epoch);                                // Appointment time (epoch)
    $booked_on = time();                                        // When the booking was made
    $stmt->bindParam(4, $booked_on);
    $status = "BKD";                                            // Same status semantics as his
    $stmt->bindParam(5, $status);
    $stmt->execute();
    $conn = null;
    return true;
}


///////////////////////////////////////////////////////////
// GET ALL BOOKINGS FOR THE CURRENT (LOGGED-IN) USER     //
///////////////////////////////////////////////////////////

/**
 * appt_getter($conn)
 * Returns upcoming bookings joined with staff info, same shape your pages use.
 * We alias column names to what your HTML expects:
 *   - book_id, appointment_date, booked_on, status
 *   - role, first_name, last_name, room, staff_id
 */
function appt_getter($conn){
    $sql = "SELECT 
                b.bookid          AS book_id,           -- alias for page code
                b.appointmentdate AS appointment_date,  -- alias epoch -> appointment_date
                b.bookedon        AS booked_on,         -- alias epoch -> booked_on
                b.status,
                s.role,
                s.fname           AS first_name,        -- alias
                s.sname           AS last_name,         -- alias
                s.room,
                s.staffid         AS staff_id           -- alias
            FROM book b 
            JOIN staff s ON b.staffid = s.staffid 
            WHERE b.userid = ?
            ORDER BY b.appointmentdate ASC";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(1, $_SESSION["user_id"]);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $conn = null;
    return $result ?: false;                                   // Keep your original truthy/false behavior
}


/////////////////////////////
// CANCEL AN APPOINTMENT  //
/////////////////////////////

/**
 * appt_cancel($conn, $book_id, $user_id)
 * Deletes a booking **only if** it belongs to the current user (safety).
 * (His version isn’t strict everywhere; we make it safe without changing UX.)
 */
function appt_cancel($conn, $book_id, $user_id) {
    $sql = "DELETE FROM book WHERE bookid = ? AND userid = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(1, $book_id);
    $stmt->bindParam(2, $user_id);
    $stmt->execute();
    $conn = null;
    return true;
}


/////////////////////////////////////////////
// FETCH A SINGLE APPOINTMENT (FOR EDIT)   //
/////////////////////////////////////////////

/**
 * appt_fetch($conn, $book_id, $user_id)
 * Returns one appointment row (only if owned by the user).
 * Aliases columns so your edit form can prefill without changing HTML:
 *   appointment_date (epoch), staff_id
 */
function appt_fetch($conn, $book_id, $user_id) {
    $sql = "SELECT 
                bookid            AS book_id,
                userid,
                staffid           AS staff_id,
                appointmentdate   AS appointment_date,
                bookedon          AS booked_on,
                status
            FROM book
            WHERE bookid = ? AND userid = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(1, $book_id);
    $stmt->bindParam(2, $user_id);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $conn = null;
    return $result ?: false;                                    // Exactly your contract
}


///////////////////////////////////////
// UPDATE AN EXISTING APPOINTMENT    //
///////////////////////////////////////

/**
 * appt_update($conn, $book_id, $user_id, $staff_id, $epoch_time)
 * Updates the clinician and time for one appointment if the booking is owned by the user.
 * Matches your v04/alterbooking.php call signature.
 */
function appt_update($conn, $book_id, $user_id, $staff_id, $epoch_time) {
    $sql = "UPDATE book 
            SET staffid = ?, appointmentdate = ?
            WHERE bookid = ? AND userid = ?";
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
