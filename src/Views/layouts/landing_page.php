<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FlareBooks - Home</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <!-- Link to your shared CSS file, or paste the CSS directly here -->
    <link rel="stylesheet" href="<?= getFile("public/css/main.css") ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <header> 
        <div class="container">
            <a href="<?= routeTo("/home") ?>" class="logo">
                <img src="<?= LOGO ?>" alt="FlareBooks Logo">
                FlareBooks
            </a>
            <nav>
                <ul>
                    <li><a href="<?= routeTo("/home") ?>" class="<?= isURL('/home') || isURL('/') ? 'active' : '' ?>">Home</a></li>
                    <li><a href="<?= routeTo("/features") ?>" class="<?= isURL('/features') ? 'active' : '' ?>">Features</a></li>
                    <li><a href="<?= routeTo("/about") ?>" class="<?= isURL('/about') ? 'active' : '' ?>">About</a></li>
                    <li><a href="<?= routeTo("/contact") ?>" class="<?= isURL('/contact') ? 'active' : '' ?>">Contact</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <?= $content ?? '' ?>

    <footer>
        <div class="container">
            <p>&copy; 2025 FlareBooks. All Rights Reserved.</p>
        </div>
    </footer>
</body>
</html>