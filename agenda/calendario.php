<?php
include 'back/config.php'; // Caminho para o arquivo de configuração do banco de dados

header('Content-Type: application/json');

// Lógica para buscar horários disponíveis no banco de dados
// ...

// Supondo que os dados sejam retornados em uma variável chamada $dados
echo json_encode($dados);
