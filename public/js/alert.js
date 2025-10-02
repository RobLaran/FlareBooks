function showAlert(e, type='warning') {
    e.preventDefault(); 
    const form = e.target.closest("form"); 
    const modal = createAlert(type, () => {
        form.submit();
    });
    openAlert(modal);
}

function createAlert(type='warning', onConfirmCallback=null) {
    let settings = null;

    switch (type) {
        case 'warning':
            settings = {
                icon: window.appConfig.icons.warning,
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                confirmText: "Confirm",
                cancelText: "Cancel",
                onConfirm: () => {
                    if (onConfirmCallback) onConfirmCallback();
                    console.log("Warning confirmed");
                },
                onCancel: () => { console.log("Warning canceled"); }
            };
            break;

        case 'question':
            settings = {
                icon: window.appConfig.icons.question,
                title: "Proceed?",
                text: "Do you want to continue with this action?",
                confirmText: "Yes",
                cancelText: "No",
                onConfirm: () => {
                    if (onConfirmCallback) onConfirmCallback();
                    console.log("User said YES");
                },
                onCancel: () => { console.log("User said NO"); }
            };
            break;
    
        default:
            break;
    }

    // Remove existing modal if present
    const oldModal = document.querySelector(".alert-modal");
    if (oldModal) oldModal.remove();

    // Create modal wrapper
    const modal = document.createElement("div");
    modal.classList.add("alert-modal", "modal");

    // Modal content
    const content = document.createElement("div");
    content.classList.add("alert-content");

    // Icon
    const icon = document.createElement("div");
    icon.classList.add("alert-icon");
    const img = document.createElement("img");
    img.src = settings.icon;
    img.alt = settings.type;
    icon.appendChild(img);

    // Title
    const title = document.createElement("div");
    title.classList.add("alert-title");
    title.textContent = settings.title;

    // Text
    const text = document.createElement("div");
    text.classList.add("alert-text");
    text.textContent = settings.text;

    // Buttons
    const buttons = document.createElement("div");
    buttons.classList.add("alert-buttons");

    const confirmBtn = document.createElement("button");
    confirmBtn.classList.add("ok-button", "button", "default");
    confirmBtn.textContent = settings.confirmText;

    const cancelBtn = document.createElement("button");
    cancelBtn.classList.add("cancel-button", "button", "danger");
    cancelBtn.textContent = settings.cancelText;

    buttons.appendChild(confirmBtn);
    buttons.appendChild(cancelBtn);

    // Event listeners
    confirmBtn.addEventListener("click", () => {
        settings.onConfirm();
        modal.remove();
    });

    cancelBtn.addEventListener("click", () => {
        settings.onCancel();
        modal.remove();
    });

    // Append children
    content.appendChild(icon);
    content.appendChild(title);
    content.appendChild(text);
    content.appendChild(buttons);
    modal.appendChild(content);

    return modal;
}

function openAlert(modal) {
    document.body.appendChild(modal);
}

function closeAlert(modal) {
    document.body.removeChild(modal)
}
