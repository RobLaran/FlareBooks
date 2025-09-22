

<div class="add-book-page">
    <form action="<?= routeTo('/books/add') ?>" method="POST" class="form-container">
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
                <input type="text" name="image_url" id="image-url" placeholder="https://example.com/image.jpg">
            </div>
        </div>
        <div class="isbn-field input-container">
            <label for="ISBN-input">ISBN:</label>
            <input type="text" name="ISBN" id="ISBN-input">
        </div>
        <div class="status-field input-container">
            <label for="status-selection">Status:</label>
            <select name="status" id="status-selection">
                <option value="Available" class="online">Available</option>
                <option value="Unavailable" class="offline">Unavailable</option>
            </select>
        </div>
        <div class="author-field input-container">
            <label for="author-input">Author:</label>
            <input type="text" name="author" id="author-input">
        </div>
        <div class="publisher-field input-container">
            <label for="publisher-input">Publisher:</label>
            <input type="text" name="publisher" id="publisher-input">
        </div>
        <div class="title-field input-container">
            <label for="title-input">Title:</label>
            <textarea name="title" id="title-input"></textarea>
        </div>
        <div class="quantity-field input-container">
            <label for="quantity-input">Quantity:</label>
            <input type="number" name="quantity" id="quantity-input" value="1">
        </div>
        <div class="genre-field input-container">
            <label for="genre-selection">Genre:</label>
            <select name="genre" id="genre-selection">
                <?php if(count($genres) > 0): ?>
                    <?php foreach($genres as $genre): ?>
                        <option value="<?= $genre['id'] ?>"><?= $genre['genre'] ?></option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>
        <div class="button-container">
            <button class="button default">Confirm</button>
            <a href="<?= routeTo('/books') ?>" class="button danger">Cancel</a>
        </div>
    </form>
</div>