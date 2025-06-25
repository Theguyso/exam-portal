<?php
// Security check
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../login.php");
    exit();
}
?>
<html>
<!-- Sidebar Toggle Button -->
<button id="sidebarToggle" class="sidebar-toggle">☰</button>

<!-- Sidebar HTML -->
<div class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <h2>Exam Portal</h2>
        <p>Admin Dashboard</p>


    </div>
    
    <ul class="sidebar-menu">
        <!-- Dashboard -->
        <li class="<?= basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : '' ?>">
            <a href="http://localhost/exam_portal/admin/dashboard.php">
                <i class="icon">📊</i> Dashboard
            </a>
        </li>
        
        <!-- Exams -->
        <li class="menu-dropdown <?= str_contains($_SERVER['PHP_SELF'], 'exams/') ? 'open' : '' ?>">
            <a href="#">
                <i class="icon">📝</i> Exams
                <i class="dropdown-icon">▼</i>
            </a>
            <ul class="submenu">
                <li><a href="http://localhost/exam_portal/admin/exams/manage.php">Manage Exams</a></li>
                <li><a href="http://localhost/exam_portal/admin/exams/create.php">Create New</a></li>
            </ul>
        </li>
        
        <!-- Questions -->
        <li class="menu-dropdown <?= str_contains($_SERVER['PHP_SELF'], 'questions/') ? 'open' : '' ?>">
            <a href="#">
                <i class="icon">❓</i> Questions
                <i class="dropdown-icon">▼</i>
            </a>
            <ul class="submenu">
                <li><a href="http://localhost/exam_portal/admin/exams/questions/bank.php">Question Bank</a></li>
                <li><a href="http://localhost/exam_portal/admin/exams/questions/import.php">Bulk Import</a></li>
            </ul>
        </li>
        
        <!-- Users -->
        <li class="<?= basename($_SERVER['PHP_SELF']) == 'manage_users.php' ? 'active' : '' ?>">
            <a href="http://localhost/exam_portal/admin/users/manage.php">
                <i class="icon">👥</i> User Management
            </a>
        </li>
        
        <!-- Results -->
        <li class="menu-dropdown <?= str_contains($_SERVER['PHP_SELF'], 'results/') ? 'open' : '' ?>">
            <a href="#">
                <i class="icon">📈</i> Results
                <i class="dropdown-icon">▼</i>
            </a>
            <!-- <ul class="submenu">
                <li><a href="http://localhost/exam_portal/admin/results/analytics.php">Analytics</a></li>
            </ul> -->
        </li>
    </ul>
    
    <div class="sidebar-footer">
        <div class="user-profile">
            <span class="username"><?= htmlspecialchars($_SESSION['username']) ?></span>
            <span class="role">Admin</span>
        </div>
        <a href="http://localhost/exam_portal/logout.php" class="logout-btn">
            <i class="icon">🚪</i> Logout
        </a>
    </div>
</div>
</html>
<style>
    

.sidebar-toggle {
    position: fixed;
    top: 15px;
    left: 15px;
    z-index: 1000;
    background:#fe0000;
    color: white;
    border: none;
    font-size: 1.5rem;
    padding: 5px 10px;
    cursor: pointer;
    border-radius: 4px;
}

.sidebar.collapsed {
    width: 0px;
}

.sidebar.collapsed .sidebar-header,
.sidebar.collapsed .menu-dropdown > a > span,
.sidebar.collapsed .submenu,
.sidebar.collapsed .username,
.sidebar.collapsed .role {
    display: none;
}

.sidebar.collapsed .sidebar-menu li a {
    justify-content: center;
}
    
    
    /* Sidebar Styling */
    .sidebar {
        width: 250px;
        background:#fe0000;
        color: white;
        height: 100vh;
        position: fixed;
        overflow-y: auto;
        transition: all 0.3s;
    }
    
    .sidebar-header {
        padding: 20px;
        border-bottom: 1px solid rgba(255,255,255,0.1);
    }
    
    .sidebar-header h2 {
        margin: 0;
        font-size: 1.3rem;
    }
    
    .sidebar-header p {
        margin: 5px 0 0;
        font-size: 0.8rem;
        opacity: 0.8;
    }
    
    .sidebar-menu {
        list-style: none;
        padding: 15px 0;
    }
    
    .sidebar-menu li a {
        display: flex;
        align-items: center;
        padding: 12px 20px;
        color: #ecf0f1;
        text-decoration: none;
        transition: all 0.2s;
    }
    
    .sidebar-menu li a:hover {
        background: rgba(255,255,255,0.1);
    }
    
    .sidebar-menu .icon {
        margin-right: 10px;
        font-size: 1.1rem;
    }
    
    .sidebar-menu .dropdown-icon {
        margin-left: auto;
        font-size: 0.7rem;
    }
    
    .submenu {
        list-style: none;
        padding-left: 20px;
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s ease;
    }
    
    .menu-dropdown.open .submenu {
        max-height: 300px;
    }
    
    .submenu li a {
        padding: 8px 20px;
        font-size: 0.9rem;
    }
    
    .active {
        background:rgb(238, 67, 67);
    }
    
    .active a {
        font-weight: bold;
    }
    
    .sidebar-footer {
        position: absolute;
        bottom: 0;
        width: 100%;
        padding: 15px;
        border-top: 1px solid rgba(255,255,255,0.1);
    }
    
    .user-profile {
        margin-bottom: 15px;
    }
    
    .username {
        display: block;
        font-weight: bold;
    }
    
    .role {
        font-size: 0.8rem;
        opacity: 0.7;
    }
    
    .logout-btn {
        display: flex;
        align-items: center;
        color: #ecf0f1;
        text-decoration: none;
        padding: 8px 15px;
        border-radius: 4px;
        background: rgba(255,255,255,0.1);
        transition: all 0.2s;
    }
    
    .logout-btn:hover {
        background: rgba(255,255,255,0.2);
    } */
</style>

<script>
    // Simple dropdown functionality
    document.querySelectorAll('.menu-dropdown > a').forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            const parent = this.parentElement;
            parent.classList.toggle('open');
        });
    });

    // Toggle functionality
    const sidebar = document.getElementById('sidebar');
    const toggleBtn = document.getElementById('sidebarToggle');
    let isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
    
    // Set initial state
    if (isCollapsed) {
        sidebar.classList.add('collapsed');
    }
    
    // Toggle function
    function toggleSidebar() {
        sidebar.classList.toggle('collapsed');
        isCollapsed = !isCollapsed;
        localStorage.setItem('sidebarCollapsed', isCollapsed);
        
        // Update dropdown icons if needed
        document.querySelectorAll('.menu-dropdown').forEach(item => {
            if (isCollapsed) {
                item.classList.remove('open');
            }
        });
    }
    
    // Click event
    toggleBtn.addEventListener('click', toggleSidebar);
    
    // Auto-close submenus when collapsing
    sidebar.addEventListener('transitionend', function() {
        if (sidebar.classList.contains('collapsed')) {
            document.querySelectorAll('.menu-dropdown').forEach(item => {
                item.classList.remove('open');
            });
        }
    });
    
    // Close sidebar when clicking outside (mobile)
    document.addEventListener('click', function(e) {
        if (window.innerWidth <= 992 && 
            !sidebar.contains(e.target) && 
            e.target !== toggleBtn && 
            !sidebar.classList.contains('collapsed')) {
            toggleSidebar();
        }
    });
</script>