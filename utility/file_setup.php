/*
 * @Author: AJ Javadi 
 * @Email: amirjavadi25@gmail.com
 * @Date: 2024-09-18 15:25:20 
 * @Last Modified by: AJ Javadi
 * @Last Modified time: 2024-09-18 15:26:38
 * @Description: file:///Applications/XAMPP/xamppfiles/htdocs/footpower/utility/file_setup.php
 * File Setup script to create public and private directories with files and folders in them using PHP
 */



 // TODO: set up private, public directories with subdirectories in them.
 
<?php

// Define the base directory
$baseDir = 'footpower';

// Define the structure
$structure = [
    'public' => [
        'index.php' => '<?php require_once __DIR__ . "/../vendor/autoload.php"; // Your application logic here',
    ],
    'src' => [
        'Config' => [
            'Database.php' => "<?php\n\nnamespace Footpower\\Config;\n\nclass Database {\n    // Database configuration\n}",
        ],
        'Models' => [
            'User.php' => "<?php\n\nnamespace Footpower\\Models;\n\nclass User {\n    // User model\n}",
            'Log.php' => "<?php\n\nnamespace Footpower\\Models;\n\nclass Log {\n    // Log model\n}",
            'Activity.php' => "<?php\n\nnamespace Footpower\\Models;\n\nclass Activity {\n    // Activity model\n}",
        ],
        'Controllers' => [
            'UserController.php' => "<?php\n\nnamespace Footpower\\Controllers;\n\nclass UserController {\n    // User controller logic\n}",
            'LogController.php' => "<?php\n\nnamespace Footpower\\Controllers;\n\nclass LogController {\n    // Log controller logic\n}",
            'ActivityController.php' => "<?php\n\nnamespace Footpower\\Controllers;\n\nclass ActivityController {\n    // Activity controller logic\n}",
        ],
        'Views' => [
            'users' => [],
            'logs' => [],
            'activities' => [],
        ],
        'Utils' => [
            'Helpers.php' => "<?php\n\nnamespace Footpower\\Utils;\n\nclass Helpers {\n    // Helper functions\n}",
        ],
    ],
    'tests' => [],
    'scripts' => [
        'create_database.sql' => "-- SQL script to create the database\n-- Add your SQL statements here",
        'seed_database.php' => "<?php\n\n// Script to seed the database\n// Add your seeding logic here",
    ],
    'vendor' => [],
    '.gitignore' => "vendor/\n.env\n",
    'composer.json' => '{
    "name": "your-name/footpower",
    "description": "A fitness tracking application",
    "type": "project",
    "require": {
        "php": ">=7.4",
        "fakerphp/faker": "^1.9"
    },
    "autoload": {
        "psr-4": {
            "Footpower\\\\": "src/"
        }
    }
}',
    'README.md' => "# Footpower\n\nA fitness tracking application.\n\n## Installation\n\n## Usage\n\n## Contributing\n\n## License",
];

// Function to create directories and files
function createStructure($basePath, $structure) {
    foreach ($structure as $name => $content) {
        $path = $basePath . DIRECTORY_SEPARATOR . $name;
        if (is_array($content)) {
            // If it's an array, it's a directory
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
                echo "Created directory: $path\n";
            }
            createStructure($path, $content);
        } else {
            // If it's not an array, it's a file
            if (!file_exists($path)) {
                file_put_contents($path, $content);
                echo "Created file: $path\n";
            }
        }
    }
}

// Create the project structure
createStructure(__DIR__, [$baseDir => $structure]);

echo "Project structure created successfully!\n";