<?php /*Common subroutines go here*/

function usr_msg()
{
    if (isset($_SESSION["msg"])) { /*checks for the session variable being set */

        if (str_contains($_SESSION["msg"], "ERROR")) {
            $msg = "<div id='error'> USER MESSAGE: " . $_SESSION["msg"] . "</div>";

        } else {
            $msg = "<div id='umsg'> USER MESSAGE: " . $_SESSION["msg"] . "</div>";
        }

        $_SESSION['msg'] = "";
        unset($_SESSION["msg"]);
        return $msg;
    } else {
        return "";
    }
    }

function usr_mail()
{
    if (isset($_SESSION["mail"])) { /*checks for the session variable being set */

        if (str_contains($_SESSION["mail"], "ERROR")) {
            $mail = "<div id='error'> EMAIL: " . $_SESSION["mail"] . "</div>";

        } else {
            $mail = "<div id='email'> EMAIL: " . $_SESSION["mail"] . "</div>";
        }

        $_SESSION['mail'] = "";
        unset($_SESSION["mail"]);
        return $mail;
    } else {
        return "";
    }
}


function usr_url()
{
    if (isset($_SESSION["url"])) { /*checks for the session variable being set */

        if (str_contains($_SESSION["url"], "ERROR")) {
            $url = "<div id='error'> URL: " . $_SESSION["url"] . "</div>";

        } else {
            $url = "<div id='url'> URL: " . $_SESSION["url"] . "</div>";
        }

        $_SESSION['url'] = "";
        unset($_SESSION["url"]);
        return $url;
    } else {
        return "";
    }
}