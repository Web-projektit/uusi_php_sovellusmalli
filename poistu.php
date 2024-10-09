<?php
include "asetukset.php";
include "debuggeri.php";
include "db.php";
include "rememberme.php";
/* Sessionin purkaminen, huom. session_start() loggedIn-funktiossa. */
// if (!session_id()) session_start();
if ($loggedIn = loggedIn() === false) {
    header("location: login.php");
    exit;
    }
/*if (!isset($_SESSION["loggedIn"]) || $_SESSION["loggedIn"] !== true){
    header("location: login.php");
    exit;
    }*/
debuggeri(__FILE__.",session ja cookie:");    
debuggeri($_SESSION);    
debuggeri($_COOKIE);    
$user_id = $_SESSION['user_id'] ?? '';
//if (is_int($user_id)) {
//    delete_rememberme_token($user_id);
//    }
if (isset($_COOKIE['rememberme'])) {
    delete_rememberme_token($user_id);
    unset($_COOKIE['rememberme']);
    //setcookie('rememberme', null, -1, "", "", false, true);
    setcookie('rememberme', '', time() - 3600, "/", "", false, true);
    }
$_SESSION = [];
/* If it's desired to kill the session, also delete the session cookie.
    Note: This will destroy the session, and not just the session data. */
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]);
    }   
session_destroy();
header("location:".ETUSIVU);
?>