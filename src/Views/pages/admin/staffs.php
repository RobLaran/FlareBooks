<div class="staffs-page">
	<div class="table-container">
        <div class="row one">
            <button class="button default" id="open-add-staff-form">Add Staff</button>
        </div>

        <div class="row two">
            <div class="select-entries">
                <span>
                    Showing 
                    <form method="GET" id="entries-form" style="display:inline;">
                        <select name="limit" id="entries-selection" onchange="staffsTable.changeEntries(this.value)">
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
				<label for="search-staff-input">Search:</label>
				<input type="text" class="search-input" id="search-staff-input" name="search" oninput="staffsTable.noFetchSearch(this.value)">
            </div>

        </div>

        <div class="table-container row three">
            <table class="table-zebra">
                <thead>
                    <tr class="table-heading" id="staffs-table-heading">
                    </tr>
                </thead>

                <tbody class="table-body" id="staffs-table">

                </tbody>
            </table>
        </div>

        <div class="entries-pagination-container row four">
            <div class="showed-entries">
            </div>

            <ul class="pagination" id="staffs-pagination">
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
    document.getElementById('open-add-staff-form').addEventListener('click', () => {
        const form = modal.addStaffForm('<?= routeTo('/admin/staffs/add') ?>');
        modal.open(form, 'Add New Staff');
        modal.imagePreviewAction();
    });

    function openEditForm(staff) {
        const form = modal.editStaffForm('<?= routeTo('/admin/staffs/update/') ?>' + staff['id'], staff);
        modal.open(form, 'Edit Staff');
        modal.imagePreviewAction();
    }

    // Table
    const staffsData = <?php echo json_encode([$staffs]); ?>;

    const staffsTable = createDynamicTable({
        data: staffsData[0],
        containerId: "staffs-table",
		headingId: "staffs-table-heading",
        paginationId: "staffs-pagination",
        itemsPerPage: 5,
        tableVar: "staffsTable",
		columns: {
            "Image": (image) => 
                `<img class="staff-image" src="${image}" width="40" height="50">`  
        },
		sortable: [],
		hidden: [ "id", "Password" ],
        actions: (row) => {
            return `
                <div class="action-buttons">
                    <button class="button" id="open-edit-staff-form" onclick='openEditForm(${JSON.stringify(row)})'>
                        <img src="<?= getFile("public/img/edit.png") ?>">
                    </button>

                    <form class="delete-staff-form" action="<?= routeTo('/admin/staffs/delete/') ?>${row['id']}" method="POST">
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="button" class="button act-remove danger" onclick="showAlert(event)">
                            <img src="<?= getFile("public/img/delete.png") ?>">
                        </button>
                    </form>
                </div>
            `;
        }

    });

    staffsTable.renderTable(1);
</script>