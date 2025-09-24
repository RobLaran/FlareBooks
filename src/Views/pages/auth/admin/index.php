<form action="<?= routeTo("/auth/login/admin") ?>" method="POST">
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
    <div class="confirm-password-input-container input-container">
        <label for="confirm-password-input">Confirm Password:</label>
        <input type="password" name="confirm-password" id="confirm-password-input" >
    </div>
    <div class="form-buttons-container">
        <button class="button default">Login as Admin</button>
    </div>
    <div class="form-links-container">
        <span>
            <a href="<?= routeTo('/auth/login') ?>" style="color: #ff6600">
                Login as User? <strong>Click here.</strong>
            </a>
        </span>
    </div>
</form>