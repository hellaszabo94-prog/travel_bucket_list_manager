<?php 
if(count($_POST)>0){

    if(isset($_POST["Logout"])){

        $_SESSION=[];
    
    if(ini_get("session.use_cookies")){
        $parameter = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time()-86400,
            $parameter["path"],
            $parameter["domain"],
            $parameter["secure"],
            $parameter["httponly"],
        );
    }  
    session_destroy();

    header("Location: log.php");

    exit;
    }
}

?>