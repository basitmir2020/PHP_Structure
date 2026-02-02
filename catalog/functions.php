<?php

    function filter($param){
        $param = stripslashes($param);
        $param = str_replace("`","",$param);
        $param = htmlentities($param);
        $param = strip_tags($param);
        $param = addslashes($param);
        return $param;
    }

    function generateToken(){
        $token = baseEncode(baseEncode(md5(uniqid())));
        $_SESSION['csrf'] = $token;
        return $token;
    }

    function registerToken(){
       $hexaToken = bin2hex(openssl_random_pseudo_bytes(40));
       return $hexaToken;
    }

    // Deprecated/Insecure functions removed: encrypt, Password_Encryption, Generate_Salt, Password_Check.
    // Use PHP's native password_hash() and password_verify() instead.

	function baseEncode($param){
		return base64_encode($param);
	}

	function baseDecode($param){
		return base64_decode($param);
	}

    function sendMail(userModel $user){
        $to_email = $user->getEmail();
        $subject = "Confirm Account";
        $users =  strtoupper($user->getFName().' '.$user->getLName());
        $SenderEmail = 'From:sender address';
        $SenderEmail .= "MIME-Version: 1.0\r\n";
        $SenderEmail .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
        $message =  '<html>';
        $message .= '<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">';
        $message .= '<body>';
        $message .= '<h1 class="btn btn-primary"> Hello '.$users.'</h1>';
        $message .= '<p>';
        $message .= '<form action="WEBPATH.'.$user->getOrganization().'/Activate?token='.$user->getToken().'" method="post">';
        $message .= '<button style="border-radius: 5px; color: white; background: darkblue;">Click Here</button>';
        $message .= '</form>';
        $message .= '</p>';
        $message .= '</body>';
        $message .=  '</html>';
        if(mail($to_email,$subject,$message,$SenderEmail))
            return true;
        return false;
    }

    function getLoader($path=false){
        $loader="";
        if($path){
           $loader.= $path;
        }
        return "<img src='".$loader.IMAGES."loader.gif"."' id='loader' style='display:none; width=15%;' />";
    }

    /**
     * Escapes a string for safe output in HTML.
     * Prevents XSS vulnerabilities.
     *
     * @param string|null $string The string to escape. Defaults to null, which results in an empty string.
     * @return string The escaped string.
     */
    function esc_html(string $string = null): string {
        return htmlspecialchars($string ?? '', ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }
?>