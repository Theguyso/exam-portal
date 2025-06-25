<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../login.php");
    exit();
}

include '../../includes/db.php';
include '../partials/sidebar.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $exam_name = trim($_POST['exam_name']);
    $description = trim($_POST['description']);
    $duration = (int)$_POST['duration'];
    $passing_score = (int)$_POST['passing_score'];
    $created_by = $_SESSION['user_id'];
    
    try {
        $stmt = $conn->prepare("INSERT INTO exams 
                              (exam_name, description, duration_minutes, passing_score, created_by) 
                              VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$exam_name, $description, $duration, $passing_score, $created_by]);
        
        $exam_id = $conn->lastInsertId();
        $_SESSION['message'] = "Exam created successfully!";
        header("Location: ../questions/add.php?exam_id=$exam_id");
        exit();
    } catch (PDOException $e) {
        $_SESSION['error'] = "Error creating exam: " . $e->getMessage();
    }
}
?>

<div class="main-content">
    <div class="header">
        <h1>Create New Exam</h1>
        <a href="manage.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Exams
        </a>
    </div>

    <!-- Status Messages -->
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <?= $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body">
            <form method="POST" novalidate>
                <div class="form-group">
                    <label for="exam_name">Exam Name*</label>
                    <input type="text" class="form-control" id="exam_name" name="exam_name" required>
                </div>
                
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="duration">Duration (minutes)*</label>
                            <input type="number" class="form-control" id="duration" name="duration" min="1" value="30" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="passing_score">Passing Score (%)*</label>
                            <input type="number" class="form-control" id="passing_score" name="passing_score" min="1" max="100" value="60" required>
                        </div>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Create Exam
                </button>
            </form>
        </div>
    </div>
</div>

<style>
    /* Container spacing to fit sidebar */
    .main-content {
        margin-left: 250px; /* match sidebar width */
        padding: 20px 30px;
        min-height: 100vh;
        background: #f4f6f9;
        transition: margin-left 0.3s ease;
    }

    /* Adjust when sidebar collapsed */
    .sidebar.collapsed ~ .main-content {
        margin-left: 70px;
    }

    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        flex-wrap: wrap;
    }
    
    h1 {
        font-size: 1.8rem;
        color: #2c3e50;
        margin: 0;
    }
    
    .form-group {
        margin-bottom: 20px;
    }
    
    label {
        font-weight: 600;
        color: #34495e;
        display: block;
        margin-bottom: 8px;
    }

    .form-control {
        width: 100%;
        padding: 10px 12px;
        font-size: 16px;
        border: 1px solid #ced4da;
        border-radius: 4px;
        transition: border-color 0.2s ease;
    }
    
    .form-control:focus {
        border-color: #4361ee;
        outline: none;
        box-shadow: 0 0 5px rgba(67, 97, 238, 0.4);
    }
    
    textarea.form-control {
        min-height: 100px;
        resize: vertical;
    }
    
    .row {
        display: flex;
        flex-wrap: wrap;
        margin: 0 -15px;
    }
    
    .col-md-6 {
        flex: 0 0 50%;
        max-width: 50%;
        padding: 0 15px;
        box-sizing: border-box;
    }
    
    .card {
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        padding: 25px;
        max-width: 800px;
        margin: 0 auto 30px auto;
    }

    /* Button styles consistent with sidebar buttons */
    .btn {
        display: inline-flex;
        align-items: center;
        padding: 10px 16px;
        border-radius: 5px;
        font-size: 15px;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
        border: none;
        transition: background-color 0.25s ease;
        color: white;
    }
    
    .btn-primary {
        background-color: #fe0000;
    }
    .btn-primary:hover {
        background-color: #3651c7;
    }
    
    .btn-secondary {
        background-color:#fe0000;
        color: white;
    }
    .btn-secondary:hover {
                background-color:#fe0000;

    }
    
    .btn i {
        margin-right: 8px;
        font-size: 1.1rem;
    }

    /* Alert styling */
    .alert {
        max-width: 800px;
        margin: 0 auto 20px auto;
        padding: 15px 20px;
        border-radius: 6px;
        font-weight: 600;
    }
    
    .alert-danger {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }


</style>
