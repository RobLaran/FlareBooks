function createDynamicTable(config) {
    const { data, containerId, headingId, paginationId, itemsPerPage = 5, actions = null, tableVar, columns = {}, sortable = [], hidden = [] , modal = null} = config;
    const savedFilters = localStorage.getItem(`${tableVar}-filters`);
    const savedSort = localStorage.getItem(`${tableVar}-sort`);

    let filters = {
        search: null,      // string
        column: null,       // { column, value, key }
        dateRange: null     // { column, start, end }
    };

    let currentPage = 1;
    let filteredData = [...data];
    let rowsPerPage = itemsPerPage;

    let sortDirection = {}; // store sorting state for each column

    if (savedFilters) {
        try {
            filters = JSON.parse(savedFilters);
            applyFilters(); // apply saved filters automatically
        } catch (e) {
            console.error("Error loading saved filters:", e);
        }
    }

    if (savedSort) sortDirection = JSON.parse(savedSort);


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
            heading.setAttribute('id', header);

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
                        filteredData = [...filteredData]; // reset to original order
                        applyFilters();
                    }

                    renderTable(1);
                    localStorage.setItem(`${tableVar}-sort`, JSON.stringify(sortDirection));
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

            tableRow.addEventListener('click', function (event) {
                const target = event.target; // get the clicked element

                // Check if a button was clicked (e.g., edit or delete)
                if (target.classList.contains('button') || target.closest('.button')) {
                    event.stopPropagation(); // stop the click from bubbling to the row
                    return;
                }

                // Otherwise, open the modal for the row
                modal.open(modal.borrowerInfo(row), 'Borrower Info');
            });
            
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

            // Local search mode
            if (!route) {
                filters.search = query;
                applyFilters();
                return;
            }

            // Remote (server-side) search
            if (query === "") {
                filters.search = null;
                applyFilters();
                return;
            }

            fetch(`${route}?q=` + encodeURIComponent(query))
                .then(response => response.json())
                .then(results => {
                    filteredData = results;
                    renderTable(1);
                })
                .catch(error => console.error("Error fetching search results:", error));

        } catch (error) {
            console.log("Search error:", error);
        }
    }

    function noFetchSearch(query) {
        filters.search = query?.trim().toLowerCase() || null;
        applyFilters();
    }



    function initColumnFilter(config) {
        const { columnSelectId, column, key = null, clearBtnId = null } = config;

        const columnSelect = document.getElementById(columnSelectId);
        const clearBtn = clearBtnId ? document.getElementById(clearBtnId) : null;

        columnSelect.addEventListener("change", () => {
            filters.column = columnSelect.value === "" ? null : { column, key, value: columnSelect.value };
            applyFilters();
        });

        if (clearBtn) {
            clearBtn.addEventListener("click", () => {
                columnSelect.value = "";
                filters.column = null;
                applyFilters();
            });
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
            filters.dateRange = {
                column: columnSelect.value,
                start: startDate.value ? new Date(startDate.value) : null,
                end: endDate.value ? new Date(endDate.value) : null
            };
            applyFilters();
        });

        clearBtn.addEventListener("click", () => {
            startDate.value = "";
            endDate.value = "";
            filters.dateRange = null;
            applyFilters();
        });
    }


    function applyFilters() {
        filteredData = [...data].filter(item => {
            let pass = true;

            // 🔹 Helper for nested search
            const flattenValues = obj => {
                let values = [];
                for (const val of Object.values(obj)) {
                    if (val && typeof val === "object") {
                        values = values.concat(flattenValues(val));
                    } else {
                        values.push(String(val));
                    }
                }
                return values;
            };

            // 🔹 Search filter
            if (filters.search && filters.search.trim() !== "") {
                const query = filters.search.toLowerCase();
                const allValues = flattenValues(item);
                const match = allValues.some(val => val.toLowerCase().includes(query));
                if (!match) pass = false;
            }

            // 🔹 Column filter
            if (filters.column && filters.column.value !== "") {
                const { column, key, value } = filters.column;
                const fieldValue = key ? item[key][column] : item[column];
                if (fieldValue != value) pass = false;
            }

            // 🔹 Date range filter
            if (filters.dateRange && filters.dateRange.column) {
                const { column, start, end } = filters.dateRange;
                const date = new Date(item[column]);
                if (isNaN(date)) return false;
                if (start && date < start) pass = false;
                if (end && date > end) pass = false;
            }

            return pass;
        });

        renderTable(1);
        localStorage.setItem(`${tableVar}-filters`, JSON.stringify(filters));
    }

    function reset() {
        filters = { search: null, column: null, dateRange: null };
        filteredData = [...data];
        localStorage.removeItem(`${tableVar}-filters`);
        renderTable(1);
    }

    return {
        renderTable,
        changeEntries,
        search,
        noFetchSearch,
        initColumnFilter,
        initDateFilter,
        reset
    };
}
