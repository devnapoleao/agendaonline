document.addEventListener('DOMContentLoaded', function () {
    const calendarioContainer = document.getElementById('calendario-container');
    const popup = document.getElementById('popup');
    const overlay = document.getElementById('overlay');

    function abrirPopup() {
        popup.style.display = 'block';
        overlay.style.display = 'block';
    }

    function fecharPopup() {
        popup.style.display = 'none';
        overlay.style.display = 'none';
    }

    overlay.onclick = fecharPopup;

    // Generate calendar days
    horariosDisponiveis.forEach(function (horario) {
        // Create a new table cell
        var cell = document.createElement('td');
        cell.textContent = new Date(horario.dia).getDate();
        cell.onclick = function () {
            abrirPopup();
            // You can create a form and append it to the popup here
            // e.g., a simple form for name and email
            popup.innerHTML = '<form>' +
                'Nome: <input type="text" name="nome"><br>' +
                'Email: <input type="email" name="email"><br>' +
                '<input type="submit" value="Submit">' +
                '</form>';
        };
        // Append the cell to the calendar container
        calendarioContainer.appendChild(cell);
    });
});
