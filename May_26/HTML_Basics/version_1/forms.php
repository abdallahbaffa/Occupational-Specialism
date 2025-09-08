<?php

echo "<!DOCTYPE html>";

echo "<html>"; # Opening for HTML.



    echo "<head>";
    echo "<title>Forms</title>";
    echo "<link rel='stylesheet' href='css/styles.css'></head>"; # The head tag is above the body tag. This is my title for index.
    echo "</head>";


    echo "<body>";

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "Your name: " . $_POST['name'];
     echo "<br>";
     echo "Your email: " . $_POST['email'];
     echo "<br>";
     echo "Your password: " . $_POST['pwd'];
     echo "<br>";
     echo "Your password confirmed: " . $_POST['pwd2'];
}

    echo "<form method='post' action=''>";  # If there is no action, it will just reload this page.

    echo "<label for='name'>Name</label>";
    echo "<input type='text' name='name' id='name' placeholder='Enter your name.' required>";
    echo "<br>";
    echo "<label for='email'>Email</label>";
    echo "<input type='text' name='email' id='email' placeholder='Enter your email.' required>";
echo "<br>";
    echo "<input type='password' name='pwd' id='password'>";
    echo "<label for='password'>Password</label>";
    echo "<br>";
echo "<input type='password' name='pwd2' id='password2'>";
echo "<label for='submit'>Password Confirm</label>";

echo "<input type='submit' value='Submit'>";

    echo "</form>";


    echo "</body>";



echo "</html>"; # Closing for HTML.

?>