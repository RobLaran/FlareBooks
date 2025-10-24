<div class="auth-body">
    <div class="auth-form-container">
        <header class="form-header">
            <a href="<?= routeTo('/') ?>">
                <img src="<?= LOGO ?>" alt="Form Logo">
                <span><?= BRAND ?></span>
            </a>
        </header>

        <main class="form-main">
            <?php if(!isset($_SESSION['user'])): ?>
                <form action="<?= routeTo("/auth/login") ?>" method="POST">
                    <div class="username-input-container input-container">
                        <label for="username-input">Username:</label>
                        <input type="text" name="username" id="username-input" >
                    </div>
                    <div class="email-input-container input-container">
                        <label for="email-input">Email:</label>
                        <input type="text" name="email" id="email-input" >
                    </div>
                    <div class="password-input-container input-container">
                        <label for="password-input">Password:</label>
                        <input type="password" name="password" id="password-input" >
                    </div>
                    <div class="form-buttons-container">
                        <input type="hidden" name="role" id="role-input">
                        <button class="button default" onclick="setRole('librarian')">Login as Librarian</button>
                        <button class="button default" onclick="setRole('admin')">Login as Admin</button>
                    </div>
                </form>
            <?php else: ?>
                <h2>You are already logged in.</h2>
                <?php if($_SESSION['user']['role'] == 'Admin'): ?>
                    <a href="<?= routeTo('/admin/dashboard') ?>"><p>Go to dashboard?</p></a>
                <?php elseif($_SESSION['user']['role'] == 'Librarian'): ?>
                    <a href="<?= routeTo('/dashboard') ?>"><p>Go to dashboard?</p></a>
                <?php endif; ?>
            <?php endif; ?>
            
        </main>
    </div>
</div>

<script>
    function setRole(role="") {
        const input =document.getElementById('role-input');

        input.value = role;
    }
</script>