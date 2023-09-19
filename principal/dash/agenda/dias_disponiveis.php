<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="refresh" content="1">
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

    // Obtém o nome da empresa
    $nomeEmpresa = obterNomeEmpresa($emailUsuario, $conn);
    ?>

    <img src="<?php echo $caminhoLogo; ?>" alt="Logo da sua empresa">
    <h1>Consultar Dias Disponíveis - <?php echo $nomeEmpresa; ?></h1>

    <?php
    // Recupere o mês selecionado
    $mes = $_POST['mes'];

    // Consulta SQL para obter dias disponíveis com base no mês selecionado e na empresa do usuário
    $sql = "SELECT DISTINCT dia FROM horarios_disponiveis WHERE mes = ? AND empresa = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $mes, $emailUsuario);
    $stmt->execute();
    $result = $stmt->get_result();

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
        // Função para recarregar a página a cada 1 segundo
        function atualizarPagina() {
            setTimeout(function () {
                location.reload();
            }, 1000); // 1000 milissegundos = 1 segundo
        }

        // Chama a função de atualização quando a página carrega
        window.onload = atualizarPagina;
    </script>
</body>
</html>
