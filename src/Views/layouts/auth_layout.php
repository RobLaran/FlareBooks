<?php require 'src/Views/partials/head.php'; ?>

    <div class="auth-body">
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert error">
                <span class="close-btn" onclick="this.parentElement.style.display='none';">&times;</span>
                <?= $_SESSION['error'] ?>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert success">
                <span class="close-btn" onclick="this.parentElement.style.display='none';">&times;</span>
                <?= $_SESSION['success'] ?>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <div class="auth-form-container">
            <header class="form-header">
                <img src="<?= LOGO ?>" alt="Form Logo">
                <span><?= BRAND ?></span>
            </header>

            <main class="form-main">
                <?= $content ?: '' ?>
            </main>
        </div>
    </div>
    
</body>
</html>