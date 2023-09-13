<?php
// Inclua o arquivo de configuração com os dados de conexão ao banco de dados
require_once("config.php");

if (isset($_GET["empresa"]) && isset($_GET["mes"]) && isset($_GET["dia"])) {
    $emailEmpresa = $_GET["empresa"];
    $mesSelecionado = $_GET["mes"];
    $diaSelecionado = $_GET["dia"];

    // Criar uma conexão com o banco de dados usando os dados do arquivo config.php
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verificar a conexão
    if ($conn->connect_error) {
        die("Erro na conexão com o banco de dados: " . $conn->connect_error);
    }

    // Consulta SQL para obter os horários disponíveis com base no dia, mês e empresa
    $sqlHorariosDisponiveis = "SELECT horario FROM horarios_disponiveis WHERE empresa = ? AND mes = ? AND dia = ?";
    $stmtHorariosDisponiveis = $conn->prepare($sqlHorariosDisponiveis);
    $stmtHorariosDisponiveis->bind_param("sss", $emailEmpresa, $mesSelecionado, $diaSelecionado);
    $stmtHorariosDisponiveis->execute();
    $resultHorariosDisponiveis = $stmtHorariosDisponiveis->get_result();

    $horarios = array();

    while ($rowHorariosDisponiveis = $resultHorariosDisponiveis->fetch_assoc()) {
        $horarios[] = $rowHorariosDisponiveis;
    }

    // Feche a conexão com o banco de dados
    $stmtHorariosDisponiveis->close();
    $conn->close();

    // Retorne os horários disponíveis como JSON
    echo json_encode($horarios);
} else {
    echo "Parâmetros inválidos.";
}
