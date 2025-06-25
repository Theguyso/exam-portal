<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../login.php");
    exit();
}

require_once '../../../includes/db.php';

// Get exam_id if passed
$exam_id = $_GET['exam_id'] ?? null;

// Fetch exams for dropdown
$exams = $conn->query("SELECT id, exam_name FROM exams ORDER BY exam_name")->fetchAll();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $exam_id = $_POST['exam_id'];
    $question_text = trim($_POST['question_text']);
    $option1 = trim($_POST['option1']);
    $option2 = trim($_POST['option2']);
    $option3 = trim($_POST['option3']);
    $option4 = trim($_POST['option4']);
    $correct_option = (int)$_POST['correct_option'];
    $marks = (int)($_POST['marks'] ?? 1); // Default to 1 if marks not set

    try {
        $stmt = $conn->prepare("INSERT INTO questions 
                              (exam_id, question_text, option1, option2, option3, option4, correct_option, marks) 
                              VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$exam_id, $question_text, $option1, $option2, $option3, $option4, $correct_option, $marks]);
        
        $_SESSION['message'] = "Question added successfully!";
        
        // Check if add_another exists before using it
        $redirect_url = isset($_POST['add_another']) ? "add.php?exam_id=$exam_id" : "bank.php?exam_id=$exam_id";
        header("Location: $redirect_url");
        exit();
    } catch (PDOException $e) {
        $_SESSION['error'] = "Error adding question: " . $e->getMessage();
    }
}

// Now include the header files AFTER potential header() calls
include '../partials/sidebar.php';
?>

<div class="main-content">
    <div class="header">
        <h1>Add New Question</h1>
        <a href="bank.php<?= $exam_id ? "?exam_id=$exam_id" : '' ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Question Bank
        </a>
    </div>

    <?php include '../../partials/messages.php'; ?>

    <div class="card">
        <div class="card-body">
            <form method="POST">
                <div class="form-group">
                    <label for="exam_id">Select Exam*</label>
                    <select name="exam_id" id="exam_id" class="form-control" required>
                        <option value="">-- Select Exam --</option>
                        <?php foreach ($exams as $exam): ?>
                        <option value="<?= $exam['id'] ?>" <?= $exam_id == $exam['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($exam['exam_name']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="question_text">Question Text*</label>
                    <textarea name="question_text" id="question_text" class="form-control" rows="4" required></textarea>
                </div>

                <div class="options-grid">
                    <div class="form-group">
                        <label for="option1">Option A*</label>
                        <input type="text" name="option1" id="option1" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="option2">Option B*</label>
                        <input type="text" name="option2" id="option2" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="option3">Option C*</label>
                        <input type="text" name="option3" id="option3" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="option4">Option D*</label>
                        <input type="text" name="option4" id="option4" class="form-control" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="correct_option">Correct Option*</label>
                            <select name="correct_option" id="correct_option" class="form-control" required>
                                <option value="">-- Select Correct Option --</option>
                                <option value="1">Option A</option>
                                <option value="2">Option B</option>
                                <option value="3">Option C</option>
                                <option value="4">Option D</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="marks">Marks*</label>
                            <input type="number" name="marks" id="marks" class="form-control" min="1" value="1" required>
                        </div>
                    </div>
                </div>

                <div class="form-footer">
                    <button type="submit" name="add_another" value="1" class="btn btn-primary">
                        <i class="fas fa-save"></i> Save and Add Another
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check"></i> Save and Return
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .options-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
        margin-bottom: 20px;
    }
    
    .form-group {
        margin-bottom: 15px;
    }
    
    .form-control {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 16px;
    }
    
    textarea.form-control {
        min-height: 100px;
    }
    
    .row {
        display: flex;
        flex-wrap: wrap;
        margin: 0 -10px;
    }
    
    .col-md-6 {
        flex: 0 0 50%;
        max-width: 50%;
        padding: 0 10px;
    }
    
    .form-footer {
        display: flex;
        gap: 10px;
        margin-top: 20px;
    }
    
    .btn {
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 16px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    
    .btn-primary {
        background-color: #4361ee;
        color: white;
    }
    
    .btn-success {
        background-color: #4caf50;
        color: white;
    }
    
    .btn-secondary {
        background-color: #6c757d;
        color: white;
    }
</style>

<script>
    // Auto-focus first field
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('question_text').focus();
    });
</script>

