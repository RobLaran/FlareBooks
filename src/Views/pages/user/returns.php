<div class="return-book-page">
	<div class="container">
		<!-- Borrower Info -->
		<div class="card borrowed-book-selection">
			<h2>Select Borrowed Book</h2>
			<form id="borrowedBookForm" method="POST" action="<?= routeTo('/returns/add') ?>" novalidate>
				<div class="search-input input-container">
					<input type="text" id="borrowedBookListSearchBox" placeholder="Search by Name, Code, Email, or Address">
				</div>
				<div class="borrowed-book-list" id="borrowedBookList">
                    <?php if (count($transactions) > 0): ?>

						<?php foreach ($transactions as $transaction): ?>

							<div class="transaction" data-id="<?= $transaction['borrowed_id'] ?>">
								<div>
									<img src="<?= isImageUrl($transaction['image']) ? $transaction['image'] : getFile('public/img/' . $transaction['image']) ?>" alt="">
								</div>
								<div>
									<strong><?= $transaction['first_name'] . ' ' . $transaction['last_name'] ?></strong><br>
									Book Title: <?= $transaction['title'] ?><br>
									Book Author: <?= $transaction['author'] ?><br>
									ISBN: <?= $transaction['ISBN'] ?><br>
									<?php if($transaction['is_overdue']): ?>
										<div class="overdue">Overdue</div>
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
				<input type="text" id="search-input" name="search" data-route="<?= routeTo('/returns/search-returned-books') ?>" onkeyup="booksTable.search(this, this.value)">
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
					<div class="book-image">
						<img src="${row.Image}">
					</div>
					<div class="book-details">
						<strong>${row.Title}</strong><br>
						by ${row.Author}<br>
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
					<button type="button" class="button act-remove danger" onclick="showAlert(event)"><i class="fa-regular fa-trash" style:"font-weight: 700;"></i></button>
				</form>
            `;
        }
    });

    booksTable.renderTable(1);
</script>