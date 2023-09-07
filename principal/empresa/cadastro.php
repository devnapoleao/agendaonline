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

// Processa os dados do formulário
$mes = $_POST['mes'];
$dia = $_POST['dia'];
$horario = $_POST['horario'];

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

// Feche a conexão com o banco de dados quando terminar
$conn->close();
?>
