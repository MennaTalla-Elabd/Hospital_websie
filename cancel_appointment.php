<?php
session_start();
include "connect.php";

if(!isset($_SESSION['patient_id'])){
    $_SESSION['patient_id'] = 1;
}

$patient_id = $_SESSION['patient_id'];

if(isset($_POST['appointment_id'])){
    $id = $_POST['appointment_id'];
    $conn->query("DELETE FROM appointments WHERE appointment_id = $id AND patient_id = $patient_id");
}

header("Location: appointments.html");
exit();
?>
