<div class="user-dashboard-page">
    <div class="dashboard-container">
        <div class="dashboard-header">
            <h1>📚 Welcome to FlareBooks</h1>
        </div>

        <!-- Statistics Cards -->
        <div class="stats-grid">
            <div class="stat-card books">
                <div class="stat-icon">📚</div>
                <div class="stat-value">1,247</div>
                <div class="stat-label">Total Books</div>
            </div>

            <div class="stat-card borrowed">
                <div class="stat-icon">📖</div>
                <div class="stat-value">342</div>
                <div class="stat-label">Books Borrowed</div>
            </div>

            <div class="stat-card borrowers">
                <div class="stat-icon">👥</div>
                <div class="stat-value">156</div>
                <div class="stat-label">Active Members</div>
            </div>

            <div class="stat-card overdue">
                <div class="stat-icon">⚠️</div>
                <div class="stat-value">23</div>
                <div class="stat-label">Overdue Books</div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="content-grid">

            <!-- Quick Actions -->
            <div class="card">
                <div class="card-header">
                    <div class="card-icon">⚡</div>
                    <h2 class="card-title">Quick Actions</h2>
                </div>

                <button class="quick-action-btn">
                    <span class="quick-action-icon">➕</span>
                    <span>Borrow Book</span>
                </button>

                <button class="quick-action-btn secondary">
                    <span class="quick-action-icon">↩️</span>
                    <span>Return Book</span>
                </button>

                <button class="quick-action-btn" style="background: linear-gradient(135deg, #667eea, #764ba2);">
                    <span class="quick-action-icon">👤</span>
                    <span>Add Member</span>
                </button>

                <button class="quick-action-btn secondary"
                    style="background: linear-gradient(135deg, #f093fb, #f5576c);">
                    <span class="quick-action-icon">📚</span>
                    <span>Add New Book</span>
                </button>

                <div
                    style="margin-top: 30px; padding: 20px; background: linear-gradient(135deg, #ffecd2, #fcb69f); border-radius: 15px; text-align: center;">
                    <div style="font-size: 2rem; margin-bottom: 10px;">📊</div>
                    <div style="font-weight: bold; color: #333; margin-bottom: 5px;">Library Status</div>
                    <div style="font-size: 0.9rem; color: #666;">All systems operational</div>
                    <span class="badge success" style="margin-top: 10px; display: inline-block;">Active</span>
                </div>
            </div>
        </div>

    </div>
</div>