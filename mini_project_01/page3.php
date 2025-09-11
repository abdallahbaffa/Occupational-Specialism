<?php

// The document type declaration for HTML5
echo "<!DOCTYPE html>";

// Start of the HTML document
echo "<html>";

// The head section contains meta-information about the page
echo "<head>";
echo "<title>Colors in Nature</title>";
// Links to the external CSS file, 'styles.css', for consistent styling
echo "<link rel='stylesheet' href='css/styles.css'>";
echo "</head>";

// The body section contains the visible content of the page
echo "<body>";

// Main container for the page content, helps with centering and layout
echo "<div class='page-container'>";

// Header section with the main title for the page
echo "<div class='header'>";
echo "<h1>Colors in Nature</h1>";
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
echo "<h2>Beautiful Colors Found in Nature</h2>";
echo "<p>In this page, I will be showing you pictures of nature with my favorite: </p>";

// This section creates a gallery of images
echo "<div class='image-gallery'>";

// Start of an image item
echo "<div class='image-item'>";
// This is the first image, linking to the file in the images folder
echo "<img src='images/yellow.jpg' alt='Colorful nature scene 1'>";
echo "<div class='image-caption'>";
echo "<p>Yellow tree.</p>";
echo "</div>";
echo "</div>";

// Second image item
echo "<div class='image-item'>";
echo "<img src='images/green.jpg' alt='Colorful nature scene 2'>";
echo "<div class='image-caption'>";
echo "<p>Forest trail to jungle.</p>";
echo "</div>";
echo "</div>";

// Third image item
echo "<div class='image-item'>";
echo "<img src='images/purple.jpg' alt='Colorful nature scene 3'>";
echo "<div class='image-caption'>";
echo "<p>Purple underwater.</p>";
echo "</div>";
echo "</div>";

// End of the image gallery section
echo "</div>";

// An additional content box explaining why nature is colorful
echo "<div class='fact-box'>";
echo "<h3>Why is nature so colorful?</h3>";
echo "<p>Colors in nature serve many purposes. Flowers use bright colors to attract pollinators, animals use colors for camouflage or warning signals, and some colors are simply the result of how light interacts with natural materials.</p>";
echo "</div>";

// This ends the main content 'div'
echo "</div>";

// Footer at the bottom of the page
echo "<div class='footer'>";
echo "<p>Abdallah's Mini Project #01 - Colors in Nature</p>";
echo "</div>";

// This ends the main page container 'div'
echo "</div>";

// End of ANOTHER doc
echo "</body>";
echo "</html>";

?>