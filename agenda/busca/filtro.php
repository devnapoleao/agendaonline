<?php
include 'back/config.php'; // Inclui o arquivo de configuração

// Criar conexão
$conn = new mysqli($host, $username, $password, $dbname);

// Verifica conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Obtém o e-mail da empresa da URL
$emailEmpresa = isset($_GET['email']) ? $_GET['email'] : '';

// Consulta para obter os dados da empresa
$sqlEmpresa = "SELECT nome, imagem_fundo FROM EMPRESA WHERE email = ?";
$stmtEmpresa = $conn->prepare($sqlEmpresa);
$stmtEmpresa->bind_param("s", $emailEmpresa);
$stmtEmpresa->execute();
$resultEmpresa = $stmtEmpresa->get_result();

if ($rowEmpresa = $resultEmpresa->fetch_assoc()) {
    $nomeEmpresa = $rowEmpresa['nome'];
    $caminhoImagemFundo = $rowEmpresa['imagem_fundo'];
} else {
    $nomeEmpresa = "Empresa não encontrada";
    $caminhoImagemFundo = "caminho_padrao/imagem_fundo.jpg"; // Substitua pelo caminho padrão
}

// Consulta para obter os meses, dias e horários disponíveis
$sql = "SELECT mes, dia, horario FROM HORARIOS WHERE id_empresa = (SELECT id_empresa FROM EMPRESA WHERE email = ?) AND status = 0 ORDER BY mes, dia, horario";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $emailEmpresa);
$stmt->execute();
$result = $stmt->get_result();

$dadosAgendamento = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $mes = $row['mes'];
        $dia = $row['dia'];
        $horario = $row['horario'];
        $dadosAgendamento[$mes]['dias'][$dia][] = $horario;
    }
} else {
    echo "0 resultados";
}

// Fecha a conexão
$conn->close();

return [
    'nomeEmpresa' => $nomeEmpresa,
    'caminhoImagemFundo' => $caminhoImagemFundo,
    'dadosAgendamento' => $dadosAgendamento
];
?>
