<?php
include 'back/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Booking logic
    $idHorario = $_POST['id_horario'];
    $nome = $_POST['nome'];
    $email = $_POST['email'];

    // Update the appointment status in the database
    // ...
}
?>