<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}
include '../includes/db.php';
// include 'assets/footer.php';


// Fetch available exams
$stmt = $conn->prepare("SELECT * FROM exams");
$stmt->execute();
$exams = $stmt->fetchAll(PDO::FETCH_ASSOC);

// If an exam is selected, fetch its questions
if (isset($_GET['exam_id'])) {
    $exam_id = $_GET['exam_id'];
    $stmt = $conn->prepare("SELECT * FROM questions WHERE exam_id = :exam_id");
    $stmt->bindParam(':exam_id', $exam_id);
    $stmt->execute();
    $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html>
<head>
    <style>
        h2 {
    color: #333;
    text-align: center;
}

ul {
                padding-left: 0px;
                background-color: aliceblue;
                box-sizing: content-box;
            }
            li {
                padding-left: 0px;
                max-width: 200px;
                background-color: antiquewhite;
            }

    li a{
        margin-left: 0px;
        color: #28a745;
        padding-bottom: 5px;
        padding-left: 5px;
        border: none;
    }
    i{
        padding: 5px;
    }
form {
    background: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
    max-width: 600px;
    margin: auto;
}

.question {
    margin-bottom: 20px;
    padding: 15px;
    background: #f9f9f9;
    border-radius: 5px;
    border: 1px solid #ddd;
}

.options {
    margin-top: 10px;
}

.options label {
    display: block;
    padding: 5px;
    cursor: pointer;
}

input[type="radio"] {
    margin-right: 8px;
}
.exam{
    border-left: 1px solid #333;
    border-right: 1px solid #333;
    /* border-bottom: 1px solid #333; */
    border-top: 1px solid #333;


}



button {
    background-color: #fe0000;
    color: white;
    padding: 10px 15px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    width: 100%;
    font-size: 16px;
    margin-top: 20px;
}

button:hover {
    background-color:rgb(245, 54, 54);
}


h2{
    margin-top: 100px;
}

    </style>
        <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

</head>
<header>
<?php
include '../assets/header.php';

?>
</header>
<body>
    <section>
    <body>
    <div class="container">
        <!-- <h1>Start Exam</h1> -->

        <!-- Button to show the exam dropdown -->
        <form method="POST" action="">
            <button type="submit" name="show_dropdown">Select Exam</button>
        </form>

        <!-- Dropdown menu for exams -->
        <div class="dropdown">
            <form method="GET" action="">
                <select name="exam_id" onchange="this.form.submit()">
                    <option value="">-- Select Exam --</option>
                    <?php foreach ($exams as $exam): ?>
                        <option value="<?= $exam['id'] ?>"><?= $exam['exam_name'] ?></option>
                    <?php endforeach; ?>
                </select>
            </form>
        </div>

    <!-- Display questions if an exam is selected -->
    <?php if (isset($questions)): ?>
        <h2>Exam Questions</h2>
        <form action="submit_exam.php" method="POST">
            <input type="hidden" name="exam_id" value="<?= $exam_id ?>">
            <?php foreach ($questions as $question): ?>
                <div class="question">
                    <p><?= $question['question_text'] ?></p>
                    <div class="options">
                        <label><input type="radio" name="answer[<?= $question['id'] ?>]" value="1"> <?= $question['option1'] ?></label><br>
                        <label><input type="radio" name="answer[<?= $question['id'] ?>]" value="2"> <?= $question['option2'] ?></label><br>
                        <label><input type="radio" name="answer[<?= $question['id'] ?>]" value="3"> <?= $question['option3'] ?></label><br>
                        <label><input type="radio" name="answer[<?= $question['id'] ?>]" value="4"> <?= $question['option4'] ?></label><br>
                    </div>
                </div>
            <?php endforeach; ?>
            <button type="submit">Submit Exam</button>
        </form>
    <?php endif; ?>
    </section>
</body>
</html>