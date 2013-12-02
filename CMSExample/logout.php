<?php
$includes = true;
require_once('includes/functions.php');
/**
 * @author Michael Hancoski
 * @copyright 2013
 * 1. Find the session
 * 2. uset all varables
 * 3. destroy the cookie
 * 4. destroy the seesion
 */

session_start();
$_SESSION = array();
if(isset($_COOKIE[session_name()])){
    setcookie(session_name(), '', time()-420000, '/');
}
session_destroy();

redirect_to('login.php?logout=1');

?>