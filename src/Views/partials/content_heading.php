<div class="content-heading">
    <h1><?= $title ?></h1>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert error">
            <span class="close-btn" onclick="this.parentElement.style.display='none';">&times;</span>
            <?= $_SESSION['error'] ?>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['errors'])): ?>
        <?php foreach($_SESSION['errors'] as $error => $errorMessage): ?>
            <div class="alert error">
                <span class="close-btn" onclick="this.parentElement.style.display='none';">&times;</span>
                <?= $errorMessage ?>
            </div>
        <?php endforeach; ?>
        <?php unset($_SESSION['errors']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert success">
            <span class="close-btn" onclick="this.parentElement.style.display='none';">&times;</span>
            <?= $_SESSION['success'] ?>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>
    
</div>

