<?php

function dbconnect_insert(){
    $servername = "localhost";

    $dbusername = "gconsoleinsert";

    $dbpassword = "password1G";

    $dbname = "gconsole";
} /*THESE THINGS SHOULD NOT BE STORED IN PLAIN TEXT!! VERY INSECURE!!*/

try {
    $conn = new PDO("mysql:host=$servername;port=3306;dbname=$dbname", $dbusername, $dbpassword); /*PHP Data Object is what PDO means, and MySQLI is another type of connection of the database if wanted. But using the latter is kinda being deappricated and pushed out, but with the PDO it will conect to any timype of data sources form one command set, so if migrated data system, all that has ot be changed is something in the port so we can re-connect.*/
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); /*sets error modes*/
    return $conn;
} catch(PDOException $e) {
    error_log("Database error in super_checker: " . $e->getMessage()); /*If attempt to connect does not work, then it is taken into varibale e and that outputs the rror.*/

    throw $e; /*Outputs the error*/
}