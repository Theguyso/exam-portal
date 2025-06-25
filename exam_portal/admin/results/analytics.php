<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../login.php");
    exit();
}

require_once '../../includes/db.php';
include '../partials/sidebar.php';

// Get exam ID from query string
$exam_id = $_GET['exam_id'] ?? null;

// Fetch all exams for dropdown
$exams = $conn->query("SELECT id, exam_name FROM exams ORDER BY exam_name")->fetchAll();

// Fetch results data based on selected exam
if ($exam_id) {
    $exam = $conn->query("SELECT exam_name FROM exams WHERE id = $exam_id")->fetch();
    
    $results = $conn->query("
        SELECT r.*, u.username 
        FROM results r
        JOIN users u ON r.user_id = u.id
        WHERE r.exam_id = $exam_id
        ORDER BY r.score DESC
    ")->fetchAll();

    // Calculate statistics
    $stats = $conn->query("
        SELECT 
            COUNT(*) AS total_attempts,
            AVG(score) AS average_score,
            MAX(score) AS highest_score,
            MIN(score) AS lowest_score,
            SUM(CASE WHEN score >= (SELECT passing_score FROM exams WHERE id = $exam_id) THEN 1 ELSE 0 END) AS passed,
            SUM(CASE WHEN score < (SELECT passing_score FROM exams WHERE id = $exam_id) THEN 1 ELSE 0 END) AS failed
        FROM results
        WHERE exam_id = $exam_id
    ")->fetch();
    
    // Score distribution for chart
    $distribution = $conn->query("
        SELECT 
            FLOOR(score/10)*10 AS score_range,
            COUNT(*) AS count
        FROM results
        WHERE exam_id = $exam_id
        GROUP BY FLOOR(score/10)
        ORDER BY score_range
    ")->fetchAll();
}
?>

<div class="main-content">
    <div class="header">
        <h1>Results Analytics</h1>
        <form method="get" class="exam-filter">
            <select name="exam_id" onchange="this.form.submit()" class="form-control">
                <option value="">Select Exam</option>
                <?php foreach ($exams as $e): ?>
                <option value="<?= $e['id'] ?>" <?= $exam_id == $e['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($e['exam_name']) ?>
                </option>
                <?php endforeach; ?>
            </select>
        </form>
    </div>

    <?php if ($exam_id): ?>
    <!-- Exam Summary -->
    <div class="section">
        <h2><?= htmlspecialchars($exam['exam_name']) ?> Performance</h2>
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-value"><?= $stats['total_attempts'] ?></div>
                <div class="stat-label">Total Attempts</div>
            </div>
            <div class="stat-card">
                <div class="stat-value"><?= round($stats['average_score'], 1) ?></div>
                <div class="stat-label">Average Score</div>
            </div>
            <div class="stat-card">
                <div class="stat-value"><?= $stats['highest_score'] ?></div>
                <div class="stat-label">Highest Score</div>
            </div>
            <div class="stat-card">
                <div class="stat-value"><?= round(($stats['passed']/$stats['total_attempts'])*100, 1) ?>%</div>
                <div class="stat-label">Pass Rate</div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3>Score Distribution</h3>
                </div>
                <div class="card-body">
                    <canvas id="distributionChart" height="250"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3>Pass/Fail Ratio</h3>
                </div>
                <div class="card-body">
                    <canvas id="passChart" height="250"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Results Table -->
    <div class="card">
        <div class="card-header">
            <h3>Detailed Results</h3>
            <!-- <a href="export.php?exam_id=<?= $exam_id ?>" class="btn btn-sm btn-secondary">
                <i class="fas fa-download"></i> Export
            </a> -->
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Rank</th>
                            <th>Student</th>
                            <th>Score</th>
                            <th>Percentage</th>
                            <th>Status</th>
                            <th>Date Taken</th>
                        </tr>
                    </thead>
                    <tbody>
<?php
$passing_score = $conn->query("SELECT passing_score FROM exams WHERE id = $exam_id")->fetchColumn();
$total_questions = $conn->query("SELECT COUNT(*) FROM questions WHERE exam_id = $exam_id")->fetchColumn();

foreach ($results as $index => $result): 
    $percentage = $total_questions > 0 ? round(($result['score'] / $total_questions) * 100, 1) : 0;
    $passed = isset($passing_score) ? $percentage >= $passing_score : false;
?>
    <tr>
        <td><?= $index + 1 ?></td>
        <td><?= htmlspecialchars($result['username']) ?></td>
        <td><?= $result['score'] ?>/<?= $total_questions ?></td>
        <td><?= $percentage ?>%</td>
        <td>
            <span class="badge <?= $passed ? 'badge-success' : 'badge-danger' ?>">
                <?= $passed ? 'Passed' : 'Failed' ?>
            </span>
        </td>
        <td><?= date('M d, Y', strtotime($result['submitted_at'])) ?></td>
    </tr>
<?php endforeach; ?>
</tbody>

                </table>
            </div>
        </div>
    </div>
    <?php else: ?>
    <div class="alert alert-info">
        Please select an exam to view analytics
    </div>
    <?php endif; ?>
</div>

<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
    .main-content {
    margin-left: 240px; /* Adjust based on your sidebar width */
    padding: 30px;
    background-color: #f8f9fa;
    min-height: 100vh;
    transition: margin-left 0.3s ease;
}

@media (max-width: 768px) {
    .main-content {
        margin-left: 0;
        padding: 15px;
    }
}

.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
}

.exam-filter select {
    width: 250px;
}

    .exam-filter {
        width: 300px;
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin: 20px 0;
    }
    
    .stat-card {
        background: white;
        border-radius: 8px;
        padding: 15px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        text-align: center;
    }
    
    .stat-value {
        font-size: 24px;
        font-weight: bold;
        color: #fe0000;
    }
    
    .stat-label {
        color: #6c757d;
        font-size: 14px;
    }
    
    .row {
        display: flex;
        flex-wrap: wrap;
        margin: 0 -15px 20px;
    }
    
    .col-md-6 {
        flex: 0 0 50%;
        max-width: 50%;
        padding: 0 15px;
    }
    
    .badge {
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 12px;
    }
    
    .badge-success {
        background: #d4edda;
        color: #155724;
    }
    
    .badge-danger {
        background: #f8d7da;
        color: #721c24;
    }
    
    .section {
        margin-bottom: 30px;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        <?php if ($exam_id): ?>
        // Score Distribution Chart
        const distCtx = document.getElementById('distributionChart').getContext('2d');
        new Chart(distCtx, {
            type: 'bar',
            data: {
                labels: <?= json_encode(array_map(function($d) { return $d['score_range'] . '-' . ($d['score_range']+10); }, $distribution)) ?>,
                datasets: [{
                    label: 'Number of Students',
                    data: <?= json_encode(array_column($distribution, 'count')) ?>,
                    backgroundColor: '#3a0ca3'
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Number of Students'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Score Range'
                        }
                    }
                }
            }
        });

        // Pass/Fail Chart
        const passCtx = document.getElementById('passChart').getContext('2d');
        new Chart(passCtx, {
            type: 'doughnut',
            data: {
                labels: ['Passed', 'Failed'],
                datasets: [{
                    data: [<?= $stats['passed'] ?>, <?= $stats['failed'] ?>],
                    backgroundColor: ['#4caf50', '#f44336']
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
        <?php endif; ?>
    });
</script>

