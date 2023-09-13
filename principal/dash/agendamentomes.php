<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Consultar Mês Disponível</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins">
    <style>
body {
            background-color: #f0f5f0; /* Fundo verde claro */
            font-family: 'Poppins', sans-serif;
            font-size: 18px;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            flex-direction: column; /* Adicionando flex-direction para centralizar verticalmente */
        }

        /* Estilize a logo */
        img {
            max-width: 50%; /* Defina a largura máxima como 50% do tamanho original */
            height: auto; /* Mantenha a proporção da altura */
            display: block; /* Para centralizar a imagem horizontalmente */
            margin: 0 auto; /* Para centralizar a imagem horizontalmente */
            margin-bottom: 20px; /* Espaço abaixo da logo */
        }

        h1 {
            font-size: 28px;
            text-align: center;
            margin-bottom: 20px;
            color: #006600; /* Cor do título */
        }

        form {
            background-color: #fff; /* Fundo branco */
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            max-width: 400px;
            width: 90%;
            text-align: left;
        }

        label {
            display: block;
            margin-bottom: 10px;
            font-size: 20px;
        }

        select, input[type="submit"] {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            margin-bottom: 20px;
            border: 2px solid #006600; /* Borda verde escura */
            border-radius: 5px;
        }

        input[type="submit"] {
            background-color: transparent; /* Fundo transparente */
            color: #006600; /* Cor do texto */
            cursor: pointer;
            font-size: 18px;
            border: 2px solid #006600; /* Borda verde escura */
            border-radius: 5px;
            transition: background-color 0.3s; /* Efeito de transição suave */
        }

        input[type="submit"]:hover {
            background-color: #006600; /* Cor de fundo ao passar o mouse */
            color: #fff; /* Cor do texto ao passar o mouse */
        }

        @media (max-width: 768px) {
            h1 {
                font-size: 24px;
            }

            form {
                width: calc(100% - 20px); /* 100% menos 20px para a margem esquerda e direita */
                max-width: none; /* Remova a largura máxima */
                margin-left: 20px; /* Adicione margem à esquerda */
                margin-right: 20px; /* Adicione margem à direita */
                background-color: #f0f5f0; /* verde claro */
            }

            /* Remova a margem esquerda e direita em telas menores */
            select {
                margin-left: 0;
                margin-right: 0;
            }
        }
        @media screen and (min-width: 768px) {
            img {
                max-width: 15%; /* Reduzir a largura da imagem em 30% */
                margin-bottom: 10px; /* Diminuir a margem inferior da imagem */
            }
        }
    </style>
</head>
<body>
    <img src="logo.png" alt="Logo da sua empresa">
    <h1>Consultar Mês Disponível</h1>
<form action="agendamentodia.php" method="GET">

        <label for="mes">Selecione o Mês:</label>
        <select name="mes" id="mes">
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

            // Obtém o nome da empresa associada ao email do usuário
            $nomeEmpresa = obterNomeEmpresa($emailUsuario, $conn);

            // Consulta para obter o email da empresa
            $sqlEmailEmpresa = "SELECT email FROM empresas WHERE empresa = ?";
            $stmtEmailEmpresa = $conn->prepare($sqlEmailEmpresa);
            $stmtEmailEmpresa->bind_param("s", $nomeEmpresa);
            $stmtEmailEmpresa->execute();
            $resultEmailEmpresa = $stmtEmailEmpresa->get_result();

            if ($resultEmailEmpresa->num_rows > 0) {
                $rowEmailEmpresa = $resultEmailEmpresa->fetch_assoc();
                $emailEmpresa = $rowEmailEmpresa['email'];

                // Agora que você tem o email da empresa, pode usar isso para buscar os meses disponíveis
                $sqlMesesDisponiveis = "SELECT DISTINCT mes FROM horarios_disponiveis WHERE empresa = ?";
                $stmtMesesDisponiveis = $conn->prepare($sqlMesesDisponiveis);
                $stmtMesesDisponiveis->bind_param("s", $emailEmpresa);
                $stmtMesesDisponiveis->execute();
                $resultMesesDisponiveis = $stmtMesesDisponiveis->get_result();

                if ($resultMesesDisponiveis->num_rows > 0) {
                    while ($rowMesesDisponiveis = $resultMesesDisponiveis->fetch_assoc()) {
                        echo "<option value='{$rowMesesDisponiveis['mes']}'>{$rowMesesDisponiveis['mes']}</option>";
                    }
                } else {
                    echo "<option value='' disabled>Nenhum mês disponível encontrado para a empresa: $nomeEmpresa</option>";
                }
            } else {
                echo "<option value='' disabled>Empresa não encontrada.</option>";
            }
            if (isset($_GET["mes"])) {
                $mesSelecionado = $_GET["mes"];
                $emailUsuario = $_COOKIE["usuario"];
            
                $conn = new mysqli($servername, $username, $password, $dbname);
            
                if ($conn->connect_error) {
                    die("Erro na conexão com o banco de dados: " . $conn->connect_error);
                }
            
                // Obtém o nome da empresa associada ao email do usuário
                $nomeEmpresa = obterNomeEmpresa($emailUsuario, $conn);
            
                // Consulta para obter os dias disponíveis com base no mês e na empresa
                $sql = "SELECT DISTINCT dia FROM horarios_disponiveis WHERE empresa = ? AND mes = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ss", $emailEmpresa, $mesSelecionado);
                $stmt->execute();
                $result = $stmt->get_result();
            
                $diasDisponiveis = [];
            
                while ($row = $result->fetch_assoc()) {
                    $diasDisponiveis[] = $row["dia"];
                }
            
                // Retorna os dias disponíveis em formato JSON
                echo json_encode($diasDisponiveis);
            } else {
                echo "Parâmetro 'mes' não especificado.";
            }
            ?>
        </select><br>
        <input type="hidden" name="mes_selecionado" id="mes_selecionado">

        <input type="submit" value="Consultar Dias Disponíveis">
    </form>
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        const mesSelect = document.getElementById("mes");
        const mesSelecionadoInput = document.getElementById("mes_selecionado");

        mesSelect.addEventListener("change", function () {
            const selectedMonth = mesSelect.value;
            mesSelecionadoInput.value = selectedMonth;
        });
    });
</script>




</body>
</html>
