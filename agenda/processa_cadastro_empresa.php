<?php
include 'back/config.php'; // Inclui o arquivo de configuração do banco de dados

// Cria uma conexão
$conn = new mysqli($host, $username, $password, $dbname);

// Verifica se a conexão foi bem-sucedida
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Obtém os dados do formulário
$email = $_POST['email'];
$nome = $_POST['nome'];
$senha = $_POST['senha']; // A senha deve ser criptografada antes de ser armazenada
$senhaCriptografada = password_hash($senha, PASSWORD_DEFAULT);

// Trata a imagem de fundo se houver
if (isset($_FILES['imagem_fundo']) && $_FILES['imagem_fundo']['error'] === UPLOAD_ERR_OK) {
    // Aqui você deve adicionar a lógica para processar e salvar a imagem
    // e obter o caminho da imagem a ser salvo no banco de dados
    $caminhoImagem = 'caminho/para/imagem/salva.jpg'; // Exemplo de caminho
} else {
    $caminhoImagem = ''; // Sem imagem de fundo
}

// Prepara a consulta SQL
$sql = "INSERT INTO EMPRESA (email, nome, senha, imagem_fundo) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

// Vincula os parâmetros à consulta preparada
$stmt->bind_param("ssss", $email, $nome, $senhaCriptografada, $caminhoImagem);

// Executa a consulta
if ($stmt->execute()) {
    echo "Empresa cadastrada com sucesso!";
    // Redireciona para uma página específica ou volta ao formulário
    header("Location: sucesso.html"); // Substitua com a localização de destino
} else {
    echo "Erro ao cadastrar empresa: " . $conn->error;
}

// Fecha a conexão
$conn->close();
?>
