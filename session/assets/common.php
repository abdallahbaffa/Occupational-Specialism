<?php /*Common subroutines go here*/

function usr_msg()
{
    if(isset($_SESSION["msg"])){ /*checks for the session variable being set */

        if(str_contains($_SESSION["msg"],"ERROR")) {
            $msg = "<div id='error'> USER MESSAGE: " . $_SESSION["msg"] . "</div>";

        } else {
    $msg = "<div id='umsg'> USER MESSAGE: " . $_SESSION["msg"] . "</div>";
        }

        $_SESSION['msg'] = "";
        unset($_SESSION["msg"]);
        return $msg;
} else {
        return"";
}

}