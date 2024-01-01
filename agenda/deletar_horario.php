<?php
session_start();
include 'back/config.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['id_empresa'])) {
    echo "Por favor, faça login para acessar esta página.";
    exit;
}

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

$idHorario = $_GET['id'] ?? '';

if ($idHorario) {
    $sql = "DELETE FROM HORARIOS WHERE id_horario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $idHorario);
    $result = $stmt->execute();

    if ($result) {
        echo "Horário excluído com sucesso.";
    } else {
        echo "Erro ao excluir o horário: " . $conn->error;
    }
} else {
    echo "ID do horário não especificado.";
}

// Fecha a conexão
$conn->close();

// Redireciona para o dashboard
header("Location: dash.php");
exit;
?>
