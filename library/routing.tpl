<?php
    // htmlEssentials.tpl is included via config.tpl
    // require_once VIEW . "htmlEssentials.tpl"; 
    // require_once CONTROLLER . "controller.tpl"; // If controller.tpl is needed by htmlEssentials directly

    class routing{
        function __construct(){
            $url = (isset($_GET['url'])) ? $_GET['url'] : false;
            $url = rtrim($url,"/");
            $url = explode("/",$url);
            
            // htmlEssentials is instantiated in config.tpl or required globally.
            // If not, it needs: $html = new htmlEssentials();
            // Assuming $html is globally available or htmlEssentials methods are static
            // For this structure, htmlEssentials is loaded in config.tpl and likely used there or its methods are static.
            // The original code implies an instance:
            $html = new htmlEssentials();


            if(empty($url[0])){ // Simplified check for empty or null first part
               $html->body("index", "index", "", ""); // Default to "index" page
            }else{
                if(isset($url[1])){ // If there's a second part (e.g., action or parameter)
                    $html->body($url[0], $url[0], $url[1], "");
                }else{ // Only one part in URL
                    if($url[0]=="index"){
                        $html->body("index", $url[0], "", "");
                    }else if($url[0]=="Login"){ // Make sure 'Login' view file exists and matches case
                        $html->body($url[0], $url[0], "", true); // Assuming last param is 'isLoginPage'
                    }else{
                        $html->body($url[0], $url[0], "", "");
                    }
                }
            }
        }
    }
?>