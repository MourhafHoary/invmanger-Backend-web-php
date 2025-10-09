<?php
/**
 * Main entry point for the Inventory Manager application
 * Following MVC architecture
 */

// Define base path constants
define('BASE_PATH', __DIR__);
define('VIEWS_PATH', BASE_PATH . '/views');
define('MODELS_PATH', BASE_PATH . '/models');
define('CONTROLLERS_PATH', BASE_PATH . '/controllers');
define('PUBLIC_PATH', BASE_PATH . '/public');

// Redirect to main landing page
header('Location: index.html');
exit;