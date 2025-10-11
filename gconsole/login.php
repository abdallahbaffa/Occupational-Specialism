<?php // This open the php code section

session_start();
require_once "assets/dbconn.php";
require_once "assets/common.php";

// Check if user is already logged in
if (isset($_SESSION['user'])) {
    $_SESSION['usermessage'] = "ERROR: You are already logged in!";
    header("Location: index.php");
    exit; // Stop further execution
}

// Check if the form was submitted via POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Connect to the database and attempt to fetch user data
    $usr = login(dbconnect_insert(), $_POST["username"]);

    // Verify the password if user data was found
    if ($usr && password_verify($_POST["password"], $usr["password"])) { // verifies the password is matched
        $_SESSION["user"] = true;  // sets up the session variables
        $_SESSION["userid"] = $usr["user_id"];
        $_SESSION['usermessage'] = "SUCCESS: User Successfully Logged In";
        // Assuming you have an audtitor function similar to the original, adjust the call accordingly.
        // audtitor(dbconnect_insert(),$_SESSION["userid"],"log", "User has successfully logged in");
        header("location:index.php");  //redirect on success
        exit;
    } else {
        // Password verification failed or user not found
        $_SESSION['usermessage'] = "ERROR: User login failed. Username or password incorrect.";
        // Optional: Log failed login attempt if user_id was retrieved
        // if($usr && isset($usr["user_id"])) {
        //     audtitor(dbconnect_insert(), $usr["user_id"], "flo", "User has unsuccessfully logged in");
        // }
        header("Location: login.php");
        exit; // Stop further execution
    }
}

// Handle messages passed via URL (e.g., from logout)
if (!isset($_GET["message"])) {
    $message = false;
} else {
    // Decode the message for display.
    $message = htmlspecialchars(urldecode($_GET["message"]));
}

echo "<!DOCTYPE html>";
// Start of the HTML document

echo "<html>";

echo "<head>";
echo "<title>Games Consoles</title>";
// Links to the external CSS file, 'styles.css', which handles the website's design
echo "<link rel='stylesheet' href='css/styles.css'>";
echo "</head>";

echo "<body>";
echo "<div class='container'>";

require_once "assets/topbar.php";
require_once "assets/nav.php";

echo "<div class='content'>";

echo "<h1> G Console Login </h1>";
echo "<br>";

echo "<p id='intro'>Welcome to the home of tracking the consoles you own</p>";

// Display any message (from session or URL)
if (!$message) {
    echo user_message();
} else {
    echo $message;
}

// Login Form
echo "<form method='post' action=''>";
echo "<input type='text' name='username' placeholder='Username' required>";
echo "<br>";
echo "<input type='password' name='password' placeholder='Password' required>";
echo "<br>";
echo "<input type='submit' name='submit' value='Login'>";
echo "</form>";

// Include the image if you want it on the login page
echo "<img src='images/image_index.png'>"; # Index image picture.

echo "</div>"; // Closes div class='content'
echo "</div>"; // Closes div class='container'

echo "</body>";

echo "</html>";
?>