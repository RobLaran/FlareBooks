<div class="genres-page">
	<div class="table-container">
        <div class="row one">
            <!-- <form action="">
                <div class="inputs-container">
                    <div class="genre-input-container">
                        <label for="genre-input">Genre: </label>
                        <input type="text" name="genre" id="genre-input">
                    </div>
                    <div class="description-input-container">
                        <label for="description-input">Description: </label> 
                        <textarea name="description" id="description-inpu"></textarea>
                    </div>
                </div>
            </form> -->
            <button class="button default" id="open-add-genre-form">Add Genre</button>
        </div>

        <div class="row two">
            <div class="select-entries">
                <span>
                    Showing 
                    <form method="GET" id="entries-form" style="display:inline;">
                        <select name="limit" id="entries-selection" onchange="genresTable.changeEntries(this.value)">
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
				<input type="text" id="search-input" name="search" data-route="<?= routeTo('/admin/books/search-books') ?>" onkeyup="genresTable.search(this, this.value)">
            </div>

        </div>

        <div class="table-container row three">
            <table class="table-zebra">
                <thead>
                    <tr class="table-heading" id="genres-table-heading">
                    </tr>
                </thead>

                <tbody class="table-body" id="genres-table">

                </tbody>
            </table>
        </div>

        <div class="entries-pagination-container row four">
            <div class="showed-entries">
            </div>

            <ul class="pagination" id="genres-pagination">
            </ul>
        </div>


    </div>
</div>

<script src="<?= getFile("public/js/table.js") ?>"></script>
<script src="<?= getFile("public/js/modal.js") ?>"></script>
<script>
    // Modal
    const modal = createModal();

    // Open modal when clicking a button
    document.getElementById('open-add-genre-form').addEventListener('click', () => {
        const form = modal.addGenreForm('<?= routeTo('/admin/genres/add') ?>');
        modal.open(form, 'Add New Genre');
    });

    // Table
    const genresData = <?php echo json_encode([$genres]); ?>;

    const genresTable = createDynamicTable({
        data: genresData[0],
        containerId: "genres-table",
		headingId: "genres-table-heading",
        paginationId: "genres-pagination",
        itemsPerPage: 5,
        tableVar: "genresTable",
		columns: {
            "Status": (status) => 
				`<span class="status ${status ? "online" : "offline"}">${status ? "Active" : "Inactive"}</span>`
        },
		sortable: [ "Name", "Description", "Status" ],
		hidden: [ "id" ],
        actions: (row) => {
            return `
                <div class="action-buttons">
                    <a href="<?= routeTo("/admin/genres/edit/") ?>${row['id']}" class="button act-edit safe">
                        <img src="<?= getFile("public/img/edit.png") ?>">
                    </a>
                    <form class="delete-genre-form" action="<?= routeTo('/admin/genres/delete/') ?>${row["id"]}" method="POST">
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="button" class="button act-remove danger" onclick="showAlert(event)">
                            <img src="<?= getFile("public/img/delete.png") ?>">
                        </button>
                    </form>
                </div>
            `;
        }
    });

    genresTable.renderTable(1);
</script>