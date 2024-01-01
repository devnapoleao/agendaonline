function showPopup(horariosJson) {
    const horarios = JSON.parse(horariosJson);
    const horariosDiv = document.getElementById('horariosDisponiveis');
    horariosDiv.innerHTML = ''; // Limpa horários anteriores

    horarios.forEach(horario => {
        const horarioElement = document.createElement('div');
        horarioElement.textContent = horario;
        horariosDiv.appendChild(horarioElement);
    });

    // Exibir o popup
    document.getElementById('popup').style.display = 'block';
}

function hidePopup() {
    document.getElementById('popup').style.display = 'none';
}
