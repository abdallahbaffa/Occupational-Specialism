<?php // This open the php code section

echo "<!DOCTYPE html>";  # essential html line to dictate the page type

echo "<html>";  # opens the html content of the page

echo "<head>";  # opens the head section

echo "<title> GibJohn Tutoring </title>";  # sets the title of the page (web browser tab)
echo "<link rel='stylesheet' type='text/css' href='solution/css/styles.css' />";  # links to the external style sheet

echo "</head>";  # closes the head section of the page

echo "<body>";  # opens the body for the main content of the page.

echo "<div class='container'>";

echo "<h2 align='center'><u>Unlock Your Potential with GibJohn Tutoring</u></h2>";  # sets a h2 heading as a welcome

require_once "solution/assets/topbar.php";

require_once "solution/assets/nav.php";

echo "<p><b><u>GibJohn Tutoring</u></b></p>";

echo "<div class='content'>";
echo "<br>";

echo "<img src=solution/images/index_pic.jpg>";  # image added to improve the appearance of the index page (sadly not in the design).

echo "<footer>";
echo "<p>Contact Information:</p>";
echo "<p>Email: <a href='mailto:info@gibjohntutoring.com'>info@gibjohntutoring.com</a></p>";
echo "<p>Phone: (123) 456 7890</p>";
echo "<p><a href=#>Privacy Policy</a> | <a href='#'>Terms of Service</a></p>";
echo "</footer>";

echo "</div>";

echo "</div>";

echo "</body>";

echo "</html>";
?>