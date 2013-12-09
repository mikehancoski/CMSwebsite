@echo off 

echo Enter your mysql username and press [enter]: 
set /p sqlusername=%=%
echo Enter your password and press [enter]:
set /p sqlpassword=%=%

(
echo ^<?php
echo if^(^!$includes^){die^(^'Access Denied^'^);}
echo /**
echo * @author Michael Hancoski
echo * @copyright 2013
echo */
echo define^(^'DB_SERVER^', ^'localhost^'^);
echo define^(^'DB_USER^', ^'%sqlusername%^'^);
echo define^(^'DB_PASS^', ^'%sqlpassword%^'^);
echo define^(^'DB_NAME^', ^'demo_db^'^);
echo:
echo: 
echo ?^> 
)> CMSexample/includes/constants.php

set sqlusername=NULL
set sqlpassword=NULL
