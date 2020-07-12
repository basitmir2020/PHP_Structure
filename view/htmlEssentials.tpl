<?php
    class htmlEssentials{
        function __construct(){}
        private  function header($title){include INC."__Header.tpl";}
        private function navigation(){include INC."__Navigation.tpl";}
        public function body($body,$title,$arg = false,$noInclude = false){
            if($noInclude){
                include VIEW.strtolower($body).".tpl";
            }else{
                $this->header($title);
                $this->navigation();
                include VIEW.strtolower($body).".tpl";
                $this->footer();
              }
        }
        private function footer(){include INC."__Footer.tpl";}
    }
?>