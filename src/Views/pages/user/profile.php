<div class="profile-page">
    <div class="profile-container">
        <h2>Librarian Profile Details & Actions</h2>

        <div class="profile-card">
            <div class="profile-avatar-section">
                <div class="profile-avatar">
                    <span class="initials">JU</span>
                </div>
                <button class="avatar-upload-btn">Change Photo</button>
            </div>

            <div class="profile-info-form">
                <div class="info-row editable">
                    <label for="staffName">Name:</label>
                    <input type="text" id="staffName" value="John User" disabled>
                </div>
                <div class="info-row editable">
                    <label for="staffEmail">Email:</label>
                    <input type="email" id="staffEmail" value="john.user@flarebooks.com">
                </div>
                <div class="info-row editable">
                    <label for="staffPhone">Phone:</label>
                    <input type="tel" id="staffPhone" value="(555) 505-1234">
                </div>

                <div class="info-row static">
                    <label>Staff ID:</label>
                    <p id="staffId">LIB-405</p>
                </div>
                <div class="info-row static">
                    <label>Role:</label>
                    <p id="staffRole">Senior Librarian</p>
                </div>
                <div class="info-row static">
                    <label>Access Level:</label>
                    <p id="accessLevel"><span class="status-admin">Full Admin</span></p>
                </div>

                <div class="action-buttons-group contact-actions">
                    <button class="update-btn">Save Contact Changes</button>
                    <button class="cancel-btn">Cancel</button>
                </div>
            </div>
        </div>

        <div class="password-change-section">
            <h3>Change Your Password</h3>
            <div class="password-form-grid">
                <div class="info-row password-input">
                    <label for="currentPass">Current Password:</label>
                    <input type="password" id="currentPass" placeholder="Enter current password">
                </div>
                <div class="info-row password-input">
                    <label for="newPass">New Password:</label>
                    <input type="password" id="newPass" placeholder="Enter new password">
                </div>
                <div class="info-row password-input">
                    <label for="confirmPass">Confirm New Password:</label>
                    <input type="password" id="confirmPass" placeholder="Confirm new password">
                </div>
                <div class="password-action-buttons">
                    <button class="update-btn password-btn">Update Password</button>
                </div>
            </div>
        </div>

    </div>
</div>