<?php
    //Classe Singleton de Conexão ao Banco - Reutilização do Código
    abstract class BDSingleton{

        static private $con;
        const dbname = "trabalho-progweb";
        const dbip = "127.0.0.1";
        const dbuser = "root";
        const dbpass = "";

        public static function &getConexao(){

            if ( self::$con == null ){
                try{
                    // Atribui o objeto PDO à variável $INSTANCIA.
                    self::$con= new PDO('mysql:host='.self::dbip.';dbname='.self::dbname,self::dbuser,self::dbpass);
                    // Garante que o PDO lance exceções durante erros.
                    self::$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    // Garante que os dados sejam armazenados com codificação UFT-8.
                    self::$con->exec('SET NAMES utf8');
                }catch(PDOException $e){
                    die( 'Falha ao Conectar: '.$e->getMessage() );
                }
            }

            // Retorna a conexão.
            return self::$con;
        }
    }

    function bd_verificar_apelido_existe( $con, $apelido ){
        $sql = 'SELECT COUNT(*) as contador FROM usuario WHERE apelido ="'.$apelido.'"';
        $res = $con->query( $sql )->fetchAll();
        
        $cont = $res[ 0 ][ 'contador' ];
        
        return  $cont > 0 ;
    }

    function bd_verificar_email_existe( $con, $email ){
        $sql = 'SELECT COUNT(*) as contador FROM usuario WHERE email ="'.$email.'"';
        $res = $con->query( $sql )->fetchAll();
        
        $cont = $res[ 0 ][ 'contador' ];
        
        return  $cont > 0 ;
    }

    function bd_adicionar_usuario( $con, $usuario ){
        $sql = 'INSERT INTO usuario(email,nome,sobrenome,apelido,senha,dataIngresso,dataNascimento) VALUES 
        ("'.$usuario['email'].'","'.$usuario['nome'].'","'.$usuario['sobrenome'].'","'.$usuario['apelido'].'","'.$usuario['senha'].'","'.$usuario[ 'dtIngres' ].'","'.$usuario[ 'dtNasc' ].'")';
        
        $suc = $con->exec( $sql );
        
        return $suc;
    }

?>