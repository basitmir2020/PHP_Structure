<?php
      require "config.tpl"; // Keep this

      // Determine if it's an API request
      $isApiRequest = false;
      if (isset($_GET['url'])) {
          $urlPath = rtrim($_GET['url'], '/'); // Get the path part of the URL
          $urlParts = explode('/', $urlPath);
          if (!empty($urlParts) && $urlParts[0] === 'api') {
              $isApiRequest = true;
          }
      }

      if ($isApiRequest) {
          // require_once LIB."apiRouting.tpl"; // Removed, ApiRouter is autoloaded
          $app = new \App\Http\ApiRouter(); // Use FQN
      } else {
          // Web router uses CoreRouter
          // require_once LIB."CoreRouter.tpl"; // Removed, CoreRouter is autoloaded
          // Parameters for CoreRouter: templateNamePrefix, defaultViewName, defaultTitle, standaloneRoutes
          // CoreEssentials (now ViewManager) is autoloaded via App\Core namespace
          // "index" is the default view/title. "Login" is a standalone page.
          // The templateNamePrefix for the main site is an empty string.
          // modulePath="Public", templateNamePrefix="", defaultControllerName="Home", defaultMethodName="index"
          $app = new \App\Core\CoreRouter("Public", "", "Home", "index"); // Use FQN, updated for new constructor
      }
?>