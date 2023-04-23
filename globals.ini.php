<?php
/*
 * globals.inc.php
 * Global variables and settings
 */


 // Base directories
// Automatic, taken from CGI variables.
$baseDir = dirname($_SERVER['SCRIPT_FILENAME']);


$baseURL = dirname($_SERVER['SCRIPT_NAME']);

// Temporal dir, create if not exists, however Web server 
// may not have the appropriate permission to do so

$tempDir = "$baseDir/tmp";

if (!file_exists($tempDir)) {
    mkdir($tempDir, 0744);
}

$clustaloHome = "/home/u217733";

$clustaloExe = "$clustaloHome/bin/clustalo";

$clustaloCmdLine = "$clustaloExe ";


?>
