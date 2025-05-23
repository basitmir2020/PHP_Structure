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
          require LIB."apiRouting.tpl"; // Load API router
          $app = new ApiRouting();
      } else {
          require LIB."routing.tpl";    // Load Web router
          $app = new routing();
      }
?>