<?php
// Verificar se o usuário está autenticado (substitua esta lógica pela sua própria)
if (!isset($_COOKIE["usuario"])) {
    header("Location: index.php"); // Redireciona para a página de login se o usuário não estiver autenticado
    exit;
}

// Inclua o arquivo de configuração com os dados de conexão ao banco de dados
require_once("config.php");

// Obtém o email do usuário a partir do cookie (você pode usar esse email para identificar a empresa)
$emailUsuario = $_COOKIE["usuario"];

// Função para obter o nome da empresa com base no email do usuário
function obterNomeEmpresa($email, $conn) {
    // Consulta SQL para buscar o nome da empresa no banco de dados
    $sql = "SELECT empresa FROM empresas WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row["empresa"];
    } else {
        return "Empresa Desconhecida";
    }
}

// Criar uma conexão com o banco de dados usando os dados do arquivo config.php
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar a conexão
if ($conn->connect_error) {
    die("Erro na conexão com o banco de dados: " . $conn->connect_error);
}

// Obtém o nome da empresa
$nomeEmpresa = obterNomeEmpresa($emailUsuario, $conn);

// Função para obter o caminho da logomarca
function obterCaminhoLogo($emailUsuario) {
    $targetDir = "../logos/"; // Diretório onde as logomarcas estão armazenadas (subindo um nível para a pasta ao lado)
    $logoNome = $emailUsuario . ".png"; // Nome do arquivo (baseado no email)

    // Verificar se o arquivo da logomarca existe no diretório de logomarcas
    if (file_exists($targetDir . $logoNome)) {
        return $targetDir . $logoNome;
    } else {
        return $targetDir . "logo_padrao.png"; // Nome da logomarca padrão
    }
}

// Obtém o caminho da logomarca
$caminhoLogo = obterCaminhoLogo($emailUsuario);

?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <style>
        /* Estilos para o botão */
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff; /* Cor de fundo do botão */
            color: #fff; /* Cor do texto do botão */
            text-decoration: none; /* Remover sublinhado do link (caso seja usado dentro de um <a>) */
            border: none; /* Remover borda do botão */
            border-radius: 5px; /* Cantos arredondados */
            cursor: pointer;
        }

        /* Estilo de hover (quando o mouse passa por cima) */
        .btn:hover {
            background-color: #0056b3; /* Cor de fundo alterada no hover */
        }
    </style>
</head>
<body>
    <h1>Bem-vindo à Dashboard - <?php echo $nomeEmpresa; ?></h1>
    
    <!-- Exibe a logomarca -->
    <img src="<?php echo $caminhoLogo; ?>" alt="Logomarca" width="200">
    
    <!-- Botão estilizado -->
    <a class="btn" href="indique.php">Indique e Ganhe</a>
    
    <!-- Funcionalidades da Dashboard -->

   <!-- Botão estilizado -->
   <a class="btn" href="cadastrohorario.php">Cadastrar Horário</a>
   
    <!-- Adicione aqui o código para a funcionalidade "Cadastrar Serviço" -->


       <!-- Botão estilizado -->
   <a class="btn" href="cadastrarequipe.php">Cadastrar Equipe</a>

      <!-- Botão estilizado -->
      <a class="btn" href="cadastroservico.php">Cadastrar Serviço</a>

          <!-- Botão estilizado -->
          <a class="btn" href="cadastroagendamento.php">Cadastrar Agendamento</a>

</body>
</html>
