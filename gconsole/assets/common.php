<?php /*Common subroutines go here*/

function only_user($conn, $user_name)
{
    try {
        $sql = "SELECT user_name FROM users WHERE user_name = ?"; //Set up the sql statement
        $stmt = $conn->prepare($sql); //Prepares
        $stmt->bindParam(1, $user_name); // Binded so it can be more secure.
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

function new_console($conn, $post){
    try {
        $sql = "INSERT INTO consoles (manufacturer, console_name, release_date, controller_number, bit) VALUES(?, ?, ?, ?, ?)"; #Doing a prepared statement, telling the database that the values would be sent badned independately. If this approach was not done, it would be easier to sql inject your things, which is really bad.
        $stmt = $conn->prepare($sql); #Prepare to sql
        $stmt->bindParam(1, $post['manufacturer']); #Bind parameters for security
        $stmt->bindParam(2, $post['console_name']);
        $stmt->bindParam(3, $post['release_date']);
        $stmt->bindParam(4, $post['controller_number']);
        $stmt->bindParam(5, $post['bit']);
        $stmt->execute(); #Run the query to insert
        $conn = null; //Stops the connection. Should not be leaving open connections because it is not safe. Leaving an open active connection to your database.
    } catch (PDOException $e) {
        //Handle database errors
        error_log("Audit Database Error: " . $e->getMessage()); //log the error
        throw new exception("Audit Database Error: " . $e->getMessage()); //Throw exception for calling script to handle.
    } catch (Exception $e) {
        // handle validation or other errors,
        error_log("Auditing Error: " . $e->getMessage()); //log the error
        throw new exception("Auditing Error: " . $e->getMessage()); //Throw exception for calling script to handle.
    }
}

function user_message(){
    if(isset($_SESSION['usermessage'])){
        $message = "<p>". $_SESSION['usermessage'] ."</p>";
        unset($_SESSION['usermessage']);
        return $message;
    } else{
        $message = "";
        return $message;
    }
}

function reg_user($conn,$post){
    try {
        //Prepare and execute the SQL query.
        $sql = "INSERT INTO users (user_name, password, sign_up_date, date_of_birth, country) VALUES(?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql); //Prepare to SQL.
        $stmt->bindParam(1, $post['user_name']); //Bind parameters for security.
        $hpswd = password_hash($post['password'], PASSWORD_DEFAULT); //Hash the password.  //Using a pre-built library as part of PHP to because my development environment has no encryption available. I am using default encryption is because I don't have anything else built into my development environment, if this was a real scenario, I would use another encryption method like: [PASSWORD_BCRYPT] OR [PASSWORD_ARGON2I] to make the encryption even more secure.
        $stmt->bindParam(2, $hpswd);
        $stmt->bindParam(3, $post['sign_up_date']);
        $stmt->bindParam(4, $post['date_of_birth']);
        $stmt->bindParam(5, $post['country']);
        $stmt->execute(); //Run the query to insert.
        $conn = null;
        return true; //Registration successful.
    } catch (PDOException $e) {
        //Handle database errors.
        error_log("User Register Database error: " . $e->getMessage()); //Log the error.
        throw new exception("User Register Database Error: " . $e); //Throw exception for calling scripts.
    } catch (Exception $e) {
        //Handle validation or other errors.
        error_log("User Registration error: " . $e->getMessage()); //Log the error.
        throw new exception("User Registration error: " . $e->getMessage()); //Throw exception
    }
}

function login($conn, $usrname)
{
    try { //try this code, catch errors
        $sql = "SELECT user_id, password FROM users WHERE user_name = ?"; // set up the sql statement.
        $stmt = $conn->prepare($sql); //prepares
        $stmt->bindParam(1,$usrname); //binds the parameters to execute
        $stmt->execute(); //runs the sql code
        $result = $stmt->fetch(PDO::FETCH_ASSOC); //brings back results
        $conn = null; //nulls off the connection so can not be abused.
        if ($result) {
            return $result;
        } else {
            $_SESSION['usermessage'] = "User not found.";
            header("Location: login.php");
            exit; //stops further execution
        }
    } catch (Exception $e) {
        $_SESSION['usermessage'] = "User login" . $e->getMessage();
        header("Location: login.php");
        exit; //stops further execution
    }
}

function getnewuserid($conn, $email){
    $sql = "SELECT user_id FROM users WHERE user_name = ?";
    $stmt = $conn->prepare($sql); //prepares
    $stmt->bindParam(1, $email);
    $stmt->execute(); //runs the sql code
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $conn = null;
    return $result['user_id'];
}

function auditor($conn, $userid, $code, $long_desc)
{
    // *** Added: Use a try...catch block for professional error handling ***
    try {
        // *** Added: Input Validation ***
        // Checks if the required user ID is numeric/valid and if code/desc are not empty.
        if (!is_numeric($userid) || $userid <= 0 || empty($code) || empty($long_desc)) {
            // Throw a general exception if data is invalid BEFORE hitting the database
            throw new Exception("Audit data missing or invalid.");
        }
        // 1. SQL Statement and Preparation (Same as original)
        $sql = "INSERT INTO audits (user_id, date, code, long_desc) VALUES (?,?,?,?)";
        $stmt = $conn->prepare($sql);
        // 2. Date/Time Capture (Same as original)
        $date = date('Y-m-d');
        // 3. Binding Parameters (Same as original)
        $stmt->bindParam(1, $userid);
        $stmt->bindParam(2, $date);
        $stmt->bindParam(3, $code);
        $stmt->bindParam(4, $long_desc);
        // 4. Execution (Same as original)
        $stmt->execute();
        // 5. Connection Management (Fixed: Use $conn = null; instead of undefined close_connection())
        $conn = null; // Close the connection properly
        return true;
    } catch (PDOException $e) {
        // Handles database errors (e.g., table/column mismatch)
        error_log("Audit Database Error: " . $e->getMessage());
        throw new Exception("Audit Database Error: " . $e->getMessage());
    } catch (Exception $e) {
        // Handles validation errors (thrown above) or other runtime errors
        error_log("Audit Runtime Error: " . $e->getMessage());
        throw new Exception("Audit Runtime Error: " . $e->getMessage());
    }
}

?>