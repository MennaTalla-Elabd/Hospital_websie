<?php
include "connect.php"; 

if($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = $_POST['email'];
    $password = $_POST['password'];
    $position = $_POST['Position'];

    // اختيار الجدول حسب Position
    if($position == "Patient"){
        $table = "Patients";
    } elseif($position == "Doctor") {
        $table = "Doctors";
    } elseif($position == "Staff") {
        $table = "Staff";
    } else {
        die("Please select a valid position");
    }

    // جلب المستخدم
    $stmt = $conn->prepare("SELECT * FROM $table WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows == 1){
        $user = $result->fetch_assoc();
        // تحقق كلمة المرور (لو مش مشفرة: strcmp) أو password_verify لو مشفرة
        if($user['password'] === $password){
            echo "<h3>✅ Login successful! Welcome ".$user['f_name']."</h3>";
            // هنا ممكن تعمل session
            // session_start();
            // $_SESSION['user'] = $user['email'];
            // header("Location: dashboard.php");
        } else {
            echo "<h3 style='color:red'> Wrong password!</h3>";
        }
    } else {
        echo "<h3 style='color:red'> User not found!</h3>";
    }
}
?>
