<?php

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
    <h1>Suas indicações - <?php echo $nomeEmpresa; ?></h1>
    
    <!-- Exibe a logomarca -->
    <img src="<?php echo $caminhoLogo; ?>" alt="Logomarca" width="200">
    
    
    <!-- Funcionalidades da Dashboard -->

    <h1>Suas indicações</h1>

<!-- Adicione o código JavaScript para copiar o link ao clicar -->
<script>
    function copiarLink() {
        // Selecione o elemento que contém o link
        var linkElement = document.getElementById('linkIndicacao');

        // Crie um campo de texto temporário
        var tempInput = document.createElement('input');
        tempInput.value = linkElement.textContent;

        // Adicione o campo de texto temporário à página
        document.body.appendChild(tempInput);

        // Selecione o campo de texto temporário
        tempInput.select();
        tempInput.setSelectionRange(0, 99999); // Para dispositivos móveis

        // Copie o texto para a área de transferência
        document.execCommand('copy');

        // Remova o campo de texto temporário da página
        document.body.removeChild(tempInput);

        // Exiba uma mensagem de sucesso
        alert('Link copiado para a área de transferência: ' + linkElement.textContent);
    }
</script>

<?php
// Obtém o email do usuário a partir do cookie (você pode usar esse email para identificar a empresa)
$emailUsuario = $_COOKIE["usuario"];

// Crie o link de indicação
$linkIndicacao = "https://localhost/fisiogerlan/novas.php?indicado=" . urlencode($emailUsuario) . "&indicacao=true&nomeEmpresa=" . urlencode($nomeEmpresa);


?>

<!-- Exibe o link de indicação com um ID para selecioná-lo facilmente no JavaScript -->
<h2>Seu link de indicação:</h2>
<p id="linkIndicacao"><?php echo $linkIndicacao; ?></p>

<!-- Botão para copiar o link -->
<button class="btn" onclick="copiarLink()">Copiar Link</button>

    <!-- Adicione aqui o código para a funcionalidade "Cadastrar Horário" -->

    <h2>Quantidade de Indicações: </h2>
    <?php
    // Consulta SQL para contar quantas vezes o nome da empresa se repete na coluna empresaindicadora
    $sql_count = "SELECT COUNT(*) as quantidade FROM empresas WHERE empresaindicadora = '$nomeEmpresa'";
    
    $result = $conn->query($sql_count);
    $row = $result->fetch_assoc();
    $quantidade_indicacoes = $row['quantidade'];

    echo "<p>Quantidade de indicações para '$nomeEmpresa': $quantidade_indicacoes</p>";
    // Calcula o valor a receber
$valor_a_receber = $quantidade_indicacoes * 15.00;

// Exibe o valor formatado em reais (R$)
echo "<p>Valor a receber para '$nomeEmpresa': R$ " . number_format($valor_a_receber, 2, ',', '.') . "</p>";
    ?>
   
</body>
</html>
