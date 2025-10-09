<?php /*Common subroutines go here*/

function new_console($conn, $post){
    try {
        $sql = "INSERT INTO consoles (manufacturer, console_name, release_date, controller_no, bit) VALUES(?, ?, ?, ?, ?)"; #Doing a prepared statement, telling the database that the values would be sent badned independately. If this approach was not done, it would be easier to sql inject your things, which is really bad.
        $stmt = $conn->prepare($sql); #Prepare to sql

        $stmt->bindParam(1, $post['manufacturer']); #Bind parameters for security
        $stmt->bindParam(2, $post['c_name']);
        $stmt->bindParam(3, $post['release']);
        $stmt->bindParam(4, $post['controller_no']);
        $stmt->bindParam(5, $post['bit']);

        $stmt->execute(); #Run the query to insert
        $conn = null; //Stops the connection. Should not be leaving open connections because it is not safe. Leaving an open active connection to your database.
    } catch (PDOException $e) {
        #Handles database errors
        error_log("Console Database Error: " . $e->getMessage());
        throw new exception("Database Error: " . $e->getMessage());
    }
}

function user_message(){
    if(isset($_SESSION['usermessage'])){
    $message = "<p>". $_SESSION['usermessage'] ."</p>";
    unset($_SESSION['usermessage']);
    return $message;
    }
    else {
        return "";
    }
}

function only_user($conn, $username)
{
    try {
        $sql = "SELECT username FROM users WHERE username = ?"; //Set up the sql statement
        $stmt = $conn->prepare($sql); //Prepares
        $stmt->bindParam(1, $username); // Binded so it can be more secure.
        $stmt->execute(); //Runs the sql code
        $result = $stmt->fetch(PDO::FETCH_ASSOC); //Brings back results.
        if ($result) {
            return true;
        } else {
            return false;
        }
    } catch (PDOException $e) { //Catch error
        //Log the error (crucial!)
        error_log("Database error in only_user: " . $e->getMessage());
        //Throw the exception.
        throw $e; //Re-throw the exception.
    }
}

function reg_user($conn,$post){
      try {
          //Prepare and execute the SQL query.
          $sql = "INSERT INTO users (username, password, signupdate, dob, country) VALUES(?, ?, ?, ?, ?)";
          $stmt = $conn->prepare($sql); //Prepare to SQL.

          $stmt->bindParam(1, $post['username']); //Bind parameters for security.
          $hpswd = password_hash($post['password'], PASSWORD_DEFAULT); //Hash the password.  //Using a pre-built library as part of PHP to because my development environment has no encryption available. I am using default encryption is because I don't have anything else built into my development environment, if this was a real scenario, I would use another encryption method like: [PASSWORD_BCRYPT] OR [PASSWORD_ARGON2I] to make the encryption even more secure.
          $stmt->bindParam(2, $hpswd);
          $stmt->bindParam(3, $post['signupdate']);
          $stmt->bindParam(4, $post['dob']);
          $stmt->bindParam(5, $post['country']);

          $stmt->execute(); //Run the query to insert.
          $conn = null;
          return true; //Registration successful.
      } catch (PDOException $e) {
        //Handle database errors.
          error_log("User Register Database error: " . $e->getMessage()); //Log the error.
          throw new exception("User Register Database Error: " . $e->getMessage()); //Throw exception for calling scripts.
      } catch (Exception $e) {
          //Handle validation or other errors.
          error_log("User Registration error: " . $e->getMessage()); //Log the error.
          throw new exception("User Registration error: " . $e->getMessage()); //Throw exception
      }
}


function login($conn, $usrname)
{
    try { //try this code, catch errors
        $sql = "SELECT user_id, password FROM users WHERE username = ?"; // set up the sql statement.
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(1, $usrname);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $conn = null;

        if ($result) {
            return $result;

        } else {
            $_SESSION['usermessage'] = "User not found.";
            header(header: "Location: index.php");
            exit;
        }

    } catch (Exception $e) {
        $_SESSION['usermessage'] = "User login" . $e->getMessage();
        header(header: "Location: login.php");
        exit;
    }
}


function auditor($conn, $userid, $code, $long_desc){
    $sql = "INSERT INTO audits (date, userid, code, long_desc) VALUES (?,?,?,?)";
    $stmt = $conn->prepare($sql); //prepare to sql
    $date = date(format:'Y-m-d'); //exact mysql date field that it needs and accepts
    $stmt->bindParam(1, $date); //bind parameters for security
    $stmt->bindParam(2, $userid);
    $stmt->bindParam(3, $code);
    $stmt->bindParam(4, $long_desc);
    
    $stmt->execute(); //run the query to insert
    $conn = null; //closes the connection so can not be abused
    return true; //registration successful
}


function getnewuserid($conn, $email){
    $sql = "SELECT user_id FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql); //prepares
    $stmt->bindParam(1, $email);
    $stmt->execute(); //runs the sql code
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['user_id'];
}