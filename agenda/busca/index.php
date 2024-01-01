<!DOCTYPE html>
<html lang="pt-br">
<head>
    <script src="script.js"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agendamento na <?php echo $nomeEmpresa; ?></title>
    <link rel="stylesheet" href="estilos.css">
    <style>
        body {
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
        }
    </style>
</head>
<body>

<?php
// Inclua o arquivo PHP que faz a conexão com o banco de dados e obtém os dados necessários
include 'filtro.php';

echo "<style>body { background-image: url('" . $caminhoImagemFundo . "'); }</style>";
echo "<div class='mensagem-empresa'>Agende já um horário na " . $nomeEmpresa . "</div>";

foreach ($dadosAgendamento as $mes => $infoMes) {
    echo "<div class='calendar'>";
    echo "<div class='month-header'>" . $mes . "</div>";
    echo "<div class='days-grid'>";

    foreach ($infoMes['dias'] as $dia => $horarios) {
        echo "<div class='day' onclick='showPopup(\"" . json_encode($horarios) . "\")'>" . $dia . "</div>";
    }

    echo "</div></div>";
}
?>

<div class="popup" id="popup">
    <span class="close-btn" onclick="hidePopup()">&times;</span>
    <p>Horários para o dia selecionado:</p>
    <div id="horariosDisponiveis">
        <!-- Horários disponíveis serão inseridos aqui pelo JavaScript -->
    </div>
</div>

</body>
</html>
