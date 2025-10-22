<div class="dashboard-page">
    <div class="summary-cards">
        <div class="card">
            <h3>Total Books</h3>
            <p id="total-books">0</p>
        </div>
        <div class="card">
            <h3>Total Borrowers</h3>
            <p id="total-borrowers">0</p>
        </div>
        <div class="card">
            <h3>Borrowed (Active)</h3>
            <p id="total-borrowed">0</p>
        </div>
        <div class="card">
            <h3>Active Staff</h3>
            <p id="total-staff">0</p>
        </div>
    </div>

    <div class="charts-section">
        <div class="chart-card">
            <h3>Books by Status</h3>
            <canvas id="booksByStatus"></canvas>
        </div>
        <div class="chart-card">
            <h3>Monthly Borrowed (Last 6 months)</h3>
            <canvas id="monthlyBorrowed"></canvas>
        </div>
    </div>

    <div class="recent-activity">
        <h3>Recent Borrowed Books</h3>
        <table id="recent-table">
            <thead>
                <tr>
                    <th>Book Title</th>
                    <th>Borrower</th>
                    <th>Borrowed At</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <tr><td colspan="4">Loading...</td></tr>
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
async function loadDashboard() {
    try {
        const res = await fetch('<?= routeTo("/admin/reports/stats") ?>', { credentials: 'same-origin' });
        const json = await res.json();
        if (!json.success) {
            console.error(json.message || 'Failed to load stats');
            return;
        }

        const totals = json.totals || json.totals === undefined ? json.totals : json.totals;
        // Backwards compatibility: our controller returns 'totals' key
        const data = json;

        // Update summary cards
        document.getElementById('total-books').textContent = data.totals.total_books ?? 0;
        document.getElementById('total-borrowers').textContent = data.totals.total_borrowers ?? 0;
        document.getElementById('total-borrowed').textContent = data.totals.total_borrowed ?? 0;
        document.getElementById('total-staff').textContent = data.totals.total_staff ?? 0;

        // Recent table
        const tbody = document.querySelector('#recent-table tbody');
        tbody.innerHTML = '';
        if (data.recent.length === 0) {
            tbody.innerHTML = '<tr><td colspan="4">No recent borrow records.</td></tr>';
        } else {
            data.recent.forEach(row => {
                const tr = document.createElement('tr');
                const returned = row['Borrowed Date'] ? 'Borrowed' : 'Returned';
                tr.innerHTML = `
                    <td>${row["Book Title"] ?? ''}</td>
                    <td>${row['Borrower'] ?? ''}</td>
                    <td>${row['Borrowed Date'] ?? ''}</td>
                    <td><span class="status ${returned === 'Returned' ? 'active' : 'pending'}">${returned}</span></td>
                `;
                tbody.appendChild(tr);
            });
        }

        // Books by status chart
        const booksByStatus = data.booksByStatus || [];
        const labels = booksByStatus.map(r => r.status || 'Unknown');
        const counts = booksByStatus.map(r => parseInt(r.count || 0));
        const ctx1 = document.getElementById('booksByStatus').getContext('2d');
        if (window._booksByStatusChart) window._booksByStatusChart.destroy();
        window._booksByStatusChart = new Chart(ctx1, {
            type: 'pie',
            data: { labels, datasets: [{ data: counts }] },
            options: { responsive: true }
        });

        // Monthly borrowed chart
        const monthly = data.monthlyBorrowed || [];
        const months = monthly.map(r => r.month);
        const borrowedCounts = monthly.map(r => parseInt(r.count || 0));
        const ctx2 = document.getElementById('monthlyBorrowed').getContext('2d');
        if (window._monthlyChart) window._monthlyChart.destroy();
        window._monthlyChart = new Chart(ctx2, {
            type: 'bar',
            data: {
                labels: months,
                datasets: [{ label: 'Borrowed', data: borrowedCounts }]
            },
            options: { scales: { y: { beginAtZero: true } } }
        });

    } catch (err) {
        console.error('Dashboard load failed', err);
    }
}

// Load on start
loadDashboard();
</script>

