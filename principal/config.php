<?php
// Dados de conexão com o banco de dados
$servername = "localhost"; // Endereço do servidor MySQL (geralmente "localhost")
$username = "root"; // Nome de usuário do banco de dados
$password = ""; // Senha do banco de dados
$dbname = "agenda"; // Nome do banco de dados

// Criar uma conexão com o banco de dados
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar a conexão
if ($conn->connect_error) {
    die("Erro na conexão com o banco de dados: " . $conn->connect_error);
}
?>
