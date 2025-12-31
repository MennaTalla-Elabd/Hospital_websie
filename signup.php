<?php
// connect.php محتاج يكون موجود مسبقًا
include 'connect.php'; // هنا تربط بقاعدة البيانات

$message = '';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $firstname = trim($_POST['firstname']);
    $lastname  = trim($_POST['lastname']);
    $email     = trim($_POST['email']);
    $password  = trim($_POST['password']);
    $gender    = isset($_POST['gender']) ? $_POST['gender'] : '';

    // basic validation
    if(empty($firstname) || empty($lastname) || empty($email) || empty($password) || empty($gender)) {
        $message = "Please fill all fields!";
    } else {
        // password hash
        $hashed_pass = password_hash($password, PASSWORD_DEFAULT);

        // check if email already exists
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $res = $stmt->get_result();
        if($res->num_rows > 0){
            $message = "Email already registered!";
        } else {
            // insert user
            $stmt = $conn->prepare("INSERT INTO users (firstname, lastname, email, password, gender) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $firstname, $lastname, $email, $hashed_pass, $gender);
            if($stmt->execute()){
                $message = "Account created successfully!";
                header("Location: fill profile patient.html"); // redirect بعد التسجيل
                exit;
            } else {
                $message = "Error: ".$stmt->error;
            }
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sign Up to Hospital</title>
<link rel="stylesheet" type="text/css" href="Styles.css" />
<style>
    /* copy نفس CSS اللي عندك */
    .login_signup { max-width: 500px; margin: 65px auto; padding: 40px; background: rgba(255, 255, 255, 0.9); border-radius: 20px; box-shadow: 0 0 20px rgba(0,0,0,0.5); }
    h1 { text-align: center; margin-bottom: 10px; color: #000; }
    p { text-align: center; font-size: 16px; color: #000; margin-bottom: 30px; }
    label { display: block; font-size: 14px; margin-bottom: 5px; color: #000; }
    input.box { width: 100%; padding: 10px; margin-bottom: 15px; border-radius: 6px; border: 1px solid #ccc; font-size: 14px; }
    .btn { width: 100%; padding: 12px; border-radius: 6px; border: none; cursor: pointer; background-color: #40a7eb; color: white; font-size: 16px; margin-top: 10px; text-align: center; }
    input[type="submit"]:hover { background-color: #2d8ac6; }
    p input[type="radio"] { margin-left: 10px; margin-right: 5px; }
    .topbar { position: fixed; top: 0; left: 0; width: 100%; z-index: 1000; margin: 20px 0px; padding: 40px; background-color: rgba(229, 236, 236, 0.92); }
    .bar a { text-decoration: none; color: black; font-weight: bold; font-size: x-large; justify-content: center; gap: 80px; padding: 20px; }
    body { background-image: url(log.jpg); }
    .message { text-align: center; color:red; margin-bottom:10px; font-weight:bold; }
</style>
</head>
<body>

<header class="topbar">
<div class="bar">
    <a href="sandw.HTML">Home</a>
    <a href="login.html">Login</a>
    <a href="search.html">search</a>
    <a href="booking.html">Booking</a>
    <a href="profile admin.html">My Profile</a>
</div>
</header>

<form method="post" action="" class="login_signup">
    <h1>Create New Account</h1>
    <p>Welcome back to your personal account</p>

    <?php if($message != '') { echo '<div class="message">'.$message.'</div>'; } ?>

    <label>First Name</label>
    <input type="text" name="firstname" placeholder="Enter your first name" class="box">

    <label>Second Name</label>
    <input type="text" name="lastname" placeholder="Enter your second name" class="box">

    <label>Email Address</label>
    <input type="email" name="email" placeholder="Write Your Email Address" class="box">

    <label>Password</label>
    <input type="password" name="password" placeholder="Write Your Password Here" class="box">

    <p>Gender<br/>
        <input type="radio" name="gender" value="male"> Male
        <input type="radio" name="gender" value="female"> Female
    </p>

    <input type="submit" value="Create" class="btn">
</form>

</body>
</html>
