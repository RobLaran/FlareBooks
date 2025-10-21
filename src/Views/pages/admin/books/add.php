<div class="add-book-page">
    <form action="<?= routeTo('/admin/books/add') ?>" method="POST" class="form-container" id="add-book-form">
        <div class="image-preview">
            <img src="<?= getFile('/public/img/image-preview.jpg') ?>" alt="Image Preview" id="image-preview"" data-default="<?= getFile('/public/img/image-preview.jpg') ?>">
        </div>
        <div class="image-field input-container">
            <div class="image-input-container">
                <label for="image-input">Upload Image(File):</label>
                <input type="file" name="image" id="image-input" accept="image/*">
            </div>
            <div class="image-url-container">
                <label for="image-url">Or Image URL:</label>
                <input type="text" name="image_url" id="image-url" placeholder="https://example.com/image.jpg" value="<?= $old['image'] ?? '' ?>">
            </div>
        </div>
        <div class="isbn-field input-container">
            <label for="ISBN-input" class="required">ISBN:</label>
            <input type="text" name="ISBN" id="ISBN-input" required value="<?= $old['ISBN'] ?? '' ?>"> 
        </div>
        <div class="author-field input-container">
            <label for="author-input" class="required">Author:</label>
            <input type="text" name="author" id="author-input" required value="<?= $old['author'] ?? '' ?>"> 
        </div>
        <div class="publisher-field input-container">
            <label for="publisher-input">Publisher:</label>
            <input type="text" name="publisher" id="publisher-input" value="<?= $old['publisher'] ?? '' ?>"> 
        </div>
        <div class="title-field input-container">
            <label for="title-input" class="required">Title:</label>
            <textarea name="title" id="title-input" required value="<?= $old['title'] ?? '' ?>" ></textarea>
        </div>
        <div class="quantity-field input-container">
            <label for="quantity-input">Quantity:</label>
            <input type="number" name="quantity" id="quantity-input" value="<?= $old['quantity'] ?? '1' ?>">
        </div>
        <div class="genre-field input-container">
            <label for="genre-selection">Genre:</label>
            <select name="genre" id="genre-selection">
                <?php if(count($genres) > 0): ?>
                    <?php foreach($genres as $genre): ?>
                        <option value="<?= $genre['id'] ?>" <?= !empty($old['genre']) ? 'selected' : '' ?>><?= $genre['Name'] ?></option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>
        <div class="button-container">
            <button type="button" class="button default" onclick="showAlert(event, 'question')">Confirm</button>
            <a href="<?= routeTo('/admin/books') ?>" class="button danger">Cancel</a>
        </div>
    </form>
</div>