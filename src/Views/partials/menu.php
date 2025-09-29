<aside id="menu">
    <div class="web-heading">
        <img src="<?= LOGO ?>" alt="" id="web-logo">
        <span id="web-title"><?= BRAND ?></span>
    </div>
    <nav>
        <ul>
            

            <?php if(isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'admin'): ?>
            <!-- 
                ADMIN NAVS
                    - dashboard
                    - books 
                    - genres
                    - users
                    - reports
                    - profile
            -->
                <li class="<?= isURL('/admin/dashboard') ? 'active' : '' ?>">
                    <a href="<?= routeTo('/admin/dashboard') ?>">Dashboard</a>
                </li>
                <li class="<?= isURL('/admin/books') ? 'active' : '' ?>">
                    <a href="<?= routeTo('/admin/books') ?>">Books</a>
                </li>
                <li class="<?= isURL('/admin/genres') ? 'active' : '' ?>">
                    <a href="<?= routeTo('/admin/genres') ?>">Genres</a>
                </li>
                <li class="<?= isURL('/admin/staffs') ? 'active' : '' ?>">
                    <a href="<?= routeTo('/admin/staffs') ?>">Staffs</a>
                </li>
                <li class="<?= isURL('/admin/reports') ? 'active' : '' ?>">
                    <a href="<?= routeTo('/admin/reports') ?>">Reports</a>
                </li>
                <li class="<?= isURL('/admin/profile') ? 'active' : '' ?>">
                    <a href="<?= routeTo('/admin/profile') ?>">Profile</a>
                </li>
            <?php elseif(isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'user'): ?>
            <!-- 
                USER NAVS
                    - dashboard
                    - books
                    - borrowers
                    - borrowed books
                    - returns
                    - profile
            -->
                <li class="<?= isURL('/dashboard') ? 'active' : '' ?>">
                    <a href="<?= routeTo('/dashboard') ?>">Dashboard</a>
                </li>
                <li class="<?= isURL('/books') ? 'active' : '' ?>">
                    <a href="<?= routeTo('/books') ?>">Books</a>
                </li>
                <li class="<?= isURL('/borrowers') ? 'active' : '' ?>">
                    <a href="<?= routeTo('/borrowers') ?>">Borrowers</a>
                </li>
                <li class="<?= isURL('/borrowed-books') ? 'active' : '' ?>">
                    <a href="<?= routeTo('/borrowed-books') ?>">Borrow Book</a>
                </li>
                <li class="<?= isURL('/returns') ? 'active' : '' ?>">
                    <a href="<?= routeTo('/returns') ?>">Return Book</a>
                </li>
                <li class="<?= isURL('/profile') ? 'active' : '' ?>">
                    <a href="<?= routeTo('/profile' . '/' . $_SESSION['user']['id']) ?>">Profile</a>
                </li>
            <?php endif; ?> 
        </ul>
    </nav>
</aside>