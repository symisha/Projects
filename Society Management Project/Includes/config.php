<?php
$server = 'localhost';
$username = 'root';
$password = '';
$database = 'societydatabase';
$charset = 'utf8mb4';
/* Attempt to connect to MySQL database */
$conn = mysqli_connect($server, $username, $password, $database);
 
// Check connection

if($conn)
{
    // echo "Connection Successful";
}
else
{
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
?>