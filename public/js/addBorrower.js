let addBorrowerForm = document.getElementById('add-borrower-form');

if(addBorrowerForm) {
    addBorrowerForm.addEventListener('submit', function(e) {
    e.preventDefault(); // stop form from submitting immediately

    Swal.fire({
        title: 'Add Borrower?',
        text: "Do you want to save this changes to the library?",
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