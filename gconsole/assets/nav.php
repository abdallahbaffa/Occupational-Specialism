<?php /*This file is for the navigation bar*/
echo "<div class='navi'>";
echo "<nav>";
echo "<ul>";
echo "<li> <a href='index.php'>Home</a></li>"; // Open a cell for a link to be housed.

// Check if the user is NOT logged in
if (!isset($_SESSION["user"])) {
    // Show Register and Login links
    echo "<li> <a href='register.php'>User Register</a></li>";
    echo "<li> <a href='login.php'>User Login</a></li>";
} else {
    // User IS logged in, show Console Register and Logout links
    echo "<li> <a href='console_register.php'>Console Register</a></li>";
    echo "<li> <a href='logout.php'>Logout</a></li>";
}
echo "</ul>"; // Closes the row of the table.
echo "</nav>";
echo "</div>";
?>