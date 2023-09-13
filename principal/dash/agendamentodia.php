<!DOCTYPE html>
<html lang="en">
<head>
    <!-- ... -->
</head>
<body>
    <img src="logo.png" alt="Logo da sua empresa">
    <h1>Consultar Dia Disponível</h1>
    <form action="agendamentohora.php" method="GET">
        <?php
        // Verificar se o usuário está autenticado (substitua esta lógica pela sua própria)
        if (!isset($_COOKIE["usuario"])) {
            die("Usuário não autenticado."); // Trate a falta de autenticação aqui
        }

        // Inclua o arquivo de configuração com os dados de conexão ao banco de dados
        require_once("config.php");

        // Criar uma conexão com o banco de dados usando os dados do arquivo config.php
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Verificar a conexão
        if ($conn->connect_error) {
            die("Erro na conexão com o banco de dados: " . $conn->connect_error);
        }

        // Obtém o email do usuário a partir do cookie (você pode usar esse email para identificar a empresa)
        $emailUsuario = $_COOKIE["usuario"];

        // Consulta SQL para buscar o nome da empresa e o email da empresa no banco de dados
        $sql = "SELECT empresa, email FROM empresas WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $emailUsuario);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $nomeEmpresa = $row["empresa"];
            $emailEmpresa = $row["email"];

            // Verifique se foi selecionado um mês
            if (isset($_GET["mes"])) {
                $mesSelecionado = $_GET["mes"];

                // Consulta para obter os dias disponíveis com base no mês e na empresa
                $sqlDiasDisponiveis = "SELECT DISTINCT dia FROM horarios_disponiveis WHERE empresa = ? AND mes = ?";
                $stmtDiasDisponiveis = $conn->prepare($sqlDiasDisponiveis);
                $stmtDiasDisponiveis->bind_param("ss", $emailEmpresa, $mesSelecionado);
                $stmtDiasDisponiveis->execute();
                $resultDiasDisponiveis = $stmtDiasDisponiveis->get_result();

                if ($resultDiasDisponiveis->num_rows > 0) {
                    echo "<label for='dia'>Selecione o Dia:</label>";
                    echo "<select name='dia' id='dia'>";
                    while ($rowDiasDisponiveis = $resultDiasDisponiveis->fetch_assoc()) {
                        echo "<option value='{$rowDiasDisponiveis['dia']}'>{$rowDiasDisponiveis['dia']}</option>";
                    }
                    echo "</select><br>";
                } else {
                    echo "<p>Nenhum dia disponível encontrado para o mês selecionado.</p>";
                }
            } else {
                echo "<p>Por favor, selecione um mês para ver os dias disponíveis.</p>";
            }
        } else {
            echo "<p>Empresa não encontrada.</p>";
        }
        ?>
        <!-- Adicione campos de input escondidos para enviar mês, empresa e dia via URL -->
    <input type="hidden" name="mes" value="<?php echo $mesSelecionado; ?>">
    <input type="hidden" name="dia" value="<?php echo isset($_GET["dia"]) ? $_GET["dia"] : ''; ?>">
    
        <input type="submit" value="Consultar Dia Disponível">
    </form>
    
    <?php
    // ...

    // Verifique se o dia foi selecionado
    if (isset($_GET["dia"])) {
        $diaSelecionado = $_GET["dia"];

        // Consulta SQL para obter os horários disponíveis com base no dia, mês e empresa
        $sqlHorariosDisponiveis = "SELECT horario FROM horarios_disponiveis WHERE empresa = ? AND mes = ? AND dia = ?";
        $stmtHorariosDisponiveis = $conn->prepare($sqlHorariosDisponiveis);
        $stmtHorariosDisponiveis->bind_param("sss", $emailEmpresa, $mesSelecionado, $diaSelecionado);
        $stmtHorariosDisponiveis->execute();
        $resultHorariosDisponiveis = $stmtHorariosDisponiveis->get_result();

        if ($resultHorariosDisponiveis->num_rows > 0) {
            echo "<label for='horario'>Selecione o Horário:</label>";
            echo "<select name='horario' id='horario'>";
            while ($rowHorariosDisponiveis = $resultHorariosDisponiveis->fetch_assoc()) {
                echo "<option value='{$rowHorariosDisponiveis['horario']}'>{$rowHorariosDisponiveis['horario']}</option>";
            }
            echo "</select><br>";
        } else {
            echo "<p>Nenhum horário disponível encontrado para o dia selecionado.</p>";
        }
    }
    ?>
    <!-- ... (restante do formulário e conteúdo HTML) -->
    
    
    <!-- ... (código JavaScript para atualizar o valor do input "mes_selecionado") -->
</body>
</html>
