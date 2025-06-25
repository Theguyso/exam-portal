<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../login.php");
    exit();
}

require_once '../../includes/db.php';
include '../partials/sidebar.php';

// Handle user actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_role'])) {
        $user_id = $_POST['user_id'];
        $new_role = $_POST['role'];
        
        $stmt = $conn->prepare("UPDATE users SET role = ? WHERE id = ?");
        if ($stmt->execute([$new_role, $user_id])) {
            $_SESSION['message'] = "User role updated successfully";
        } else {
            $_SESSION['error'] = "Failed to update user role";
        }
    }
    
    if (isset($_POST['reset_password'])) {
        $user_id = $_POST['user_id'];
        $temp_password = bin2hex(random_bytes(4)); // Generate temporary password
        $hashed_password = password_hash($temp_password, PASSWORD_BCRYPT);
        
        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        if ($stmt->execute([$hashed_password, $user_id])) {
            $_SESSION['message'] = "Password reset successful. Temporary password: $temp_password";
        } else {
            $_SESSION['error'] = "Failed to reset password";
        }
    }
    
    if (isset($_POST['delete_user'])) {
        $user_id = $_POST['user_id'];
        
        try {
            $conn->beginTransaction();
            
            // Delete user's results first
            $stmt = $conn->prepare("DELETE FROM results WHERE user_id = ?");
            $stmt->execute([$user_id]);
            
            // Then delete user
            $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
            $stmt->execute([$user_id]);
            
            $conn->commit();
            $_SESSION['message'] = "User deleted successfully";
        } catch (PDOException $e) {
            $conn->rollBack();
            $_SESSION['error'] = "Error deleting user: " . $e->getMessage();
        }
    }
    
    header("Location: manage.php");
    exit();
}

// Fetch all users
$users = $conn->query("
    SELECT id, username, email, role 
    FROM users 
    ORDER BY id DESC
")->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="main-content">
    <div class="header">
        <h1>Manage Users</h1>
        <div class="user-count">
            Total Users: <?= count($users) ?>
        </div>
    </div>

    <?php include '../partials/messages.php'; ?>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="user-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Joined</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                 <tbody>
                        <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?= $user['id'] ?></td>
                            <td><?= htmlspecialchars($user['username']) ?></td>
                            <td><?= htmlspecialchars($user['email']) ?></td>
                            <td>
                                <form method="POST" class="role-form">
                                    <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                    <select name="role" class="role-select" onchange="this.form.submit()">
                                        <option value="student" <?= $user['role'] === 'student' ? 'selected' : '' ?>>Student</option>
                                        <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                                    </select>
                                    <input type="hidden" name="update_role" value="1">
                                </form>
                            </td>
                            <td>N/A</td> <!-- Removed date display -->
                            <td class="actions">
                                <form method="POST" class="action-form">
                                    <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                    <button type="submit" name="reset_password" class="btn-reset" 
                                            onclick="return confirm('Generate temporary password for this user?')">
                                        <i class="fas fa-key"></i> Reset Password
                                    </button>
                                    <button type="submit" name="delete_user" class="btn-delete"
                                            onclick="return confirm('Permanently delete this user and all their data?')">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    .user-table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .user-table th, .user-table td {
        padding: 12px 15px;
        border-bottom: 1px solid #e0e0e0;
    }
    
    .user-table th {
        background-color: #f5f7fa;
        font-weight: 600;
        text-align: left;
    }
    
    .role-select {
        padding: 6px 10px;
        border-radius: 4px;
        border: 1px solid #ced4da;
    }
    
    .actions {
        white-space: nowrap;
    }
    
    .action-form {
        display: flex;
        gap: 8px;
    }
    
    .btn-reset, .btn-delete {
        padding: 6px 12px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 13px;
        display: flex;
        align-items: center;
        gap: 5px;
    }
    
    .btn-reset {
        background-color: #ffc107;
        color: #212529;
    }
    
    .btn-reset:hover {
        background-color: #e0a800;
    }
    
    .btn-delete {
        background-color: #dc3545;
        color: white;
    }
    
    .btn-delete:hover {
        background-color: #c82333;
    }
    
    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }
    
    .user-count {
        background-color: #4361ee;
        color: white;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 14px;
    }
    
    .role-form {
        margin: 0;
    }
</style>

