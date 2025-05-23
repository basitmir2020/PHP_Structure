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
<<<<<<< HEAD
      $app = new \App\Core\CoreRouter("__Admin__", "index", "Login", []); // Use FQN
=======
      // modulePath="admin"
      $app = new \App\Core\CoreRouter("admin", "__Admin__", "index", "Login", []); // Use FQN, added modulePath
>>>>>>> cc4ee689bdc1f642d96f230e284fd6de713c3d61
?>