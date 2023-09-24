<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="refresh" content="1">
    <title>Consultar Horários Disponíveis</title>
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
            flex-direction: column; /* Centralizar verticalmente */
        }

        h1 {
            font-size: 24px;
            text-align: center;
            margin-bottom: 20px;
            color: #006600; /* Cor do título */
        }

        /* Estilize a logo */
        img {
            max-width: 50%; /* Defina a largura máxima como 50% do tamanho original */
            height: auto; /* Mantenha a proporção da altura */
            display: block; /* Para centralizar a imagem horizontalmente */
            margin: 0 auto; /* Para centralizar a imagem horizontalmente */
            margin-bottom: 20px; /* Espaço abaixo da logo */
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        li {
            margin: 10px 0;
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

        @media (max-width: 768px) {
            h1 {
                font-size: 24px;
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
    <?php
    // Inclua o arquivo de configuração com os dados de conexão ao banco de dados
    require_once("config.php");

    // Verifique se o mês e o dia foram passados como parâmetros na URL
    if (isset($_GET['mes']) && isset($_GET['dia'])) {
        $mes = $_GET['mes'];
        $dia = $_GET['dia'];

        // Conexão com o banco de dados (usando as configurações do arquivo de configuração)
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Checando se houve algum erro de conexão
        if ($conn->connect_error) {
            die("Erro de conexão com o banco de dados: " . $conn->connect_error);
        }

        // Consulta SQL para obter horários disponíveis com base no mês e no dia selecionados
        $sql = "SELECT horario FROM horarios_disponiveis WHERE mes = '$mes' AND dia = '$dia'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo "<ul>";
            while ($row = $result->fetch_assoc()) {
                $horario = $row['horario'];
                // Inclua um link para a página de formulário com o horário como parâmetro na URL
                echo "<li><a href='form.php?mes=$mes&dia=$dia&horario=$horario'>$horario</a></li>";
            }
            echo "</ul>";
        } else {
            echo "Não há horários disponíveis para o dia $dia de $mes.";
        }

        // Feche a conexão com o banco de dados quando terminar
        $conn->close();
    } else {
        echo "Selecione um mês e um dia para ver os horários disponíveis.";
    }
    ?>
</body>
</html>
