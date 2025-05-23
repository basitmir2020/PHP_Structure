<?php
      require "../config.tpl"; // Keep this to load configurations and constants like LIB

      // adminEssentials.tpl is assumed to be loaded/available via config.tpl or autoloader
      // CoreRouter will instantiate it.

      require_once "../".LIB."CoreRouter.tpl"; // Path from Admin/ to library/
      
      // Parameters for CoreRouter:
      // 1. templateNamePrefix: "__Admin__"
      // 2. Default view name: "index" (typically the admin dashboard or login)
      // 3. Default title: "Login" (as per previous adminRouting logic for root access)
      // 4. Standalone routes: [] (adminRouting didn't have special standalone logic like the main site's Login)
      // CoreEssentials is now loaded via config.tpl
      $app = new CoreRouter("__Admin__", "index", "Login", []);
?>