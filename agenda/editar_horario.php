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
    $sql = "SELECT dia, horario FROM HORARIOS WHERE id_horario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $idHorario);
    $stmt->execute();
    $result = $stmt->get_result();
    $horario = $result->fetch_assoc();

    if (!$horario) {
        echo "Horário não encontrado.";
        exit;
    }
} else {
    echo "ID do horário não especificado.";
    exit;
}

// Fecha a conexão
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Horário</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <h2>Editar Horário</h2>
    <form action="processa_edicao_horario.php" method="post">
        <input type="hidden" name="id_horario" value="<?php echo $idHorario; ?>">
        
        <label for="data">Data:</label>
        <input type="date" id="data" name="data" value="<?php echo $horario['dia']; ?>" required><br><br>

        <label for="horario">Horário:</label>
        <input type="time" id="horario" name="horario" value="<?php echo $horario['horario']; ?>" required><br><br>

        <input type="submit" value="Salvar Alterações">
    </form>
</body>
</html>
