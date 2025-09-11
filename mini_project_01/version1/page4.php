<?php

// The document type declaration for HTML5
echo "<!DOCTYPE html>";

// Start of the HTML document
echo "<html>";

// The head section contains meta-information about the page
echo "<head>";
echo "<title>My Lotus Drawing</title>";
// Links to the external CSS file, 'styles.css', for consistent styling
echo "<link rel='stylesheet' href='css/styles.css'>";
echo "</head>";

// The body section contains the visible content of the page
echo "<body>";

// Main container for the page content, helps with centering and layout
echo "<div class='page-container'>";

// Header section with the main title for the page
echo "<div class='header'>";
echo "<h1>My Drawing</h1>";
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
echo "<h2><u>My 'Lotus' Drawing</u></h2>";
echo "<p>Here's my attempt at a Lotus.</p>";

// This section contains the drawing image
echo "<div class='drawing-container'>";
// This is the image of the drawing, with a class for styling
echo "<img src='images/mylotus.jpg' alt='My vibrant color drawing' class='drawing-image'>";
echo "</div>";

// This section contains a description of the drawing
echo "<div class='drawing-description'>";
echo "<h3>About My Drawing</h3>";
echo "<p>BEFORE you SAY anything (think what you want) about this 'drawing' of mine... know that I did this within 5 minutes due to time and I also did it all by finger on my phone. It sure looks wonderful😃🥲</p>";
echo "</div>";

// This ends the main content 'div'
echo "</div>";

// Footer at the bottom of the page
echo "<div class='footer'>";
echo "<p>Abdallah's Mini Project #01 - My Drawing</p>";
echo "</div>";

// This ends the main page container 'div'
echo "</div>";

// End of the LAST one🥲
echo "</body>";
echo "</html>";

?>