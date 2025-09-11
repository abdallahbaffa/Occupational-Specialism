<?php

// The document type declaration for HTML5
echo "<!DOCTYPE html>";

// Start of the HTML document
echo "<html>";
echo "<head>";
echo "<title>Forms</title>";
echo "</head>";

// The body section contains the visible content of the webpage
echo "<body>";

// This checks if the form has been submitted using the POST method
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // If the form was submitted, it prints the information entered by the user
    echo "Your name: " . $_POST['name'];
    echo "<br>";
    echo "Your email: " . $_POST['email'];
    echo "<br>";
    echo "Your favorite color: " . $_POST['fav_color'];
    echo "<br>";
    echo "Subscribe to newsletter: " . (isset($_POST['newsletter']) ? 'Yes' : 'No');
    echo "<br>";
    echo "Message: " . $_POST['message'];
}

// Start of the HTML form. It uses the POST method to send data to itself.
echo "<form method='post' action='forms.php'>";

// A label and an input field for the full name
echo "<label for='name'>Full Name</label>";
echo "<input type='text' name='name' id='name' placeholder='Enter your name.' required>";
echo "<br>";

// A label and an input field for the email address
echo "<label for='email'>Email Address</label>";
echo "<input type='email' name='email' id='email' placeholder='Enter your email.' required>";
echo "<br>";

// A label and a dropdown menu for the favorite color
echo "<label for='fav_color'>Favorite Color</label>";
echo "<select name='fav_color' id='fav_color'>";
echo "<option value=''>-Select-</option>";
echo "<option value='Red'>Red</option>";
echo "<option value='Orange'>Orange</option>";
echo "<option value='Yellow'>Yellow</option>";
echo "<option value='Green'>Green</option>";
echo "<option value='Blue'>Blue</option>";
echo "<option value='Purple'>Purple</option>";
echo "</select>";
echo "<br>";

// A checkbox input for the newsletter subscription
echo "<label for='newsletter'>";
echo "<input type='checkbox' name='newsletter' id='newsletter' value='1' checked> Subscribe to newsletter";
echo "</label>";
echo "<br>";

// A label and a text area for the user's message
echo "<label for='message'>Your Message</label>";
echo "<textarea name='message' id='message' rows='3'></textarea>";
echo "<br>";

// The submit button that sends the form data
echo "<input type='submit' value='Submit'>";

// End of the form
echo "</form>";

// End of the body and HTML document
echo "</body>";
echo "</html>";

?>