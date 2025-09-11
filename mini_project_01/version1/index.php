<?php

// The document type declaration for HTML5
echo "<!DOCTYPE html>";

// Start of the HTML document
echo "<html>";

// The head section contains meta-information about the document, in my case, the title.
echo "<head>";
echo "<title>Abdallah's Mini Project #01 ~ The First Page: Colors!</title>";

// Links to the external CSS file, 'styles.css', which handles the website's design
echo "<link rel='stylesheet' href='css/styles.css'>";
echo "</head>";

// The body section contains the visible content of the webpage
echo "<body>";

// This is the main container for the entire page content.
// It helps to center the content and apply a background color.
echo "<div class='page-container'>";

// This section creates a header for the page with the main title
echo "<div class='header'>";
echo "<h1>Colors: Interesting Facts</h1>";
echo "</div>";

// This section creates the navigation bar with links to all other pages
echo "<div class='nav'>";
echo "<a href='index.php'>Home</a>";
echo "<a href='page2.php'>Favorite Colors</a>";
echo "<a href='page3.php'>Color Nature</a>";
echo "<a href='page4.php'>My Drawing</a>";
echo "<a href='forms.php'>Mailing List</a>";
echo "</div>";

// This is the main content area of the page
echo "<div class='content'>";
echo "<h2>Did You Know?</h2>";

// This 'div' is used to style a specific fact box
echo "<div class='fact-box'>";
echo "<h3><u>Chromophobia</u></h3>";
echo "<p>Some people have a phobia of color. It is called Chromophobia.</p>";
echo "</div>";

// Another fact box
echo "<div class='fact-box'>";
echo "<h3><u>First Impressions</u></h3>";
echo "<p>Color has a big impact on first impressions. About 62-90% of assessment is based on colors alone!</p>";
echo "</div>";

// The third fact box
echo "<div class='fact-box'>";
echo "<h3><u>Synesthesia</u></h3>";
echo "<p>People with Synesthesia might see different colors when hearing different sounds.</p>";
echo "</div>";

// Simple color examples with inline styles for a basic effect
echo "<h2><u>Basic Colors</u></h2>";
echo "<p style='color: #ff6b6b; font-weight: bold;'>Red - Energy and passion</p>";
echo "<p style='color: #5a9987; font-weight: bold;'>Green - Nature and harmony</p>";
echo "<p style='color: #ffd93d; font-weight: bold;'>Yellow - Happiness and warmth</p>";

// This ends the main content 'div'
echo "</div>";

// This section creates a footer at the bottom of the page
echo "<div class='footer'>";
echo "<p>Abdallah's Mini Project #01 - Colors!</p>";
echo "</div>";

// This ends the main page container 'div'
echo "</div>";

// End of my doc.
echo "</body>";
echo "</html>";

?>