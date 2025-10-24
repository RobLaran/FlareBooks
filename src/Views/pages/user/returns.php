<div class="return-book-page">
	<div class="container">
		<!-- Borrower Info -->
		<div class="card borrowed-book-selection">
			<h2>Select Borrowed Book</h2>
			<form id="borrowedBookForm" method="POST" action="<?= routeTo('/returns/add') ?>" novalidate>
				<div class="search-input input-container">
					<input type="text" id="borrowedBookListSearchBox" placeholder="Search by Name, Code, Email, or Address" data-route="<?= routeTo("/returns/search-transaction") ?>">
				</div>
				<div class="borrowed-book-list" id="borrowedBookList">
                    <?php if (count($transactions) > 0): ?>

						<?php foreach ($transactions as $transaction): ?>

							<div class="transaction" data-id="<?= $transaction['id'] ?>">
								<div>
									<img src="<?= isImageUrl($transaction['Book Info']['Image'] ?? "") ? $transaction['Book Info']['Image'] : getFile('public/img/' . $transaction['Book Info']['Image']) ?>" alt="">
								</div>
								<div>
									<strong><?= $transaction['Borrower'] ?></strong><br>
									Book Title: <?= $transaction['Book Info']['Title'] ?><br>
									Book Author: <?= $transaction['Book Info']['Author'] ?><br>
									ISBN: <?= $transaction['Book Info']['ISBN'] ?><br>
									<?php if($transaction['Status'] == "Overdue"): ?>
										<div class="overdue"><?= $transaction['Status'] ?></div>
									<?php endif; ?>
								</div>
							</div>

						<?php endforeach; ?>

					<?php else: ?>

					<h2>No transactions</h2>

					<?php endif; ?>
				</div>

				<input type="hidden" name="transaction_id" id="selectedTransaction">

				<div class="buttons">
					<button type="submit" class="button default" onclick="showAlert(event, 'question')">Return Book</button>
					<a href="<?= routeTo('/returns') ?>" class="button default reset">Reset</a>
				</div>
			</form>
		</div>
	</div>

	<div class="table-container">
		<h2>Returned Books</h2>

		<div class="row one">
            <div class="filter-container">
                <label for="date-column-returns">Filter by:</label>
                <select id="date-column-returns">
                    <option value="Borrow Date">Borrow Date</option>
                    <option value="Due Date">Due Date</option>
                    <option value="Return Date">Return Date</option>
                </select>

                <label for="start-date-returns">From:</label>
                <input type="date" id="start-date-returns">

                <label for="end-date-returns">To:</label>
                <input type="date" id="end-date-returns">

                <button id="apply-filter-returns">Filter</button>
                <button id="clear-filter-returns">Clear</button>
            </div>

            <div class="filter-container">
                <span>Filter by Genre:</span>
                <select name="genre-selection" id="genre-filter">
                    <option value="">-- Select genre --</option>
                    <?php foreach($genres as $genre): ?>
                        <option value="<?= $genre['Name'] ?>"><?= $genre['Name'] ?></option>
                    <?php endforeach; ?>
                </select>
                <button id="clear-filter-genres">Clear</button>
            </div>
        </div>

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
				<label for="search-returned-books-input">Search:</label>
				<input type="text" class="search-input" id="search-returned-books-input" name="search"oninput="booksTable.noFetchSearch(this.value)">
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
<script src="<?= getFile("public/js/returns.js") ?>"></script>
<script>
    // Table
    const booksData = <?php echo json_encode([$data]); ?>;

    const booksTable = createDynamicTable({
        data: booksData[0],
        containerId: "books-table",
		headingId: "books-table-heading",
        paginationId: "books-pagination",
        itemsPerPage: 5,
        tableVar: "booksTable",
		columns: {
			"Book Info": (row) => `
				<div class="book-info"> 
					<div class="book-image-wrapper">
						<img class="book-image" src="${row.Image}">
					</div>
					<div class="book-details">
						<strong>${row.Title}</strong><br>
						by ${row.Author}<br>
						Genre: ${row.Genre}<br>
						ISBN: ${row.ISBN}
					</div>
				</div>
			`,
			"Status": (status) => 
				`<span class="status online">${status}</span>`
		},
		sortable: ["Borrower", "Borrow Date", "Due Date", "Return Date"],
		hidden: ["id"],
        actions: (row) => {
            return `
                <form class="delete-returned-book-form" action="<?= routeTo('/returns/delete/') ?>${row.id}" method="POST">
					<input type="hidden" name="_method" value="DELETE">
					<button type="button" class="button act-remove danger" onclick="showAlert(event)">
						<img src="<?= getFile("public/img/delete.png") ?>">
					</button>
				</form>
            `;
        }
    });

    booksTable.renderTable(1);

	// Date range filter
    booksTable.initDateFilter({
        columnSelectId: "date-column-returns",
        startDateId: "start-date-returns",
        endDateId: "end-date-returns",
        applyBtnId: "apply-filter-returns",
        clearBtnId: "clear-filter-returns"
    });

    // Genre filter
	booksTable.initColumnFilter({
		columnSelectId: "genre-filter",
		column: "Genre",
		key: "Book Info",
		clearBtnId: "clear-filter-genres"
	});

    booksTable.reset();
</script>