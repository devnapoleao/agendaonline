<?php
session_start();
include 'back/config.php'; // Inclui o arquivo de configuração

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

$email = $_POST['email'];
$senha = $_POST['senha'];

$sql = "SELECT id_empresa, senha FROM EMPRESA WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    if (password_verify($senha, $row['senha'])) {
        $_SESSION['id_empresa'] = $row['id_empresa'];
        $_SESSION['email_empresa'] = $email;
        header("Location: dash.php"); // Redireciona para o dashboard
    } else {
        echo "Senha incorreta!";
    }
} else {
    echo "E-mail não encontrado!";
}

$conn->close();
?>
