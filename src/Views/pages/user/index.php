<section class="hero-section"
    style="background-image: url('<?= getFile("public/img/lib.jpg") ?>'); background-size: cover; background-position: center;">
    <div class="container">
        <h1>Manage Your Library Effortlessly</h1>
        <p>FlareBooks is a simple and efficient library management system that helps you organize books, track
            borrowers, and manage records all in one place.</p>
        <a href="<?= routeTo("/dashboard") ?>" class="btn-primary">Get Started</a>
    </div>
</section>

<section class="overview-section">
    <div class="container">
        <h1>What FlareBooks Offers</h1>
        <p
            style="max-width: 800px; margin: 20px auto 40px auto; font-size: 1.1rem; color: var(--secondary-text-light);">
            Designed for small libraries and institutions, FlareBooks keeps your records clear and organized.
            Manage books, borrowers, and borrow transactions with ease — all through a clean and user-friendly
            dashboard.
        </p>
        <div class="features-grid">
            <div class="feature-card">
                <div class="icon">
                    <img src="<?= getFile("public/img/book.png") ?>" alt="Book Icon">
                </div>
                <h3>Book Management</h3>
                <p>Add, edit, and categorize your books with just a few clicks.</p>
            </div>
            <div class="feature-card highlight">
                <div class="icon">
                    <img src="<?= getFile("public/img/users.png") ?>" alt="Book Icon">
                </div>
                <h3>User Accounts</h3>
                <p>Handle borrowers and administrators securely with login access.</p>
            </div>
            <div class="feature-card">
                <div class="icon">
                    <img src="<?= getFile("public/img/dashboard.png") ?>" alt="Book Icon">
                </div>
                <h3>Simple Dashboard</h3>
                <p>Monitor your collection and activities from a single view.</p>
            </div>
        </div>
        <a href="<?= routeTo("/features") ?>" class="btn-primary" style="margin-top: 60px;">View All Features</a>
    </div>
</section>