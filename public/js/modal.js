function createModal() {
    // === Create Modal Structure ===
    const modalOverlay = document.createElement('div');
    modalOverlay.classList.add('modal-overlay');
    modalOverlay.style.display = 'none';

    const modalBox = document.createElement('div');
    modalBox.classList.add('modal-box');

    const modalHeader = document.createElement('div');
    modalHeader.classList.add('modal-header');

    const modalTitle = document.createElement('h2');
    modalTitle.classList.add('modal-title');
    modalTitle.textContent = 'Modal Title';

    const closeButton = document.createElement('button');
    closeButton.classList.add('close-modal');
    closeButton.innerHTML = '&times;';

    modalHeader.append(modalTitle, closeButton);

    const modalBody = document.createElement('div');
    modalBody.classList.add('modal-body');

    const modalFooter = document.createElement('div');
    modalFooter.classList.add('modal-footer');

    modalBox.append(modalHeader, modalBody, modalFooter);
    modalOverlay.append(modalBox);
    document.body.append(modalOverlay);

    // === Functions ===
    function open(content, title = 'Modal', footerContent = null) {
        modalTitle.textContent = title;
        modalBody.innerHTML = '';
        modalFooter.innerHTML = '';

        if (content instanceof HTMLElement) modalBody.append(content);
        else modalBody.innerHTML = content;

        if (footerContent instanceof HTMLElement) modalFooter.append(footerContent);

        modalOverlay.style.display = 'flex';
        setTimeout(() => modalBox.classList.add('show'), 10);
        document.body.style.overflow = 'hidden';
    }

    function close() {
        modalBox.classList.remove('show');
        setTimeout(() => {
            modalOverlay.style.display = 'none';
            document.body.style.overflow = 'auto';
        }, 200);
    }

    closeButton.addEventListener('click', close);
    modalOverlay.addEventListener('click', (e) => {
        if (e.target === modalOverlay) close();
    });

    // === Example Add Genre Form ===
    function addGenreForm(route) {
        const form = document.createElement('form');
        form.id = 'add-genre-form';
        form.noValidate = true;
        form.setAttribute('method', 'POST');
        form.setAttribute('action', route);

        const inputsContainer = document.createElement('div');
        inputsContainer.classList.add('inputs-container');

        // Genre
        const genreGroup = document.createElement('div');
        genreGroup.classList.add('form-group');
        genreGroup.innerHTML = `
            <div class="input-container">
                <label for="genre-input">Genre</label>
                <input type="text" id="genre-input" name="name" placeholder="Enter genre name" required>
            </div>
        `;

        // Description
        const descGroup = document.createElement('div');
        descGroup.classList.add('form-group');
        descGroup.innerHTML = `
            <div class="input-container">
                <label for="description-input">Description</label>
                <textarea id="description-input" name="description" placeholder="Enter genre description"></textarea>
            </div>
        `;

        inputsContainer.append(genreGroup, descGroup);

        const submitBtn = document.createElement('button');
        submitBtn.type = 'submit';
        submitBtn.classList.add('button');
        submitBtn.classList.add('default');
        submitBtn.textContent = 'Add Genre';

        form.append(inputsContainer, submitBtn);

        return form;
    }

    function editGenreForm(route, genre) {
        const form = document.createElement('form');
        form.id = 'add-genre-form';
        form.noValidate = true;
        form.setAttribute('method', 'POST');
        form.setAttribute('action', route);

        const inputsContainer = document.createElement('div');
        inputsContainer.classList.add('inputs-container');

        // Genre
        const genreGroup = document.createElement('div');
        genreGroup.classList.add('form-group');
        genreGroup.innerHTML = `
            <div class="input-container">
                <label for="genre-input">Genre</label>
                <input type="text" id="genre-input" name="name" placeholder="Enter genre name" value="${genre['Name']}">
            </div>
        `;

        // Description
        const descGroup = document.createElement('div');
        descGroup.classList.add('form-group');
        descGroup.innerHTML = `
            <div class="input-container">
                <label for="description-input">Description</label>
                <textarea id="description-input" name="description" placeholder="Enter genre description">${genre['Description']}</textarea>
            </div>
        `;

        inputsContainer.append(genreGroup, descGroup);

        const hiddenInput = document.createElement('input');
        hiddenInput.type = "hidden";
        hiddenInput.name = "_method";
        hiddenInput.value = "PUT";

        const submitBtn = document.createElement('button');
        submitBtn.type = 'submit';
        submitBtn.classList.add('button');
        submitBtn.classList.add('default');
        submitBtn.textContent = 'Update Genre';

        form.append(inputsContainer, hiddenInput, submitBtn);

        return form;
    }

    return { 
        open, 
        close, 
        addGenreForm, 
        editGenreForm 
    };
}
