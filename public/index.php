<?php 

// Document root
// Path: public/index.php


echo "<h1> Hello from the web root! </h1>";



// start the sssion
session_start();

// server agent
echo $_SERVER['HTTP_USER_AGENT'];


echo "<p> <b>Session Info:</b> </p>\t "; var_dump($_SESSION);


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

<!-- APP HOME PAGE  -->

<!--  -->
<!-- TOP  -->
<?php 
    include_once '../private/common/initialization.php';
    $page_title = 'Home';
    include_once 'common/top.php';
?>
<!-- END TOP  -->
<!-- MAIN  -->
Main app content goes here
<!-- END MAIN  -->
<!-- BOTTOM -->
<?php 
    include_once 'common/sidebar.php';
    include_once 'common/bottom.php';
?>

<!-- END BOTTOM -->
