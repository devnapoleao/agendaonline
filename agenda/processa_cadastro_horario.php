<?php
session_start();
include 'back/config.php'; // Inclui o arquivo de configuração do banco de dados

// Verifica se o usuário está logado
if (!isset($_SESSION['id_empresa'])) {
    echo "Usuário não está logado.";
    exit;
}

$idEmpresa = $_SESSION['id_empresa'];

// Cria uma conexão com o banco de dados
$conn = new mysqli($host, $username, $password, $dbname);

// Verifica a conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Obtém os dados do formulário
$data = $_POST['data'];
$horario = $_POST['horario'];

// Prepara a consulta para inserir os dados
$sql = "INSERT INTO HORARIOS (id_empresa, dia, horario, status) VALUES (?, ?, ?, 0)";
$stmt = $conn->prepare($sql);

// Vincula os parâmetros e executa
$stmt->bind_param("iss", $idEmpresa, $data, $horario);

if ($stmt->execute()) {
    echo "Horário cadastrado com sucesso!";
    // Redireciona para a página de dashboard ou outra página desejada
    header("Location: dash.php");
} else {
    echo "Erro ao cadastrar horário: " . $conn->error;
}

// Fecha a conexão
$conn->close();
?>
