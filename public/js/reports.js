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
    window.print();
});
