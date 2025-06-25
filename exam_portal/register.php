<?php
include 'includes/db.php';
// include 'assets/footer.php';
// include 'assets/header.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $role = $_POST['role'] ?? 'student'; // Default to 'student' if role is not provided

    
    // Insert user into the database
    $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (:username, :email, :password, :role)");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $password);
    $stmt->bindParam(':role', $role);


    if ($stmt->execute()) {
        echo "Registration successful! <a href='../login.php'>Login here</a>";
    } else {
        echo "Registration failed.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <style>

body{
    height: 100%;
    background-image: url(assets/bgg.jpg);
    background-size: cover;
    text-align: center;
    background-color: blur(8px);
    align-items: center;
    font-family: Arial, sans-serif;

}
.input-container {
            position: relative;
            margin-bottom: 15px;
        }
        .input-container i {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: solid #333;
            /* z-index: -1; */
        }
h1 {
    
    text-align: center;
    color: #333;
        
}

form {
    background: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgb(24, 185, 3);
    display: inline-block;
    max-width: 300px;
    width: 100%;
    height: 100%;
    margin-top: 100px;
} 



input[type="email"],
        input[type="password"],
        input[type="text"]
{
            width: 100%;
            padding: 10px 10px 10px 30px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            border-bottom-color: #fe0000;
            box-sizing: border-box;

        }

button {
    background-color: #fe0000;
    color: white;
    padding: 10px 15px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    width: 100%;
}

form {
    background: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 10px #fe0000;
    display: inline-block;
    max-width: 300px;
    width: 100%;
    /* height: 100%; */
    margin-top: 100px;
} 

button:hover {
    background-color: #218838;
}

.pp {
    margin-top: 15px;
    margin-bottom: 300px;
}

a {
    color: #007bff;
    text-decoration: none;
}

a:hover {
    text-decoration: underline;
}
</style>
</head>
<header>
    <?php
    include 'assets/header.php';
    ?>
</header>
<body>
    <form method="POST" action="">
    <h1>Register</h1>
    <div class="input-container">
        <input type="text" name="username" placeholder="Username" required><br>
    </div>
    <div class="input-container">
        <input type="email" name="email" placeholder="Email" required><br>
    </div>
    <div class="input-container">
        <input type="password" name="password" placeholder="Password" required><br>
    </div>
        <!-- <input type="hidden" name="role" value="admin"> -->
        <button type="submit">Register</button>
    </form>
    <p class="pp">Already have an account? <a href="login.php">Login here</a></p>
</body>
</html>

<?php
include 'assets/footer.php';
?>