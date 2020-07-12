<?php
    class adminRouting{
        function __construct(){
            $url = (isset($_GET['url'])) ? $_GET['url'] : false;
            $url = rtrim($url,"/");
            $url = explode("/",$url);
            $html = new adminEssentials();
            if(is_null($url[0]) || !isset($url[0]) || empty($url[0])){
                $html->body("index","Login","","");
            }else{
                if(isset($url[1])){
                    $html->body($url[0],$url[0],$url[1],"");
                }else{
                    if($url[0]=="index"){
                        $html->body("index",$url[0],"","");
                    }else{
                        $html->body($url[0],$url[0],"","");
                    }
                }
            }
        }
    }
?>