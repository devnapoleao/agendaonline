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
    <form action="dias_disponiveis.php" method="POST">
        <label for="mes">Selecione o Mês:</label>
        <select name="mes" id="mes">
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

            // Consulta SQL para obter meses disponíveis
            $sql = "SELECT DISTINCT mes FROM horarios_disponiveis";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='{$row['mes']}'>{$row['mes']}</option>";
                }
            }
            ?>
        </select><br>
        <input type="submit" value="Consultar Dias Disponíveis">
    </form>
    
</body>
</html>
