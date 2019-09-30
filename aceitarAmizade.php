<?php
    require_once 'api/BDSingleton.php';

    $con=BDSingleton::getConexao();

    session_start();
    if( !isset($_SESSION) || !isset($_SESSION['logado']) || $_SESSION['logado'] != true ) {
        header("location: ./index.php"); // Vai pro inicio
    }
    

    $idSolicitado=$_SESSION['id'];
    $idSolicitante=$_GET['idSolicitante'];
    $dataAceite=date("Y/m/d");

    $ps=$con->query("UPDATE amigos SET dataConfirmacao='$dataAceite', situacao='A' 
    WHERE idSolicitante=$idSolicitante AND idSolicitado=$idSolicitado;");

    header("location: ./perfil-amigo.php?id=$idSolicitante");
    
?>