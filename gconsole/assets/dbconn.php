<?php

function dbconnect_insert(){
    //the variables are defined locally inside the function.
    $servername = "localhost"; //sets servername.

    $dbusername = "root"; //this had to be changed, this variable name, as it fought against the admin reg and user reg.

    $dbpassword = ""; //password for database useraccount.

    $dbname = "gconsole"; //database name to connect to.

    /*THESE THINGS SHOULD NOT BE STORED IN PLAIN TEXT!! VERY INSECURE!!*/

    try {
        //the variables are used locally and correctly here.
        $conn = new PDO("mysql:host=$servername;port=3306;dbname=$dbname", $dbusername, $dbpassword); //creates a PDO connection to the database.
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        //return the connection object.
        return $conn;
    } catch(PDOException $e) {
        error_log("Database error in super_checker: " . $e->getMessage());
        throw $e; //re-throw the exception //outputs the error.
    }
}

// !!! IMPORTANT: You must call the function to get the connection
// Example of calling the function later in your script:
// $connection = dbconnect_insert();
?>