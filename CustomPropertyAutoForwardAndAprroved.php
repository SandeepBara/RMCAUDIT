<?php
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);

// Load the necessary paths configuration
require FCPATH . 'app/Config/Paths.php';

// Initialize the Paths
$paths = new Config\Paths();

// Bootstrap the application
// echo(ini_get("memory_limit"));die;
$app = require rtrim($paths->systemDirectory, '/ ') . '/bootstrap.php';

$controller = new \App\Controllers\AmurtMisPortal(); // Manually create the controller instance

$_REQUEST["remarks"]="Auto Forward";
$_REQUEST["btn_approved_submit"]="ok";
$_REQUEST["btn_verify_submit"]="ok";
$_REQUEST["cmd"]=true;

// Call the method
$controller->propertyAutoApproved();
?>
