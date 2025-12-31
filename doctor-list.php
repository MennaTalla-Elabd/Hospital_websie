<?php
// doctors_list.php
include 'connect.php';

$sql = "SELECT d.doctor_id, d.f_name, d.l_name, d.email, d.gender, d.degree, d.working_days, d.work_start_time, d.work_end_time, s.speciality_name
        FROM Doctors d
        JOIN Speciality s ON d.speciality_id = s.speciality_id";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<h2>Doctors List</h2>";
    echo "<table border='1' cellpadding='10'>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Gender</th>
                <th>Degree</th>
                <th>Working Days</th>
                <th>Work Hours</th>
                <th>Speciality</th>
            </tr>";
    while($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>".$row['doctor_id']."</td>
                <td>".$row['f_name']." ".$row['l_name']."</td>
                <td>".$row['email']."</td>
                <td>".$row['gender']."</td>
                <td>".$row['degree']."</td>
                <td>".$row['working_days']."</td>
                <td>".$row['work_start_time']." - ".$row['work_end_time']."</td>
                <td>".$row['speciality_name']."</td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "No doctors found.";
}

$conn->close();
?>
