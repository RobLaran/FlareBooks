<?php require 'src/Views/partials/head.php'; ?>

    <div class="auth-body">
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