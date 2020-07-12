<?php
    class adminEssentials{
		function __construct(){}
        private  function header($title){include INC."__Admin__Header.tpl";}
        private function navigation(){include INC."__Admin__Navigation.tpl";}
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
       private function footer(){include INC.'__Admin__Footer.tpl';}
    }
?>