<?php
require 'back/config.php'; // Inclui o arquivo de configuração

// Captura o email da query string e valida
$email = isset($_GET['email']) ? filter_var($_GET['email'], FILTER_SANITIZE_EMAIL) : null;

// Conecta-se ao banco de dados
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Verifica se o email é válido
    if ($email && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Prepara a consulta para buscar os agendamentos para o email fornecido
        // Junção das tabelas para obter informações relevantes
        $stmt = $pdo->prepare("
            SELECT h.*, e.nome as nome_empresa, c.nome as nome_cliente 
            FROM horarios h
            JOIN empresa e ON h.id_empresa = e.id_empresa
            JOIN cliente c ON h.id_cliente = c.id_cliente
            WHERE c.email = :email
            ORDER BY h.dia, h.horario
        ");
        $stmt->execute(['email' => $email]);

        // Busca os resultados
        $agendamentos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        echo "Email inválido.";
        $agendamentos = []; // Mantém o array de agendamentos vazio
    }

} catch(PDOException $e) {
    die("ERROR: Could not connect. " . $e->getMessage());
}

// Processamento adicional, como gerar um array de dias para o calendário, se necessário
// [...]

?>
<?php
// ...
// Código de conexão e outras configurações do PDO
// ...

// Verifica se é uma solicitação AJAX e se o parâmetro 'data' está presente
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' && isset($_GET['data'])) {
    $dataSelecionada = $_GET['data'];
    // Prepara a consulta SQL para buscar horários disponíveis na data selecionada
    $stmt = $pdo->prepare("SELECT horario FROM horarios WHERE dia = :dia AND status = 0 ORDER BY horario");
    $stmt->execute(['dia' => $dataSelecionada]);
    $horariosDisponiveis = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Configura o cabeçalho para retornar JSON
    header('Content-Type: application/json');
    // Retorna os dados em formato JSON
    echo json_encode($horariosDisponiveis);
    exit;
}

// Restante do seu código PHP...
// ...
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendário de Agendamentos</title>
    <link rel="stylesheet" href="estilo2.css">
</head>
<body>
<div class="calendario-container">
    <div class="mes"><?php echo date('F'); // Mês atual ?></div>
    <div class="dias-semana">
        <div>D</div>
        <div>S</div>
        <div>T</div>
        <div>Q</div>
        <div>Q</div>
        <div>S</div>
        <div>S</div>
    </div>
    <?php
// [...] (O restante do script que conecta ao banco e obtém os agendamentos)

// Pega o mês e o ano atuais, ou de uma variável se fornecido
$mesAtual = date('m');
$anoAtual = date('Y');

// Primeiro dia do mês
$primeiroDiaMes = new DateTime("$anoAtual-$mesAtual-01");
// Último dia do mês
$ultimoDiaMes = new DateTime($primeiroDiaMes->format('Y-m-t'));

// Cria um array para marcar os dias com agendamentos
$diasComAgendamentos = [];
foreach ($agendamentos as $agendamento) {
    $dataAgendamento = new DateTime($agendamento['dia']);
    $diasComAgendamentos[$dataAgendamento->format('j')] = true; // Marca o dia do agendamento
}

// Cria um iterador de dias para o mês atual
$periodo = new DatePeriod($primeiroDiaMes, new DateInterval('P1D'), $ultimoDiaMes);

?>
<div class="dias">
    <?php
    // Gera os dias do mês
    foreach ($periodo as $dia) {
        $classeAgendamento = isset($diasComAgendamentos[$dia->format('j')]) ? 'dia-com-agendamento' : '';
        echo "<div class='dia {$classeAgendamento}' data-dia='{$dia->format('Y-m-d')}'>" . $dia->format('j') . "</div>";
    }
    ?>
</div>

<!-- Estrutura do Pop-up -->
<div id="popup" class="popup" style="display:none;">
    <div class="popup-content">
        <span class="close">&times;</span>
        <div id="conteudoPopup"></div>
    </div>
</div>
<script>



    document.addEventListener('DOMContentLoaded', function() {
    var dias = document.querySelectorAll('.dia');
    var popup = document.getElementById('popup');
    var conteudoPopup = document.getElementById('conteudoPopup');
    var close = document.getElementsByClassName('close')[0];

    dias.forEach(function(dia) {
        dia.addEventListener('click', function() {
            var dataSelecionada = this.getAttribute('data-dia');
            buscarHorarios(dataSelecionada); // Chama a função buscarHorarios
        });
    });

    // Restante do código para buscarHorarios e fechar o popup
    // ...
});

document.addEventListener('DOMContentLoaded', function() {
    var dias = document.querySelectorAll('.dia');
    var popup = document.getElementById('popup');
    var conteudoPopup = document.getElementById('conteudoPopup');
    var close = document.getElementsByClassName('close')[0];

    dias.forEach(function(dia) {
        dia.addEventListener('click', function() {
            var dataSelecionada = this.getAttribute('data-dia');
            // Aqui você pode chamar a função para preencher e mostrar o pop-up
            conteudoPopup.innerHTML = 'Horários para ' + dataSelecionada; // Exemplo de conteúdo
            popup.style.display = 'block';
        });
    });


    // Função para buscar e exibir horários disponíveis
    function buscarHorarios(data) {
        var xhr = new XMLHttpRequest();
        xhr.onload = function() {
            if (xhr.status === 200) {
                var horarios = JSON.parse(xhr.responseText);
                var conteudoPopup = document.getElementById('conteudoPopup');
                conteudoPopup.innerHTML = '<h3>Horários disponíveis para ' + data + '</h3>'; // Cabeçalho com a data

                // Verifica se existem horários disponíveis
                if (horarios.length > 0) {
                    horarios.forEach(function(horario) {
                        conteudoPopup.innerHTML += '<p>' + horario.horario + '</p>'; // Adiciona horários disponíveis
                    });
                } else {
                    conteudoPopup.innerHTML += '<p>Nenhum horário disponível.</p>'; // Mensagem se não houver horários
                }

                // Mostra o pop-up
                document.getElementById('popup').style.display = 'block';
            }
        };
        xhr.open('GET', 'calendario.php?data=' + data, true);
        xhr.send();
    }


    // Fecha o popup ao clicar no 'X'
    close.onclick = function() {
        popup.style.display = "none";
    };

    // Fecha o popup ao clicar fora do conteúdo
    window.onclick = function(event) {
        if (event.target == popup) {
            popup.style.display = "none";
        }
    };
});
window.onerror = function(message, source, lineno, colno, error) {
    console.log("Erro capturado em JavaScript:");
    console.log("Mensagem: ", message);
    console.log("Fonte: ", source);
    console.log("Linha: ", lineno);
    console.log("Coluna: ", colno);
    console.log("Erro: ", error);
    return true; // Previne a exibição padrão do erro no console
};
function buscarHorarios(data) {
    console.log("Buscando horários para a data: ", data);
    var xhr = new XMLHttpRequest();
    xhr.onload = function() {
        if (xhr.status === 200) {
            console.log("Resposta recebida: ", xhr.responseText);
            var horarios = JSON.parse(xhr.responseText);
            var conteudoPopup = document.getElementById('conteudoPopup');
            conteudoPopup.innerHTML = '<h3>Horários disponíveis para ' + data + '</h3>';
            
            // Restante do código...
        } else {
            console.log("Erro na solicitação AJAX: ", xhr.statusText);
        }
    };
    xhr.onerror = function() {
        console.log("Erro na solicitação.");
    };
    xhr.open('GET', 'calendario.php?data=' + data, true);
    xhr.send();
}


</script>
</div>
</body>
</html>
