<?php
	require_once 'api/Comum.php';
	require_once 'api/BDSingleton.php';

	$con=BDSingleton::getConexao();

	session_start();
	if( !isset($_SESSION) || !isset($_SESSION['logado']) || $_SESSION['logado'] != true ) {
		header("location: ./index.php"); // Vai pro inicio
	}

	if(isset($_POST['enviar'])){
		$idUsuario=$_SESSION['id'];
		$conteudo=Comum::limparHTML($_POST['conteudo']);

		if($conteudo!=""){
			$ps=$con->query("INSERT INTO publicacao (conteudo,idUsuario) values ('$conteudo',$idUsuario);");
		}else{
			echo "<script>alert('Conteúdo vazio!');</script>";
		}		
	}

	
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
					<div style="background-image:url(<?php echo 'usuarios/'.$_SESSION['apelido'].'/perfil.jpg'?>);" class="foto-perfil">
					</div>
					<a href="perfil.php"><h1><?php echo $_SESSION['nome']." ".$_SESSION['sobrenome']; ?></h1></a><br/>
				</div>
				<div class="hot-post">
					<ul class="lista-menu">
						<li><a href='amigos.php'>Amigos</a></li>
						<li><a href='buscarUsuario.php'>Buscar Usuários</a></li>
					</ul>
					<div class="clearfix"></div>
				</div>
			</aside>
			<section class="mural-timeline">
				<div class="publicacao">
					<form method="POST" action="home.php" class="publicacao">
						<input type="txt" style="height:400%;" class="post box-effect" id="conteudo" name="conteudo" placeholder="Digite sua publicação"/>
						<input type="submit" class="button-enviar-publicacao post box-effect" value="Enviar" name="enviar"/>
					</form>
				</div>
				<?php
					$idUsuario=$_SESSION['id'];
					$ps=$con->query("SELECT distinct p.id, p.conteudo, p.dataHora, u.nome as usuario_nome,
					 u.sobrenome as usuario_sobrenome, u.apelido as usuario_apelido, u.id as usuario_id
					FROM publicacao AS p 
					JOIN usuario AS u ON p.idUsuario = u.id 
					JOIN amigos AS a ON (
						(p.idUsuario = a.idSolicitado AND a.idSolicitante = $idUsuario AND a.situacao = 'A') 
						OR 
						(p.idUsuario = a.idSolicitante AND a.idSolicitado= $idUsuario AND a.situacao = 'A')
						OR p.idUsuario = $idUsuario
					) ORDER BY p.id DESC LIMIT 100;");
					$regs = $ps->fetchAll();
					

					foreach($regs as $row) {

						$date = new DateTime( $row['dataHora'] );
						$dataBR = $date->format('d/m/y H:i:s');
						
					$card = "<article class='post box-effect'>".
					"<div class='topo'>".
					"<div class='post-esq topo-titulo'>".	
					"<p>" . $row['conteudo'] . "</p>".
					"</div>".
					"<div class='post-dir topo-autor'>".
					"<h4>" . $dataBR . "</h4>".
					"<p>" . $row['usuario_nome'] . " " . $row['usuario_sobrenome'] . "</p>".
					"<img style='width: 35px; height: 35px;' src='usuarios/" . $row['usuario_apelido'] . "/perfil.jpg'/>".
					"<div class='clearfix'></div>".
					"</div>".
					"<div class='clearfix'></div>".
					"</div>".
					"<div class='rodape'>".
					"<div class='post-esq'>".
					"<a>Continue reading</a>".
					"</div>".
					"<div class='post-dir'>".
					"<a>General</a>".
					"<i style='margin-left:10%;' class='fa fa-heart'>60</i>".
					"</div>".
					"</div>".
					"<div class='clearfix'></div>" .
					"</article>";

					echo $card;
					
					}
				?>
			</section>
			<aside class="mural mural-amizade box-effect">
			<div class="menu">
					<div id="logo"></div>
					<h2>Solicitações de Amizade</h2>
				</div>
				<div class="hot-post">
					<ul class="lista-menu">
                        <?php
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