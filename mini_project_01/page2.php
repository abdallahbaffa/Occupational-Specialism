<?php

// The document type declaration for HTML5
echo "<!DOCTYPE html>";

// Start of the HTML document
echo "<html>";

// The head section contains meta-information about the page
echo "<head>";
echo "<title>Abdallah's Favorite Colors</title>";
// Links to the external CSS file, 'styles.css', for consistent styling
echo "<link rel='stylesheet' href='css/styles.css'>";
echo "</head>";

// The body section contains the visible content of the page
echo "<body>";

// Main container for the page content, helps with centering and layout
echo "<div class='page-container'>";

// Header section with the main title for the page
echo "<div class='header'>";
echo "<h1>My Favorite Colors</h1>";
echo "</div>";

// Navigation menu with links to all other pages
echo "<div class='nav'>";
echo "<a href='index.php'>Home</a>";
echo "<a href='page2.php'>Favorite Colors</a>";
echo "<a href='page3.php'>Color Nature</a>";
echo "<a href='page4.php'>My Drawing</a>";
echo "<a href='forms.php'>Mailing List</a>";
echo "</div>";

// Main content area of the page
echo "<div class='content'>";
echo "<h2>These are my three favorite colors:</h2>";

// Start of a color box to display information about the yellow color
echo "<div class='color-box'>";
// A small sample of the color, using an inline style for the specific color code
echo "<div class='color-sample' style='background-color: #FFFFB4;'></div>";
echo "<h3><u>Light Yellow (#FFFFB4)</u></h3>";
echo "<p>I like the brightness of yellow. It reminds me of sunshine and happiness.</p>";
echo "</div>";

// A color box for the green color
echo "<div class='color-box'>";
echo "<div class='color-sample' style='background-color: #B4FFB4;'></div>";
echo "<h3><u>Green (#B4FFB4)</u></h3>";
echo "<p>I like the natural and calming feeling of green. It makes me think of nature and peace.</p>";
echo "</div>";

// A color box for the purple color
echo "<div class='color-box'>";
echo "<div class='color-sample' style='background-color: #B4B4FF;'></div>";
echo "<h3><u>Light Purple (#B4B4FF)</u></h3>";
echo "<p>I like the mysteriousness of purple. It feels both royal and deep to me.</p>";
echo "</div>";

// This ends the main content 'div'
echo "</div>";

// Footer at the bottom of the page
echo "<div class='footer'>";
echo "<p>Abdallah's Mini Project #01 - Favorite Colors</p>";
echo "</div>";

// This ends the main page container 'div'
echo "</div>";

// End of another doc
echo "</body>";
echo "</html>";

?>