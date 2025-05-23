<?php
      require "../config.tpl"; // Keep this to load configurations and constants like LIB

      // adminEssentials.tpl is assumed to be loaded/available via config.tpl or autoloader
      // CoreRouter will instantiate it.

      // require_once "../".LIB."CoreRouter.tpl"; // Removed, CoreRouter is autoloaded
      
      // Parameters for CoreRouter:
      // 1. templateNamePrefix: "__Admin__"
      // 2. Default view name: "index" (typically the admin dashboard or login)
      // 3. Default title: "Login" (as per previous adminRouting logic for root access)
      // 4. Standalone routes: [] (adminRouting didn't have special standalone logic like the main site's Login)
      // CoreEssentials (now ViewManager) is autoloaded via App\Core namespace
      // modulePath="Admin", templateNamePrefix="__Admin__", defaultControllerName="Auth", defaultMethodName="index"
      $app = new \App\Core\CoreRouter("Admin", "__Admin__", "Auth", "index"); // Use FQN, updated for new constructor
?>