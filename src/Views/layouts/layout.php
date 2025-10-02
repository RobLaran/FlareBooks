<?php require 'src/Views/partials/head.php'; ?>
<?php require 'src/Views/partials/header.php'; ?>
<?php require 'src/Views/partials/menu.php'; ?>

     <main id="content">
         <?php require 'src/Views/partials/content_heading.php'; ?>

        <!-- <?= var_dump($_SERVER) ?> -->
        <?= $content ?? '' ?>
     </main>

<?php require 'src/Views/partials/footer.php'; ?>
<?php require 'src/Views/partials/alert.php'; ?>




