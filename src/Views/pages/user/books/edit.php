<div class="edit-book-page">
    <form action="<?= routeTo('/books/update/' . $book['ISBN']) ?>" method="POST" class="form-container" id="update-book-form">
        <div class="image-preview">
            <?php if(isImageUrl($book['image'] ?? "")): ?>
                <img src="<?= $book['image'] ?? $old['image'] ?>" alt="Image URL Preview " id="image-preview" data-default="<?= getFile('/public/img/image-preview.jpg') ?>">
            <?php else: ?>
                <img src="<?= getFile('public/img/' . ($book['image']  ?? $old['image'] )) ?>" alt="Image Preview in File" id="image-preview" data-default="<?= getFile('/public/img/image-preview.jpg') ?>">
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
        <div class="isbn-field input-container">
            <label for="ISBN-input" class="required">ISBN:</label>
            <input type="text" name="ISBN" id="ISBN-input" value="<?= $book['ISBN'] ?? $old['ISBN'] ?>" required>
        </div>
        <!-- <div class="status-field input-container">
            <label for="status-selection">Status:</label>
            <select name="status" id="status-selection">
                <option value="Available" class="online" <?= ($book['status'] ?? $old['status']) == 'Available' ? 'selected' : '' ?> >Available</option>
                <option value="Unavailable" class="offline" <?= ($book['status'] ?? $old['status'])== 'Unavailable' ? 'selected' : '' ?> >Unavailable</option>
            </select>
        </div> -->
        <div class="author-field input-container">
            <label for="author-input" class="required">Author:</label>
            <input type="text" name="author" id="author-input" value="<?= $book['author'] ?? $old['author'] ?>" required>
        </div>
        <div class="publisher-field input-container">
            <label for="publisher-input">Publisher:</label>
            <input type="text" name="publisher" id="publisher-input" value="<?= $book['publisher'] ?? $old['publisher'] ?>">
        </div>
        <div class="title-field input-container">
            <label for="title-input" class="required">Title:</label>
            <textarea name="title" id="title-input" required><?= $book['title'] ?? $old['title'] ?></textarea>
        </div>
        <div class="quantity-field input-container">
            <label for="quantity-input">Quantity:</label>
            <input type="number" name="quantity" id="quantity-input" value="<?= $book['quantity'] ?>">
        </div>
        <div class="genre-field input-container">
            <label for="genre-selection">Genre:</label>
            <select name="genre" id="genre-selection">
                <?php if(count($genres) > 0): ?>
                    <?php foreach($genres as $genre): ?>
                        <option value="<?= $genre['id'] ?>" <?= ($book['genre_id'] ?? $old['genre_id']) == $genre['id'] ? 'selected' : '' ?> ><?= $genre['Name'] ?></option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>
        <div class="button-container">
            <input type="hidden" name="_method" value="PUT">
            <button type="button" class="button default" onclick="showAlert(event, 'question')">Update</button>
            <a href="<?= routeTo('/books') ?>" class="button danger">Cancel</a>
        </div>
    </form>
</div>