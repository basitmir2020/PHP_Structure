<?php
use App\Config\Bootstrap;
use App\Config\Modules;
use App\Core\CoreRouter;
use App\Http\ApiRouter;

// Initialize Application
require_once dirname(__DIR__) . '/app/Config/Bootstrap.php';
Bootstrap::init();

// Routing Logic
$url = isset($_GET['url']) ? rtrim($_GET['url'], '/') : '';
$urlParts = explode('/', $url);
$firstSegment = $urlParts[0] ?? '';

// API Routing
if ($firstSegment === 'api') {
    // Shift off 'api' from URL effectively handled by ApiRouter internal logic usually
    // But existing ApiRouter might expect 'api' to be part of it or not. 
    // Checking ApiRouter: it probably checks request path.
    // In original index.php: if ($urlParts[0] === 'api') { $app = new \App\Http\ApiRouter(); }
    $app = new ApiRouter();
    exit;
}

// Module Routing
$modules = Modules::getList();
$moduleName = null;
$controllerParams = [];

foreach ($modules as $modName => $config) {
    if (strtolower($firstSegment) === strtolower($modName)) {
        $moduleName = $modName;
        // Remove module segment from URL so Router sees "Module/Controller/Method" -> "Controller/Method"
        // Actually CoreRouter expects the URL to be passed normally? 
        // Original: index.php?url=Admin/Auth -> url=Admin/Auth. 
        // CoreRouter splits url. $url[0] is controller.
        // If we go to /admin, url is "admin". 
        // If we rely on CoreRouter parsing "admin" as controller, that's wrong if "Admin" is the module.

        // Fix: If we are in a module path, we might need to adjust $_GET['url'] or how CoreRouter interprets it.
        // Let's look at CoreRouter again.
        // It takes $modulePath (e.g. "Public").
        // It parses URL. $url[0] is Controller.

        // If I go to /admin/login
        // URL = admin/login.
        // If I instantiate CoreRouter("Admin"), it expects proper Controller name from URL?
        // If URL is "admin/login", $url[0] is "admin". Controller becomes "AdminController"? No.
        // We need to shift the module name off the URL if it's in the URL.

        array_shift($urlParts); // Remove 'admin'
        $_GET['url'] = implode('/', $urlParts); // Clean URL for Router
        break;
    }
}

// Default Module
if (!$moduleName) {
    $moduleName = Modules::getDefaultModule();
    // Start router for default module. URL is already "controller/method" (e.g. "login")
}

$moduleConfig = $modules[$moduleName];
$app = new CoreRouter(
    $moduleName,
    $moduleConfig['default_controller'],
    $moduleConfig['default_method']
);
