<div class="profile-page">
    <div class="profile-container">
        <form action="<?= routeTo("/profile/" . $info['id']) ?>" method="POST" class="profile-card">
            <div class="profile-avatar-section">
                <div class="profile-avatar">
                    <?php if(!empty($info['image'])): ?>
                       <?php if(isImageUrl($info['image'])): ?>
                            <img src="<?= $info['image'] ?? $old['image'] ?>" alt="Image URL Preview " id="image-preview" data-default="<?= getFile('/public/img/image-preview.jpg') ?>">
                        <?php else: ?>
                            <img src="<?= getFile('public/img/' . ($info['image']  ?? $old['image'] )) ?>" alt="Image Preview in File" id="image-preview" data-default="<?= getFile('/public/img/image-preview.jpg') ?>">
                        <?php endif; ?>
                    <?php else: ?>
                        <span class="initials">Avatar</span>
                    <?php endif; ?>
                </div>
                <div class="image-field input-container">
                    <div class="image-input-container">
                        <label for="image-input">Upload Image(File):</label>
                        <input type="file" name="image" id="image-input" accept="image/*">
                    </div>
                    <div class="image-url-container">
                        <label for="image-url">Or Image URL:</label>
                        <input type="text" name="image_url" id="image-url" placeholder="https://example.com/image.jpg">
                    </div>
                </div>
            </div>

            <div class="profile-info-form">
                <div class="info-row static">
                    <label>Role:</label>
                    <p id="staffRole"><?= $info['role'] ?></p>
                </div>
                <br>
                <div class="info-row input-container">
                    <label for="staffName">Name:</label>
                    <input type="text" name="name" id="staffName" value="<?= $info['name'] ?>">
                </div>
                <div class="info-row input-container">
                    <label for="staffEmail">Email:</label>
                    <input type="email" name="email" id="staffEmail" value="<?= $info['email'] ?>">
                </div>

                <div class="action-buttons-group contact-actions">
                    <input type="hidden" name="_method" value="PUT">
                    <button class="button default save-button" onclick="showAlert(event, 'question')">Save Changes</button>
                </div>
            </div>
        </form>

        <div class="password-change-section">
            <h3>Change Your Password</h3>
            <div class="password-form-grid">
                <div class="info-row input-container">
                    <label for="newPass">New Password:</label>
                    <input type="password" id="newPass" placeholder="Enter new password">
                </div>
                <div class="info-row input-container">
                    <label for="confirmPass">Confirm New Password:</label>
                    <input type="password" id="confirmPass" placeholder="Confirm new password">
                </div>
                <div class="password-action-buttons">
                    <button class="button default">Update Password</button>
                </div>
            </div>
        </div>

    </div>
</div>