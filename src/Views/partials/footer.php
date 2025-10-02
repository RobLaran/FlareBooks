
            <footer>
                <p>&copy; <?= date("Y") ?> <?= BRAND ?>. All rights reserved.</p>
            </footer>
        </div>
    </body>
</html>

<script>
    window.appConfig = {
        icons: {
            question: "<?= getFile('public/img/question.png') ?>",
            warning: "<?= getFile('public/img/warning.png') ?>"
        }
    };
</script>
