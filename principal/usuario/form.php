<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Horário</title>
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
            min-height: 100vh;
        }

        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            max-width: 400px;
            width: calc(100% - 40px); /* 20px de espaço nas bordas laterais */
        }

        h1 {
            font-size: 28px;
            text-align: center;
            margin-bottom: 20px;
            color: #006600; /* Cor do título */
        }

        h2 {
            font-size: 24px;
            text-align: center;
            margin-bottom: 20px;
            color: #006600; /* Cor do título */
        }

        form {
            background-color: #f0f5f0; /* Fundo branco */
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 100%;
            text-align: left;
        }





        @media (max-width: 768px) {
            h1, h2 {
                font-size: 24px;
            }

            .container {
                max-width: 100%;
            }
        }

        /* Estilize a logo */
        img {
            max-width: 50%; /* Defina a largura máxima como 50% do tamanho original */
            height: auto; /* Mantenha a proporção da altura */
            display: block; /* Para centralizar a imagem horizontalmente */
            margin: 0 auto; /* Para centralizar a imagem horizontalmente */
            margin-bottom: 20px; /* Espaço abaixo da logo */
        }

        /* Adicione estas regras CSS */

form {
    background-color: #f0f5f0; /* Fundo branco */
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    max-width: 400px;
    width: 100%;
    text-align: center; /* Centralize o conteúdo do formulário */
    margin: 20px auto; /* Centralize o formulário verticalmente e adicione espaço nas bordas superior e inferior */
}

label {
    display: block;
    margin-bottom: 10px;
    font-size: 20px;
    text-align: left; /* Alinhe o texto do rótulo à esquerda */
}

input[type="text"], input[type="submit"] {
    width: 95%;
    padding: 10px;
    font-size: 16px;
    margin-bottom: 20px;
    border: 2px solid #006600; /* Borda verde escura */
    border-radius: 5px;
}

input[type="submit"] {
    background-color: #006600; /* Fundo verde escuro */
    color: #fff; /* Texto branco */
    cursor: pointer;
    font-size: 18px;
}

input[type="submit"]:hover {
    background-color: #004c00; /* Cor mais escura no hover */
}

    </style>
</head>
<body>
<img src="logo.png" alt="Logo da sua empresa">
<?php
// Função para mostrar o símbolo de carregamento
function showLoadingSymbol()
{
    echo '<div id="loading" style="text-align: center;">
            <img src="loading.gif" alt="Carregando...">
         </div>';
}

// Verifique se os parâmetros foram passados na URL
if (isset($_GET['mes']) && isset($_GET['dia']) && isset($_GET['horario'])) {
    $mes = $_GET['mes'];
    $dia = $_GET['dia'];
    $horario = urldecode($_GET['horario']); // Decodifique o horário

    // Verifique se o formulário foi enviado
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nome = $_POST['nome'];
        $whatsapp = $_POST['whatsapp'];

        // Configurações de email
        $to = 'contato.napoleao2023@gmail.com';
        $subject = 'Sousa DEV - Agendamento de Horário';
        $message = "Mês: $mes\nDia: $dia\nHorário: $horario\nNome: $nome\nWhatsApp: $whatsapp";
        $headers = 'From: agendamento@agendado.me' . "\r\n";

        // Envie o email assincronamente
        if (mail($to, $subject, $message, $headers)) {
            // Mostrar o símbolo de carregamento
            showLoadingSymbol();

            // Conexão com o banco de dados
            $host = "localhost";
            $user = "root";
            $password = "";
            $database = "agenda";

            $conn = new mysqli($host, $user, $password, $database);

            if ($conn->connect_error) {
                die("Erro de conexão com o banco de dados: " . $conn->connect_error);
            }

            // Exclua o horário escolhido do sistema
            $sql = "DELETE FROM horarios_disponiveis WHERE mes = '$mes' AND dia = '$dia' AND horario = '$horario'";
            if ($conn->query($sql) === TRUE) {
                // Horário excluído com sucesso!
                echo '<script>
                        setTimeout(function() {
                            alert("Seu cadastro foi efetuado com sucesso!");
                            window.location.href = "obrigado.html";
                        }, 5000); // 5000 milissegundos = 5 segundos
                      </script>';
            } else {
                echo "<script>
                        alert('Erro ao excluir horário: " . $conn->error . "');
                        window.history.back();
                      </script>";
            }

            // Feche a conexão com o banco de dados quando terminar
            $conn->close();
        } else {
            echo "<script>
                    alert('Erro ao enviar o email. Por favor, tente novamente.');
                    window.history.back();
                  </script>";
        }
    }
} else {
    echo "Parâmetros inválidos. Por favor, volte e selecione um horário válido.";
}
?>
<form action="" method="POST">
    <label for="nome">Nome:</label>
    <input type="text" id="nome" name="nome" placeholder="Digite seu nome" required><br>
    <label for="whatsapp">WhatsApp:</label>
    <input type="text" id="whatsapp" name="whatsapp" placeholder="Digite seu WhatsApp" required><br>
    <input type="submit" value="Agendar Agora">
</form>
</body>
</html>
