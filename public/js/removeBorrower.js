document.querySelectorAll('.delete-borrower-form').forEach(form => {
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        Swal.fire({
            title: 'Delete Borrower?',
            text: "This action cannot be undone!",
            icon: 'warning',
            iconColor: '#ff6600',
            showCancelButton: true,
            confirmButtonColor: '#FFD60A',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: 'none',
            background: '#fed2a8',
            color: '#000',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                this.submit();
            }
        });
    });
});