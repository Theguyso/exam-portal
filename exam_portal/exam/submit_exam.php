<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

include '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $exam_id = $_POST['exam_id'];
    $user_id = $_SESSION['user_id'];
    $answers = $_POST['answer'];

    // Fetch correct answers
    $stmt = $conn->prepare("SELECT id, correct_option FROM questions WHERE exam_id = :exam_id");
    $stmt->bindParam(':exam_id', $exam_id);
    $stmt->execute();
    $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Calculate score
    $score = 0;
    foreach ($questions as $question) {
        $question_id = $question['id'];
        $correct_option = $question['correct_option'];
        if (isset($answers[$question_id]) && $answers[$question_id] == $correct_option) {
            $score++;
        }
    }

    // Save result to database
    $stmt = $conn->prepare("INSERT INTO results (user_id, exam_id, score) VALUES (:user_id, :exam_id, :score)");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':exam_id', $exam_id);
    $stmt->bindParam(':score', $score);
    $stmt->execute();

    // Redirect to results page
    header("Location: ../results.php?exam_id=$exam_id");
    exit();
}
?>