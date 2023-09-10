<?php
// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Valide o email e senha (substitua esta parte pela validação real)
    $email = $_POST["email"];
    $senha = $_POST["senha"];

    // Simule uma consulta ao banco de dados (substitua pela sua lógica de consulta real)
    $usuarioValido = buscarNoBanco($email, $senha);

    if ($usuarioValido) {
        // Define o cookie com o email do usuário
        setcookie("usuario", $email, time() + 3600, "/");
        
        // Redireciona para a dashboard
        header("Location: dash/index.php");
        exit;
    } else {
        echo "Usuário ou senha inválidos.";
    }
}

// Função para buscar no banco de dados
function buscarNoBanco($email, $senha) {
    // Configurar as informações do banco de dados (substitua pelas suas próprias credenciais)
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "agenda";

    // Criar uma conexão com o banco de dados
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verificar a conexão
    if ($conn->connect_error) {
        die("Erro na conexão com o banco de dados: " . $conn->connect_error);
    }

    // Consulta SQL para verificar as credenciais
    $sql = "SELECT * FROM empresas WHERE email = '$email' AND senha = '$senha'";
    $result = $conn->query($sql);

    // Verificar se encontrou um registro
    if ($result->num_rows > 0) {
        $conn->close();
        return true;
    } else {
        $conn->close();
        return false;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
    <h1>Bem-vindo</h1>
    <form method="post" action="">
        <label for="email">Digite o email:</label>
        <input type="text" id="email" name="email" required><br><br>
        
        <label for="senha">Digite a senha:</label>
        <input type="password" id="senha" name="senha" required><br><br>
        
        <input type="submit" value="Login">
    </form>
    <p>Se não possui uma conta, <a href="novas.php">cadastre-se aqui</a>.</p>
</body>
</html>
