<?php
// Verificar se o usuário está autenticado (substitua esta lógica pela sua própria)
if (!isset($_COOKIE["usuario"])) {
    header("Location: index.php"); // Redireciona para a página de login se o usuário não estiver autenticado
    exit;
}

// Inclua o arquivo de configuração com os dados de conexão ao banco de dados
require_once("config.php");

// Obtém o email do usuário a partir do cookie (você pode usar esse email para identificar a empresa)
$emailUsuario = $_COOKIE["usuario"];

// Função para obter o nome da empresa com base no email do usuário
function obterNomeEmpresa($email, $conn) {
    // Consulta SQL para buscar o nome da empresa no banco de dados
    $sql = "SELECT empresa FROM empresas WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row["empresa"];
    } else {
        return "Empresa Desconhecida";
    }
}

// Criar uma conexão com o banco de dados usando os dados do arquivo config.php
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar a conexão
if ($conn->connect_error) {
    die("Erro na conexão com o banco de dados: " . $conn->connect_error);
}

// Obtém o nome da empresa
$nomeEmpresa = obterNomeEmpresa($emailUsuario, $conn);

// Função para obter o caminho da logomarca
function obterCaminhoLogo($emailUsuario) {
    $targetDir = "../logos/"; // Diretório onde as logomarcas estão armazenadas (subindo um nível para a pasta ao lado)
    $logoNome = $emailUsuario . ".png"; // Nome do arquivo (baseado no email)

    // Verificar se o arquivo da logomarca existe no diretório de logomarcas
    if (file_exists($targetDir . $logoNome)) {
        return $targetDir . $logoNome;
    } else {
        return $targetDir . "logo_padrao.png"; // Nome da logomarca padrão
    }
}

// Obtém o caminho da logomarca
$caminhoLogo = obterCaminhoLogo($emailUsuario);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['meses_selecionados']) && isset($_POST['dias_selecionados'])) {
        // Certifique-se de que os valores sejam strings
        $mesesSelecionados = is_array($_POST['meses_selecionados']) ? implode(",", $_POST['meses_selecionados']) : $_POST['meses_selecionados'];
        $diasSelecionados = is_array($_POST['dias_selecionados']) ? implode(",", $_POST['dias_selecionados']) : $_POST['dias_selecionados'];
        $horario = $_POST['horario'];
        $empresa = $emailUsuario; // Use o email do usuário como identificação da empresa

        // Loop através dos meses e dias selecionados e insira os horários correspondentes no banco de dados
        foreach (explode(",", $mesesSelecionados) as $mes) {
            foreach (explode(",", $diasSelecionados) as $dia) {
                // Consulta SQL para inserir o horário no banco de dados (use prepared statements para evitar SQL injection)
                $sql = "INSERT INTO horarios_disponiveis (mes, dia, horario, empresa) VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssss", $mes, $dia, $horario, $empresa);

                if ($stmt->execute() !== TRUE) {
                    echo "Erro ao cadastrar horário: " . $stmt->error;
                }
            }
        }
    } else {
        echo "Por favor, selecione pelo menos um mês e um dia antes de enviar o formulário.";
    }
}


?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <style>
        /* Estilos para o botão */
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff; /* Cor de fundo do botão */
            color: #fff; /* Cor do texto do botão */
            text-decoration: none; /* Remover sublinhado do link (caso seja usado dentro de um <a>) */
            border: none; /* Remover borda do botão */
            border-radius: 5px; /* Cantos arredondados */
            cursor: pointer;
        }

        /* Estilo de hover (quando o mouse passa por cima) */
        .btn:hover {
            background-color: #0056b3; /* Cor de fundo alterada no hover */
        }

        /* Estilos para os botões de mês e dia */
        .meses, .dias {
            display: flex;
            flex-wrap: wrap;
        }

        .mes, .dia {
            margin: 5px;
            padding: 5px;
            background-color: #ccc;
            cursor: pointer;
        }

        .mes.selected, .dia.selected {
            background-color: #007bff;
            color: #fff;
        }
    </style>
</head>
<body>
    <h1>Bem-vindo à Dashboard - <?php echo $nomeEmpresa; ?></h1>
    
    <!-- Exibe a logomarca -->
    <img src="<?php echo $caminhoLogo; ?>" alt="Logomarca" width="200">
    
    <form action="" method="POST">
        <label for="mes">Mês:</label>
        <div class="meses">
            <?php
            $meses = [
                "Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho",
                "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro"
            ];

            foreach ($meses as $mesNome) {
                echo '<button type="button" class="mes">' . $mesNome . '</button>';
            }
            ?>
        </div>
        <br>
        <label for="dias">Dias:</label>
        <div class="dias">
            <?php
            for ($i = 1; $i <= 31; $i++) {
                echo '<button type="button" class="dia">' . $i . '</button>';
            }
            ?>
        </div>
        <br>
        <label for="horario">Horário:</label>
        <input type="text" name="horario" id="horario" placeholder="Ex: 09:00 às 10:00">
        <br>
        <input type="hidden" name="meses_selecionados[]" id="meses_selecionados" value="">
        <input type="hidden" name="dias_selecionados[]" id="dias_selecionados" value="">


        <input type="submit" value="Cadastrar">
        <!-- Botão de redirecionamento -->
        <a href="https://agendado.me/fisiogerlan/usuario/index.php" class="btn">Horários cadastrados</a>
    </form>
    <script>
    // Arrays para armazenar seleções de meses e dias
    const mesesSelecionados = [];
    const diasSelecionados = [];

    // Função para adicionar/remover a classe 'selected' quando um mês ou dia é clicado e atualizar os arrays
    function toggleSelection(element, array) {
        element.classList.toggle('selected');
        const value = element.innerText;

        if (array.includes(value)) {
            const index = array.indexOf(value);
            array.splice(index, 1);
        } else {
            array.push(value);
        }

        // Atualize os campos ocultos com as seleções
        document.getElementById("meses_selecionados").value = mesesSelecionados.join(",");
        document.getElementById("dias_selecionados").value = diasSelecionados.join(",");
    }

    // Adicione um evento de clique aos botões de mês e dia
    const meses = document.querySelectorAll('.mes');
    const dias = document.querySelectorAll('.dia');

    meses.forEach((mes) => {
        mes.addEventListener('click', () => {
            toggleSelection(mes, mesesSelecionados);
        });
    });

    dias.forEach((dia) => {
        dia.addEventListener('click', () => {
            toggleSelection(dia, diasSelecionados);
        });
    });
</script>
<html>
<head>
    <title>Horários Cadastrados</title>
    <style>
        /* Seus estilos CSS aqui */
    </style>
</head>
<body>
    <h1>Horários Cadastrados - <?php echo $nomeEmpresa; ?></h1>
    
    <form action="" method="GET">
        <label for="mes">Mês:</label>
        <select name="mes" id="mes">
            <option value="">Todos</option>
            <?php
            $meses = [
                "Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho",
                "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro"
            ];

            foreach ($meses as $mesNome) {
                echo '<option value="' . $mesNome . '"';
                if ($mesFiltrado === $mesNome) {
                    echo ' selected';
                }
                echo '>' . $mesNome . '</option>';
            }
            ?>
        </select>
        
        <label for="dia">Dia:</label>
        <select name="dia" id="dia">
            <option value="">Todos</option>
            <?php
            for ($i = 1; $i <= 31; $i++) {
                echo '<option value="' . $i . '"';
                if ($diaFiltrado == $i) {
                    echo ' selected';
                }
                echo '>' . $i . '</option>';
            }
            ?>
        </select>
        
        <input type="submit" value="Atualizar">
    </form>

    <table>
        <tr>
            <th>Mês</th>
            <th>Dia</th>
            <th>Horário</th>
            <th>Editar</th>
            <th>Excluir</th>
        </tr>
        <?php
        // Função para obter o email da empresa com base no nome da empresa
        function obterEmailEmpresa($nome, $conn) {
            // Consulta SQL para buscar o email da empresa no banco de dados
            $sql = "SELECT email FROM empresas WHERE empresa = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $nome);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                return $row["email"];
            } else {
                return null; // Retorna null se a empresa não for encontrada
            }
        }

        // Obtém o email da empresa com base no nome da empresa
        $emailEmpresa = obterEmailEmpresa($nomeEmpresa, $conn);

        if ($emailEmpresa !== null) {
            // Consulta SQL para buscar os horários cadastrados com base no email da empresa
            $sql = "SELECT mes, dia, horario FROM horarios_disponiveis WHERE empresa = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $emailEmpresa);
            $stmt->execute();
            $result = $stmt->get_result();

            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['mes'] . "</td>";
                echo "<td>" . $row['dia'] . "</td>";
                echo "<td>" . $row['horario'] . "</td>";
                echo "<td><a href='#'>Editar</a></td>";
                echo "<td><a href='#'>Excluir</a></td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='5'>Empresa não encontrada.</td></tr>";
        }
        ?>
    </table>
</body>

</body>
</body>
</html>
