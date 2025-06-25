<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'includes/db.php';
include 'assets/header.php';

$role = $_SESSION['role'];



?>

<!DOCTYPE html>
<html>
<head>
    <style>

:root{
    --bg-color: #ffffff;
    --second-bg-color: #ffffff;
    --text-color: #fe0000;
    --main-color: #8b9467;
}

body{
    background: var(--bg-color);
    color: var(--text-color);

}



section{
    min-height: 100vh;
}



.home{
    height: 100vh;
    background-image: url(assets/bgg.jpg);
    background-size: cover;
    text-align: center;
    display: flex;
    background-color: blur(8px);
    align-items: center;
    padding: 0 10%;
}

.home-content{
    
    max-width: 600px;
    z-index: 99;
}

.home-content h1{
    position: relative;
    font-size: 56px;
    font-weight: 700;
    line-height: 1.2;
}


.home-content h3{
    position: relative;
    font-size: 32px;
    font-weight: 700;
    color: var(--text-color);
}


 
.home-content .btn-box{
    position: relative;
    display: flex;
    justify-content: space-between;
    width: 345px;
    height: 50px;
    margin-top: 50px;
    border-width: 5px solid var(--main-color);

}

.btn-box :hover{
    color: var(--main-color);
}


.btn-box a{
    position: relative;
    display: inline-flex;
    justify-content: center;
    align-items: center;
    width: 150px;
    height: 100%;
    background: var(--text-color);
    border-radius: 10px;
    font-size: 19px;
    color: var(--bg-color);
    text-decoration: none;
    font-weight: 600;
    letter-spacing: 1px;
    z-index: 1;
    transition: .5s;
}




/* Media Queries for Responsive Design */
@media (min-width: 768px) { 
    .home { flex-direction: row; text-align: left; } 
    .home-content { width: 60%; } 
    .home-imagehover { width: 40%; height: auto; margin-top: 0; }
 } 
 @media (min-width: 1024px) {
     .header { padding: 20px 40px; } 
     .navbar a { margin-left: 40px; } 
     .home-content h1 { font-size: 48px; } 
     .home-content h3 { font-size: 32px; } 
     .home-content p { font-size: 20px; } 
     
    }





</style>
</head>
<body>
    <section class="home" id="home">
        <div class="home-content"><h1>Welcome</h1>
            <h3>To your  dashboard</h3>

                 <div class="btn-box">
                 <?php if ($role === 'admin'): ?>
 <a href="admin/create_exam.php">Create Exam</a>  
    <?php else: ?> 
        <a href="exam/start_exam.php">Start Exam</a>       
    <?php endif; ?>

    <a href="logout.php">Logout</a>
                 </div>
                </div>
                
                </section>
  
    <?php
include 'assets/footer.php';
?>
</body>
</html>
