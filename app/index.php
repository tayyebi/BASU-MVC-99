<?php

// Read configuration
include('Core/Config.php');

if (_Debug)
{
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}
else
    // Turn off all error reporting
    error_reporting(0);

// Exception handler
include('Core/Exceptions.php');

// Models core
include('Core/Model.php');

// Controllers core
include('Core/Controller.php');

// Router
include('Core/App.php');

new App;