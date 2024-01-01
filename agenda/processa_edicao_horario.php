<?php
session_start();
include 'back/config.php';

// Verifica se o usuário está logado e tem permissão
if (!isset($_SESSION['id_empresa'])) {
    echo "Usuário não logado. Acesso negado.";
    exit;
}

// Conexão com o banco de dados
$conn = new mysqli($host, $username, $password, $dbname);

// Verifica a conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Recebe os dados do formulário
$idHorario = $_POST['id_horario'];
$data = $_POST['data'];
$horario = $_POST['horario'];

// Prepara a consulta SQL para atualizar o horário
$sql = "UPDATE HORARIOS SET dia = ?, horario = ? WHERE id_horario = ? AND id_empresa = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssii", $data, $horario, $idHorario, $_SESSION['id_empresa']);

// Executa a consulta
if ($stmt->execute()) {
    echo "Horário atualizado com sucesso.";
    // Redireciona para o dashboard ou outra página conforme desejado
    header("Location: dash.php");
} else {
    echo "Erro ao atualizar o horário: " . $conn->error;
}

// Fecha a conexão
$conn->close();
?>
