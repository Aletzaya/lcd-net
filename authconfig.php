<?php

// ALL PATHS BELOW ARE RELATIVE TO THE DIRECTORY WHERE YOU HAVE INSTALLED vAuthenticate

$resultpage = "vAuthenticate.php";	// THIS IS THE PAGE THAT WOULD CHECK FOR AUTHENTICITY

$admin = "/lcd-net/admin/index.php";	// THIS IS THE PATH TO THE ADMIN INTERFACE

$success = "/lcd-net/menu.php?op=st";	// THIS IS THE PAGE TO BE SHOWN IF USER IS AUTHENTICATED

$failure = "/lcd-net/failed.php";	// THIS IS THE PAGE TO BE SHOWN IF USERNAME-PASSWORD COMBINATION DOES NOT MATCH

// The $_SERVER['HTTP_HOST'] takes care of the root directory of the web server

// This makes it possible to implement the script even on IP-based systems.

// For name-based systems, just think of $_SERVER['HTTP_HOST'] as the domain name

// example: $_SERVER['HTTP_HOST'] will have to be www.yourdomain.com

// For IP-based systems, this will replace the IP address

// example: $_SERVER['HTTP_HOST'] will have to be 66.199.47.5

$changepassword = "https://" . $_SERVER['HTTP_HOST'] . "/lcd-net/chgpwd.php"; // Path to change password file

$login = "https://" . $_SERVER['HTTP_HOST'] . "/lcd-net/login.php"; // Path to page with the login box

$logout = "https://" . $_SERVER['HTTP_HOST'] . "/lcd-net/logout.php"; // Path to logout page

// DB SETTINGS

$dbhost = "127.0.0.1";	// Change this to the proper DB Host name

$dbusername = "u938386532_root"; 	// Change this to the proper DB User

$dbpass = "Lcd9623299";	// Change this to the proper DB User password

$dbname	= "u938386532_lcd"; 	// Change this to the proper DB Name

?>