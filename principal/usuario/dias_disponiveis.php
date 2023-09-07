<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="refresh" content="1.5">
    <title>Consultar Dias Disponíveis</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins">
    <style>
        body {
            background-color: #f0f5f0; /* Fundo verde claro */
            font-family: 'Poppins', sans-serif;
            font-size: 18px;
            margin: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
        }

        h1 {
            font-size: 24px;
            text-align: center;
            margin-bottom: 20px;
            color: #006600; /* Cor do título */
        }

        form {
            background-color: #fff; /* Fundo branco */
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
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

        /* Estilize a logo */
        img {
            max-width: 50%; /* Defina a largura máxima como 50% do tamanho original */
            height: auto; /* Mantenha a proporção da altura */
            display: block; /* Para centralizar a imagem horizontalmente */
            margin: 0 auto; /* Para centralizar a imagem horizontalmente */
            margin-bottom: 20px; /* Espaço abaixo da logo */
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
        li {
            margin: 10px 0;
        }
        ul {
            list-style-type: none; /* Remover os pontos/bullet points da lista não ordenada */
        }
        a {
            text-decoration: none;
            color: #006600; /* Cor do link */
            font-weight: bold;
            font-size: 16px;
            display: block;
            width: 200px; /* Largura desejada */
            height: 30px; /* Altura desejada */
            line-height: 30px; /* Centralizar verticalmente */
            margin: 0 auto; /* Centralizar horizontalmente */
            border: 2px solid #006600; /* Borda verde escura */
            border-radius: 5px;
            text-align: center;
        }

        a:hover {
            text-decoration: underline;
        }

    </style>
</head>
<body>
<img src="logo.png" alt="Logo da sua empresa">
    <h1>Consultar Dias Disponíveis</h1>

    <?php
  // Configuração do banco de dados
  $host = "localhost";
  $user = "root";
  $password = "";
  $database = "agenda";

// Conexão com o banco de dados
$conn = new mysqli($host, $user, $password, $database);

// Checando se houve algum erro de conexão
if ($conn->connect_error) {
    die("Erro de conexão com o banco de dados: " . $conn->connect_error);
}

    // Recupere o mês selecionado
    $mes = $_POST['mes'];

    // Consulta SQL para obter dias disponíveis com base no mês selecionado
    $sql = "SELECT DISTINCT dia FROM horarios_disponiveis WHERE mes = '$mes'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<ul>";
        while ($row = $result->fetch_assoc()) {
            $dia = $row['dia'];
            // Inclua um link para a página de horários com o dia como parâmetro na URL
            echo "<li><a href='horarios_disponiveis.php?mes=$mes&dia=$dia'>$dia</a></li>";
        }
        echo "</ul>";
    } else {
        echo "Não há dias disponíveis para o mês de $mes.";
    }

    // Feche a conexão com o banco de dados quando terminar
    $conn->close();
    ?>
        <script>
        // Função para recarregar a página a cada 1.5 segundos
        function atualizarPagina() {
            setTimeout(function () {
                location.reload();
            }, 1500); // 1500 milissegundos = 1,5 segundos
        }

        // Chama a função de atualização quando a página carrega
        window.onload = atualizarPagina;
    </script>
</body>
</html>
