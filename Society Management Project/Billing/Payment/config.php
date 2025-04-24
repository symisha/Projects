<?php
// Include Composer's autoloader
require_once("../../vendor/autoload.php");

// Check if the .env file exists
if (!file_exists(__DIR__ . '/.env')) {
    die('.env file not found!');
}

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load(); // Load the .env file

?>
