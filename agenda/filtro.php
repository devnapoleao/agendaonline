<?php
include 'back/config.php'; // Inclui o arquivo de configuração

// Criar conexão
$conn = new mysqli($host, $username, $password, $dbname);

// Verifica conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Obtém o ID da empresa da sessão
$idEmpresa = $_SESSION['id_empresa'] ?? '';

// Consulta para obter os dados da empresa
$sqlEmpresa = "SELECT nome, imagem_fundo FROM EMPRESA WHERE id_empresa = ?";
$stmtEmpresa = $conn->prepare($sqlEmpresa);
$stmtEmpresa->bind_param("i", $idEmpresa);
$stmtEmpresa->execute();
$resultEmpresa = $stmtEmpresa->get_result();

if ($rowEmpresa = $resultEmpresa->fetch_assoc()) {
    $nomeEmpresa = $rowEmpresa['nome'];
    $caminhoImagemFundo = $rowEmpresa['imagem_fundo'];
} else {
    $nomeEmpresa = "Empresa não encontrada";
    $caminhoImagemFundo = "caminho_padrao/imagem_fundo.jpg";
}

// Consulta para obter os horários disponíveis
$sqlDisponiveis = "SELECT id_horario, dia, horario FROM HORARIOS WHERE id_empresa = ? AND status = 0 ORDER BY dia, horario";
$stmtDisponiveis = $conn->prepare($sqlDisponiveis);
$stmtDisponiveis->bind_param("i", $idEmpresa);
$stmtDisponiveis->execute();
$resultDisponiveis = $stmtDisponiveis->get_result();

$horariosDisponiveis = [];
while ($row = $resultDisponiveis->fetch_assoc()) {
    $horariosDisponiveis[] = $row;
}
// Consulta para obter os horários ocupados
$sqlOcupados = "SELECT dia, horario, cliente FROM HORARIOS WHERE id_empresa = ? AND status = 1 ORDER BY dia, horario";
$stmtOcupados = $conn->prepare($sqlOcupados);
$stmtOcupados->bind_param("i", $idEmpresa);
$stmtOcupados->execute();
$resultOcupados = $stmtOcupados->get_result();

$horariosOcupados = [];
while ($row = $resultOcupados->fetch_assoc()) {
    $horariosOcupados[] = $row;
}
$sqlDisponiveis = "SELECT id_horario, DATE_FORMAT(dia, '%Y-%m-%d') as dia, TIME_FORMAT(horario, '%H:%i') as horario FROM HORARIOS WHERE id_empresa = ? AND status = 0 ORDER BY dia, horario";
$stmtDisponiveis = $conn->prepare($sqlDisponiveis);
$stmtDisponiveis->bind_param("i", $idEmpresa);
$stmtDisponiveis->execute();
$resultDisponiveis = $stmtDisponiveis->get_result();

$horariosDisponiveis = [];
while ($row = $resultDisponiveis->fetch_assoc()) {
    $dia = $row['dia']; // Formato 'YYYY-MM-DD'
    $horariosDisponiveis[$dia][] = [
        'id_horario' => $row['id_horario'],
        'horario' => $row['horario']
    ];
}
// Fecha a conexão
$conn->close();

return [
    'nomeEmpresa' => $nomeEmpresa,
    'caminhoImagemFundo' => $caminhoImagemFundo,
    'horariosDisponiveis' => $horariosDisponiveis,
    'horariosOcupados' => $horariosOcupados
];

?>
