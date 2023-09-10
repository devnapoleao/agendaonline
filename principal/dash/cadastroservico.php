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

// Variável para armazenar a mensagem de sucesso
$mensagem = "";

// Processar o formulário de cadastro de serviço
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nomeServico = $_POST['nome_servico'];
    $descricaoServico = $_POST['descricao_servico'];

    // Certifique-se de que o nome da empresa está disponível
    if (!empty($nomeEmpresa)) {
        // Consulta SQL para inserir o serviço no banco de dados
        $sql = "INSERT INTO servicos (nome_servico, descricao_servico, empresa) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $nomeServico, $descricaoServico, $nomeEmpresa); // Vincula o nome da empresa

        if ($stmt->execute() === TRUE) {
            $mensagem = "Serviço cadastrado com sucesso.";
        } else {
            $mensagem = "Erro ao cadastrar serviço: " . $stmt->error;
        }
    } else {
        $mensagem = "Nome da empresa não disponível.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Cadastro de Serviço</title>
</head>
<body>
    <h1>Cadastro de Serviço - <?php echo $nomeEmpresa; ?></h1>
    
    <!-- Exibir mensagem de sucesso ou erro -->
    <?php
    if (!empty($mensagem)) {
        echo "<p>$mensagem</p>";
    }
    ?>
    
    <form action="" method="POST">
        <label for="nome_servico">Nome do Serviço:</label>
        <input type="text" name="nome_servico" id="nome_servico" required>
        <br>
        <label for="descricao_servico">Descrição do Serviço:</label>
        <textarea name="descricao_servico" id="descricao_servico" required></textarea>
        <br>
        <input type="submit" value="Cadastrar Serviço">
    </form>
    
    <!-- Tabela de Serviços -->
    <h2>Serviços Cadastrados</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Nome do Serviço</th>
            <th>Descrição do Serviço</th>
            <th>Ações</th>
        </tr>
        <?php
        // Consulta SQL para buscar serviços cadastrados com base na empresa
        $sql = "SELECT id, nome_servico, descricao_servico FROM servicos WHERE empresa = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $nomeEmpresa);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['id'] . "</td>";
            echo "<td>" . $row['nome_servico'] . "</td>";
            echo "<td>" . $row['descricao_servico'] . "</td>";
            echo "<td><a href='#'>Editar</a> | <a href='#'>Excluir</a></td>";
            echo "</tr>";
        }
        ?>
    </table>
</body>
</html>




Temos o cadas fixo que não tem problema
mas no cadastro variável pode haver choque de horários, um cliente cadastrar um serviço de 12h - 13h
o outro cadastra de 12h - 12h30m. Como resolver a possibilidade de choque de horário?