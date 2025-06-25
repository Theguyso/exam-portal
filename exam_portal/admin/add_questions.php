<?php
session_start();
if ($_SESSION['role'] !== 'admin') {
    die("Access denied.");
}

include '../includes/db.php';

// Fetch available exams
$stmt = $conn->prepare("SELECT * FROM exams");
$stmt->execute();
$exams = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $exam_id = $_POST['exam_id'];
    $question_text = $_POST['question_text'];
    $option1 = $_POST['option1'];
    $option2 = $_POST['option2'];
    $option3 = $_POST['option3'];
    $option4 = $_POST['option4'];
    $correct_option = $_POST['correct_option'];

    // Insert question into the database
    $stmt = $conn->prepare("INSERT INTO questions (exam_id, question_text, option1, option2, option3, option4, correct_option) VALUES (:exam_id, :question_text, :option1, :option2, :option3, :option4, :correct_option)");
    $stmt->bindParam(':exam_id', $exam_id);
    $stmt->bindParam(':question_text', $question_text);
    $stmt->bindParam(':option1', $option1);
    $stmt->bindParam(':option2', $option2);
    $stmt->bindParam(':option3', $option3);
    $stmt->bindParam(':option4', $option4);
    $stmt->bindParam(':correct_option', $correct_option);

    if ($stmt->execute()) {
        echo "Question added successfully!";
    } else {
        echo "Failed to add question.";
    }
}

// Get the exam_id from the URL if it exists
$exam_id = $_GET['exam_id'] ?? null;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Questions</title>
    <style>
        body { font-family: Arial, sans-serif; }
        form { max-width: 500px; margin: 0 auto; }
        label { display: block; margin-top: 10px; }
        input, textarea, select { width: 100%; padding: 8px; margin-top: 5px; }
        button { margin-top: 20px; padding: 10px 20px; }
    </style>
</head>
<body>
    <h1>Add Questions</h1>

    <!-- Form to add questions -->
    <form method="POST" action="">
        <label for="exam_id">Select Exam:</label>
        <select name="exam_id" required>
            <option value="">-- Select Exam --</option>
            <?php foreach ($exams as $exam): ?>
                <option value="<?= $exam['id'] ?>" <?= ($exam_id == $exam['id']) ? 'selected' : '' ?>>
                    <?= $exam['exam_name'] ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="question_text">Question:</label>
        <textarea name="question_text" rows="4" required></textarea>

        <label for="option1">Option 1:</label>
        <input type="text" name="option1" required>

        <label for="option2">Option 2:</label>
        <input type="text" name="option2" required>

        <label for="option3">Option 3:</label>
        <input type="text" name="option3" required>

        <label for="option4">Option 4:</label>
        <input type="text" name="option4" required>

        <label for="correct_option">Correct Option:</label>
        <select name="correct_option" required>
            <option value="">-- Select Correct Option --</option>
            <option value="1">Option 1</option>
            <option value="2">Option 2</option>
            <option value="3">Option 3</option>
            <option value="4">Option 4</option>
        </select>

        <button type="submit">Add Question</button>
    </form>

    <p><a href="../dashboard.php">Back to Dashboard</a></p>
</body>
</html>