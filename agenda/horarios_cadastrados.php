<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Horários Disponíveis</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <?php
    session_start();
    $emailEmpresa = $_SESSION['email_empresa'] ?? '';

    if (!$emailEmpresa) {
        echo "Por favor, faça login para acessar esta página.";
        exit;
    }

    // Inclui o filtro.php e recebe os dados
    $dados = include 'filtro.php'; 

    // Verifica se a variável $dados está definida e não é nula
    if (!isset($dados) || $dados === null) {
        echo "Erro ao carregar os dados.";
        exit;
    }

    echo "<h2>Horários Disponíveis - " . htmlspecialchars($dados['nomeEmpresa']) . "</h2>";

    // Botão de Voltar para Dashboard
    echo "<a href='dash.php' class='botao-voltar'>Voltar para Dashboard</a>";
    ?>

    <section>
        <table>
            <tr>
                <th>Data</th>
                <th>Horário</th>
                <th>Ações</th>
            </tr>
            <?php
            foreach ($dados['horariosDisponiveis'] as $horario) {
                echo "<tr><td>" . htmlspecialchars($horario['dia']) . "</td><td>" . htmlspecialchars($horario['horario']) . "</td>";
                echo "<td><a href='editar_horario.php?id=" . htmlspecialchars($horario['id_horario']) . "'>Editar</a> ";
                echo "<a href='deletar_horario.php?id=" . htmlspecialchars($horario['id_horario']) . "' onclick='return confirm(\"Tem certeza?\");'>Deletar</a></td></tr>";
            }
            ?>
        </table>
    </section>
</body>
</html>
