<?php
	require_once 'api/Comum.php';
	require_once 'api/BDSingleton.php';

	$con=BDSingleton::getConexao();

	session_start();
	if( !isset($_SESSION) || isset($_SESSION['logado']) && $_SESSION['logado'] == false ){
		header("location: ./index.php"); // Vai pro inicio
	}

	if(isset($_GET['id']) && $_GET['id']!=""){
		$idUsuario=$_GET['id'];
	}else{
		$idUsuario=$_SESSION['id'];
	}
	$ps=$con->query("SELECT * FROM usuario where id=$idUsuario;");
							$regs = $ps->fetchAll();
	
?>

<html lang="pt-br">
    <head>
        <meta charset="utf-8"/>
        <link href="https://fonts.googleapis.com/css?family=Squada+One|Luckiest+Guy|Josefin+Sans|Bowlby+One+SC|Oswald|Roboto+Condensed|Tajawal" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="css/index.css">
        <link rel="stylesheet" type="text/css" href="css/home.css"/>
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">
		<script src="node_modules/jquery/dist/jquery.min.js"></script>
    </head>
    <body>
		<div class="header slow-transition">
            <div class="wrapper">
                <a class="logo" href="home.php">INSTACEFET</a>
				<nav>
					<ul>
						<li><a href="logout.php"><i class="fa fa-sign-out-alt fa-2x"></i></a></li>
						<li><a href="#"><i class="fa fa-envelope-open fa-2x"></i></a></li>
						<li><a href="perfil.php"><i class="fa fa-user-circle fa-2x"></i></a></li>
					</ul>
				</nav>
            </div>
        </div>
        <main class="centralizado">
			<aside class="mural mural-perfil box-effect">
				<div class="menu" >
					<div style="cursor:default;background-image:url(
						<?php 
							foreach($regs as $row){
								$apelido=$row['apelido'];
								$nome=$row['nome'];
								$sobrenome=$row['sobrenome'];
								$id=$row['id'];
							}

							echo 'usuarios/'.$apelido.'/perfil.jpg'
						?>);" class="foto-perfil">
					</div>
					<a href="perfil.php"><h1><?php echo $nome." ".$sobrenome; ?></h1></a><br/>
				</div>
				<div class="hot-post">
					<ul class="lista-menu">
						<li>
							<?php 
								echo "<a href='amigos.php?id=" . $idUsuario . "'>";
								echo "Amigos</a>"
							?>
						</li>
						<li><a href='buscarUsuario.php'>Buscar Usuários</a></li>
					</ul>
					<div class="clearfix"></div>
				</div>
			</aside>
			<section class="mural-timeline">	
				<div style="height:inherit;"class="post box-effect mural-foto">
                    <ul class="lista-menu">
						<?php
							
							$ps=$con->query("SELECT DISTINCT a.id, u.id, u.nome, u.sobrenome, u.apelido
						   FROM amigos AS a 
						   JOIN usuario AS u ON a.idSolicitado = u.id 
						   or a.idSolicitante = u.id
						   WHERE idSolicitado = $idUsuario 
						   OR idSolicitante = $idUsuario 
						   AND situacao = 'A'
						   
						   ORDER BY u.nome LIMIT 100;");
							$regs = $ps->fetchAll();
							if(count($regs)<=0){
									echo '<p> Não foi encontrado nenhum Amigo!</p>';
							}else{
								foreach($regs as $row){
									if($row['id']!=$idUsuario){
										echo "<li>";
										echo "<a href='perfil-amigo.php?id=" . $row['id'] . "'>";
										echo "<img style='margin-right:5%;width: 35px; height: 35px;' src='usuarios/" . $row['apelido'] . "/perfil.jpg'/>";
										echo "<span>" . $row['nome']  ."   ". $row['sobrenome'] ."</span></a>";
										echo '</li>';
									}
								}
							}
                        ?>
                    </ul>
				</div>
			</section>
			<aside class="mural mural-amizade box-effect">
				<div class="menu">
					<div id="logo"></div>
					<h2>Solicitações de Amizade</h2>
				</div>
				<div class="hot-post">
					<ul class="lista-menu">
						<?php
							$idUsuario=$_SESSION['id'];
							$ps=$con->query("SELECT a.id, u.id as usuario_id, u.nome as usuario_nome,
							 u.sobrenome as usuario_sobrenome, u.apelido as usuario_apelido 
							FROM amigos AS a 
							JOIN usuario AS u ON a.idSolicitante = u.id 
							WHERE idSolicitado = $idUsuario 
							AND situacao = 'p'
							ORDER BY u.nome LIMIT 100;");

							$regs = $ps->fetchAll();
							if(count($regs)<=0){
								echo '<li>';
								echo "Não existem solicitações";
								echo '</li>';
							}else{
								foreach($regs as $row){
									echo '<li>';
									echo "<a href='perfil-amigo.php?id=" . $row['usuario_id'] . "'>";
									echo "<img style='margin-right:5%;width: 35px; height: 35px;' src='usuarios/" . $row['usuario_apelido'] . "/perfil.jpg'/>";
									echo "<span>" . $row['usuario_nome']  ."   ". $row['usuario_sobrenome'] ."</span></a>";
									echo '</li>';
								}
							}
                        ?>
                    </ul>	
				</div>
			</aside>
        </main>
        <footer>
        </footer>
    </body>
</html>