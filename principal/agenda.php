

<?php
// agora vou usar um link para obter os dados e fazer o agendamento 
// Verifica se o parâmetro "email" está presente na URL
if(isset($_GET['email'])) {
    // Conexão com o banco de dados (substitua pelos seus próprios dados)
    $host = "seu_host";
    $usuario = "seu_usuario";
    $senha = "sua_senha";
    $banco = "seu_banco";

    $conn = new mysqli($host, $usuario, $senha, $banco);

    if ($conn->connect_error) {
        die("Erro na conexão com o banco de dados: " . $conn->connect_error);
    }

    // Obtenha o email da URL (substitua pela lógica real)
    $email = $_GET['email'];

    // Consulta para obter os meses disponíveis
    $query_meses = "SELECT DISTINCT mes FROM horarios_disponiveis WHERE email = '$email'";
    $result_meses = $conn->query($query_meses);

    if ($result_meses->num_rows > 0) {
        while ($row_mes = $result_meses->fetch_assoc()) {
            $mes = $row_mes['mes'];
            echo "<button onclick=\"showDias('$mes', '$email')\">$mes</button>";
        }
    } else {
        echo "Nenhum mês disponível.";
    }

    // Feche a conexão com o banco de dados
    $conn->close();
}
?>

<script>
function showDias(mes, email) {
    // Consulta para obter os dias disponíveis
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("dias").innerHTML = this.responseText;
        }
    };
    xhttp.open("GET", "get_dias.php?email=" + email + "&mes=" + mes, true);
    xhttp.send();
}

function showHorarios(dia, email) {
    // Consulta para obter os horários disponíveis
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("horarios").innerHTML = this.responseText;
        }
    };
    xhttp.open("GET", "get_horarios.php?email=" + email + "&dia=" + dia, true);
    xhttp.send();
}
</script>

<!-- Aqui você exibe os dias e horários em divs -->
<div id="dias"></div>
<div id="horarios"></div>
