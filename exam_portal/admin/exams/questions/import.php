<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../login.php");
    exit();
}

include '../../../includes/db.php';
include '../../partials/sidebar.php';

// Handle CSV import
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['csv_file'])) {
    $exam_id = $_POST['exam_id'];
    $file = $_FILES['csv_file'];

    if ($file['error'] !== UPLOAD_ERR_OK) {
        $_SESSION['error'] = "File upload error: " . $file['error'];
    } elseif (pathinfo($file['name'], PATHINFO_EXTENSION) !== 'csv') {
        $_SESSION['error'] = "Only CSV files are allowed";
    } else {
        try {
            $conn->beginTransaction();
            $inserted = 0;

            if (($handle = fopen($file['tmp_name'], "r")) !== FALSE) {
                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    if ($data[0] === 'question_text') continue;
                    if (count($data) < 6) continue;

                    $question = [
                        'exam_id' => $exam_id,
                        'question_text' => trim($data[0]),
                        'option1' => trim($data[1]),
                        'option2' => trim($data[2]),
                        'option3' => trim($data[3]),
                        'option4' => trim($data[4]),
                        'correct_option' => (int)$data[5]
                    ];

                    $stmt = $conn->prepare("INSERT INTO questions 
                        (exam_id, question_text, option1, option2, option3, option4, correct_option) 
                        VALUES (?, ?, ?, ?, ?, ?, ?)");
                    $stmt->execute(array_values($question));
                    $inserted++;
                }
                fclose($handle);
                $conn->commit();
                $_SESSION['message'] = "Successfully imported $inserted questions";
            }
        } catch (PDOException $e) {
            $conn->rollBack();
            $_SESSION['error'] = "Import failed: " . $e->getMessage();
        }
    }
    header("Location: import.php");
    exit();
}

$exams = $conn->query("SELECT id, exam_name FROM exams ORDER BY exam_name")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Import Questions</title>
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

        .btn-outline-primary {
            border: 1px solid #007bff;
            color: #007bff;
            background-color: transparent;
        }

        .btn-sm {
            padding: 6px 12px;
            font-size: 13px;
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
            margin-top: 15px;
        }

        .table th, .table td {
            padding: 12px;
            border: 1px solid #dee2e6;
        }

        code {
            background: #f1f1f1;
            padding: 2px 6px;
            border-radius: 4px;
        }

        .text-muted {
            color: #6c757d;
            font-size: 13px;
        }

        .row {
            display: flex;
            flex-wrap: wrap;
            gap: 30px;
        }

        .col-md-6 {
            flex: 0 0 48%;
        }
    </style>
</head>
<body>

<div class="main-content">
    <div class="header">
        <h1>Bulk Import Questions</h1>
        <a href="bank.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Question Bank
        </a>
    </div>

    <?php include '../../partials/messages.php'; ?>

    <div class="row">
        <!-- Import Form -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h3>Import from CSV</h3>
                    <form method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="exam_id">Select Exam*</label>
                            <select id="exam_id" name="exam_id" class="form-control" required>
                                <option value="">-- Select Exam --</option>
                                <?php foreach ($exams as $exam): ?>
                                <option value="<?= $exam['id'] ?>"><?= htmlspecialchars($exam['exam_name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="csv_file">CSV File*</label>
                            <input type="file" id="csv_file" name="csv_file" class="form-control" accept=".csv" required>
                            <small class="text-muted">
                                File must be in CSV format with columns: question_text, option1, option2, option3, option4, correct_option
                            </small>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-upload"></i> Import Questions
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- CSV Format Info -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h3>CSV Format</h3>
                    <p>Your CSV file should have exactly 6 columns in this order:</p>

                    <table class="table">
                        <thead>
                            <tr>
                                <th>question_text</th>
                                <th>option1</th>
                                <th>option2</th>
                                <th>option3</th>
                                <th>option4</th>
                                <th>correct_option</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>What is 2+2?</td>
                                <td>3</td>
                                <td>4</td>
                                <td>5</td>
                                <td>6</td>
                                <td>2</td>
                            </tr>
                        </tbody>
                    </table>

                    <h4>Notes:</h4>
                    <ul>
                        <li><code>correct_option</code> should be a number (1-4)</li>
                        <li>First row is treated as header and skipped</li>
                        <li>
                            <!-- <a href="sample_questions.csv" download class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-download"></i> Download Sample CSV
                            </a> -->
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>

