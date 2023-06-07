<?php
// File to store the visitor log
$logFile = 'success_counter.txt';

// Get the current timestamp
$currentTimestamp = date('Y-m-d H:i');

// Get the visitor's IP address
$visitorIP = $_SERVER['REMOTE_ADDR'];

// Create the log entry
$logEntry = $currentTimestamp . ' - ' . $visitorIP . "\n";

// Append the log entry to the file
file_put_contents($logFile, $logEntry, FILE_APPEND);
?>
