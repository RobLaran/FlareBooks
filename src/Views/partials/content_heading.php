<?php 
use Helpers\SessionHelper
?>

<div class="content-heading">
    <h1><?= $title ?></h1>

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
    
</div>

