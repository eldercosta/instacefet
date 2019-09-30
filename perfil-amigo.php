<?php
	require_once 'api/Comum.php';
	require_once 'api/BDSingleton.php';

	$con=BDSingleton::getConexao();

	session_start();
	if( !isset($_SESSION) || isset($_SESSION['logado']) && $_SESSION['logado'] == false ){
		header("location: ./index.php"); // Vai pro inicio
	}
	
	if(isset($_GET['id']) && $_GET['id']>=0 ){
		$idUsuario=$_GET['id'];
		if($idUsuario==$_SESSION['id']){
			header("location: ./perfil.php"); 
		}

		$ps=$con->query("SELECT * FROM usuario WHERE id = $idUsuario;");
		$regs = $ps->fetchAll();
		foreach($regs as $row) {
			$amigoNome=$row['nome'];
			$amigoSobrenome=$row['sobrenome'];
			$amigoApelido=$row['apelido'];
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
					<div style="cursor:default;background-image:url(<?php echo 'usuarios/'.$amigoApelido.'/perfil.jpg'?>);" class="foto-perfil"></div>
					<h1><?php echo $amigoNome." ".$amigoSobrenome; ?></h1>
				</div>
				<div class="hot-post">
					<ul class="lista-menu">
						<?php
							$idSessao=$_SESSION['id'];
							$ps=$con->query("SELECT * FROM amigos WHERE (idSolicitante = $idSessao 
							or idSolicitante = $idUsuario)  AND (idSolicitado=$idUsuario or idSolicitado=$idSessao);");
							$regs = $ps->fetchAll();
							foreach($regs as $row){
								$situacao=$row['situacao'];
							}
								if(count($regs)<=0){
									echo '<li>';
									echo "<a href='solicitarAmizade.php?idSolicitado=" . $idUsuario . "'>";
									echo "Solicitar Amizade";
									echo "</a>";
									echo '</li>'; 
								}
								else{
									if($row['situacao']=='A'){
										echo '<li>';
										echo "<a href='desfazerAmizade.php?idSolicitado=" . $idUsuario . "'>";
										echo "Desfazer Amizade";
										echo "</a>";
										echo '</li>'; 
									}elseif($row['situacao']=='P' && $row['idSolicitante']==$idSessao){
										echo '<li>';
										echo "<a href='cancelarSolicitacao.php?idSolicitado=" . $idUsuario . "'>";
										echo "Cancelar Solicitação";
										echo "</a>";
										echo '</li>';
									}elseif($row['situacao']=='P' && $row['idSolicitado']==$idSessao){
										echo '<li>';
										echo "<a href='aceitarAmizade.php?idSolicitante=" . $idUsuario . "'>";
										echo "Aceitar Amizade";
										echo "</a>";
										echo '</li>';
										echo '<li>';
										echo "<a href='recusarAmizade.php?idSolicitante=" . $idUsuario . "'>";
										echo "Recusar Amizade";
										echo "</a>";
										echo '</li>';
									}
								}
						?>
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
				<div style="background-image:url(<?php echo 'usuarios/'.$amigoApelido.'/fundo.jpg'?>);" class="post box-effect mural-foto">
				</div>	
				<?php
				
					$idUsuario=$_GET['id'];
					$ps=$con->query("SELECT p.id, p.conteudo, p.dataHora, u.nome as usuario_nome,
					 u.sobrenome as usuario_sobrenome, u.apelido as usuario_apelido, u.id as usuario_id
					FROM publicacao AS p 
					JOIN usuario AS u ON (p.idUsuario = u.id AND u.id = $idUsuario
					)ORDER BY p.id DESC LIMIT 100;");

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
					"<i class='fa fa-heart'>60</i>".
					"<i class='fa fa-star-o'>160</i>".
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
							WHERE idSolicitado = $idSessao 
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