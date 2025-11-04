<?php // This open the php code section

// Check if a message is passed via the URL, otherwise start session
if (!isset($_GET['message'])) {
    session_start();
    $message = false; // Flag to indicate no message from URL
} else {
    // Decode and sanitize the message from the URL
    $message = htmlspecialchars(urldecode($_GET['message']), ENT_QUOTES, 'UTF-8');
}

// Include common functions (like user_message)
require_once "assets/common.php";

// Start generating the HTML output
echo "<!DOCTYPE html>"; // Essential HTML declaration
echo "<html>"; // Open the HTML tag
echo "<head>"; // Open the head section
echo "<title> Version 5</title>"; // Set the page title
echo "<link rel='stylesheet' href='css/styles.css'>"; // Link to the external CSS file
echo "</head>"; // Close the head section
echo "<body>"; // Open the body section

// Include the top bar and navigation bar
require_once "assets/topbar.php"; // Brings in the top bar (branding, login status, etc.)
require_once "assets/nav.php"; // Brings in the main navigation menu

// Open the main content area div
echo "<div class='content'>";
echo "<br>"; // Add a line break
echo "<h2> Welcome to Primary Oaks - Your Health is our Concern</h2>"; // Welcome heading
echo "<p class='content'> Make appointments fast and easy. </p>"; // Informational text
echo "<p class='content'> You have to be registered to login and book </p>"; // Registration info
echo "<br>"; // Add another line break

// Display the message from the URL (if it existed) or the session message
if ($message) {
    // If a message came from the URL, display it directly
    echo "<div class='message'>". $message . "</div>";
} else {
    // Otherwise, check for and display a message stored in the session using the common function
    echo user_message(); // This function also clears the session message after displaying it
}

// Close the content div and the body/html tags
echo "</div>"; // Close the content div
echo "</body>"; // Close the body section
echo "</html>"; // Close the HTML section

?>