<?php

// Show all errors (for development)
ini_set('display_errors', 1);
error_reporting(E_ALL);
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// DB creds
$servername = "sql301.infinityfree.com";
$username   = "if0_38711925";
$password   = "DK3cIXds1FPDMK";
$dbname     = "if0_38711925_stands";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Échec de la connexion: " . $conn->connect_error);
}
?>