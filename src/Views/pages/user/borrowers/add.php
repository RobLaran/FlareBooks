<div class="add-borrower-page">
    <form action="<?= routeTo('/borrowers/add') ?>" method="POST" class="form-container" id="add-borrower-form" novalidate>
        <div class="fname-field input-container">
            <label for="fname-input" class="required">First Name:</label>
            <input type="text" name="fname" id="fname-input" required value="<?= $old['fname'] ?? '' ?>">
        </div>
        <div class="lname-field input-container">
            <label for="lname-input" class="required">Last Name:</label>
            <input type="text" name="lname" id="lname-input" required value="<?= $old['lname'] ?? '' ?>">
        </div>
        <div class="email-field input-container">
            <label for="email-input" class="required">Email:</label>
            <input type="text" name="email" id="email-input" required value="<?= $old['email'] ?? '' ?>">
        </div>
        <div class="phone-field input-container">
            <label for="phone-input">Phone Number:</label>
            <input type="text" name="phone" id="phone-input" value="<?= $old['phone'] ?? '' ?>">
        </div>
        <div class="address-field input-container">
            <label for="address-input">Address:</label>
            <input type="text" name="address" id="address-input" value="<?= $old['address'] ?? '' ?>">
        </div>
        <div class="birth-field input-container">
            <label for="birth-input">Date of Birth:</label>
            <input type="date" name="birth" id="birth-input" value="<?= $old['birth'] ?? '' ?>">
        </div>
        <div class="button-container">
            <button type="button" class="button default" onclick="showAlert(event,'question')">Confirm</button>
            <a href="<?= routeTo('/borrowers') ?>" class="button danger">Cancel</a>
        </div>
    </form>
</div>