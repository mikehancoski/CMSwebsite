#!/bin/bash

echo Enter mysql user name and press enter:
read username
echo Enter mysql password and press enter:
read password
echo "<?php
if(!\$includes){die('Access Denied');}
/**
* @author Michael Hancoski
* @copyright 2013
*/
define('DB_SERVER', 'localhost');
define('DB_USER', '$username');
define('DB_PASS', '$password');
define('DB_NAME', 'demo_db');


?>" > CMSexample/includes/constants.php
