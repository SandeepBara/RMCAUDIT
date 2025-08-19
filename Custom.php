<?php
  
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);

// Load the necessary paths configuration
require FCPATH . 'app/Config/Paths.php';

// Initialize the Paths
$paths = new Config\Paths();

// Bootstrap the application
$app = require rtrim($paths->systemDirectory, '/ ') . '/bootstrap.php';

$controller = new \App\Controllers\AmurtMisPortal(); // Manually create the controller instance

// Call the method
$controller->centralDashboardPushPropertyData();
$controller->centralDashboardPushTradeData();
$controller->centralDashboardPushWatereData();
?>
