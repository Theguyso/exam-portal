<?php
// Admin auth check
session_start();
if ($_SESSION['role'] !== 'admin') die("Access denied.");

include '../includes/db.php';

// Fetch stats
$exams_count = $conn->query("SELECT COUNT(*) FROM exams")->fetchColumn();
$questions_count = $conn->query("SELECT COUNT(*) FROM questions")->fetchColumn();
$users_count = $conn->query("SELECT COUNT(*) FROM users")->fetchColumn();
$recent_results = $conn->query("SELECT u.username, r.score, e.exam_name 
                               FROM results r
                               JOIN users u ON r.user_id = u.id
                               JOIN exams e ON r.exam_id = e.id
                               ORDER BY r.submitted_at DESC LIMIT 5")->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
</head>
<style>
    /* Main content alignment with sidebar */
.admin-container {
    display: flex;
}

.sidebar {
    /* This is already styled elsewhere */
}

.content {
    flex: 1;
    margin-left: 250px;
    padding: 30px;
    background: #f4f6f9;
    min-height: 100vh;
    transition: margin-left 0.3s ease;
}

/* When sidebar is collapsed */
.sidebar.collapsed + .content {
    margin-left: 70px;
}

/* Page Heading */
.content h1 {
    font-size: 2rem;
    margin-bottom: 20px;
}

/* Stats Grid */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 40px;
}

.stat-card {
    background: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgb(238, 172, 172);
    text-align: center;
    transition: transform 0.2s;
}

.stat-card:hover {
    transform: translateY(-5px);
}

.stat-card h3 {
    margin: 0;
    font-size: 1.1rem;
    color: #333;
}

.stat-card p {
    font-size: 1.8rem;
    font-weight: bold;
    color: #rgb(67, 238, 110);;
    margin-top: 10px;
}

/* Recent Results Table */
.content h2 {
    font-size: 1.5rem;
    margin-bottom: 15px;
}

.recent-table {
    width: 100%;
    border-collapse: collapse;
    background: white;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgb(238, 67, 67);
}

.recent-table thead {
    background: rgb(238, 67, 67);
    color: white;
}

.recent-table th, .recent-table td {
    padding: 12px 15px;
    text-align: left;
}

.recent-table tbody tr:nth-child(even) {
    background: rgb(238, 67, 67);
}

.recent-table tbody tr:hover {
    background: #rgb(67, 238, 110);;
}

</style>
<body>
    <div class="admin-container">
        <!-- Sidebar -->
        <?php include 'partials/sidebar.php'; ?>

        <!-- Main Content -->
        <div class="content">
            <h1>Dashboard Overview</h1>
            
            <!-- Stats Cards -->
            <div class="stats-grid">
                <div class="stat-card">
                    <h3>Exams</h3>
                    <p><?= $exams_count ?></p>
                </div>
                <div class="stat-card">
                    <h3>Questions</h3>
                    <p><?= $questions_count ?></p>
                </div>
                <div class="stat-card">
                    <h3>Users</h3>
                    <p><?= $users_count ?></p>
                </div>
            </div>

            <!-- Recent Activity Table -->
            <h2>Recent Results</h2>
            <table class="recent-table">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Exam</th>
                        <th>Score</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recent_results as $result): ?>
                    <tr>
                        <td><?= htmlspecialchars($result['username']) ?></td>
                        <td><?= htmlspecialchars($result['exam_name']) ?></td>
                        <td><?= $result['score'] ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>