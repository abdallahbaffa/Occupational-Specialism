<?php
/**
 * assets/dbconn.php
 * Central PDO connector. Matches his DB name and keeps behavior simple.
 * No echoes here (library file). Clean, reliable, and identical for read/write.
 */

function dbconnect_read() {
    // Connect for SELECTs (same settings as write; separated for clarity)
    return _dbconnect_core();
}

function dbconnect_insert() {
    // Connect for INSERT/UPDATE/DELETE
    return _dbconnect_core();
}

function _dbconnect_core() {
    // --- Adjust only if your credentials differ ---
    $host    = 'localhost';         // DB host
    $dbname  = 'primaryoaks';       // IMPORTANT: matches his schema name
    $user    = 'root';              // Your MySQL username
    $pass    = '';                  // Your MySQL password (if any)
    $charset = 'utf8mb4';           // Safe default charset

    // PDO DSN string (MySQL)
    $dsn = "mysql:host={$host};dbname={$dbname};charset={$charset}";

    // PDO options for consistent, safe behavior
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Throw exceptions on errors
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Return rows as assoc arrays
        PDO::ATTR_EMULATE_PREPARES   => false,                  // Use native prepares when possible
    ];

    // Create and return the PDO instance
    return new PDO($dsn, $user, $pass, $options);
}
