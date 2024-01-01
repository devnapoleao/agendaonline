<?php
// cadastrar_horario.php
$conn = new mysqli('host', 'username', 'password', 'database');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$data = $_POST['data'];
$hora = $_POST['hora'];

$sql = "INSERT INTO horario (data, hora) VALUES (?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $data, $hora);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo "Horário cadastrado com sucesso!";
} else {
    echo "Erro ao cadastrar horário.";
}

$conn->close();
?>
