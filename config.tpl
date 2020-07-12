<?php
    define("WEBPATH","");
    define("ADMIN_PATH",WEBPATH.'Admin/');
    define('SESSIONTIME',$_SERVER['REQUEST_TIME']);
    define('TIMEOUT',3600);
    include('catalog/session.tpl');
    session::start();
    error_reporting(E_ALL);

    date_default_timezone_set("Asia/Kolkata");

    //SERVER
    define("CATALOG","catalog/");
    define("CONTROLLER","controller/");
    define("IMAGES",WEBPATH."img/");
    define("INC","includes/");
    define("MODEL","model/");
    define("DBASE","persistence/");
    define("PROCESS","process/");
    define("SCRIPTS",WEBPATH."js/");
    define("STYLES",WEBPATH."css/");
    define("LIB","library/");
    define("ROOT",dirname(__FILE__)."\\");
    define("UPLOAD_PATH",ROOT."img\\uploads\\");
    define("VIEW","view/");
    define("VENDOR",WEBPATH.'vendor/');
    define("FONTS",WEBPATH."fonts/");

    //LOGOUT ROUTES
    define('USER_LOGOUT',WEBPATH."login".'?RID=103');
    define('ADMIN_LOGOUT',ADMIN_PATH.'?RID=103');

    //REQUEST ROUTES
    

    //MESSAGES
    define('LOGOUT_MESSAGE','Successfully Logged Out!');
    define('LOGIN_MESSAGE','Successfully Logged In!');


    require_once VIEW. "htmlEssentials.tpl";
    require_once CATALOG.'validation.tpl';
    require_once DBASE.'dbContext.tpl';
    require_once CATALOG.'functions.tpl';

    //Database Configuration
    define('DBHOST','localhost');
    define('DBUSER','root');
    define('DBPASS','');
    define('DBNAME','');
?>