<div class="borrow-book-page">
	<div class="container">
		<!-- Search & Book List -->
		<div class="card book-selection">
			<h2>Search Book</h2>
				<div class="search-input input-container">
					<input type="text" id="bookListSearchBox" data-route="<?= routeTo('/borrowed-books/search-book') ?>" placeholder="Search by Title, Author, or ISBN">
				</div>

			<div class="book-list" id="bookList">
				<?php if (count($books) > 0): ?>

					<?php foreach ($books as $book): ?>

						<div class="book-item <?= $book['Status'] != "Available" ? 'not-available' : '' ?>" data-id="<?= $book['ISBN'] ?>">
							<div>
								<img src="<?= isImageUrl($book['Image'] ?? "") ? $book['Image'] : $book['Image'] ?>" alt="">
							</div>
							<div>
								<strong><?= $book['Title'] ?></strong><br>
								by <?= $book['Author'] ?><br>
								ISBN: <?= $book['ISBN'] ?>
								<div class="availability <?= strtolower($book['Status']) ?>"><?= $book['Status'] ?></div>
							</div>
						</div>

					<?php endforeach; ?>

				<?php else: ?>

					<h2>No books</h2>

				<?php endif; ?>
			</div>
		</div>

		<!-- Borrower Info -->
		<div class="card borrower-selection">
			<h2>Borrower Information</h2>
			<form id="borrowForm" method="POST" action="<?= routeTo('/borrowed-books/add') ?>" novalidate>
				<div class="search-input input-container">
					<input type="text" id="borrowerListSearchBox" date-route="<?= routeTo('/borrowed-books/search-borrower') ?>" placeholder="Search by Name, Code, Email, or Address">
				</div>
				<div class="borrower-list" id="borrowerList">
					<?php if (count($borrowers) > 0): ?>

						<?php foreach ($borrowers as $borrower): ?>

							<div class="borrower <?= $borrower['Status'] != "Active" ? 'not-available' : '' ?>" data-id="<?= $borrower['Code'] ?>">
								<div>
									<strong><?= $borrower['First Name'] . ' ' . $borrower['Last Name'] ?></strong><br>
									Code: <?= $borrower['Code'] ?><br>
									Email: <?= $borrower['Email'] ?><br>
									Address: <?= $borrower['Address'] ?>
									<div class="availability <?= $borrower['Status'] != "Active" ? 'unavailable' : 'available' ?>"><?= ucfirst($borrower['Status']) ?></div>
								</div>
							</div>

						<?php endforeach; ?>

					<?php else: ?>

						<h2>No books</h2>

					<?php endif; ?>
				</div>

				<div class="due-date-input input-container">
					<label for="dueDate" id="due-date-label">Due Date:</label>
					<input type="date" name="due_date" id="dueDate" required>
				</div>

				<input type="hidden" name="book_id" id="selectedBookId">
				<input type="hidden" name="borrower_code" id="selectedBorrowerId">

				<div class="buttons">
					<button type="submit" class="button default" onclick="showAlert(event, 'question')">Confirm Borrow</button>
					<a href="<?= routeTo('/borrowed-books') ?>" class="button default reset">Reset</a>
				</div>
			</form>
		</div>
	</div>

	<div class="table-container">
		<h2>Borrowed Books</h2>

		<div class="row one">
            <div class="filter-container">
                <label for="date-column-transactions">Filter by:</label>
                <select id="date-column-transactions">
                    <option value="Borrow Date">Borrow Date</option>
                    <option value="Due Date">Due Date</option>
                </select>

                <label for="start-date-transactions">From:</label>
                <input type="date" id="start-date-transactions">

                <label for="end-date-transactions">To:</label>
                <input type="date" id="end-date-transactions">

                <button id="apply-filter-transactions">Filter</button>
                <button id="clear-filter-transactions">Clear</button>
            </div>
        </div>

        <div class="row two">
            <div class="select-entries">
                <span>
                    Showing 
                    <form method="GET" id="entries-form" style="display:inline;">
                        <!-- If create another table change this -->
                        <select name="limit" id="entries-selection" onchange=" borrowedBooksTable.changeEntries(this.value)">
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

                <!-- If create another table change this -->
				<input type="text" id="search-input" name="search" data-route="<?= routeTo('/borrowed-books/search-borrowed-books') ?>" onkeyup="borrowedBooksTable.search(this, this.value)">
            </div>

        </div>

        <div class="table-container row three">
            <table class="table-zebra">
                <thead>
                    <!-- If create another table change this headingId -->
                    <tr class="table-heading" id="borrowedBooks-table-heading"> 
                    </tr>
                </thead>

                <!-- If create another table change this containerId -->
                <tbody class="table-body" id="borrowedBooks-table">

                </tbody>
            </table>
        </div>

        <div class="entries-pagination-container row four">
            <div class="showed-entries">
            </div>

            <!-- If create another table change this paginationId -->
            <ul class="pagination" id="borrowedBooks-pagination">
            </ul>
        </div>

    </div>
</div>

<script src="<?= getFile("public/js/table.js") ?>"></script>
<script src="<?= getFile("public/js/borrowedBooks.js") ?>"></script>
<script>
	// Table
    const borrowedBooksData = <?php echo json_encode([$transactions]); ?>;

    const borrowedBooksTable = createDynamicTable({
        data: borrowedBooksData[0],
        containerId: "borrowedBooks-table",
		headingId: "borrowedBooks-table-heading",
        paginationId: "borrowedBooks-pagination",
        itemsPerPage: 5,
        tableVar: "borrowedBooksTable",
		sortable: [ "Borrower", "Borrow Date", "Due Date", "Status" ],
        columns: {
            "Book Info": (row) => `
				<div class="book-info"> 
					<div class="book-image-wrapper">
						<img class="book-image" src="${row.Image}">
					</div>
					<div class="book-details">
						<strong>${row.Title}</strong><br>
						by ${row.Author}<br>
						ISBN: ${row.ISBN}
					</div>
				</div>
			`,
			"Status": (status) => 
				`<span class="status ${status == "Overdue" ? "offline" : "online"}">${status}</span>`
        },
		hidden: ["id"],
        actions: (row) => {
            return `
                <form class="delete-borrowed-book-form" action="<?= routeTo('/borrowed-books/delete/') ?>${row.id}" method="POST">
					<input type="hidden" name="_method" value="DELETE">
					<button type="button" class="button act-remove danger" onclick="showAlert(event)">
						<img src="<?= getFile("public/img/delete.png") ?>">
					</button>
				</form>
            `;
        }
    });

    // Change table if needed
    borrowedBooksTable.renderTable(1);

	// Date range filter
    borrowedBooksTable.initDateFilter({
        columnSelectId: "date-column-transactions",
        startDateId: "start-date-transactions",
        endDateId: "end-date-transactions",
        applyBtnId: "apply-filter-transactions",
        clearBtnId: "clear-filter-transactions"
    });
</script>