<div class="return-book-page">
	<div class="container">
		<!-- Borrower Info -->
		<div class="card borrowed-book-selection">
			<h2>Select Borrowed Book</h2>
			<form id="borrowedBookForm" method="POST" action="<?= routeTo('/borrowed-books/add') ?>" novalidate>
				<div class="search-input input-container">
					<input type="text" id="borrowedBooktSearchBox" placeholder="Search by Name, Code, Email, or Address">
				</div>
				<div class="borrowed-book-list" id="borrowedBookList">
					
				</div>

				<input type="hidden" name="borrowed_book_id" id="selectedBorrowedBookId">

				<div class="buttons">
					<button type="submit" class="button default" onclick="showAlert(event, 'question')">Return Book</button>
					<a href="<?= routeTo('/borrowed-books') ?>" class="button default reset">Reset</a>
				</div>
			</form>
		</div>
	</div>

	<div class="table-container">
		<h2>Returned Books</h2>

        <div class="row two">
            <div class="select-entries">
                <span>
                    Showing 
                    <form method="GET" id="entries-form" style="display:inline;">
                        <select name="limit" id="entries-selection" onchange="booksTable.changeEntries(this.value)">
							<option value="5">5</option>
                            <option value="10">10</option>
                            <option value="15">15</option>
                            <option value="20">20</option>
							<option value="all">All</option>
                        </select>
                        <input type="hidden" name="page" value="1">
                    </form>
                    entries
                </span>
            </div>

            <div class="search-input-container">
				<label for="search-input">Search:</label>
				<input type="text" id="search-input" name="search" onkeyup="booksTable.applyFilters(this.value)">
            </div>

        </div>

        <div class="table-container row three">
            <table class="table-zebra">
                <thead>
                    <tr class="table-heading" id="books-table-heading">
                    </tr>
                </thead>

                <tbody class="table-body" id="books-table">

                </tbody>
            </table>
        </div>

        <div class="entries-pagination-container row four">
            <div class="showed-entries">
            </div>

            <ul class="pagination" id="books-pagination">
            </ul>
        </div>


    </div>
</div>

<script src="<?= getFile("public/js/table.js") ?>"></script>
<script>
    const booksData = <?php echo json_encode([$data]); ?>;

    const booksTable = createDynamicTable({
        data: booksData,
        containerId: "books-table",
		headingId: "books-table-heading",
        paginationId: "books-pagination",
        itemsPerPage: 5,
        tableVar: "booksTable",
		columns: {
			"Book Info": (row) => `
				<div class="book-cell">
					<span>${row.Title}</span>
				</div>
			`
		},
        actions: (row) => {
            return `
                <button onclick="alert('Returning: ${row.Title}')">Return</button>
                <button onclick="alert('Deleting: ${row.ISBN}')">Delete</button>
            `;
        }
    });

    booksTable.renderTable(1);
</script>