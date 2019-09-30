<?php
    //1: Iniciar sessao
    session_start();

    //2: Apagar dados globais da sessao
    $_SESSION = array();

    //3: Se usou cookie, apaga cookie
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }

    //4: Destroi sessao
    session_destroy();

    //5: Manda o usuario pro Index.
    header("location: ./index.php"); // Vai pro login

?>