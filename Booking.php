<?php
// Booking.php

// الاتصال بالداتا بيز
include 'connect.php';

// التأكد من وصول البيانات
if(isset($_POST['doctor_id'], $_POST['patient_id'], $_POST['specialty'], $_POST['appointment_date'], $_POST['appointment_time'])){

    $doctor_id = $_POST['doctor_id'];
    $patient_id = $_POST['patient_id'];
    $specialty = $_POST['specialty'];
    $appointment_date = $_POST['appointment_date'];
    $appointment_time = $_POST['appointment_time'];
    $status = "pending"; // الحالة الافتراضية
    $created_at = date('Y-m-d H:i:s');

    // إدخال البيانات في جدول appointments
    $stmt = $conn->prepare("INSERT INTO appointments (patient_id, doctor_id, specialty, appointment_date, appointment_time, status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iisssss", $patient_id, $doctor_id, $specialty, $appointment_date, $appointment_time, $status, $created_at);

    if($stmt->execute()){
        echo "<script>alert('Appointment booked successfully!'); window.location.href='booking.html';</script>";
    } else {
        echo "<script>alert('Failed to book appointment.'); window.history.back();</script>";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "<script>alert('Invalid submission'); window.history.back();</script>";
}
?>
