<?php
    require_once './BDSingleton.php';

    $con=BDSingleton::getConexao();

    session_start();
    if( !isset($_SESSION) || !isset($_SESSION['logado']) || $_SESSION['logado'] != true ) {
        header("location: ../index.php"); // Vai pro inicio
    }
    

    $id=$_GET['id'];

    $ps=$con->query("DELETE FROM publicacao WHERE id=$id");

    header("location: ../perfil.php");
    
?>