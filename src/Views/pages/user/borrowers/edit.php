<div class="edit-borrower-page">
    <form action="<?= routeTo('/borrowers/update/' . $borrower['id']) ?>" method="POST" class="form-container" id="add-borrower-form" novalidate>
        <div class="fname-field input-container">
            <label for="fname-input" class="required">First Name:</label>
            <input type="text" name="fname" id="fname-input" required value="<?= $borrower['first_name'] ?>">
        </div>
        <div class="lname-field input-container">
            <label for="lname-field" class="required">Last Name:</label>
            <input type="text" name="lname" id="lname-input" required value="<?= $borrower['last_name'] ?>">
        </div>
        <div class="email-field input-container">
            <label for="email-input" class="required">Email:</label>
            <input type="text" name="email" id="email-input" required value="<?= $borrower['email'] ?>">
        </div>
        <div class="phone-field input-container">
            <label for="phone-input">Phone Number:</label>
            <input type="text" name="phone" id="phone-input" value="<?= $borrower['phone'] ?>">
        </div>
        <div class="address-field input-container">
            <label for="address-input">Address:</label>
            <input type="text" name="address" id="address-input" value="<?= $borrower['address'] ?>">
        </div>
        <div class="birth-field input-container">
            <label for="birth-input">Date of Birth:</label>
            <input type="date" name="birth" id="birth-input" value="<?= $borrower['date_of_birth'] ?>">
        </div>
        <div class="status-field input-container">
            <label for="status-selection">Status:</label>
            <select name="status" id="status-selection">
                <option value="active" <?= $borrower['status'] == 'active' ?>>Active</option>
                <option value="inactive" <?= $borrower['status'] == 'inactive' ?>>Inactive</option>
                <option value="banned" <?= $borrower['status'] == 'banned' ?>>Banned</option>
            </select>
        </div>
        <div class="button-container">
            <input type="hidden" name="_method" value="PUT">
            <button type="button" class="button default" onclick="showAlert(event, 'question')">Update</button>
            <a href="<?= routeTo('/borrowers') ?>" class="button danger">Cancel</a>
        </div>
    </form>
</div>