<?php
function dbconnect_insert(){
    $servername = "localhost"; //sets servername
    $dbusername = "root"; //sets database username
    $dbpassword = ""; //password for database useraccount
    $dbname = "primary_oaks"; //database name to connect to
    try { //attempt this block of code, catching an error
        $conn = new PDO("mysql:host=$servername;port=3306;dbname=$dbname", $dbusername, $dbpassword); // creates a PDO connection to the database
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //sets error modes
        return $conn;
    } catch(PDOException $e) { //catch statement if it fails
        error_log("Database error in dbconnect_insert: " . $e->getMessage());
        $_SESSION['usermessage'] = "Database Error: " . $e->getMessage();
        header("Location: index.php");
        exit; // outputs the error
    }
}
function dbconnect_select(){
    $servername = "localhost"; //sets servername
    $dbusername = "root"; //sets database username
    $dbpassword = ""; //password for database useraccount
    $dbname = "primary_oaks"; //database name to connect to
    try { //attempt this block of code, catching an error
        $conn = new PDO("mysql:host=$servername;port=3306;dbname=$dbname", $dbusername, $dbpassword); // creates a PDO connection to the database
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //sets error modes
        return $conn;
    } catch(PDOException $e) { //catch statement if it fails
        error_log("Database error in dbconnect_select: " . $e->getMessage());
        $_SESSION['usermessage'] = "Database Error: " . $e->getMessage();
        header("Location: index.php");
        exit; // outputs the error
    }
}
?>