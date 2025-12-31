<?php
include 'db.php';

// معالجة إضافة سجل جديد
if(isset($_POST['add'])){
    $patient_id = $_POST['patient_id'];
    $patient_name = $_POST['patient_name'];
    $type = $_POST['type'];
    $doctor = $_POST['doctor'];
    $speciality = $_POST['speciality'];
    $date = $_POST['date'];

    $stmt = $conn->prepare("INSERT INTO patient_records (patient_id, patient_name, type, doctor, speciality, record_date) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $patient_id, $patient_name, $type, $doctor, $speciality, $date);
    $stmt->execute();
    $stmt->close();
}

// معالجة حذف سجل
if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM patient_records WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

// معالجة البحث
$search_id = '';
if(isset($_GET['search'])){
    $search_id = $_GET['search'];
}

$sql = "SELECT * FROM patient_records";
if($search_id !== ''){
    $sql .= " WHERE patient_id LIKE '%".$conn->real_escape_string($search_id)."%'";
}
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Patient Records</title>
<style>
body{ font-family: Arial; background: rgb(218,218,255); }
h1{ text-align:center; padding:20px; background: rgba(169,179,188,0.92);}
form{ margin:20px auto; text-align:center; }
input,opt,select,button{ padding:8px 12px; margin:4px; border-radius:6px; }
table{ width:90%; margin:20px auto; border-collapse: collapse; }
th,td{ border:1px solid #999; padding:8px; text-align:center; }
th{ background: rgba(169,179,188,0.92);}
button.delete{ background:red; color:white; border:none; border-radius:6px; padding:4px 8px; cursor:pointer; }
</style>
</head>
<body>

<h1>Patient Records</h1>

<!-- Form إضافة سجل -->
<form method="post" action="">
<input type="text" name="patient_id" placeholder="Patient ID" required>
<input type="text" name="patient_name" placeholder="Patient Name" required>
<select name="type" required>
    <option value="">Select Type</option>
    <option value="Lab Tests">Lab Tests</option>
    <option value="Radiology">Radiology</option>
    <option value="Follow_Up">Follow Up</option>
    <option value="Prescription">Prescription</option>
    <option value="Check_Up">Check Up</option>
</select>
<select name="doctor" required>
    <option value="">Select Doctor</option>
    <option>Ahmed Mohamed</option>
    <option>Sara Samy</option>
    <option>Alsaeed Khedr</option>
    <option>Amr Hisham</option>
    <option>Islam Mohamed</option>
</select>
<select name="speciality" required>
    <option value="">Select Speciality</option>
    <option>Cardiology</option>
    <option>Dermatology</option>
    <option>Neurology</option>
    <option>Pediatrics</option>
    <option>Orthopedics</option>
</select>
<input type="date" name="date" required>
<button type="submit" name="add">Add Record</button>
</form>

<!-- Search -->
<form method="get" action="" style="text-align:center;">
<input type="text" name="search" placeholder="Search by Patient ID" value="<?php echo htmlspecialchars($search_id); ?>">
<button type="submit">Search</button>
<a href="patient_records.php">Show All</a>
</form>

<!-- جدول السجلات -->
<table>
<thead>
<tr>
<th>ID</th>
<th>Patient ID</th>
<th>Patient Name</th>
<th>Type</th>
<th>Doctor</th>
<th>Speciality</th>
<th>Date</th>
<th>Action</th>
</tr>
</thead>
<tbody>
<?php
if($result->num_rows > 0){
    while($row = $result->fetch_assoc()){
        echo "<tr>
        <td>{$row['id']}</td>
        <td>{$row['patient_id']}</td>
        <td>{$row['patient_name']}</td>
        <td>{$row['type']}</td>
        <td>{$row['doctor']}</td>
        <td>{$row['speciality']}</td>
        <td>{$row['record_date']}</td>
        <td><a href='?delete={$row['id']}' onclick=\"return confirm('Are you sure?');\"><button class='delete'>Delete</button></a></td>
        </tr>";
    }
}else{
    echo "<tr><td colspan='8'>No records found</td></tr>";
}
?>
</tbody>
</table>

</body>
</html>
