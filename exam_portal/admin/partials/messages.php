<?php if (isset($_SESSION['message'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= htmlspecialchars($_SESSION['message']); ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        <?php unset($_SESSION['message']); ?>
    </div>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= htmlspecialchars($_SESSION['error']); ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        <?php unset($_SESSION['error']); ?>
    </div>
<?php endif; ?>

<?php if (isset($_SESSION['warning'])): ?>
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <?= htmlspecialchars($_SESSION['warning']); ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        <?php unset($_SESSION['warning']); ?>
    </div>
<?php endif; ?>

<style>
    .alert {
        position: relative;
        padding: 1rem 1.5rem;
        margin-bottom: 1.5rem;
        border: 1px solid transparent;
        border-radius: 0.375rem;
        font-size: 0.9375rem;
    }
    
    .alert-success {
        color: #0f5132;
        background-color: #d1e7dd;
        border-color: #badbcc;
    }
    
    .alert-danger {
        color: #842029;
        background-color: #f8d7da;
        border-color: #f5c2c7;
    }
    
    .alert-warning {
        color: #664d03;
        background-color: #fff3cd;
        border-color: #ffecb5;
    }
    
    .alert-dismissible {
        padding-right: 3.5rem;
    }
    
    .close {
        position: absolute;
        top: 0;
        right: 0;
        padding: 1rem 1.5rem;
        color: inherit;
        background: transparent;
        border: 0;
        font-size: 1.5rem;
        line-height: 1;
        opacity: 0.5;
        cursor: pointer;
    }
    
    .close:hover {
        opacity: 1;
    }
</style>

<script>
    // Auto-dismiss alerts after 5 seconds
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                const fadeEffect = setInterval(() => {
                    if (!alert.style.opacity) {
                        alert.style.opacity = 1;
                    }
                    if (alert.style.opacity > 0) {
                        alert.style.opacity -= 0.1;
                    } else {
                        clearInterval(fadeEffect);
                        alert.remove();
                    }
                }, 50);
            });
        }, 5000);
        
        // Manual dismiss
        document.querySelectorAll('[data-dismiss="alert"]').forEach(button => {
            button.addEventListener('click', function() {
                this.closest('.alert').remove();
            });
        });
    });
</script>