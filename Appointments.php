<?php
session_start();
include "connect.php";

if(!isset($_SESSION['patient_id'])){
    $_SESSION['patient_id'] = 1;
}

$patient_id = $_SESSION['patient_id'];

$sql = "SELECT a.appointment_id, a.specialty, a.appointment_date, a.appointment_time,
        d.f_name AS doctor_fname, d.l_name AS doctor_lname
        FROM appointments a
        JOIN doctors d ON a.doctor_id = d.doctor_id
        WHERE a.patient_id = $patient_id
        ORDER BY a.appointment_date, a.appointment_time";

$result = $conn->query($sql);
$appointments = [];
while($row = $result->fetch_assoc()){
    $appointments[] = $row;
}

header('Content-Type: application/json');
echo json_encode($appointments);
?>
