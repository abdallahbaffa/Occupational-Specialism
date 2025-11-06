<?php
// Ensure session is started before checking $_SESSION variables.
// It's generally good practice to start the session in nav.php if it's not
// guaranteed to be started before nav.php is included.
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

echo "<div class='navi'>";
echo "<nav>";
echo "<ul>";

// Always show Home link
echo "<li class='linkbox'><a href='index.php'>Home</a></li>";

// Show Login and Register links only if user is NOT logged in
if (!isset($_SESSION['userid'])) {
    echo "<li class='linkbox'><a href='login.php'>Login</a></li>";
    echo "<li class='linkbox'><a href='register.php'>Register</a></li>";
}
// No specific links (like Logout) are shown for logged-in users in version_01

echo "</ul>";
echo "</nav>";
echo "</div>";
?>