
<?php
session_start();
if ($_SESSION['role'] !== 'admin') {
    die("Access denied.");
}

include '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $exam_name = $_POST['exam_name'];
    $created_by = $_SESSION['user_id'];

    $stmt = $conn->prepare("INSERT INTO exams (exam_name, created_by) VALUES (:exam_name, :created_by)");
    $stmt->bindParam(':exam_name', $exam_name);
    $stmt->bindParam(':created_by', $created_by);

    if ($stmt->execute()) {
        // Redirect to add_questions.php with the newly created exam ID
        $exam_id = $conn->lastInsertId(); // Get the ID of the newly created exam
        header("Location: add_questions.php?exam_id=$exam_id");
        exit();
    } else {
        echo "Failed to create exam.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create Exam</title>
    <style>
        body { font-family: Arial, sans-serif;
        
        }
        form { max-width: 500px; margin: 0 auto; 
        }
        input, button { width: 100%; padding: 8px; margin-top: 10px;
            border-radius: 5px;
            background-color: white;

        }

        .pp {
    margin-top: 15px;
    margin-bottom: 300px;
         }
         a {
    color: white;
    text-decoration: none;
    background-color:rgb(4, 224, 34);
    padding: 10px;
    border-radius: 5px;
    }
    </style>
</head>
<body>
    <h1>Create Exam</h1>
    <form method="POST" action="">
        <input type="text" name="exam_name" placeholder="Exam Name" required>
        <button type="submit">Create Exam</button>
       <p class="pp"> <a href="add_questions.php">Add Questions</a></p>
    </form>
</body>
</html>