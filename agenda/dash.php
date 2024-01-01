
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Dashboard da Empresa</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <?php
    session_start();
    $emailEmpresa = $_SESSION['email_empresa'] ?? '';

    if (!$emailEmpresa) {
        echo "Por favor, faça login para acessar o dashboard.";
        exit;
    }

    $dados = include 'filtro.php';
    ?>

    <h2>Dashboard da Empresa - <?php echo $dados['nomeEmpresa']; ?></h2>


<section>
<a href="horarios_cadastrados.php" class="botao-voltar">Horários Cadastrados</a>
</section>
<section>
    <h3>Cadastrar Horários em Massa</h3>
    <form action="processa_cadastro_horario_massa.php" method="post">
        <label for="data_inicio">Data de Início:</label>
        <input type="date" id="data_inicio" name="data_inicio" required><br><br>

        <label for="data_fim">Data de Fim:</label>
        <input type="date" id="data_fim" name="data_fim" required><br><br>

        <label for="horario_inicio">Horário de Início (hh:mm):</label>
        <input type="time" id="horario_inicio" name="horario_inicio" required><br><br>

        <label for="horario_fim">Horário de Fim (hh:mm):</label>
        <input type="time" id="horario_fim" name="horario_fim" required><br><br>

        <label for="intervalo">Intervalo entre Horários (em minutos):</label>
        <input type="number" id="intervalo" name="intervalo" required><br><br>

        <input type="submit" value="Cadastrar Horários">
    </form>
</section>


    <section>
        <h3>Horários Ocupados</h3>
        <table>
            <tr>
                <th>Data</th>
                <th>Horário</th>
                <th>Cliente</th>
            </tr>
            <?php
            foreach ($dados['horariosOcupados'] as $horario) {
                echo "<tr><td>" . $horario['dia'] . "</td><td>" . $horario['horario'] . "</td><td>" . $horario['cliente'] . "</td></tr>";
            }
            ?>
        </table>
    </section>
</body>
</html>
