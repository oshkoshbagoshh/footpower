<?php 

// Document root
// Path: public/index.php


echo "<h1> Hello from the web root! </h1>";



// start the sssion
session_start();

// server agent
echo $_SERVER['HTTP_USER_AGENT'];


// var_dump($_SESSION);


// Create a unique token 
// Securing a session by converting 16 to 32 byes 

// $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(16));

// generate a fresh token after 15 minutes 


// $_SESSION['token_expires'] = time() + 900;

// time() returns the number of seconds since January 1st, 1970


// check if token is expired
if (time() > $_SESSION['token_expires']) {
    $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(16));
    $_SESSION['token_expires'] = time() + 900;
}
