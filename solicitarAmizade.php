<?php
    require_once 'api/BDSingleton.php';

    $con=BDSingleton::getConexao();

    session_start();
    if( !isset($_SESSION) || !isset($_SESSION['logado']) || $_SESSION['logado'] != true ) {
        header("location: ./index.php"); // Vai pro inicio
    }
    

    $idSolicitado=$_GET['idSolicitado'];
    $dataSolicitacao=date("Y/m/d");
    $idSolicitante=$_SESSION['id'];

    $ps=$con->query("INSERT INTO amigos(dataSolicitacao,idSolicitante,dataConfirmacao,idSolicitado,situacao)
    values ('$dataSolicitacao',$idSolicitante,NULL,$idSolicitado,'P');");

    header("location: ./perfil-amigo.php?id=$idSolicitado");
    
?>