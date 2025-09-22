document.getElementById('update-book-form').addEventListener('submit', function(e) {
    e.preventDefault(); // stop form from submitting immediately

    Swal.fire({
        title: 'Update Book?',
        text: "Do you want to save changes to this book?",
        icon: 'warning',
        iconColor: '#ff6600',
        showCancelButton: true,
        confirmButtonColor: '#FFD60A',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: 'none',
        background: '#fed2a8',
        color: '#000',
        confirmButtonText: 'Yes, update it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            this.submit(); // submit form only if confirmed
        }
    });
});