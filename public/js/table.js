function createDynamicTable(config) {
    const { data, containerId, headingId, paginationId, itemsPerPage = 5, actions = null, tableVar, columns = {}, sortable = [], hidden = [] } = config;

    let currentPage = 1;
    let filteredData = [...data];
    let rowsPerPage = itemsPerPage;

    let sortDirection = {}; // store sorting state for each column

    function renderTable(page) {
        if (!data || data.length === 0) {
            document.getElementById(containerId).innerHTML = "<p>No records found</p>";
            return;
        }

        currentPage = page;

        let start, end, pageItems;

        if (rowsPerPage === "all") {
            start = 0;
            end = filteredData.length;
        } else {
            start = (page - 1) * rowsPerPage;
            end = start + rowsPerPage;
        }

        pageItems = filteredData.slice(start, end);

        // 🔹 Get table headers dynamically
        let headers = Object.keys(data[0]);
        let tableBody = document.getElementById(containerId);
        let tableHeading = document.getElementById(headingId);

        tableBody.innerHTML = "";
        tableHeading.innerHTML = "";

        headers.forEach(header => {
            if(hidden.includes(header)) return;

            const heading = document.createElement('th');
            heading.style.cursor = "pointer";

            // 🔹 Initialize sort direction if not set
            if (!(header in sortDirection)) {
                sortDirection[header] = null; // null = unsorted, true = asc, false = desc
            }

            function updateHeaderText() {
                if (sortDirection[header] === true) {
                    heading.innerHTML = header + " ▲";
                } else if (sortDirection[header] === false) {
                    heading.innerHTML = header + " ▼";
                } else {
                    heading.innerHTML = header + " ▲▼"; // unsorted
                }
            }

            if(sortable.includes(header)) {
                updateHeaderText()

                heading.addEventListener("click", () => {
                    // toggle ASC -> DESC -> UNSORTED
                    if (sortDirection[header] === null) {
                        sortDirection[header] = true; // ASC
                    } else if (sortDirection[header] === true) {
                        sortDirection[header] = false; // DESC
                    } else {
                        sortDirection[header] = null; // reset to UNSORTED
                    }

                    if (sortDirection[header] !== null) {
                        filteredData.sort((a, b) => {
                            let valA = a[header];
                            let valB = b[header];

                            // 🧠 Detect if the values look like a human-readable date (e.g. "October 4, 2025")
                            const isReadableDate = /[a-zA-Z]+ \d{1,2}, \d{4}/.test(valA);

                            if (isReadableDate) {
                                // Parse "October 4, 2025" into Date objects
                                valA = new Date(valA);
                                valB = new Date(valB);

                                console.log(valA);
                                console.log(valB);
                                
                            } else if (!isNaN(parseFloat(valA)) && !isNaN(parseFloat(valB))) {
                                // If numeric, compare as numbers
                                valA = parseFloat(valA);
                                valB = parseFloat(valB);
                            }

                            if (valA < valB) return sortDirection[header] ? -1 : 1;
                            if (valA > valB) return sortDirection[header] ? 1 : -1;
                            return 0;
                        });
                    } else {
                        filteredData = [...data]; // reset to original order
                    }

                    renderTable(1);
                });

            } else {
                heading.innerHTML = header;
            }
            
            tableHeading.append(heading);
        });


        if (actions) {
            const heading = document.createElement('th');
            heading.innerHTML = 'Actions';
            tableHeading.append(heading);
        }

        // 🔹 Rows
        pageItems.forEach((row, index) => {
            const tableRow = document.createElement('tr');
            headers.forEach(header => {
                if(hidden.includes(header)) return;
                const tableData = document.createElement('td');

                if (columns[header]) {
                    tableData.innerHTML = columns[header](row[header]);
                } else {
                    tableData.innerHTML = row[header];
                }

                tableRow.append(tableData);
            });
            
            if (actions) {
                const tableData = document.createElement('td');
                tableData.innerHTML = actions(row, index);
                tableRow.append(tableData);
            }
            
            tableBody.append(tableRow);
        });

        // 🔹 update "showed entries"
        const showingStart = filteredData.length === 0 ? 0 : start + 1;
        const showingEnd = Math.min(end, filteredData.length);
        document.querySelector(".showed-entries").innerText =
            `Showing ${showingStart} to ${showingEnd} of ${filteredData.length} entries`;

        renderPagination();
    }

    function renderPagination() {
        const totalPages = rowsPerPage === "all" ? 1 : Math.ceil(filteredData.length / rowsPerPage);
        let buttons = "";

        for (let i = 1; i <= totalPages; i++) {
            buttons += `<li class="page ${i === currentPage ? "current" : ""}" >
                            <button onclick="${tableVar}.renderTable(${i})">${i}</button>
                        </li> `;
        }

        document.getElementById(paginationId).innerHTML = buttons;
    }

    function changeEntries(value) {
        rowsPerPage = value === "all" ? "all" : parseInt(value);
        renderTable(1);
    }

    function search(input, query) {
       try {
         const route = input.dataset.route;

         if(query === "") {
            reset();
            return;
         }

        fetch(`${route}?q=` + encodeURIComponent(query))
			.then(response => response.json())
			.then(results => {
                filteredData = results;
                renderTable(1);
            });
            
       } catch(error) {
            console.log("error");
       }
    }

    function initDateFilter(config) {
        const {
            columnSelectId,
            startDateId,
            endDateId,
            applyBtnId,
            clearBtnId
        } = config;

        const columnSelect = document.getElementById(columnSelectId);
        const startDate = document.getElementById(startDateId);
        const endDate = document.getElementById(endDateId);
        const applyBtn = document.getElementById(applyBtnId);
        const clearBtn = document.getElementById(clearBtnId);

        applyBtn.addEventListener("click", () => {
            const col = columnSelect.value;
            const start = startDate.value ? new Date(startDate.value) : null;
            const end = endDate.value ? new Date(endDate.value) : null;


            filteredData = [...data].filter(item => {
                const date = new Date(item[col]);
                if (isNaN(date)) return false;
                if (start && date < start) return false;
                if (end && date > end) return false;
                return true;
            });

            renderTable(1);
        });

        clearBtn.addEventListener("click", () => {
            startDate.value = "";
            endDate.value = "";
            filteredData = [...data];
            renderTable(1);
        });
    }

    function reset() {
        filteredData = [...data];
        renderTable(1);
    }


    return {
        renderTable,
        changeEntries,
        search,
        initDateFilter
    };
}
