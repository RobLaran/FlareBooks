<?php 
use Helpers\SessionHelper
?>

<?php require 'src/Views/partials/head.php'; ?>

    <div class="auth-body">
        <?php if (SessionHelper::hasKey(key: 'error')): ?>
            <div class="alert error">
                <span class="close-btn" onclick="this.parentElement.style.display='none';">&times;</span>
                <?= SessionHelper::getFlash('error') ?>
            </div>
        <?php endif; ?>

        <?php if (SessionHelper::hasKey('errors')): ?>
            <?php foreach(SessionHelper::getFlash('errors') as $error => $errorMessage): ?>
                <div class="alert error">
                    <span class="close-btn" onclick="this.parentElement.style.display='none';">&times;</span>
                    <?= $errorMessage ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

        <?php if (SessionHelper::hasKey(key: 'success')): ?>
            <div class="alert success">
                <span class="close-btn" onclick="this.parentElement.style.display='none';">&times;</span>
                <?= SessionHelper::getFlash('success') ?>
            </div>
        <?php endif; ?>

        <div class="auth-form-container">
            <header class="form-header">
                <a href="<?= routeTo('/') ?>">
                    <img src="<?= LOGO ?>" alt="Form Logo">
                    <span><?= BRAND ?></span>
                </a>
            </header>

            <main class="form-main">
                <?= $content ?: '' ?>
            </main>
        </div>
    </div>
    
</body>
</html>