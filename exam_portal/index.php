<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>
<body>
<!-- <?php
//session_start(); // Start the session (if needed)
?> -->

<!DOCTYPE html>
<html>
<head>
    
    <style>
        *{
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    text-decoration: none;
    border: none;
    outline: none;
    scroll-behavior: smooth;
    font-family: 'Poppins', sans-serif;
}

:root{
    --bg-color:#ffffff;
    --second-bg-color: #ffffff;
    --text-color: #fe0000;
    --main-color:rgb(199, 192, 192);
}

html{
    font-size: 68.5%;
    overflow-x: hidden;
}
body{
    background: var(--bg-color);
    color: var(--text-color);

}



section{
    min-height: 100vh;
    padding: 10rem 9% 2rem;
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
.home-content h1::before{
    content: '';
    position: absolute;
    top: 0;
    right:0 ;
    width: 100%;
    height: 100%;
     
 }

.home-content h3{
    position: relative;
    font-size: 32px;
    font-weight: 700;
    color: var(--text-color);
}

.home-content p{
    position: relative;
    font-size: 16px;
    margin: 20px 0 40px;
 }
 
.home-content .btn-box{
    position: relative;
    display: flex;
    justify-content: space-between;
    width: 345px;
    height: 50px;
}


.btn-box a{
    position: relative;
    display: inline-flex;
    justify-content: center;
    align-items: center;
    width: 150px;
    height: 100%;
    background: var(--main-color);
    border: 2px solid var(--main-color);
    border-radius: 20px;
    font-size: 19px;
    color: var(--bg-color);
    text-decoration: none;
    font-weight: 600;
    letter-spacing: 1px;
    z-index: 1;
    overflow: hidden;
    transition: .5s;
}
.btn-box a:hover{
    color: var(--main-color);
}

.btn-box a:nth-child(2){
    background: transparent;
    color: var(--main-color);
}

.btn-box a:nth-child(2)::before{
    background: var(--main-color);
}
.btn-box a:nth-child(2):hover{
    color:var(--bg-color) ;
}

.btn-box a::before{
content: '';
position: absolute;
top: 0;
left: 0;
width: 0;
height: 100%;
background: var(--bg-color);
z-index: -1;
transition: .5s;
}

.btn-box a:hover::before{
width: 100%;
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

@keyframes  showRight{
    100%{
        width: 0;
    }
}

@keyframes manipActiveHover{
    100%{
        pointer-events: auto;
    }
}


.heading{
    font-size: 5rem;
    margin-bottom: 3rem;
    text-align: center;
}

span{
    color: var(--main-color);
}

.header{
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    padding: 20px 10%;
    background: transparent;
    display: flex;
    justify-content: space-between;
    align-items: center;
    z-index:100 ;
}



.navbar a{
    color: #fe0000;
    font-size: 15px;
}

.logo{
    position: relative;
    font-size: 24px;
    color: var(--text-color);
    text-decoration:none ;
    font-weight: 100;
}
    </style>
</head>
<header class="header">
        <a href ="#" class="logo">Exam Portal.</a>

        

        <nav class="navbar">
            <a href="#home" class="active">Home</a>
            <a href="register.php">Register</a>
            <a href="login.php">login</a>
        </nav>
    </header>
<body>
    
        <section class="home" id="home">
        <div class="home-content"><h1>Welcome</h1>
            <h3>To the exam portal</h3>
            <p> The Exam Portal is designed to help students take exams online and allow admins to manage exams and questions efficiently.
            Whether you're a student or an admin, you'll find everything you need here.</p>
        
                 <div class="btn-box">
                    <a href="register.php">Register</a>
                    <a href="login.php">Login</a>
                 </div>
                </div>
                
                
                </section>
            </section>

        
    

</body>
</html>

<?php
include 'assets/footer.php';
?>
