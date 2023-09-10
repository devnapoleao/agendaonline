
<?php
// Verifica se o formulário de cadastro foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtenha os dados do formulário
    $empresa = $_POST["empresa"];
    $representante = $_POST["representante"];
    $contato = $_POST["contato"];
    $email = $_POST["email"];
    $senha = $_POST["senha"];
    $dataaquisicao = $_POST["dataaquisicao"];
    
    // Obtenha o valor do parâmetro 'nomeEmpresa' da URL
    $empresaindicadora = $_GET["nomeEmpresa"];
    
    // Valide os dados (substitua esta parte pela validação real)
    if (empty($empresa) || empty($representante) || empty($contato) || empty($email) || empty($senha)) {
        echo "Todos os campos devem ser preenchidos.";
    } else {
        // Processar o upload da imagem da logomarca
        $logoNome = processarUploadLogo($email);

        if ($logoNome !== null) {
            // Insira os dados no banco de dados (substitua pela sua lógica de inserção real)
            if (inserirNoBanco($empresa, $representante, $contato, $email, $senha, $logoNome, $empresaindicadora, $dataaquisicao)) {
                // Redireciona de volta para a página de login após o cadastro bem-sucedido
                header("Location: index.php");
                exit;
            } else {
                echo "Erro ao cadastrar. Tente novamente mais tarde.";
            }
        }
    }
}


// Restante do código HTML
?>
<!DOCTYPE html>
<html>
<head>
    <title>Cadastro de Empresa</title>
</head>
<body>
    <h1>Cadastro de Empresa</h1>
    <?php
    // Exibir mensagens de erro aqui, se houver
    if ($_SERVER["REQUEST_METHOD"] == "POST" && (empty($empresa) || empty($representante) || empty($contato) || empty($email) || empty($senha))) {
        echo "<p>Todos os campos devem ser preenchidos.</p>";
    }
    ?>
        <form method="post" action="" enctype="multipart/form-data">
        <input type="hidden" id="empresaindicadora" name="empresaindicadora" value="<?php echo isset($empresaindicadora) ? htmlspecialchars($empresaindicadora) : ''; ?>">
        <input type="hidden" id="dataaquisicao" name="dataaquisicao" value="<?php echo date('Y-m-d'); ?>">

        <label for="empresa">Nome da Empresa:</label>
        <input type="text" id="empresa" name="empresa" required><br><br>

        <label for="representante">Nome do Representante:</label>
        <input type="text" id="representante" name="representante" required><br><br>

        <label for="contato">Contato:</label>
        <input type="text" id="contato" name="contato" required><br><br>

        <label for="email">Email:</label>
        <input type="text" id="email" name="email" required><br><br>

        <label for="senha">Senha:</label>
        <input type="password" id="senha" name="senha" required><br><br>

        <label for="logo">Logomarca (PNG):</label>
        <input type="file" id="logo" name="logo" accept=".png" required><br><br>

        <input type="submit" value="Cadastrar">
    </form>
</body>
</html>

<?php
// Função para processar o upload da logomarca (separada do código principal)
function processarUploadLogo($email) {
    $targetDir = "logos/"; // Diretório onde as logomarcas serão armazenadas
    $logoNome = $email . ".png"; // Nome do arquivo (baseado no email)

    // Verificar se o arquivo é uma imagem PNG
    $imageFileType = strtolower(pathinfo($_FILES["logo"]["name"], PATHINFO_EXTENSION));
    if ($imageFileType != "png") {
        echo "Apenas arquivos PNG são permitidos.";
        return null;
    }

    $targetFile = $targetDir . $logoNome;

    // Tentar mover o arquivo para o diretório de logomarcas
    if (move_uploaded_file($_FILES["logo"]["tmp_name"], $targetFile)) {
        return $logoNome;
    } else {
        echo "Erro ao fazer upload da logomarca.";
        return null;
    }
}

// Função para inserir os dados no banco de dados (separada do código principal)
function inserirNoBanco($empresa, $representante, $contato, $email, $senha, $logoNome, $empresaindicadora, $dataaquisicao) {
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

    // Consulta SQL para inserir os dados na tabela "empresas" com o nome da logomarca
    $sql = "INSERT INTO empresas (empresa, representante, contato, email, senha, logo, empresaindicadora, data_aquisicao) 
    VALUES ('$empresa', '$representante', '$contato', '$email', '$senha', '$logoNome', '$empresaindicadora', '$dataaquisicao')";


    // Executar a consulta e verificar se foi bem-sucedida
    if ($conn->query($sql) === TRUE) {
        $conn->close();
        return true;
    } else {
        $conn->close();
        return false;
    }
}
?>
