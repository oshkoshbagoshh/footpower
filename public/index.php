<?php 

// Document root
// Path: public/index.php


echo "<h1> Hello from the web root! </h1>";



// start the sssion
session_start();

// server agent
echo $_SERVER['HTTP_USER_AGENT'];


// var_dump($_SESSION);

