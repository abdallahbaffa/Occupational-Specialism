<?php /*Common subroutines go here*/

function new_console($conn, $post){
    try {
        $sql = "INSERT INTO console (manufacturer, c_name, release_date, controller_no, bit) VALUES(?, ?, ?, ?, ?)"; #Doing a prepared statementt, telling the database that the values would be sent badned independately. If this appraoch was not done, it would be easier to sql inject your things, which is really bad.
        $stmt = $conn->prepare($sql); #prepare to sql

        $stmt->bindParam(1, $post['manufacturer']); #bind parameters for security
        $stmt->bindParam(2, $post['c_name']);
        $stmt->bindParam(3, $post['release']);
        $stmt->bindParam(4, $post['controller_no']);
        $stmt->bindParam(5, $post['bit']);


        $stmt->execute(); #run the query to insert
        $conn = null;
    } catch (PDOException $e) {
        #handles database errors
        error_log("Console Database Error: " . $e->getMessage());
        throw new exception("Database Error: " . $e->getMessage());
    }
}

function user_message(){
    if(isset($_SESSION['user_message'])){}
    $message = "<p>". $_SESSION['user_message'] ."</p>";
}