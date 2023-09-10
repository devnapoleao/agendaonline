<?php
// Inclua o arquivo config.php para obter as credenciais do banco de dados
require_once('dash/config.php');

// Conexão com o banco de dados usando as credenciais do config.php
$conn = new mysqli($db_host, $db_user, $db_password, $db_database);

// Checando se houve algum erro de conexão
if ($conn->connect_error) {
    die("Erro de conexão com o banco de dados: " . $conn->connect_error);
}
<?php
// Configuração do banco de dados
$host = "localhost";
$user = "u739537864_fisio04";
$password = "uj#4udnj!JUN!";
$database = "u739537864_fisio04";
// Conexão com o banco de dados
$conn = new mysqli($host, $user, $password, $database);

// Checando se houve algum erro de conexão
if ($conn->connect_error) {
    die("Erro de conexão com o banco de dados: " . $conn->connect_error);
}

// Processa os dados do formulário
$mes = $_POST['mes'];
$dia = $_POST['dia'];
$horario = $_POST['horario'];

// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Insere os dados no banco de dados
    $sql = "INSERT INTO horarios_disponiveis (mes, dia, horario) VALUES ('$mes', '$dia', '$horario')";

    if ($conn->query($sql) === TRUE) {
        // Estilo para a mensagem de sucesso
        echo '<div style="background-color: #fff; padding: 20px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); text-align: center;">';
        echo '<p style="font-size: 20px; color: #006600;">Horário cadastrado com sucesso!</p>';
        echo '</div>';

        // Redireciona de volta para index.html após 2 segundos
        header("refresh:2;url=index.html");
    } else {
        // Estilo para a mensagem de erro
        echo '<div style="background-color: #fff; padding: 20px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); text-align: center;">';
        echo '<p style="font-size: 20px; color: red;">Erro ao cadastrar horário: ' . $conn->error . '</p>';
        echo '</div>';
    }
}

// Feche a conexão com o banco de dados quando terminar
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <title>Cadastre um Horário Disponível</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins">
    <style>
        <!-- O restante do seu estilo CSS permanece aqui -->
    </style>
</head>
<body>
    <img src="logo.png" alt="Logo da sua empresa">
    <h1>Cadastre um Horário Disponível </h1>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
        <label for="mes">Mês:</label>
        <select name="mes" id="mes">
            <!-- Opções do mês -->
        </select>
        
        <label for="dia">Dia:</label>
        <select name="dia" id="dia">
            <!-- Opções do dia -->
        </select>

        <label for="horario">Horário:</label>
        <input type="text" name="horario" id="horario" placeholder="Ex: 09:00 às 10:00">
        <input type="submit" value="Cadastrar">
        <!-- Botão de redirecionamento -->
        <a href="https://agendado.me/fisiogerlan/usuario/index.php" class="btn">Horários cadastrados</a>
    </form>
</body>
</html>
