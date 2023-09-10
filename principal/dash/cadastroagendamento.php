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

// Consulta SQL para obter os serviços da empresa
$sqlServicos = "SELECT id, nome_servico, descricao_servico FROM servicos WHERE empresa = ?";
$stmtServicos = $conn->prepare($sqlServicos);
$stmtServicos->bind_param("s", $emailUsuario);
$stmtServicos->execute();
$resultServicos = $stmtServicos->get_result();

// Consulta SQL para obter os nomes da equipe da empresa
$sqlEquipe = "SELECT id, nome FROM equipe WHERE empresa = ?";
$stmtEquipe = $conn->prepare($sqlEquipe);
$stmtEquipe->bind_param("s", $emailUsuario);
$stmtEquipe->execute();
$resultEquipe = $stmtEquipe->get_result();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cliente = $_POST['cliente'];
    $telefone = $_POST['telefone'];
    $email = $_POST['email'];
    $endereco = $_POST['endereco'];
    $servicoId = $_POST['servico'];
    $equipeId = $_POST['equipe'];
    $horario = $_POST['horario'];
    $empresa = $emailUsuario; // Use o email do usuário como identificação da empresa

    // Certifique-se de que os campos necessários estão preenchidos
    if (!empty($cliente) && !empty($telefone) && !empty($email) && !empty($endereco) && !empty($servicoId) && !empty($equipeId) && !empty($horario)) {
        // Consulta SQL para inserir o agendamento no banco de dados (use prepared statements para evitar SQL injection)
        $sql = "INSERT INTO agendamentos (cliente, telefone, email, endereco, servico_id, equipe_id, horario, empresa) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssss", $cliente, $telefone, $email, $endereco, $servicoId, $equipeId, $horario, $empresa);

        if ($stmt->execute() === TRUE) {
            echo "Agendamento cadastrado com sucesso!";
        } else {
            echo "Erro ao cadastrar agendamento: " . $stmt->error;
        }
    } else {
        echo "Por favor, preencha todos os campos antes de enviar o formulário.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Cadastro de Agendamento</title>
</head>
<body>
    <h1>Cadastro de Agendamento - <?php echo $nomeEmpresa; ?></h1>

    <form action="" method="POST">
        <label for="cliente">Cliente:</label>
        <input type="text" name="cliente" id="cliente" required>
        <br>

        <label for="telefone">Telefone:</label>
        <input type="text" name="telefone" id="telefone" required>
        <br>

        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required>
        <br>

        <label for="endereco">Endereço:</label>
        <input type="text" name="endereco" id="endereco" required>
        <br>

    <label for="servico">Serviço:</label>
<select name="servico" id="servico" required>
    <option value="">Selecione um serviço</option>
    <?php
    // Substitua 'sua_conexao' pela sua conexão com o banco de dados
    $sqlServico = "SELECT nome_servico FROM servicos WHERE empresa = '$nomeEmpresa'";
    $resultServicoQuery = $conn->query($sqlServico); // Use $conn em vez de sua_conexao

    while ($rowServico = $resultServicoQuery->fetch_assoc()) {
        echo '<option value="' . $rowServico['nome_servico'] . '">' . $rowServico['nome_servico'] . '</option>';
    }
    ?>
</select>
<br>



        <label for="equipe">Equipe:</label>
<select name="equipe" id="equipe" required>
    <option value="">Selecione um membro da equipe</option>
    <?php
    // Substitua 'sua_conexao' pela sua conexão com o banco de dados
    $sqlEquipe = "SELECT nome FROM equipe WHERE empresa = '$nomeEmpresa'";
    $resultEquipeQuery = $conn->query($sqlEquipe); // Use $conn em vez de sua_conexao

    while ($rowEquipe = $resultEquipeQuery->fetch_assoc()) {
        echo '<option value="' . $rowEquipe['nome'] . '">' . $rowEquipe['nome'] . '</option>';
    }
    ?>
</select>
<br>




<?php
// Substitua 'sua_conexao' pela sua conexão com o banco de dados
$sqlEmailEmpresa = "SELECT email FROM empresas WHERE empresa = '$nomeEmpresa'";
$resultEmailEmpresa = $conn->query($sqlEmailEmpresa);

if ($resultEmailEmpresa->num_rows > 0) {
    $rowEmailEmpresa = $resultEmailEmpresa->fetch_assoc();
    $emailEmpresa = $rowEmailEmpresa['email'];

    // Agora, você tem o email da empresa, pode usar isso para buscar os meses disponíveis
    $sqlMesesDisponiveis = "SELECT DISTINCT mes FROM horarios_disponiveis WHERE empresa = '$emailEmpresa'";
    $resultMesesDisponiveis = $conn->query($sqlMesesDisponiveis);
?>


<label for="mes">Mês Disponível:</label>
<select name="mes" id="mes" required>
    <option value="">Selecione um mês disponível</option>
    <?php
    while ($rowMes = $resultMesesDisponiveis->fetch_assoc()) {
        echo '<option value="' . $rowMes['mes'] . '">' . $rowMes['mes'] . '</option>';
    }
    ?>
</select>
<br>
<?php
} else {
    echo "Empresa não encontrada";
}
?>

        <input type="submit" value="Agendar">
    </form>
</body>
</html>
