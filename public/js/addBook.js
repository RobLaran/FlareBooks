let addBookForm = document.getElementById('add-book-form');

if(addBookForm) {
    addBookForm.addEventListener('submit', function(e) {
    e.preventDefault(); // stop form from submitting immediately

    Swal.fire({
        title: 'Add Book?',
        text: "Do you want to save this book to the library?",
        icon: 'question',
        iconColor: '#ff6600',
        showCancelButton: true,
        confirmButtonColor: '#FFD60A',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: 'none',
        background: '#fed2a8',
        color: '#000',
        confirmButtonText: 'Yes, add it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            this.submit(); // submit form only if confirmed
        }
    });
});
}