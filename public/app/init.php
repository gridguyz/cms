<?php
/**
 * This makes our life easier when dealing with paths. Everything is relative
 * to the application root now.
 */
chdir( dirname( dirname( __DIR__ ) ) );

// Setup autoloading
include 'vendor/autoload.php';
