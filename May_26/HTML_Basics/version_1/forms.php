<?php

echo "<!DOCTYPE html>";

echo "<html>"; # Opening for HTML.



    echo "<head>";
    echo "<title>Forms</title>";
    echo "<link rel='stylesheet' href='css/styles.css'></head>"; # The head tag is above the body tag. This is my title for index.
    echo "</head>";


    echo "<body>";
    echo "<form method='post' action=''>";
    echo "<label for='num'>Number of Tickets</label>";
    echo "<label for='password'>The Password</label>";
    echo "<input type='text' name='num' id='num' placeholder='number of tickets' required>";
    echo "<br>";
    echo "<input type='password' name='password' id='password' placeholder='password' required>";
    echo "<hr>";
    echo "<input type='submit' name='submit' value='Send'>";

    echo "</form>";


    echo "</body>";



echo "</html>"; # Closing for HTML.

?>