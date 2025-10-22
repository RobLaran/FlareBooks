<div class="books-page">
	<div class="table-container">
        <div class="row one">
            <a href="<?= routeTo("/books/add") ?>" class="button default"><?= "Add Book" ?></a>

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
				<label for="search-books-input">Search:</label>
				<input type="text" class="search-input" id="search-books-input" name="search" oninput="booksTable.noFetchSearch(this.value)">
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
    // Table
    const booksData = <?php echo json_encode([$books]); ?>;

    const booksTable = createDynamicTable({
        data: booksData[0],
        containerId: "books-table",
		headingId: "books-table-heading",
        paginationId: "books-pagination",
        itemsPerPage: 5,
        tableVar: "booksTable",
		columns: {
            "Image": (image) => 
                `<img class="book-image" src="${image}">`                
            ,
			"Status": (status) => 
				`<span class="status ${status == "Available" ? "online" : "offline"}">${status}</span>`
		},
		sortable: ["ISBN", "Author", "Title", "Genre", "Quantity", "Status"],
		hidden: ["id"],
        actions: (row) => {
            return `
                <div class="action-buttons">
                    <a href="<?= routeTo("/books/edit/") ?>${row['ISBN']}" class="button act-edit safe">
                        <img src="<?= getFile("public/img/edit.png") ?>">
                    </a>
                    <form class="delete-book-form" action="<?= routeTo('/books/delete/') ?>${row["ISBN"]}" method="POST">
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="button" class="button act-remove danger" onclick="showAlert(event)">
                            <img src="<?= getFile("public/img/delete.png") ?>">
                        </button>
                    </form>
                </div>
            `;
        }
    });

    booksTable.renderTable(1);

    // Genre filter
	booksTable.initColumnFilter({
		columnSelectId: "genre-filter",
		column: "Genre",
		clearBtnId: "clear-filter-genres"
	});
</script>