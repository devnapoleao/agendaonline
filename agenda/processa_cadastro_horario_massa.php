<?php
session_start();
include 'back/config.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['id_empresa'])) {
    echo "Usuário não logado. Acesso negado.";
    exit;
}

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

$idEmpresa = $_SESSION['id_empresa'];
$dataInicio = new DateTime($_POST['data_inicio']);
$dataFim = new DateTime($_POST['data_fim']);
$horarioInicio = $_POST['horario_inicio'];
$horarioFim = $_POST['horario_fim'];
$intervalo = $_POST['intervalo']; // Intervalo em minutos

// Prepara a inserção no banco de dados
$sql = "INSERT INTO HORARIOS (id_empresa, dia, horario, status) VALUES (?, ?, ?, 0)";
$stmt = $conn->prepare($sql);

$dataFormatada = '';
$horaFormatada = '';

// Loop pelas datas
for ($data = $dataInicio; $data <= $dataFim; $data->modify('+1 day')) {
    $inicio = DateTime::createFromFormat('H:i', $horarioInicio);
    $fim = DateTime::createFromFormat('H:i', $horarioFim);

    // Loop pelos horários
    for ($hora = $inicio; $hora <= $fim; $hora->modify('+' . $intervalo . ' minutes')) {
        $dataFormatada = $data->format('Y-m-d');
        $horaFormatada = $hora->format('H:i:s');
        $stmt->bind_param("iss", $idEmpresa, $dataFormatada, $horaFormatada);
        $stmt->execute();
    }
}

$conn->close();

// Redireciona para o dashboard após o cadastro dos horários
header("Location: dash.php");
exit;
?>
