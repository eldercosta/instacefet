<?php
    require_once 'api/BDSingleton.php';

    $con=BDSingleton::getConexao();

    session_start();
    if( !isset($_SESSION) || !isset($_SESSION['logado']) || $_SESSION['logado'] != true ) {
        header("location: ./index.php"); // Vai pro inicio
    }
    

    $idSolicitado=$_SESSION['id'];
    $idSolicitante=$_GET['idSolicitante'];

    $ps=$con->query("DELETE FROM amigos WHERE (idSolicitante=$idSolicitante or idSolicitante=$idSolicitado)
     AND (idSolicitado=$idSolicitado or idSolicitado=$idSolicitante) ;");

    header("location: ./perfil-amigo.php?id=$idSolicitante");
    
?>