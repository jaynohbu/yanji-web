<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
  <meta http-equiv="Pragma" content="no-cache">
  <meta http-equiv="Expires" content="0">
  <title>Payment History - Yanji Restaurant</title>
  <script src="config.js"></script>
  <script src="auth.js"></script>
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body {
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
      background: #1a1a2e;
      color: #eee;
      min-height: 100vh;
    }
    .container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 20px;
    }
    header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 20px 0;
      border-bottom: 1px solid #333;
      margin-bottom: 30px;
    }
    header h1 { font-size: 24px; color: #fff; }
    .header-right {
      display: flex;
      align-items: center;
      gap: 20px;
    }
    .btn {
      padding: 10px 20px;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      font-size: 14px;
      transition: all 0.2s;
    }
    .btn-primary {
      background: #4ecdc4;
      color: #1a1a2e;
      font-weight: bold;
    }
    .btn-primary:hover { background: #3dbdb5; }
    .btn-secondary {
      background: #333;
      color: #fff;
      font-size: 12px;
      padding: 8px 12px;
    }
    .btn-secondary:hover { background: #444; }
    .btn-danger {
      background: #e74c3c;
      color: #fff;
      font-size: 12px;
      padding: 6px 12px;
    }
    .btn-danger:hover { background: #c0392b; }
    .btn-small {
      padding: 6px 10px;
      font-size: 11px;
    }
    .btn-back {
      background: #555;
      color: #fff;
      padding: 8px 16px;
      text-decoration: none;
      border-radius: 6px;
      font-size: 13px;
    }
    .btn-back:hover { background: #666; }

    /* Controls Section */
    .controls-section {
      background: #252542;
      border-radius: 12px;
      padding: 20px;
      margin-bottom: 30px;
    }
    .controls-row {
      display: grid;
      grid-template-columns: 1fr 150px 150px;
      gap: 15px;
      align-items: flex-end;
    }
    @media (max-width: 600px) {
      .controls-row {
        grid-template-columns: 1fr;
      }
    }
    .search-group {
      display: flex;
      flex-direction: column;
    }
    .search-group label {
      font-size: 12px;
      color: #888;
      margin-bottom: 6px;
    }
    .search-group input, .search-group select {
      padding: 10px 12px;
      background: #1a1a2e;
      border: 1px solid #333;
      border-radius: 6px;
      color: #fff;
      font-size: 14px;
    }
    .search-group input:focus, .search-group select:focus {
      outline: none;
      border-color: #4ecdc4;
    }

    /* Payments Table */
    .payments-table {
      width: 100%;
      border-collapse: collapse;
      background: #252542;
      border-radius: 12px;
      overflow: hidden;
    }
    .payments-table thead {
      background: #1a1a2e;
      border-bottom: 2px solid #333;
    }
    .payments-table th {
      padding: 15px;
      text-align: left;
      font-weight: 600;
      color: #aaa;
      font-size: 12px;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }
    .payments-table td {
      padding: 15px;
      border-bottom: 1px solid #333;
      color: #ddd;
    }
    .payments-table tbody tr:hover {
      background: #2d2d4d;
    }
    .payments-table tbody tr:last-child td {
      border-bottom: none;
    }

    .payment-id {
      color: #4ecdc4;
      font-weight: 600;
      font-family: monospace;
      font-size: 12px;
    }
    .payment-amount {
      font-weight: bold;
      color: #fff;
      font-size: 14px;
    }
    .payment-status {
      display: inline-block;
      padding: 4px 8px;
      border-radius: 4px;
      font-size: 11px;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }
    .status-completed {
      background: #2ecc71;
      color: #1a1a2e;
    }
    .status-refunded {
      background: #95a5a6;
      color: #1a1a2e;
    }
    .status-pending {
      background: #f39c12;
      color: #1a1a2e;
    }

    .payment-method {
      font-size: 12px;
      color: #aaa;
    }
    .payment-date {
      font-size: 12px;
      color: #888;
    }

    .actions {
      display: flex;
      gap: 8px;
    }

    /* Modal Styles */
    .modal {
      display: none;
      position: fixed;
      z-index: 1000;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.8);
    }
    .modal.show {
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .modal-content {
      background: #252542;
      border-radius: 12px;
      padding: 30px;
      width: 90%;
      max-width: 500px;
      position: relative;
      border: 1px solid #333;
    }
    .modal-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
      padding-bottom: 15px;
      border-bottom: 1px solid #333;
    }
    .modal-header h2 {
      font-size: 18px;
      color: #fff;
    }
    .close-btn {
      background: none;
      border: none;
      color: #888;
      font-size: 24px;
      cursor: pointer;
      padding: 0;
      width: 24px;
      height: 24px;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .close-btn:hover {
      color: #fff;
    }

    .modal-body {
      margin-bottom: 20px;
    }
    .form-group {
      margin-bottom: 20px;
    }
    .form-group label {
      display: block;
      margin-bottom: 8px;
      color: #aaa;
      font-size: 12px;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }
    .form-group input, .form-group textarea, .form-group select {
      width: 100%;
      padding: 10px 12px;
      background: #1a1a2e;
      border: 1px solid #333;
      border-radius: 6px;
      color: #fff;
      font-size: 14px;
      font-family: inherit;
    }
    .form-group input:focus, .form-group textarea:focus, .form-group select:focus {
      outline: none;
      border-color: #4ecdc4;
    }
    .form-group textarea {
      resize: vertical;
      min-height: 100px;
    }

    .modal-footer {
      display: flex;
      justify-content: flex-end;
      gap: 10px;
      padding-top: 20px;
      border-top: 1px solid #333;
    }

    .loading {
      text-align: center;
      padding: 40px 20px;
      color: #888;
    }
    .spinner {
      border: 3px solid #333;
      border-top: 3px solid #4ecdc4;
      border-radius: 50%;
      width: 30px;
      height: 30px;
      animation: spin 1s linear infinite;
      margin: 20px auto;
    }
    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }

    .empty-state {
      text-align: center;
      padding: 60px 20px;
      color: #666;
    }
    .empty-state p {
      font-size: 16px;
      margin-bottom: 10px;
    }
    .empty-state .icon {
      font-size: 48px;
      margin-bottom: 20px;
    }

    .summary-section {
      background: #252542;
      border-radius: 12px;
      padding: 20px;
      margin-bottom: 30px;
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 20px;
    }
    .summary-card {
      background: #1a1a2e;
      padding: 15px;
      border-radius: 8px;
      border-left: 3px solid #4ecdc4;
    }
    .summary-label {
      font-size: 12px;
      color: #888;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      margin-bottom: 5px;
    }
    .summary-value {
      font-size: 20px;
      font-weight: bold;
      color: #4ecdc4;
    }
  </style>
</head>
<body>
  <div class="container">
    <header>
      <h1>💳 Payment History</h1>
      <div class="header-right">
        <a href="orders-dashboard.php" class="btn-back">← Back to Orders</a>
        <button class="btn btn-primary" onclick="location.reload()">🔄 Refresh</button>
      </div>
    </header>

    <!-- Summary Section -->
    <div class="summary-section" id="summarySection">
      <div class="summary-card">
        <div class="summary-label">Total Transactions</div>
        <div class="summary-value" id="totalCount">0</div>
      </div>
      <div class="summary-card">
        <div class="summary-label">Total Revenue</div>
        <div class="summary-value" id="totalRevenue">$0.00</div>
      </div>
      <div class="summary-card">
        <div class="summary-label">Completed</div>
        <div class="summary-value" id="completedCount">0</div>
      </div>
      <div class="summary-card">
        <div class="summary-label">Refunded</div>
        <div class="summary-value" id="refundedCount">0</div>
      </div>
    </div>

    <!-- Controls Section -->
    <div class="controls-section">
      <div class="controls-row">
        <div class="search-group">
          <label for="searchInput">Search by Order ID or Customer</label>
          <input type="text" id="searchInput" placeholder="Search payments...">
        </div>
        <div class="search-group">
          <label for="statusFilter">Filter by Status</label>
          <select id="statusFilter">
            <option value="">All Statuses</option>
            <option value="completed">Completed</option>
            <option value="refunded">Refunded</option>
            <option value="pending">Pending</option>
          </select>
        </div>
        <button class="btn btn-secondary" onclick="paymentDashboard.clearFilters()">Clear Filters</button>
      </div>
    </div>

    <!-- Payments Table -->
    <div id="loadingIndicator" class="loading" style="display: none;">
      <div class="spinner"></div>
      <p>Loading payments...</p>
    </div>

    <table class="payments-table" id="paymentsTable" style="display: none;">
      <thead>
        <tr>
          <th>Paid By</th>
          <th>Amount</th>
          <th>Remaining Balance</th>
          <th>Status</th>
          <th>Method</th>
          <th>Date</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody id="paymentsBody">
      </tbody>
    </table>

    <div id="emptyState" class="empty-state" style="display: none;">
      <div class="icon">💳</div>
      <p>No payments found</p>
    </div>
  </div>

  <script src="payments-dashboard.js"></script>
</body>
</html>
