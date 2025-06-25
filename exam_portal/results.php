<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'includes/db.php';

// Fetch the user's result for the selected exam
if (isset($_GET['exam_id'])) {
    $exam_id = $_GET['exam_id'];
    $user_id = $_SESSION['user_id'];

    // Fetch exam details
    $stmt = $conn->prepare("SELECT exam_name FROM exams WHERE id = :exam_id");
    $stmt->bindParam(':exam_id', $exam_id);
    $stmt->execute();
    $exam = $stmt->fetch(PDO::FETCH_ASSOC);

    // Fetch user's result
    $stmt = $conn->prepare("SELECT score FROM results WHERE user_id = :user_id AND exam_id = :exam_id");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':exam_id', $exam_id);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // Calculate percentage score
    if ($result) {
        $score = $result['score'];
        $total_questions = $conn->query("SELECT COUNT(*) FROM questions WHERE exam_id = $exam_id")->fetchColumn();
        $percentage = ($total_questions > 0) ? round(($score / $total_questions) * 100, 2) : 0;
    }

    // Fetch leaderboard (top 10 scores for this exam)
    $leaderboard = $conn->query("
        SELECT u.username, r.score 
        FROM results r
        JOIN users u ON r.user_id = u.id
        WHERE r.exam_id = $exam_id
        ORDER BY r.score DESC
        LIMIT 10
    ")->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Exam Results</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1, h2 {
            color: #333;
            text-align: center;
        }
        .result-section, .leaderboard-section {
            margin: 30px 0;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 5px;
        }
        .progress-bar {
            width: 100%;
            background-color: #e0e0e0;
            border-radius: 5px;
            overflow: hidden;
            margin: 20px 0;
        }
        .progress-bar-fill {
            height: 30px;
            background-color: <?= ($percentage >= 50) ? '#4caf50' : '#f44336' ?>;
            width: <?= $percentage ?>%;
            text-align: center;
            line-height: 30px;
            color: white;
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .highlight {
            background-color: #ffeb3b;
        }
        .back-link {
            display: block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
        }
        .back-link:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Exam Results</h1>

        <?php if (isset($exam) && isset($result)): ?>
            <div class="result-section">
                <h2><?= $exam['exam_name'] ?></h2>
                <p>Your Score: <strong><?= $score ?> out of <?= $total_questions ?></strong></p>
                <p>Percentage: <strong><?= $percentage ?>%</strong></p>

                <div class="progress-bar">
                    <div class="progress-bar-fill">
                        <?= $percentage ?>%
                    </div>
                </div>
            </div>

            <div class="leaderboard-section">
                <h2>Leaderboard</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Rank</th>
                            <th>Username</th>
                            <th>Score</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($leaderboard as $index => $entry): ?>
                            <tr >
                                <td><?= $index + 1 ?></td>
                                <td><?= $entry['username'] ?></td>
                                <td><?= $entry['score'] ?>/<?= $total_questions ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p>No results found.</p>
        <?php endif; ?>

        <a href="dashboard.php" class="back-link">Back to Dashboard</a>
    </div>
</body>
</html>