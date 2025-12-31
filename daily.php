<?php

session_start();
$host = "localhost";
$user = "root";
$pass = "";
$db = "Hospital System";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ===== معالجة AJAX للحجز =====
if(isset($_POST['action']) && $_POST['action'] === 'book') {
    $patient = $_POST['patient'];
    $doctor = $_POST['doctor'];
    $specialtyname = $_POST['specialtyname'];
    $date = $_POST['date'];
    $time = $_POST['time'];

    // التحقق من التعارض
    $check = $conn->prepare("SELECT * FROM appointments WHERE doctor=? AND date=? AND time=?");
    $check->bind_param("sss", $doctor, $date, $time);
    $check->execute();
    $res = $check->get_result();
    if($res->num_rows > 0){
        echo json_encode(["status"=>"error","msg"=>"Appointment already booked"]);
        exit;
    }

    // إدخال البيانات
    $stmt = $conn->prepare("INSERT INTO appointments (patient, doctor, specialtyname, date, time) VALUES (?,?,?,?,?)");
    $stmt->bind_param("sssss",$patient,$doctor,$specialtyname,$date,$time);
    if($stmt->execute()){
        echo json_encode(["status"=>"success","msg"=>"Appointment booked"]);
    } else {
        echo json_encode(["status"=>"error","msg"=>"Failed to book"]);
    }
    exit;
}

// ===== معالجة AJAX للتقارير =====
if(isset($_GET['action']) && $_GET['action'] === 'report') {
    $type = $_GET['type'] ?? 'daily';
    $appointments = [];

    if($type=='daily'){
        $today = date('Y-m-d');
        $sql = "SELECT * FROM appointments WHERE date='$today' ORDER BY date,time";
    } else {
        $weekStart = date('Y-m-d', strtotime('monday this week'));
        $weekEnd = date('Y-m-d', strtotime('sunday this week'));
        $sql = "SELECT * FROM appointments WHERE date BETWEEN '$weekStart' AND '$weekEnd' ORDER BY date,time";
    }

    $result = $conn->query($sql);
    while($row = $result->fetch_assoc()){
        $appointments[] = $row;
    }

    echo json_encode($appointments);
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Hospital System</title>
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>

<style>
body{ font-family: Arial; background: rgb(218,218,255); margin:0; padding:0;}
h1{text-align:center; color:rgb(104,104,255);}
.container{width:90%; margin:auto;}
.booking, .reports{background:white; padding:20px; margin:20px 0; border-radius:10px;}
input, select, button{padding:8px; margin:5px; border-radius:5px;}
button{cursor:pointer; background:#007bff; color:white; border:none;}
button:hover{background:#0056b3;}
#calendar{background:white; padding:20px; border-radius:10px;}
</style>
</head>
<body>
<div class="container">

<h1>Hospital Appointment System</h1>

<!-- Booking Section -->
<div class="booking">
<h2>Book Appointment</h2>
Patient Name: <input type="text" id="patient"><br>
Doctor: 
<select id="doctor">
<option value="Ahmed Mohamed">Ahmed Mohamed</option>
<option value="Sara Samy">Sara Samy</option>
<option value="Alsaeed Khedr">Alsaeed Khedr</option>
<option value="Amr Hisham">Amr Hisham</option>
<option value="Islam Mohamed">Islam Mohamed</option>
</select><br>
Specialty: 
<select id="specialtyname">
<option value="Cardiology">Cardiology</option>
<option value="Dermatology">Dermatology</option>
<option value="Neurology">Neurology</option>
<option value="Pediatrics">Pediatrics</option>
<option value="Orthopedics">Orthopedics</option>
</select><br>
Date: <input type="date" id="date"><br>
Time: <input type="time" id="time"><br>
<button onclick="bookAppointment()">Book Now</button>
<p id="bookMsg"></p>
</div>

<!-- Calendar Section -->
<div class="booking">
<h2>Doctor Schedule</h2>
<div id="calendar"></div>
</div>

<!-- Reports Section -->
<div class="reports">
<h2>Appointments Reports</h2>
<button onclick="getReport('daily')">Daily Report</button>
<button onclick="getReport('weekly')">Weekly Report</button>
<div id="report"></div>
</div>

</div>

<script>
// ===== Booking =====
function bookAppointment(){
    const patient = document.getElementById('patient').value;
    const doctor = document.getElementById('doctor').value;
    const specialtyname = document.getElementById('specialtyname').value;
    const date = document.getElementById('date').value;
    const time = document.getElementById('time').value;

    if(!patient || !doctor || !specialtyname || !date || !time){
        alert("Please fill all fields");
        return;
    }

    fetch('', {
        method:'POST',
        headers:{'Content-Type':'application/x-www-form-urlencoded'},
        body:`action=book&patient=${encodeURIComponent(patient)}&doctor=${encodeURIComponent(doctor)}&specialtyname=${encodeURIComponent(specialtyname)}&date=${date}&time=${time}`
    })
    .then(res=>res.json())
    .then(data=>{
        document.getElementById('bookMsg').textContent = data.msg;
        loadCalendar();
        getReport(currentReportType);
    });
}

// ===== Calendar =====
let calendar;
document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar');
    calendar = new FullCalendar.Calendar(calendarEl,{
        initialView:'dayGridMonth',
        headerToolbar:{left:'prev,next today',center:'title',right:'dayGridMonth,timeGridWeek,timeGridDay'},
        events: []
    });
    calendar.render();
    loadCalendar();
});

function loadCalendar(){
    fetch('hospital_system.php?action=report&type=weekly')
    .then(res=>res.json())
    .then(data=>{
        const events = data.map(a=>({
            title: a.patient + " ("+a.specialtyname+")",
            start: a.date + "T" + a.time,
            color:"orange"
        }));
        calendar.removeAllEvents();
        calendar.addEventSource(events);
    });
}

// ===== Reports =====
let currentReportType = 'daily';
function getReport(type){
    currentReportType = type;
    fetch('hospital_system.php?action=report&type='+type)
    .then(res=>res.json())
    .then(data=>{
        const reportDiv = document.getElementById('report');
        if(data.length==0){ reportDiv.innerHTML="<p>No appointments found.</p>"; return;}
        let html = '';
        data.forEach(a=>{
            html += `<p>${a.date} ${a.time} - ${a.patient} with Dr. ${a.doctor} (${a.specialtyname})</p>`;
        });
        reportDiv.innerHTML = html;
    });
}
</script>

</body>
</html>
