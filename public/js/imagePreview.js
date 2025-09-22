const fileInput = document.getElementById('image-input');
const urlInput = document.getElementById('image-url');
const preview = document.getElementById('image-preview');

if(fileInput && urlInput) {
    // Default preview image
    const defaultImage = preview.dataset.default;

    // Handle file input
    fileInput.addEventListener("change", () => {
        const file = fileInput.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = e => preview.src = e.target.result;
            reader.readAsDataURL(file);
        } else {
            preview.src = defaultImage; // reset if no file chosen
        }
    });

    // Handle URL input
    urlInput.addEventListener("input", () => {
        const url = urlInput.value.trim();
        if (url) {
            preview.src = url;
        } else {
            preview.src = defaultImage;
        }
    });

    // Handle invalid image URLs
    preview.addEventListener("error", () => {
        preview.src = defaultImage;
    });
}

