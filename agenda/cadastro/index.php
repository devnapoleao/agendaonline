<!-- index.php -->
<!DOCTYPE html>
<html lang="pt-br">
<head>
<script src="script.js"></script>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Cadastro de Horários</title>
<style>
  body {
    font-family: 'Arial', sans-serif;
    background-image: url('fundo.jpg'); /* Substitua com o caminho para sua imagem de fundo */
    background-size: cover;
  }
  .calendar {
    width: 300px;
    margin: 50px auto;
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
  }
  .month-header {
    background: #FFB6C1; /* Cor do cabeçalho */
    padding: 20px;
    text-align: center;
    color: white;
    text-transform: uppercase;
  }
  .days-grid {
    padding: 10px;
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    text-align: center;
  }
  .day {
    padding: 10px;
    cursor: pointer;
  }
  .day:hover {
    background: #f0f0f0;
  }
  .popup {
    display: none;
    position: absolute;
    left: 50%;
    top: 50%;
    transform: translate(-50%, -50%);
    background: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.3);
    z-index: 10;
  }
  .close-btn {
    cursor: pointer;
    float: right;
    font-size: 20px;
  }
</style>
</head>
<body>

<div class="calendar">
  <!-- Cabeçalho do Calendário e Dias ... -->
</div>
<div class="popup" id="popup">
  <span class="close-btn" onclick="hidePopup()">&times;</span>
  <p>Cadastrar Novo Horário:</p>
  <form id="cadastroHorario">
    <label for="data">Data:</label>
    <input type="date" id="data" name="data" required><br><br>
    <label for="hora">Hora:</label>
    <input type="time" id="hora" name="hora" required><br><br>
    <input type="submit" value="Cadastrar Horário">
  </form>
</div>

<script>
// Script JavaScript existente...
document.getElementById('cadastroHorario').addEventListener('submit', function(e) {
  e.preventDefault();
  var data = document.getElementById('data').value;
  var hora = document.getElementById('hora').value;
  // Envie estes dados para o servidor via AJAX
  cadastrarHorario(data, hora);
});

function cadastrarHorario(data, hora) {
  var xhr = new XMLHttpRequest();
  xhr.open("POST", "cadastrar_horario.php", true);
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
  xhr.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      // Trate a resposta do servidor aqui
      alert(this.responseText);
      hidePopup();
    }
  };
  xhr.send('data=' + encodeURIComponent(data) + '&hora=' + encodeURIComponent(hora));
}
</script>

</body>
</html>
