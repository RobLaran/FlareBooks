<form action="">
    <div class="email-input-container input-container">
        <label for="email-input">Email:</label>
        <input type="text" name="email" id="email-input" >
    </div>
    <div class="password-input-container input-container">
        <label for="password-input">Password:</label>
        <input type="password" name="password" id="password-input" >
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