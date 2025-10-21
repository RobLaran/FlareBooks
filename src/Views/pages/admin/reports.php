<div class="reports-page">
    <div class="reports-filter">
        <div class="filter-group">
            <label for="report-type">Report Type</label>
            <select id="report-type">
                <option value="">-- Select Report Type --</option>
                <option value="books">Books</option>
                <option value="genres">Genres</option>
                <option value="borrowed">Borrowed Books</option>
                <option value="borrowers">Borrowers</option>
            </select>
        </div>

        <div class="filter-group">
            <label for="from-date">From</label>
            <input type="date" id="from-date">
        </div>

        <div class="filter-group">
            <label for="to-date">To</label>
            <input type="date" id="to-date">
        </div>

        <button id="generate-report" class="button default">Generate Report</button>
    </div>

    <div class="reports-result">
        <h2 id="report-title"></h2>
        <table id="reports-table" class="table-zebra">
            <thead></thead>
            <tbody></tbody>
        </table>

        <div class="no-data" id="no-data" style="display: block;">No data available for this report.</div>
    </div>

    <div class="reports-actions">
        <button id="print-report" class="button">🖨️ Print Report</button>
    </div>
</div>

<script>

    document.getElementById("generate-report").addEventListener("click", async () => {
        const type = document.getElementById("report-type").value;
        const from = document.getElementById("from-date").value;
        const to = document.getElementById("to-date").value;
        const table = document.getElementById("reports-table");
        const noData = document.getElementById("no-data");
        const title = document.getElementById("report-title");

        if (!type) return alert("Please select a report type first.");

        title.textContent = type.charAt(0).toUpperCase() + type.slice(1) + " Report";

        const response = await fetch(`<?= routeTo('/admin/reports/generate') ?>`, {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ type, from, to })
        });

        const result = await response.json();

        if (result.data.length === 0) {
            table.querySelector("thead").innerHTML = "";
            table.querySelector("tbody").innerHTML = "";
            noData.style.display = "block";
            return;
        }

        noData.style.display = "none";

        const headers = Object.keys(result.data[0]);
        table.querySelector("thead").innerHTML = `
            <tr>${headers.map(h => `<th>${h}</th>`).join("")}</tr>
        `;
        table.querySelector("tbody").innerHTML = result.data
            .map(row => `
                <tr>${headers.map(h => `<td>${row[h]}</td>`).join("")}</tr>
            `).join("");
    });

    document.getElementById("print-report").addEventListener("click", () => {
        const reportSection = document.querySelector(".reports-result").innerHTML;
        const printWindow = window.open("", "_blank");
        printWindow.document.write(`
            <html>
                <head>
                    <title>Library Report</title>
                    <style>
                        body { font-family: Arial, sans-serif; padding: 20px; }
                        h2 { text-align: center; margin-bottom: 20px; }
                        table { width: 100%; border-collapse: collapse; }
                        th, td { border: 1px solid #333; padding: 8px; text-align: left; }
                        th { background: #f2f2f2; }
                    </style>
                </head>
                <body>
                    ${reportSection}
                    <script>window.onload = () => window.print();<\/script>
                </body>
            </html>
        `);
        printWindow.document.close();
    });

</script>