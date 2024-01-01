<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Empresa</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <h2>Cadastro de Empresa</h2>
    <form action="processa_cadastro_empresa.php" method="post" enctype="multipart/form-data">
        <label for="email">E-mail:</label>
        <input type="email" id="email" name="email" required><br><br>

        <label for="nome">Nome da Empresa:</label>
        <input type="text" id="nome" name="nome" required><br><br>

        <label for="senha">Senha:</label>
        <input type="password" id="senha" name="senha" required><br><br>

        <label for="imagem_fundo">Imagem de Fundo (opcional):</label>
        <input type="file" id="imagem_fundo" name="imagem_fundo"><br><br>

        <input type="submit" value="Cadastrar">
    </form>
</body>
</html>
