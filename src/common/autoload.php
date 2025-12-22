<?php

/**
 * Simple Autoloader for FootPower Classes
 * 
 * This autoloader handles loading classes from the src/classes directory
 * without requiring PSR-4 namespaces (keeping it simple for demo)
 */

spl_autoload_register(function ($class_name) {
    // Define the base directory for classes
    $base_dir = __DIR__ . '/../classes/';
    
    // Convert class name to file path
    $file = $base_dir . $class_name . '.php';
    
    // If the file exists, include it
    if (file_exists($file)) {
        require_once $file;
        return true;
    }
    
    return false;
});

