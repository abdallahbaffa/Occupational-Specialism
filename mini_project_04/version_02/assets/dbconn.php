<?php
/**
 * Creates a PDO connection for INSERT operations.
 * @return PDO The database connection object.
 */
function dbconnect_insert(){
    $servername = "localhost";
    $dbusername = "root";
    $dbpassword = "";
    $dbname = "primary_oaks"; // Ensure this database exists

    try {
        $conn = new PDO("mysql:host=$servername;port=3306;dbname=$dbname", $dbusername, $dbpassword);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch(PDOException $e) {
        error_log("Database error in dbconnect_insert: " . $e->getMessage());
        $_SESSION['usermessage'] = "Database Error: " . $e->getMessage();
        header("Location: index.php");
        exit;
    }
}

/**
 * Creates a PDO connection for SELECT operations.
 * @return PDO The database connection object.
 */
function dbconnect_select(){
    $servername = "localhost";
    $dbusername = "root";
    $dbpassword = "";
    $dbname = "primary_oaks"; // Ensure this database exists

    try {
        $conn = new PDO("mysql:host=$servername;port=3306;dbname=$dbname", $dbusername, $dbpassword);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch(PDOException $e) {
        error_log("Database error in dbconnect_select: " . $e->getMessage());
        $_SESSION['usermessage'] = "Database Error: " . $e->getMessage();
        header("Location: index.php");
        exit;
    }
}

/**
 * Creates a PDO connection for UPDATE operations.
 * @return PDO The database connection object.
 */
function dbconnect_update(){
    $servername = "localhost";
    $dbusername = "root";
    $dbpassword = "";
    $dbname = "primary_oaks"; // Ensure this database exists

    try {
        $conn = new PDO("mysql:host=$servername;port=3306;dbname=$dbname", $dbusername, $dbpassword);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch(PDOException $e) {
        error_log("Database error in dbconnect_update: " . $e->getMessage());
        $_SESSION['usermessage'] = "Database Error: " . $e->getMessage();
        header("Location: index.php");
        exit;
    }
}

/**
 * Creates a PDO connection for DELETE operations.
 * @return PDO The database connection object.
 */
function dbconnect_delete(){
    $servername = "localhost";
    $dbusername = "root";
    $dbpassword = "";
    $dbname = "primary_oaks"; // Ensure this database exists

    try {
        $conn = new PDO("mysql:host=$servername;port=3306;dbname=$dbname", $dbusername, $dbpassword);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch(PDOException $e) {
        error_log("Database error in dbconnect_delete: " . $e->getMessage());
        $_SESSION['usermessage'] = "Database Error: " . $e->getMessage();
        header("Location: index.php");
        exit;
    }
}
?>