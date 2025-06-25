
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Website </title>
    <link rel="stylesheet" href="fontawesome-free-6.6.0-web/css/all.min.css">

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
    color: #fe0000;
    text-decoration:none ;
    font-weight: 100;
}

</style>
</head>
<body>
<header class="header">
        <a href ="#" class="logo">Exam Portal.</a>

    
        <nav class="navbar">
            <a href="index.php" class="active">Home</a>
            <a href=".../register.php">Register</a>
            <a href=".../login.php">login</a>
        </nav>
    </header>