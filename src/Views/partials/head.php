<?php 
use Helpers\SessionHelper
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="<?= LOGO ?>" type="image/x-icon">
    <link rel="stylesheet" type="text/css" href="<?= getFile("public/css/styles.css") ?>">
    <link rel="stylesheet" type="text/css" href="<?= getFile("public/css/table.css") ?>">
    <link rel="stylesheet" type="text/css" href="<?= getFile("public/css/book.css") ?>">
    <link rel="stylesheet" type="text/css" href="<?= getFile("public/css/borrowers.css") ?>">
    <link rel="stylesheet" type="text/css" href="<?= getFile("public/css/borrow-book.css") ?>">
    <link rel="stylesheet" type="text/css" href="<?= getFile("public/css/auth.css") ?>">
    <link rel="stylesheet" type="text/css" href="<?= getFile("public/css/alert.css") ?>">
    <link rel="stylesheet" type="text/css" href="<?= getFile("public/css/returns.css") ?>">
    <link rel="stylesheet" type="text/css" href="<?= getFile("public/css/user-dashboard.css") ?>">
    <link rel="stylesheet" type="text/css" href="<?= getFile("public/css/user-profile.css") ?>">
    <script src="https://kit.fontawesome.com/3e9984b045.js" crossorigin="anonymous" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11" defer></script>
    <script src="<?= getFile("public/js/menuToggle.js") ?>" defer></script>
    <script src="<?= getFile("public/js/imagePreview.js") ?>" defer></script>
    <script src="<?= getFile("public/js/alert.js") ?>" defer></script>
    <script src="<?= getFile("public/js/reload.js") ?>" defer></script>
    <title><?= BRAND ?></title>
</head> 
<body>
    <?php if (SessionHelper::hasKey(key: 'error') || SessionHelper::hasKey(key: 'errors') || SessionHelper::hasKey(key: 'success')): ?>
        <div class="alert-container">
            <?php if (SessionHelper::hasKey(key: 'error')): ?>
                <div class="alert error">
                <span class="alert-message"><?= SessionHelper::getFlash('error') ?></span>
                <span class="close-btn" onclick="this.parentElement.style.display='none';">&times;</span>
                </div>
            <?php endif; ?>

            <?php if (SessionHelper::hasKey('errors')): ?>
                    <?php foreach(SessionHelper::getFlash('errors') as $error => $errorMessage): ?>
                        <div class="alert error">
                            <span class="alert-message"><?= $errorMessage ?></span>
                            <span class="close-btn" onclick="this.parentElement.style.display='none';">&times;</span>
                        </div>
                    <?php endforeach; ?>
            <?php endif; ?>

            <?php if (SessionHelper::hasKey(key: 'success')): ?>
                <div class="alert success">
                    <span class="alert-message"><?= SessionHelper::getFlash('success') ?></span>
                    <span class="close-btn" onclick="this.parentElement.style.display='none';">&times;</span>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>

 
