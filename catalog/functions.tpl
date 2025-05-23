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

	function encrypt($param){
		$param = baseEncode(baseEncode(md5(sha1(md5(sha1(md5($param)))))));
		return $param;
	}

    function getMessages($param){
	    switch ($param){
            case "success":
                return "1";
            case "updated":
                return "2";
            default:
                return $param;
        }
    }

	function baseEncode($param){
		return base64_encode($param);
	}

	function baseDecode($param){
		return base64_decode($param);
	}

	function Password_Encryption($param){
	    $BlowFish_Hash_Format = "$2y$10$";
	    $Salt_Length = 22;
	    $Salt = Generate_Salt($Salt_Length);
	    $Formating_Blowfish_With_Salt = $BlowFish_Hash_Format .$Salt;
	    $Hash = crypt($param,$Formating_Blowfish_With_Salt);
	    return $Hash;
    }

    function Generate_Salt($param){
	    $Unique_Random_String = md5(uniqid(mt_rand(),true));
	    $Base64_String = baseEncode($Unique_Random_String);
	    $Modified_Base64_String = str_replace('+','.',$Base64_String);
	    $Salt = substr($Modified_Base64_String,0,$param);
	    return $Salt;
    }

    function Password_Check($Password,$Existing_Hash){
        $Hash = crypt($Password,$Existing_Hash);
        if($Hash === $Existing_Hash){
            return true;
        }else{
            return false;
        }
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