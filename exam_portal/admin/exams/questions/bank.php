<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../login.php");
    exit();
}

include '../../../includes/db.php';
include '../../partials/sidebar.php';

// Handle question deletion
if (isset($_GET['delete'])) {
    $question_id = $_GET['delete'];
    try {
        $stmt = $conn->prepare("DELETE FROM questions WHERE id = ?");
        $stmt->execute([$question_id]);
        $_SESSION['message'] = "Question deleted successfully";
    } catch (PDOException $e) {
        $_SESSION['error'] = "Error deleting question: " . $e->getMessage();
    }
    header("Location: bank.php" . (isset($_GET['exam_id']) ? '?exam_id=' . $_GET['exam_id'] : ''));
    exit();
}

// Fetch questions (filter by exam if specified)
$where = isset($_GET['exam_id']) ? " WHERE exam_id = " . intval($_GET['exam_id']) : "";
$questions = $conn->query("
    SELECT q.*, e.exam_name 
    FROM questions q
    JOIN exams e ON q.exam_id = e.id
    $where
    ORDER BY q.id DESC
")->fetchAll(PDO::FETCH_ASSOC);

// Fetch all exams for filter dropdown
$exams = $conn->query("SELECT id, exam_name FROM exams ORDER BY exam_name")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Question Bank</title>
    <link rel="stylesheet" href="../../../assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .main-content {
            margin-left: 250px;
            padding: 30px;
            background: #f4f6f9;
            min-height: 100vh;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .header h1 {
            font-size: 28px;
            font-weight: 600;
        }

        .actions a {
            margin-left: 10px;
        }

        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
            color: #fff;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
        }

        .btn i {
            margin-right: 6px;
        }

        .btn-primary {
            background-color:#fe0000;
        }

        .btn-secondary {
            background-color: #6c757d;
        }

        .btn-sm {
            padding: 5px 10px;
            font-size: 13px;
        }

        .btn-info {
            background-color: #17a2b8;
        }

        .btn-danger {
            background-color: #dc3545;
        }

        .card {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            margin-bottom: 25px;
            overflow: hidden;
        }

        .card-body {
            padding: 20px;
        }

        .filter-card {
            max-width: 400px;
        }

        .form-group label {
            font-weight: 500;
            margin-bottom: 5px;
            display: block;
        }

        .form-control {
            width: 100%;
            padding: 8px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th, .table td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            vertical-align: top;
            text-align: left;
        }

        .badge {
            display: inline-block;
            background-color: #28a745;
            color: white;
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 14px;
        }

        .table-responsive {
            overflow-x: auto;
        }

        ol {
            margin: 0;
            padding-left: 20px;
        }

        .actions .btn {
            margin: 2px;
        }
    </style>
</head>
<body>

<div class="main-content">
    <div class="header">
        <h1>Question Bank</h1>
        <div class="actions">
            <a href="import.php" class="btn btn-secondary">
                <i class="fas fa-file-import"></i> Bulk Import
            </a>
            <a href="add.php<?= isset($_GET['exam_id']) ? '?exam_id=' . $_GET['exam_id'] : '' ?>" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add Question
            </a>
        </div>
    </div>

    <!-- Status Messages -->
    <?php include '../../partials/messages.php'; ?>

    <!-- Exam Filter -->
    <div class="card filter-card">
        <div class="card-body">
            <form method="get">
                <div class="form-group">
                    <label for="exam_filter">Filter by Exam:</label>
                    <select id="exam_filter" name="exam_id" class="form-control" onchange="this.form.submit()">
                        <option value="">All Exams</option>
                        <?php foreach ($exams as $exam): ?>
                        <option value="<?= $exam['id'] ?>" <?= isset($_GET['exam_id']) && $_GET['exam_id'] == $exam['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($exam['exam_name']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </form>
        </div>
    </div>

    <!-- Questions Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Question</th>
                            <th>Exam</th>
                            <th>Options</th>
                            <th>Correct</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($questions as $q): ?>
                        <tr>
                            <td><?= $q['id'] ?></td>
                            <td><?= htmlspecialchars($q['question_text']) ?></td>
                            <td><?= htmlspecialchars($q['exam_name']) ?></td>
                            <td>
                                <ol type="A">
                                    <li><?= htmlspecialchars($q['option1']) ?></li>
                                    <li><?= htmlspecialchars($q['option2']) ?></li>
                                    <li><?= htmlspecialchars($q['option3']) ?></li>
                                    <li><?= htmlspecialchars($q['option4']) ?></li>
                                </ol>
                            </td>
                            <td>
                                <span class="badge"><?= chr(64 + $q['correct_option']) ?></span>
                            </td>
                            <td class="actions">
                                <!-- <a href="edit.php?id=<?= $q['id'] ?>" class="btn btn-sm btn-info">
                                    <i class="fas fa-edit"></i>
                                </a> -->
                                <a href="?delete=<?= $q['id'] ?><?= isset($_GET['exam_id']) ? '&exam_id=' . $_GET['exam_id'] : '' ?>" 
                                   class="btn btn-sm btn-danger" onclick="return confirm('Delete this question?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

</body>
</html>
