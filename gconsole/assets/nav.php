<?php /*This file is for the navigation bar*/
echo "<div class='navi'>";
echo "<nav>";

echo "<ul>";
    echo "<li> <a href='index.php'>Home</a></li>";

    if(!isset($_SESSION["user"])){
        echo "<li> <a href='register.php'>User Register</a></li>";
        echo "<li> <a href='login.php'>User Login</a></li>";
    }
        else {
            echo "<li> <a href='console-register.php'>Console Register</a></li>";
            echo "<li> <a href='logout.php'>Logout</a></li>";
        }
echo "</ul>";

echo "</nav>";

echo "</div>";