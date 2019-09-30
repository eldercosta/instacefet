<?php
    require_once "api/BDSingleton.php";
    require_once "api/Comum.php";

    $con=BDSingleton::getConexao();

        // Se já estiver logado, manda o usuario pra página inicial
        session_start();
        if( isset($_SESSION) && isset($_SESSION['logado']) && $_SESSION['logado'] == true )
        header("location: ./home.php"); // Vai pro inicio

        if (isset($_POST['login-entrar'])){
            if( isset($_POST['login-chave']) && $_POST['login-chave'] != "" && isset($_POST['login-senha']) && $_POST['login-senha'] != "" ) {
                $email=Comum::limparHTML($_POST['login-chave']);
                $senha=Comum::limparHTML($_POST['login-senha']);

                $ps=$con->query("SELECT * FROM usuario where email='$email' or apelido='$email';");
                $verifica = $ps->fetchAll();
                    if(count($verifica)<=0){
                        echo "<script>alert('Usuário ou Senha inválidos!');</script>";
                    }else{
                        $hash = $verifica[0]['senha'];
                        if( password_verify( $senha, $hash ) ) {
                            session_start();
                            $_SESSION['id']=$verifica[0]['id'];
                            $_SESSION['logado']=true;
                            $_SESSION['apelido']=$verifica[0]['apelido'];
                            $_SESSION['email']=$verifica[0]['email'];
                            $_SESSION['nome']=$verifica[0]['nome'];
                            $_SESSION['sobrenome']=$verifica[0]['sobrenome'];
                            echo "<script>alert('Login realizado!');</script>";
                            header("Location: ./home.php");
                        }else{
                            echo "<script>alert('Senha inválida');</script>";
                        }       
                    }
            }
        }

        if (isset($_POST['cadastrar'])){
            if(!(($_POST['cadastro-email']=="") || ($_POST['cadastro-senha']=="") || ($_POST['cadastro-apelido']=="") || ($_POST['cadastro-nome']=="") || ($_POST['birthday_day']==0) || ($_POST['birthday_month']==0) || ($_POST['birthday_year']==0))){
                
                //Capturando informações do cadastro do usuário
                $usuario['nome']=Comum::limparHTML($_POST['cadastro-nome']);
                $usuario['sobrenome']=Comum::limparHTML($_POST['cadastro-sobrenome']);
                $usuario['apelido']=Comum::limparHTML($_POST['cadastro-apelido']);
                $usuario['email']=Comum::limparHTML($_POST['cadastro-email']);
                $usuario['senha']=Comum::limparHTML($_POST['cadastro-senha']);
                $usuario['contrasenha']=Comum::limparHTML($_POST['cadastro-contrasenha']);

                //Capturando informações da data de nascimento e fazendo o calculo da idade
                $usuario['dia']=$_POST['birthday_day'];
                $usuario['mes']=$_POST['birthday_month'];
                $usuario['ano']=$_POST['birthday_year'];
                $usuario['dtNasc']=$usuario['ano'].'/'.$usuario['mes'].'/'.$usuario['dia'];
                $usuario['dtIngres']=date("Y/m/d");
                $diasHoje = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
                $diasNascimento = mktime( 0, 0, 0, $usuario['mes'], $usuario['dia'], $usuario['ano']);
                $idade = floor((((($diasHoje - $diasNascimento) / 60) / 60) / 24) / 365.25);
                
                $email= bd_verificar_email_existe( $con,$usuario['email']);     //Verificando se já existe usuário com o email
                $apelido= bd_verificar_apelido_existe( $con,$usuario['apelido']);   //Verificando se já existe usuário com o apelido
                
                if($email!=false){      //Condição caso email exista
		            echo "<script>alert('E-mail já existente!');</script>";
	            }else if($apelido!=false){      //Condição caso apelido exista
                    echo "<script>alert('Apelido já existente!');</script>";
                }elseif($usuario['senha']!=$usuario['contrasenha']){        //Verificando se Senhas coincidem
                    echo "<script>alert('Senhas não Coincidem!');</script>";
                }elseif(!ctype_alnum($usuario['nome'])){        //Verificando se nome passado é alfanumerico
                    echo "<script>alert('Nome inválido!');</script>";
                }elseif( ( !$usuario['sobrenome']=="" && !ctype_alnum( $usuario['sobrenome'] ) ) ){     //Verificando se sobrenome é alfanumerico. Condição especial - Sobrenome pode ser nulo
                    echo "<script>alert('Sobrenome inválido!');</script>";
                }elseif(!preg_match( '/^[\w.-]+@[\w.-]+\.[a-zA-Z]{2,6}$/', $usuario['email'])){     //Verificando se é email válido
                    echo "<script>alert('Email Inválido!');</script>";
                }elseif(!preg_match('/^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&+=]).{8,}$/',$usuario['senha'])){      //Verificando criterios da senha
                    echo "<script>alert('Senha deve possuir no minimo 8 caracteres, 1 letra maíuscula, 1 minuscula e 1 caracter especial!');</script>";
                }elseif(preg_match('/[ \t\n\r\f\v]/',$usuario['apelido'])){     //Verificando se não existem espaços no apelido
                    echo "<script>alert('Apelido Inválido!');</script>";
                }elseif($idade<18){     //Verificando se usuário é maior de 18 anos
                    echo "<script>alert('Idade Insuficiente Para Enviar Nudes!');</script>";
                }else{
                    $usuario['senha']=password_hash($_POST['cadastro-senha'],PASSWORD_DEFAULT);     //Protegendo senha do usuário no Banco
                    $cards=bd_adicionar_usuario( $con, $usuario );      //Utilizando função do banco para adicionar usuário
		            //var_dump( $con->errorInfo() );
                    if(!file_exists('usuarios/'.$usuario['apelido']))
                    {
                        MKDIR('usuarios/'.$usuario['apelido'],0775,true);
                    }
                    copy('./img/users_default/perfilPadrao.png','usuarios/'.$usuario['apelido'].'/perfil.jpg');
                    copy('./img/users_default/fundoPadrao.png','usuarios/'.$usuario['apelido'].'/fundo.jpg');
                    echo "<script>alert('Usuário Cadastrado!');</script>";
                }
            }else{
                echo "<script>alert('Informações Obrigatórias Ausentes!');</script>";
            }
        }
        
?>
<html lang="pt-br">
    <head>
        <meta charset="utf-8"/>
        <link href="https://fonts.googleapis.com/css?family=Squada+One|Luckiest+Guy|Josefin+Sans|Bowlby+One+SC|Oswald|Roboto+Condensed|Tajawal" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="css/index.css">
    </head>
    <body>
        <div class="header slow-transition">
            <div class="wrapper">
                <a class="logo" href="index.php">INSTACEFET</a>
                <form id="form-login" class="login-form" action="index.php" method="POST">
                    <input type="txt" name="login-chave" class="login-email box-effect" id="login-email" placeholder="Digite seu E-mail" autofocus="autofocus">
                    <input type="password" name="login-senha" class="login-password box-effect" id="login-senha" aria-required="true" placeholder="Digite sua Senha">
                    <input type="submit" name="login-entrar" class="login-submit-button box-effect" id="login-entrar" value="Entrar" >
                </form>
            </div>
        </div>
        <main>
            <div class="cadastro-box slow-transition">
                <div class="cadastro-subtitle">
                    <img src="img/meet-icon.png" alt="meet"/>
                    <h3>Cadastre-se e conheça um Novo Mundo!</h3>
                </div>
                <div class="form-body">
                    <form id="form-cadastro" class="form-cadastro" action="index.php" method="POST">
                        <input type="text" name="cadastro-nome" class="cadastro-nome box-effect" id="nome" placeholder="Digite seu Nome*">
                        <input type="text" name="cadastro-sobrenome" class="cadastro-sobrenome box-effect" id="sobrenome" placeholder="Digite seu Sobrenome*">
                        <input type="text" name="cadastro-apelido" class="cadastro-apelido box-effect" id="apelido" placeholder="Digite seu Apelido*">
                        <input type="email" name="cadastro-email" class="cadastro-email box-effect" id="cadastro-email" placeholder="Digite seu E-mail*">
                        <input type="password" name="cadastro-senha" class="cadastro-senha box-effect" id="cadastro-senha" aria-required="true" placeholder="Digite sua Senha*">
                        <input type="password" name="cadastro-contrasenha" class="cadastro-senha box-effect" id="cadastro-contrasenha" aria-required="true" placeholder="Confirme sua Senha*">
                        <div class="box-dt-nasc">
                            <span class="dt-nasc-txt">Data de Nascimento:</span>
                            <span class="seletor-dt" data-type="selectors" data-name="birthday_wrapper" id="birthday-wrapper">
                                <span>
                                    <select aria-label="Dia" name="birthday_day" id="day" title="Dia" class="box-birthday box-effect">
                                        <option value="0">Dia</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                        <option value="6">6</option>
                                        <option value="7">7</option>
                                        <option value="8">8</option>
                                        <option value="9">9</option>
                                        <option value="10">10</option>
                                        <option value="11">11</option>
                                        <option value="12">12</option>
                                        <option value="13">13</option>
                                        <option value="14">14</option>
                                        <option value="15">15</option>
                                        <option value="16">16</option>
                                        <option value="17">17</option>
                                        <option value="18">18</option>
                                        <option value="19">19</option>
                                        <option value="20">20</option>
                                        <option value="21">21</option>
                                        <option value="22">22</option>
                                        <option value="23" selected="1">23</option>
                                        <option value="24">24</option>
                                        <option value="25">25</option>
                                        <option value="26">26</option>
                                        <option value="27">27</option>
                                        <option value="28">28</option>
                                        <option value="29">29</option>
                                        <option value="30">30</option>
                                        <option value="31">31</option>
                                    </select>
                                    <select aria-label="Mês" name="birthday_month" id="month" title="Mês" class="box-birthday box-effect">
                                        <option value="0">Mês</option>
                                        <option value="01">Janeiro</option>
                                        <option value="02">Fevereiro</option>
                                        <option value="03" selected="1">Março</option>
                                        <option value="04">Abril</option>
                                        <option value="05">Maio</option>
                                        <option value="06">Junho</option>
                                        <option value="07">Julho</option>
                                        <option value="08">Agosto</option>
                                        <option value="09">Setembro</option>
                                        <option value="10">Outubro</option>
                                        <option value="11">Novembro</option>
                                        <option value="12">Dezembro</option>
                                    </select>
                                    <select aria-label="Ano" name="birthday_year" id="year" title="Ano" class="box-birthday box-effect">
                                        <option value="0">Ano</option>
                                        <option value="2018">2018</option>
                                        <option value="2017">2017</option>
                                        <option value="2016">2016</option>
                                        <option value="2015">2015</option>
                                        <option value="2014">2014</option>
                                        <option value="2013">2013</option>
                                        <option value="2012">2012</option>
                                        <option value="2011">2011</option>
                                        <option value="2010">2010</option>
                                        <option value="2009">2009</option>
                                        <option value="2008">2008</option>
                                        <option value="2007">2007</option>
                                        <option value="2006">2006</option>
                                        <option value="2005">2005</option>
                                        <option value="2004">2004</option>
                                        <option value="2003">2003</option>
                                        <option value="2002">2002</option>
                                        <option value="2001">2001</option>
                                        <option value="2000">2000</option>
                                        <option value="1999">1999</option>
                                        <option value="1998">1998</option>
                                        <option value="1997">1997</option>
                                        <option value="1996">1996</option>
                                        <option value="1995">1995</option>
                                        <option value="1994">1994</option>
                                        <option value="1993" selected="1">1993</option>
                                        <option value="1992">1992</option>
                                        <option value="1991">1991</option>
                                        <option value="1990">1990</option>
                                        <option value="1989">1989</option>
                                        <option value="1988">1988</option>
                                        <option value="1987">1987</option>
                                        <option value="1986">1986</option>
                                        <option value="1985">1985</option>
                                        <option value="1984">1984</option>
                                        <option value="1983">1983</option>
                                        <option value="1982">1982</option>
                                        <option value="1981">1981</option>
                                        <option value="1980">1980</option>
                                        <option value="1979">1979</option>
                                        <option value="1978">1978</option>
                                        <option value="1977">1977</option>
                                        <option value="1976">1976</option>
                                        <option value="1975">1975</option>
                                        <option value="1974">1974</option>
                                        <option value="1973">1973</option>
                                        <option value="1972">1972</option>
                                        <option value="1971">1971</option>
                                        <option value="1970">1970</option>
                                        <option value="1969">1969</option>
                                        <option value="1968">1968</option>
                                        <option value="1967">1967</option>
                                        <option value="1966">1966</option>
                                        <option value="1965">1965</option>
                                        <option value="1964">1964</option>
                                        <option value="1963">1963</option>
                                        <option value="1962">1962</option>
                                        <option value="1961">1961</option>
                                        <option value="1960">1960</option>
                                        <option value="1959">1959</option>
                                        <option value="1958">1958</option>
                                        <option value="1957">1957</option>
                                        <option value="1956">1956</option>
                                        <option value="1955">1955</option>
                                        <option value="1954">1954</option>
                                        <option value="1953">1953</option>
                                        <option value="1952">1952</option>
                                        <option value="1951">1951</option>
                                        <option value="1950">1950</option>
                                        <option value="1949">1949</option>
                                        <option value="1948">1948</option>
                                        <option value="1947">1947</option>
                                        <option value="1946">1946</option>
                                        <option value="1945">1945</option>
                                        <option value="1944">1944</option>
                                        <option value="1943">1943</option>
                                        <option value="1942">1942</option>
                                        <option value="1941">1941</option>
                                        <option value="1940">1940</option>
                                        <option value="1939">1939</option>
                                        <option value="1938">1938</option>
                                        <option value="1937">1937</option>
                                        <option value="1936">1936</option>
                                        <option value="1935">1935</option>
                                        <option value="1934">1934</option>
                                        <option value="1933">1933</option>
                                        <option value="1932">1932</option>
                                        <option value="1931">1931</option>
                                        <option value="1930">1930</option>
                                        <option value="1929">1929</option>
                                        <option value="1928">1928</option>
                                        <option value="1927">1927</option>
                                        <option value="1926">1926</option>
                                        <option value="1925">1925</option>
                                        <option value="1924">1924</option>
                                        <option value="1923">1923</option>
                                        <option value="1922">1922</option>
                                        <option value="1921">1921</option>
                                        <option value="1920">1920</option>
                                        <option value="1919">1919</option>
                                        <option value="1918">1918</option>
                                        <option value="1917">1917</option>
                                        <option value="1916">1916</option>
                                        <option value="1915">1915</option>
                                        <option value="1914">1914</option>
                                        <option value="1913">1913</option>
                                        <option value="1912">1912</option>
                                        <option value="1911">1911</option>
                                        <option value="1910">1910</option>
                                        <option value="1909">1909</option>
                                        <option value="1908">1908</option>
                                        <option value="1907">1907</option>
                                        <option value="1906">1906</option>
                                        <option value="1905">1905</option>
                                    </select>
                                </span>
                            </span>
                        </div>
                        <input type="submit" name="cadastrar" class="cadastrar-submit-button box-effect" id="cadastrar-submit" value="Cadastrar-se" >
                        <div class="aviso-cadastro">
                            <span class="aviso-txt">Ao clicar em Cadastrar-se, você concorda com nossos Termos, Política de Dados e Política de Cookies. Você pode receber notificações por SMS e pode cancelar isso quando quiser.</span>
                        </div>
                    </form>
                </div>
            </div>
        </main>
        <footer>
        </footer>
    </body>
</html>