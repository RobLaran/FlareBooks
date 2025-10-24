<div class="borrowers-page">
	<div class="table-container">
        <!-- If create another table change this -->
        <div class="row one">
            <a href="<?= routeTo("/borrowers/add") ?>" class="button default"><?= "Add Borrower" ?></a>

            <div class="filter-container">
                <label for="date-column-borrowers">Filter by:</label>
                <select id="date-column-borrowers">
                    <option value="Date of Birth">Date of Birth</option>
                    <option value="Membership Date">Membership Date</option>
                </select>

                <label for="start-date-borrowers">From:</label>
                <input type="date" id="start-date-borrowers">

                <label for="end-date-borrowers">To:</label>
                <input type="date" id="end-date-borrowers">

                <button id="apply-filter-borrowers">Filter</button>
                <button id="clear-filter-borrowers">Clear</button>
            </div>
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
				<label for="search-borrowers-input">Search:</label>

                <!-- If create another table change this -->
				<input type="text" class="search-input" id="search-borrowers-input" name="search" oninput="borrowersTable.noFetchSearch(this.value)">
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
<script src="<?= getFile("public/js/modal.js") ?>"></script>
<script>
    // Modal
    const modal = createModal();
    modal.route = "<?= routeTo('/borrowers/search-history') ?>";

    function openModal() {
        const form = modal.borrowerInfo();
        modal.open(form, 'Borrower Info');
    }

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
                    <a href="<?= routeTo("/borrowers/edit/") ?>${row.ID}" class="button act-edit safe">
                        <img src="<?= getFile("public/img/edit.png") ?>">
                    </a>
                    <form class="delete-borrower-form" action="<?= routeTo('/borrowers/delete/') ?>${row.ID}" method="POST">
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="button" class="button act-remove danger" onclick="showAlert(event)">
                            <img src="<?= getFile("public/img/delete.png") ?>">
                        </button>
                    </form>
                </div>
            `;
        },
        modal: modal
        
    });

    // Change table if needed
    borrowersTable.renderTable(1);

    // Date range filter
    borrowersTable.initDateFilter({
        columnSelectId: "date-column-borrowers",
        startDateId: "start-date-borrowers",
        endDateId: "end-date-borrowers",
        applyBtnId: "apply-filter-borrowers",
        clearBtnId: "clear-filter-borrowers"
    });

    borrowersTable.reset();
</script>