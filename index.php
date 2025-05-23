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
          require_once LIB."apiRouting.tpl"; // Load API router
          $app = new ApiRouting();
      } else {
          // Web router uses CoreRouter
          require_once LIB."CoreRouter.tpl";
          // Parameters for CoreRouter: essentialsClassName, defaultViewName, defaultTitle, standaloneRoutes
          // htmlEssentials is assumed to be loaded via config.tpl
          // "index" is the default view/title. "Login" is a standalone page.
          $app = new CoreRouter("htmlEssentials", "index", "index", ["Login"]);
      }
?>