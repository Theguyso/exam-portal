<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../login.php");
    exit();
}

include '../../includes/db.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Exams</title>
    <link href="../../assets/css/admin.css" rel="stylesheet">
    <style>
        .admin-container {
            display: flex;
        }
        
.main-content {
            margin-left: 250px;
            padding: 30px;
            flex-grow: 1;
            background-color: #f4f6f8;
            min-height: 100vh;
            transition: margin-left 0.3s;
        }

        .sidebar.collapsed + .main-content {
            margin-left: 70px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 8px;
            overflow: hidden;
        }

        .table th, .table td {
            padding: 12px 15px;
            border-bottom: 1px solid #dee2e6;
            text-align: left;
        }

        .table th {
            background-color: #f1f3f5;
            font-weight: 600;
        }

        .actions {
            white-space: nowrap;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            padding: 6px 10px;
            border-radius: 4px;
            text-decoration: none;
            font-size: 14px;
            margin-right: 5px;
            transition: background 0.2s;
        }

        .btn i {
            margin-right: 5px;
        }

        .btn-primary {
            background-color: #4361ee;
            color: white;
        }

        .btn-info {
            background-color: #3a0ca3;
            color: white;
        }

        .btn-danger {
            background-color: #f72585;
            color: white;
        }

        .btn-secondary {
            background-color: #4cc9f0;
            color: white;
        }

        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
        }

        .card {
            background: white;
            border-radius: 8px;
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <?php include '../partials/sidebar.php'; ?>

        <div class="main-content">
            <div class="header">
                <h1>Manage Exams</h1>
                <a href="create.php" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Create New Exam
                </a>
            </div>

            <!-- Status Messages -->
            <?php if (isset($_SESSION['message'])): ?>
                <div class="alert alert-success">
                    <?= $_SESSION['message']; unset($_SESSION['message']); ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger">
                    <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <div class="card">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Exam Name</th>
                            <th>Questions</th>
                            <th>Created By</th>
                            <th>Date Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $exams = $conn->query("
                            SELECT e.*, u.username as creator, 
                            (SELECT COUNT(*) FROM questions WHERE exam_id = e.id) as question_count
                            FROM exams e
                            JOIN users u ON e.created_by = u.id
                            ORDER BY e.created_at DESC
                        ")->fetchAll(PDO::FETCH_ASSOC);

                        foreach ($exams as $exam): ?>
                            <tr>
                                <td><?= $exam['id'] ?></td>
                                <td><?= htmlspecialchars($exam['exam_name']) ?></td>
                                <td><?= $exam['question_count'] ?></td>
                                <td><?= htmlspecialchars($exam['creator']) ?></td>
                                <td><?= date('M d, Y', strtotime($exam['created_at'])) ?></td>
                                <td class="actions">
                                    <!-- <a href="edit.php?id=<?= $exam['id'] ?>" class="btn btn-sm btn-info">
                                        <i class="fas fa-edit"></i> Edit
                                    </a> -->
                                    <a href="?delete=<?= $exam['id'] ?>" 
                                       class="btn btn-sm btn-danger" 
                                       onclick="return confirm('Delete this exam and all its questions?')">
                                        <i class="fas fa-trash"></i> Delete
                                    </a>
                                    <a href="../exams/questions/bank.php?exam_id=<?= $exam['id'] ?>" 
                                       class="btn btn-sm btn-secondary">
                                        <i class="fas fa-question-circle"></i> Questions
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
