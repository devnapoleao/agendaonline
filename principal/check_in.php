<?php
// Conexão com o banco de dados (substitua pelos seus próprios dados)
$host = "seu_host";
$usuario = "seu_usuario";
$senha = "sua_senha";
$banco = "seu_banco";

$conn = new mysqli($host, $usuario, $senha, $banco);

if ($conn->connect_error) {
    die("Erro na conexão com o banco de dados: " . $conn->connect_error);
}

// Processo de Check-in
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST["nome"];
    $sobrenome = $_POST["sobrenome"];
    $numero_reserva = $_POST["numero_reserva"];
    
    // Insira os dados do check-in no banco de dados
    $query = "INSERT INTO checkin (nome, sobrenome, numero_reserva, data_checkin) 
              VALUES ('$nome', '$sobrenome', '$numero_reserva', NOW())";
    
    if ($conn->query($query) === TRUE) {
        echo "Check-in realizado com sucesso.";
    } else {
        echo "Erro ao realizar o check-in: " . $conn->error;
    }
}

// Feche a conexão com o banco de dados
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sistema de Check-in</title>
</head>
<body>
    <h1>Check-in de Clientes</h1>
    <form method="POST" action="">
        <label for="nome">Nome:</label>
        <input type="text" name="nome" required><br><br>
        
        <label for="sobrenome">Sobrenome:</label>
        <input type="text" name="sobrenome" required><br><br>
        
        <label for="numero_reserva">Número de Reserva:</label>
        <input type="text" name="numero_reserva" required><br><br>
        
        <input type="submit" value="Fazer Check-in">
    </form>
</body>
</html>
