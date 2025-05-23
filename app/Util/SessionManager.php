<?php
namespace App\Util;

class SessionManager { // Renamed from 'session'

    public static function start(){
        $cookieParams = [
            'lifetime' => TIMEOUT, // From config.tpl
            'path' => '/',
            'domain' => '', // Defaults to current host
            'secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on',
            'httponly' => true,
            'samesite' => 'Lax' // Good default for SameSite
        ];
        session_set_cookie_params($cookieParams);

        ini_set('session.cookie_httponly', true); // Can be kept
        session_start();

        if(!isset($_SESSION['IP']))
            $_SESSION['IP']= gethostbyaddr($_SERVER['REMOTE_ADDR']);
        if(!isset($_SESSION['UA']))
            $_SESSION['UA'] = $_SERVER['HTTP_USER_AGENT'];

        if(self::sessionIPSec()){ // self:: is fine
            if(self::sessionBrowserSec()){ // self:: is fine
                self::sessionTimeout(); // self:: is fine
            }else{
                unset($_SESSION[SESSION_ADMIN]); // Assuming SESSION_ADMIN is a defined constant
                unset($_SESSION['UA']);
				unset($_SESSION['IP']);
                header('Location: '.ADMIN_PATH.'?RID=101'); // Assuming ADMIN_PATH is defined
            }
        }else{
            unset($_SESSION[SESSION_ADMIN]);
			unset($_SESSION['UA']);
            unset($_SESSION['IP']);
            header('Location: '.ADMIN_PATH.'?RID=102');
        }
    }

    /**
     * Regenerates the session ID to help prevent session fixation.
     * This should be called after any significant authentication state change,
     * such as user login.
     */
    public static function regenerate() {
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_regenerate_id(true); // true to delete old session file
        }
    }

    private static function sessionIPSec(){
        if(isset($_SESSION['IP']) && $_SESSION['IP'] == gethostbyaddr($_SERVER['REMOTE_ADDR'])) // Added isset for safety
            return true;
        return false;
    }

    private static function sessionBrowserSec(){
        if(isset($_SESSION['UA']) && $_SESSION['UA'] == $_SERVER['HTTP_USER_AGENT']) // Added isset for safety
            return true;
        return false;
    }

    private static function sessionTimeout(){
        if (isset($_SESSION['active']) &&
            (SESSIONTIME - $_SESSION['active']) > TIMEOUT) { // Assuming SESSIONTIME & TIMEOUT defined
            unset($_SESSION['active']);
            self::start(); // self:: is fine
        }
    }
}
?>
