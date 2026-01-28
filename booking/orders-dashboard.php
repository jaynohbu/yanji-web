<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
  <meta http-equiv="Pragma" content="no-cache">
  <meta http-equiv="Expires" content="0">
  <title>Order Management - Yanji Restaurant</title>
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
      max-width: 1000px;
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
    .btn-success {
      background: #2ecc71;
      color: #fff;
      font-size: 12px;
      padding: 6px 12px;
    }
    .btn-success:hover { background: #27ae60; }
    .btn-warning {
      background: #f39c12;
      color: #fff;
      font-size: 12px;
      padding: 6px 12px;
    }
    .btn-warning:hover { background: #e67e22; }
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

    /* Controls Section */
    .controls-section {
      background: #252542;
      border-radius: 12px;
      padding: 20px;
      margin-bottom: 30px;
    }
    .controls-row {
      display: grid;
      grid-template-columns: 1fr 200px 150px;
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
    .search-group input {
      padding: 10px 12px;
      background: #1a1a2e;
      border: 1px solid #333;
      border-radius: 6px;
      color: #fff;
      font-size: 14px;
    }
    .search-group input:focus {
      outline: none;
      border-color: #4ecdc4;
    }
    .checkbox-group {
      display: flex;
      align-items: flex-end;
      gap: 8px;
      height: 42px;
    }
    .checkbox-group input[type="checkbox"] {
      cursor: pointer;
      width: 18px;
      height: 18px;
      accent-color: #4ecdc4;
    }
    .checkbox-group label {
      color: #aaa;
      font-size: 13px;
      cursor: pointer;
      margin: 0;
    }

    /* Orders List */
    .orders-container {
      display: flex;
      flex-direction: column;
      gap: 15px;
    }
    .order-card {
      background: #252542;
      border-radius: 12px;
      padding: 20px;
      border-left: 4px solid #4ecdc4;
    }
    .order-card.completed {
      border-left-color: #2ecc71;
      opacity: 0.8;
    }
    .order-card.cancelled {
      border-left-color: #e74c3c;
      opacity: 0.7;
    }
    .order-header {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      margin-bottom: 15px;
      padding-bottom: 15px;
      border-bottom: 1px solid #333;
    }
    .order-title {
      display: flex;
      flex-direction: column;
      gap: 5px;
    }
    .order-id {
      font-size: 16px;
      font-weight: bold;
      color: #4ecdc4;
    }
    .order-customer {
      font-size: 14px;
      color: #aaa;
    }
    .order-meta {
      display: flex;
      gap: 15px;
      align-items: center;
    }
    .badge {
      display: inline-block;
      padding: 4px 10px;
      border-radius: 20px;
      font-size: 11px;
      font-weight: bold;
    }
    .badge-platform {
      background: #333;
      color: #4ecdc4;
    }
    .badge-type {
      background: #333;
      color: #aaa;
    }
    .badge-status {
      background: #f39c12;
      color: #1a1a2e;
    }
    .badge-status.completed {
      background: #2ecc71;
      color: #fff;
    }
    .badge-status.pending {
      background: #e74c3c;
      color: #fff;
    }
    .order-details {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 15px;
      margin-bottom: 15px;
      font-size: 13px;
    }
    .detail {
      display: flex;
      flex-direction: column;
    }
    .detail-label {
      color: #888;
      font-size: 11px;
      margin-bottom: 4px;
      text-transform: uppercase;
    }
    .detail-value {
      color: #fff;
      font-weight: 500;
    }
    .order-items {
      background: #1a1a2e;
      padding: 15px;
      border-radius: 8px;
      margin-bottom: 15px;
      font-size: 13px;
    }
    .order-items-title {
      color: #888;
      font-size: 11px;
      text-transform: uppercase;
      margin-bottom: 10px;
    }
    .item-line {
      display: flex;
      justify-content: space-between;
      padding: 6px 0;
      border-bottom: 1px solid #333;
      color: #aaa;
    }
    .item-line:last-child { border-bottom: none; }
    .order-total {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 12px 0;
      border-top: 2px solid #333;
      margin-top: 10px;
      font-weight: bold;
      color: #4ecdc4;
    }
    .order-actions {
      display: flex;
      gap: 8px;
      flex-wrap: wrap;
    }
    .order-actions .btn {
      flex: 1;
      min-width: 100px;
    }

    /* Modal */
    .modal {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.7);
      z-index: 1000;
      align-items: center;
      justify-content: center;
      padding: 20px;
    }
    .modal.active {
      display: flex;
    }
    .modal-content {
      background: #252542;
      border-radius: 12px;
      padding: 30px;
      max-width: 600px;
      width: 100%;
      max-height: 90vh;
      overflow-y: auto;
    }
    .modal-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 25px;
      padding-bottom: 15px;
      border-bottom: 1px solid #333;
    }
    .modal-header h2 {
      font-size: 20px;
      color: #4ecdc4;
    }
    .close-btn {
      background: none;
      border: none;
      color: #888;
      font-size: 28px;
      cursor: pointer;
      transition: color 0.2s;
    }
    .close-btn:hover {
      color: #fff;
    }

    /* Form */
    .form-group {
      margin-bottom: 20px;
    }
    .form-group label {
      display: block;
      margin-bottom: 8px;
      color: #aaa;
      font-size: 14px;
    }
    .form-group input,
    .form-group select,
    .form-group textarea {
      width: 100%;
      padding: 12px 14px;
      background: #1a1a2e;
      border: 1px solid #333;
      border-radius: 8px;
      color: #fff;
      font-size: 14px;
    }
    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
      outline: none;
      border-color: #4ecdc4;
    }
    .form-group textarea {
      resize: vertical;
      min-height: 100px;
    }
    .form-row {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 15px;
    }
    @media (max-width: 600px) {
      .form-row { grid-template-columns: 1fr; }
    }
    .btn-group {
      display: flex;
      gap: 10px;
      justify-content: flex-end;
      margin-top: 25px;
    }

    /* Loading & Empty States */
    .loading {
      text-align: center;
      padding: 40px;
      color: #888;
    }
    .empty-state {
      text-align: center;
      padding: 60px 20px;
      color: #888;
    }
    .empty-state-icon {
      font-size: 48px;
      margin-bottom: 15px;
    }
    .empty-state-title {
      font-size: 20px;
      margin-bottom: 10px;
      color: #aaa;
    }

    /* Toast */
    .toast {
      position: fixed;
      bottom: 20px;
      right: 20px;
      padding: 15px 25px;
      background: #4ecdc4;
      color: #1a1a2e;
      border-radius: 8px;
      display: none;
      z-index: 1001;
      font-weight: bold;
    }
    .toast.error { background: #e74c3c; color: #fff; }
    .toast.active { display: block; }

    @media (max-width: 600px) {
      .controls-row {
        grid-template-columns: 1fr;
      }
      .order-details {
        grid-template-columns: 1fr;
      }
      .order-actions .btn {
        padding: 8px 12px;
        font-size: 12px;
      }
    }
  </style>
</head>
<body>
  <div class="container">
    <!-- Header -->
    <header>
      <h1>Orders</h1>
      <div class="header-right">
        <button class="btn btn-primary" id="addOrderBtn">+ New Order</button>
      </div>
    </header>

    <!-- Controls Section -->
    <div class="controls-section">
      <div class="controls-row">
        <div class="search-group">
          <label>Search Orders</label>
          <input type="text" id="searchInput" placeholder="Order ID, Customer Name, Phone...">
        </div>
        <div class="checkbox-group">
          <input type="checkbox" id="showProcessedCheckbox">
          <label for="showProcessedCheckbox">Show Processed</label>
        </div>
        <button class="btn btn-secondary" id="clearFiltersBtn">Clear</button>
      </div>
    </div>

    <!-- Orders List -->
    <div class="orders-container" id="ordersContainer">
      <div class="loading">Loading orders...</div>
    </div>
  </div>

  <!-- Create/Edit Order Modal -->
  <div class="modal" id="orderModal">
    <div class="modal-content">
      <div class="modal-header">
        <h2 id="modalTitle">New Order</h2>
        <button class="close-btn" onclick="closeModal('orderModal')">&times;</button>
      </div>
      <form id="orderForm">
        <div class="form-row">
          <div class="form-group">
            <label>Platform *</label>
            <select id="platform" required>
              <option value="">Select Platform</option>
              <option value="web">Web</option>
              <option value="deliveroo">Deliveroo</option>
              <option value="hungry_panda">Hungry Panda</option>
            </select>
          </div>
          <div class="form-group">
            <label>Order Type *</label>
            <select id="orderType" required>
              <option value="">Select Type</option>
              <option value="dine_in">Dine-In</option>
              <option value="delivery">Delivery</option>
            </select>
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label>Customer Name *</label>
            <input type="text" id="customerName" required>
          </div>
          <div class="form-group">
            <label>Customer Phone *</label>
            <input type="tel" id="customerPhone" required>
          </div>
        </div>

        <div id="tableNumberField" class="form-group" style="display: none;">
          <label>Table Number</label>
          <select id="tableNumber">
            <option value="">Select a table...</option>
          </select>
        </div>

        <div id="deliveryAddressField" class="form-group" style="display: none;">
          <label>Delivery Address *</label>
          <textarea id="deliveryAddress" placeholder="Street, Postal Code, City"></textarea>
        </div>

        <div class="form-group">
          <label>Select Items from Menu *</label>
          <div style="display: flex; gap: 10px; margin-bottom: 10px;">
            <select id="menuItemSelect" style="flex: 1;">
              <option value="">Choose an item...</option>
            </select>
            <input type="number" id="itemQuantity" min="1" value="1" style="width: 80px;" placeholder="Qty">
            <button type="button" class="btn btn-secondary btn-small" onclick="dashboard.addItemToOrder()">+ Add</button>
          </div>
          
          <div id="selectedItemsList" style="margin-bottom: 15px;">
            <!-- Selected items will appear here -->
          </div>
        </div>

        <div class="form-group">
          <label>Order Comments / Special Requests</label>
          <textarea id="kitchenNotes" placeholder="Any special instructions, dietary requirements, or notes for the kitchen..."></textarea>
        </div>

        <div class="btn-group">
          <button type="button" class="btn btn-secondary" onclick="closeModal('orderModal')">Cancel</button>
          <button type="submit" class="btn btn-primary">Save Order</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Payment Modal -->
  <div class="modal" id="paymentModal">
    <div class="modal-content">
      <div class="modal-header">
        <h2 style="color: #ffffff;">Process Payment</h2>
        <button class="close-btn" onclick="closeModal('paymentModal')">&times;</button>
      </div>
      
      <!-- Payment Method Selection -->
      <div id="paymentMethodSection" style="display: none;">
        <!-- Payment Breakdown -->
        <div class="form-group" style="background: #f9f9f9; padding: 15px; border-radius: 4px; margin-bottom: 20px; color: #000000;">
          <div style="display: flex; justify-content: space-between; margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px solid #ddd;">
            <span>Subtotal:</span>
            <span>£<span id="breakdownSubtotal" style="color: #000000;">0.00</span></span>
          </div>
          <div style="display: flex; justify-content: space-between; margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px solid #ddd;">
            <span>Service Charge (15%):</span>
            <span>£<span id="breakdownServiceCharge" style="color: #000000;">0.00</span></span>
          </div>
          <div style="display: flex; justify-content: space-between; margin-bottom: 15px; padding-bottom: 10px; border-bottom: 1px solid #ddd;">
            <span>VAT (20%):</span>
            <span>£<span id="breakdownVAT" style="color: #000000;">0.00</span></span>
          </div>
          <div style="display: flex; justify-content: space-between; font-size: 18px; font-weight: bold; color: #000000;">
            <span>Total Amount:</span>
            <span>£<span id="paymentAmountDisplay" style="color: #000000;">0.00</span></span>
          </div>
        </div>
        
        <div class="form-group">
          <label>Select Payment Method *</label>
          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
            <button type="button" class="btn btn-success" onclick="dashboard.selectPaymentMethod('credit_card')" style="margin: 0;">💳 Credit Card</button>
            <button type="button" class="btn btn-success" onclick="dashboard.selectPaymentMethod('cash')" style="margin: 0;">💵 Cash</button>
          </div>
        </div>
        <div class="btn-group">
          <button type="button" class="btn btn-secondary" onclick="closeModal('paymentModal')">Cancel</button>
        </div>
      </div>

      <!-- Cash Payment Form -->
      <form id="cashPaymentForm" onsubmit="dashboard.processCashPayment(event)" style="display: none;">
        <!-- Hidden field to store total amount and orderId -->
        <input type="hidden" id="cashOrderTotal" />
        
        <!-- Payment Breakdown for Cash -->
        <div class="form-group" style="background: #f9f9f9; padding: 15px; border-radius: 4px; margin-bottom: 20px; color: #000000;">
          <div style="display: flex; justify-content: space-between; margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px solid #ddd;">
            <span>Subtotal:</span>
            <span>£<span id="cashBreakdownSubtotal" style="color: #000000;">0.00</span></span>
          </div>
          <div style="display: flex; justify-content: space-between; margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px solid #ddd;">
            <span>Service Charge (15%):</span>
            <span>£<span id="cashBreakdownServiceCharge" style="color: #000000;">0.00</span></span>
          </div>
          <div style="display: flex; justify-content: space-between; margin-bottom: 15px; padding-bottom: 10px; border-bottom: 1px solid #ddd;">
            <span>VAT (20%):</span>
            <span>£<span id="cashBreakdownVAT" style="color: #000000;">0.00</span></span>
          </div>
          <div style="display: flex; justify-content: space-between; font-size: 18px; font-weight: bold; color: #000000;">
            <span>Total Amount:</span>
            <span>£<span id="cashBreakdownTotal" style="color: #000000;">0.00</span></span>
          </div>
        </div>
        
        <div class="form-group">
          <label>Amount Received (£) *</label>
          <input type="number" id="cashAmountReceived" step="0.01" required placeholder="Enter amount received" oninput="dashboard.calculateChange()">
        </div>
        <div class="form-group">
          <label>Staff Notes (Optional)</label>
          <textarea id="staffNotes" placeholder="Add any notes about the payment..." style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; font-family: inherit; resize: vertical;"></textarea>
        </div>
        <div class="form-group" id="changeDisplay" style="display: none;">
          <label>Change (£)</label>
          <input type="number" id="changeAmount" readonly style="background: #f5f5f5; color: #27ae60; font-weight: bold;">
        </div>
        <div class="btn-group">
          <button type="button" class="btn btn-secondary" onclick="dashboard.cancelPaymentMethod()">Back</button>
          <button type="submit" class="btn btn-success">Complete Cash Payment</button>
        </div>
      </form>

    </div>
  </div>

  <!-- Toast Notification -->
  <div class="toast" id="toast"></div>

  <script src="./orders-dashboard.js"></script>
</body>
</html>
