<aside id="menu">
    <div class="web-heading">
        <img src="<?= LOGO ?>" alt="" id="web-logo">
        <span id="web-title"><?= BRAND ?></span>
    </div>
    <nav>
        <ul>
            <li class="<?= isURL('/dashboard') ? 'active' : '' ?>">
                <a href="<?= routeTo('/dashboard') ?>">Dashboard</a>
            </li>
            <li class="<?= isURL('/books') ? 'active' : '' ?>">
                <a href="<?= routeTo('/books') ?>">Books</a>
            </li>
            <li class="<?= isURL('/genres') ? 'active' : '' ?>">
                <a href="<?= routeTo('/genres') ?>">Genres</a>
            </li>
            <li class="<?= isURL('/borrowers') ? 'active' : '' ?>">
                <a href="<?= routeTo('/borrowers') ?>">Borrowers</a>
            </li>
            <li class="<?= isURL('/borrowed-books') ? 'active' : '' ?>">
                <a href="<?= routeTo('/borrowed-books') ?>">Borrowed Books</a>
            </li>
            <li class="<?= isURL('/returns') ? 'active' : '' ?>">
                <a href="<?= routeTo('/returns') ?>">Returns</a>
            </li>
            <li class="<?= isURL('/overdue-books') ? 'active' : '' ?>">
                <a href="<?= routeTo('/overdue-books') ?>">Overdue Books</a>
            </li>
            <li class="<?= isURL('/reports') ? 'active' : '' ?>">
                <a href="<?= routeTo('/reports') ?>">Reports</a>
            </li>
        </ul>
    </nav>
</aside>