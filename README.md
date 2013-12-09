CMSwebsite
==========

basic CMS website
Requiers PHP5+ and mysql and a webserver I use apache but that should not matter

The basic setup Requires you to copy the CMSEXAMPLE directory into the root folder of your web server IE www or htdocs ect
you can access the site by going to localhost/CMSEXAMPLE/index.php

- to Make the base Data base use the createbasicdb.sql script, this will create a user with the name 'user1' and password 'password' you can use this account to create new users


- to get the sql connection working you must edit the includes/constants.php to reflect you sql settings you must enter your mysql user name and password


Simple instructions 

install WAMP

Cut and paste the createbasicdb.sql into phpmyadmin (localhost/phpmyadmin click SQL)

open the file includes/constants with any text editor and add you sql user name and password 

open anybrowser and navigate to localhost/cmsexample/

//kennethwestervelt reporting in. Feel free to delete this.

//papruitt backend developer..

