<?php
    require_once 'api/BDSingleton.php';

    $con=BDSingleton::getConexao();

    session_start();
    if( !isset($_SESSION) || !isset($_SESSION['logado']) || $_SESSION['logado'] != true ) {
        header("location: ./index.php"); // Vai pro inicio
    }
    

    $idSolicitado=$_GET['idSolicitado'];
    $idSolicitante=$_SESSION['id'];

    //$ps=$con->query("DELETE FROM amigos WHERE idSolicitante=$idSolicitante or idSolicitante=$idSolicitado
     //AND idSolicitado=$idSolicitado or idSolicitado=$idSolicitante ;");
     $ps=$con->query("DELETE FROM amigos WHERE (idSolicitante=$idSolicitante or idSolicitante=$idSolicitado)
     AND (idSolicitado=$idSolicitado or idSolicitado=$idSolicitante);");

    header("location: ./perfil-amigo.php?id=$idSolicitado");
    
?>