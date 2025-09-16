<?php /*Common subroutines go here*/

function usr_msg()
{
    if(isset($_SESSION["msg"])){ /*checks for the session variable being set */
        $msg = 'USER MESSAGE: ' . $_SESSION["msg"];
        $_SESSION["msg"] = "";
        unset($_SESSION["msg"]);
        return $msg;
    }
    else {
        return"";
    }
}