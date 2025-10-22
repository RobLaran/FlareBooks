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

    function borrowerInfo(data) {
        // Root container
        const content = document.createElement('div');
        content.classList.add('borrowerModal');

        // ========== Borrower Details ==========
        const details = document.createElement('div');
        details.classList.add('borrower-details');

        const infoTable = document.createElement('table');
        infoTable.classList.add('info-table');

        const infoData = [
            ['Borrower Code:', `${data['Code'] ?? 'N/A'}`, 'b-code'],
            ['Name:', `${(data['First Name'] + ' ' + data['Last Name']) ?? 'N/A'}`, 'b-name'],
            ['Email:', `${data['Email'] ?? 'N/A'}`, 'b-email'],
            ['Phone:', `${data['Phone Number'] ?? 'N/A'}`, 'b-phone'],
            ['Address:', `${data['Address'] ?? 'N/A'}`, 'b-address'],
            ['Date of Birth:', `${data['Date of Birth'] ?? 'N/A'}`, 'b-dob'],
            ['Membership Date:', `${data['Membership Date'] ?? 'N/A'}`, 'b-membership'],
            ['Status:', `<span class="status ${data['Status'] == "Active" ? "active" : "inactive"}">${data['Status']}</span>`, 'b-status', true],
        ];

        infoData.forEach(([label, value, id, isHTML]) => {
            const tr = document.createElement('tr');
            const th = document.createElement('th');
            th.textContent = label;

            const td = document.createElement('td');
            td.id = id;
            if (isHTML) td.innerHTML = value;
            else td.textContent = value;

            tr.append(th, td);
            infoTable.appendChild(tr);
        });

        details.appendChild(infoTable);

        // Divider
        const divider1 = document.createElement('br');
        const hr = document.createElement('hr');
        const divider2 = document.createElement('br');

        // ========== Borrower History ==========
        const history = document.createElement('div');
        history.classList.add('borrower-history');

        const h4 = document.createElement('h4');
        h4.textContent = 'Borrowing History';
        history.appendChild(h4);

        const tableWrapper = document.createElement('div');
        tableWrapper.classList.add('table-wrapper');

        const table = document.createElement('table');
        table.id = 'borrowHistory';
        table.classList.add('history-table');

        // --- Table Head ---
        const thead = document.createElement('thead');
        const headRow = document.createElement('tr');
        ['Book Title', 'Borrow Date', 'Return Date'].forEach(text => {
            const th = document.createElement('th');
            th.textContent = text;
            headRow.appendChild(th);
        });
        thead.appendChild(headRow);
        table.appendChild(thead);

        // --- Table Body ---
        const tbody = document.createElement('tbody');
        table.appendChild(tbody);
        tableWrapper.appendChild(table);
        history.appendChild(tableWrapper);

        // Combine all
        content.append(details, divider1, hr, divider2, history);

        // ========== Fetch Borrower History ==========
        const borrowerCode = data['Code'] ?? data['ID']; // adjust key as needed
        const route = this.route;

        fetch(`${route}?id=${borrowerCode}`)
            .then(res => {
                if (!res.ok) throw new Error('Failed to load borrower history');
                return res.json();
            })
            .then(rows => {
                tbody.innerHTML = ''; // clear loading state

                if (!rows.length) {
                    const tr = document.createElement('tr');
                    const td = document.createElement('td');
                    td.colSpan = 4;
                    td.textContent = 'No borrowing history found.';
                    td.style.textAlign = 'center';
                    tr.appendChild(td);
                    tbody.appendChild(tr);
                    return;
                }

                rows.forEach(row => {
                    const tr = document.createElement('tr');

                    const td1 = document.createElement('td');
                    td1.textContent = row.book_title ?? '—';

                    const td2 = document.createElement('td');
                    td2.textContent = row.borrow_date ?? '—';

                    const td3 = document.createElement('td');
                    td3.textContent = row.return_date ?? '—';

                    tr.append(td1, td2, td3);
                    tbody.appendChild(tr);
                });
            })
            .catch(err => {
                console.error(err);
                tbody.innerHTML = `
                    <tr>
                        <td colspan="4" style="text-align:center;color:#999;">Error loading history.</td>
                    </tr>
                `;
            });

        // Return final modal content
        return content;
    }


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

    function addStaffForm(route) {
        const form = document.createElement('form');
        form.id = 'add-staff-form';
        form.noValidate = true;
        form.setAttribute('method', 'POST');
        form.setAttribute('action', route);

        const inputsContainer = document.createElement('div');
        inputsContainer.classList.add('inputs-container');

        const staffImageGroup = document.createElement('div');
        staffImageGroup.classList.add('form-group');
        staffImageGroup.classList.add('staff-image');
        staffImageGroup.innerHTML = `
            <div class="image-preview">
                <img src="" alt="Image Preview" id="image-preview"" data-default="">
            </div>
            <div class="image-field input-container">
                <div class="image-input-container">
                    <label for="image-input">Upload Image(File):</label>
                    <input type="file" name="imageFile" id="image-input" accept="image/*">
                </div>
                <div class="image-url-container">
                    <label for="image-url">Or Image URL:</label>
                    <input type="text" name="imageURL" id="image-url" placeholder="https://example.com/image.jpg">
                </div>
            </div>
        `;

        const staffNameGroup = document.createElement('div');
        staffNameGroup.classList.add('form-group');
        staffNameGroup.innerHTML = `
            <div class="input-container">
                <label for="staff-input">Staff Name</label>
                <input type="text" id="staff-input" name="name" placeholder="Enter staff name">
            </div>
        `;

        const usernameGroup = document.createElement('div');
        usernameGroup.classList.add('form-group');
        usernameGroup.innerHTML = `
            <div class="input-container">
                <label for="username-input">Username</label>
                <input type="text" id="username-input" name="username" placeholder="Enter username">
            </div>
        `;

        const emailGroup = document.createElement('div');
        emailGroup.classList.add('form-group');
        emailGroup.innerHTML = `
            <div class="input-container">
                <label for="email-input">Email</label>
                <input type="email" id="email-input" name="email" placeholder="Enter email">
            </div>
        `;

        const passwordGroup = document.createElement('div');
        passwordGroup.classList.add('form-group');
        passwordGroup.innerHTML = `
            <div class="input-container">
                <label for="password-input">Password</label>
                <input type="text" id="password-input" name="password" placeholder="Enter password">
            </div>
        `;

        inputsContainer.append(
            staffImageGroup,
            staffNameGroup, 
            usernameGroup,
            emailGroup,
            passwordGroup
        );

        const submitBtn = document.createElement('button');
        submitBtn.type = 'submit';
        submitBtn.classList.add('button');
        submitBtn.classList.add('default');
        submitBtn.textContent = 'Confirm Staff';

        form.append(inputsContainer, submitBtn);

        return form;
    }

    function editStaffForm(route, staff) {
        console.log(staff);
        

        const form = document.createElement('form');
        form.id = 'add-staff-form';
        form.noValidate = true;
        form.setAttribute('method', 'POST');
        form.setAttribute('action', route);

        const inputsContainer = document.createElement('div');
        inputsContainer.classList.add('inputs-container');

        const staffImageGroup = document.createElement('div');
        staffImageGroup.classList.add('form-group');
        staffImageGroup.classList.add('staff-image');
        staffImageGroup.innerHTML = `
            <div class="image-preview">
                <img src="${staff['Image']}" alt="Image Preview" id="image-preview"" data-default="">
            </div>
            <div class="image-field input-container">
                <div class="image-input-container">
                    <label for="image-input">Upload Image(File):</label>
                    <input type="file" name="imageFile" id="image-input" accept="image/*">
                </div>
                <div class="image-url-container">
                    <label for="image-url">Or Image URL:</label>
                    <input type="text" name="imageURL" id="image-url" placeholder="https://example.com/image.jpg">
                </div>
            </div>
        `;

        const staffNameGroup = document.createElement('div');
        staffNameGroup.classList.add('form-group');
        staffNameGroup.innerHTML = `
            <div class="input-container">
                <label for="staff-input">Staff Name</label>
                <input type="text" id="staff-input" name="name" placeholder="Enter staff name" value="${staff['Name']}">
            </div>
        `;

        const usernameGroup = document.createElement('div');
        usernameGroup.classList.add('form-group');
        usernameGroup.innerHTML = `
            <div class="input-container">
                <label for="username-input">Username</label>
                <input type="text" id="username-input" name="username" placeholder="Enter username" value="${staff['Username']}">
            </div>
        `;

        const emailGroup = document.createElement('div');
        emailGroup.classList.add('form-group');
        emailGroup.innerHTML = `
            <div class="input-container">
                <label for="email-input">Email</label>
                <input type="email" id="email-input" name="email" placeholder="Enter email" value="${staff['Email']}">
            </div>
        `;

        const passwordGroup = document.createElement('div');
        passwordGroup.classList.add('form-group');
        passwordGroup.innerHTML = `
            <div class="input-container">
                <label for="password-input">Password</label>
                <input type="text" id="password-input" name="password" placeholder="Enter password" value="${staff['Password']}">
            </div>
        `;

        inputsContainer.append(
            staffImageGroup,
            staffNameGroup, 
            usernameGroup,
            emailGroup,
            passwordGroup
        );

        const hiddenInput = document.createElement('input');
        hiddenInput.type = "hidden";
        hiddenInput.name = "_method";
        hiddenInput.value = "PUT";

        const submitBtn = document.createElement('button');
        submitBtn.type = 'submit';
        submitBtn.classList.add('button');
        submitBtn.classList.add('default');
        submitBtn.textContent = 'Confirm Staff';

        form.append(inputsContainer, hiddenInput, submitBtn);

        return form;
    }

    function imagePreviewAction() {
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


    }

    return { 
        open, 
        close, 
        borrowerInfo,
        addGenreForm, 
        editGenreForm,
        addStaffForm,
        editStaffForm,
        imagePreviewAction
    };
}
