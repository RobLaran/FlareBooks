<div class="borrowers-page">
	<div class="table-container">
        <!-- If create another table change this -->
        <div class="row one">
            <a href="<?= routeTo("/borrowers/add") ?>" class="button default"><?= "Add Borrower" ?></a>
        </div>

        <div class="row two">
            <div class="select-entries">
                <span>
                    Showing 
                    <form method="GET" id="entries-form" style="display:inline;">
                        <!-- If create another table change this -->
                        <select name="limit" id="entries-selection" onchange="borrowersTable.changeEntries(this.value)">
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
				<input type="text" id="search-input" name="search" data-route="<?= routeTo('/borrowers/search-borrowers') ?>" onkeyup="borrowersTable.search(this, this.value)">
            </div>

        </div>

        <div class="table-container row three">
            <table class="table-zebra">
                <thead>
                    <!-- If create another table change this headingId -->
                    <tr class="table-heading" id="borrowers-table-heading"> 
                    </tr>
                </thead>

                <!-- If create another table change this containerId -->
                <tbody class="table-body" id="borrowers-table">

                </tbody>
            </table>
        </div>

        <div class="entries-pagination-container row four">
            <div class="showed-entries">
            </div>

            <!-- If create another table change this paginationId -->
            <ul class="pagination" id="borrowers-pagination">
            </ul>
        </div>

    </div>
</div>

<script src="<?= getFile("public/js/table.js") ?>"></script>
<script>
    // Table
    const borrowersData = <?php echo json_encode([$borrowers]); ?>;

    const borrowersTable = createDynamicTable({
        data: borrowersData[0],
        containerId: "borrowers-table",
		headingId: "borrowers-table-heading",
        paginationId: "borrowers-pagination",
        itemsPerPage: 5,
        tableVar: "borrowersTable",
		sortable: ["Code", "First Name", "Last Name", "Email", "Address", "Status", "Date of Birth", "Membership Date"],
        columns: {
            "Status": (status) =>
                `<span class="status ${status == "Active" ? "online" : "offline"}">${status}</span>`
        },
		hidden: ["ID"],
        actions: (row) => {
            return `
                <div class="action-buttons">
                    <a href="<?= routeTo("/borrowers/edit/") ?>${row.ID}" class="button act-edit safe"><i class="fa-solid fa-pen-to-square"></i></a>
                    <form class="delete-borrower-form" action="<?= routeTo('/borrowers/delete/') ?>${row.ID}" method="POST">
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="button" class="button act-remove danger" onclick="showAlert(event)"><i class="fa-regular fa-trash"></i></button>
                    </form>
                </div>
            `;
        }
    });

    // Change table if needed
    borrowersTable.renderTable(1);
</script>