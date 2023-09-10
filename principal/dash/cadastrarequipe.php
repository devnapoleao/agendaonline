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

// Processar o formulário de cadastro de membro da equipe
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nomeMembro = $_POST['nome'];
    $contatoMembro = $_POST['contato'];

    // Certifique-se de que o nome da empresa está disponível
    if (!empty($nomeEmpresa)) {
        // Consulta SQL para inserir o membro da equipe no banco de dados
        $sql = "INSERT INTO equipe (nome, contato, empresa) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $nomeMembro, $contatoMembro, $nomeEmpresa); // Vincula o nome da empresa

        if ($stmt->execute() === TRUE) {
            echo "Membro da equipe cadastrado com sucesso.";
        } else {
            echo "Erro ao cadastrar membro da equipe: " . $stmt->error;
        }
    } else {
        echo "Nome da empresa não disponível.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Cadastro de Equipe</title>
</head>
<body>
    <h1>Cadastro de Membro da Equipe - <?php echo $nomeEmpresa; ?></h1>
    
    <form action="" method="POST">
        <label for="nome">Nome do Membro:</label>
        <input type="text" name="nome" id="nome" required>
        <br>
        <label for="contato">Contato:</label>
        <input type="text" name="contato" id="contato" required>
        <br>
        <input type="submit" value="Cadastrar Membro">
    </form>
    <!-- Tabela de Membros da Equipe -->
<h2>Membros da Equipe</h2>
<table>
    <tr>
        <th>ID</th>
        <th>Nome</th>
        <th>Contato</th>
        <th>Ações</th>
    </tr>
    <?php
    // Consulta SQL para buscar membros da equipe com base na empresa
    $sql = "SELECT id, nome, contato FROM equipe WHERE empresa = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $nomeEmpresa);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['id'] . "</td>";
        echo "<td>" . $row['nome'] . "</td>";
        echo "<td>" . $row['contato'] . "</td>";
        echo "<td><a href='#'>Editar</a> | <a href='#'>Excluir</a></td>";
        echo "</tr>";
    }
    ?>
</table>
</body>
</html>
