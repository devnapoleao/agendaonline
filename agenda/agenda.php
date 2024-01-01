<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Agenda de Horários</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
<?php
session_start();
include 'back/config.php'; // Include your database configuration file

// Verifique se o e-mail foi passado como parâmetro na URL
$emailEmpresa = $_GET['email'] ?? '';

if (!$emailEmpresa) {
    die('E-mail da empresa não foi fornecido na URL.');
}


// Fetch the company details and available times from the database
$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

$stmt = $conn->prepare("SELECT id_empresa, nome FROM EMPRESA WHERE email = ?");
$stmt->bind_param("s", $emailEmpresa);
$stmt->execute();
$result = $stmt->get_result();
if ($empresa = $result->fetch_assoc()) {
    echo "<h2>Horários Disponíveis - " . htmlspecialchars($empresa['nome']) . "</h2>";
} else {
    echo "Empresa não encontrada.";
    exit;
}

// Fetch available times
$stmt = $conn->prepare("SELECT id_horario, dia, horario FROM HORARIOS WHERE id_empresa = ? AND status = 0 ORDER BY dia, horario");
$stmt->bind_param("i", $empresa['id_empresa']);
$stmt->execute();
$result = $stmt->get_result();
$horariosDisponiveis = $result->fetch_all(MYSQLI_ASSOC);

$conn->close();
?>
<a href='dash.php' class='botao-voltar'>Voltar para Dashboard</a>

<div id="calendario-container"></div>

<div id="popup" class="popup" style="display: none;">
    <!-- Popup content will go here -->
</div>

<div id="overlay" class="overlay" onclick="fecharPopup()" style="display: none;"></div>

<script src="script.js"></script>
<script>
    var horariosDisponiveis = <?php echo json_encode($horariosDisponiveis); ?>;
</script>
</body>
</html>
