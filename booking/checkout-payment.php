<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Payment - Yanji Restaurant</title>
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body {
      font-family: Roboto, sans-serif;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
    }

    .payment-container {
      background: #fff;
      border-radius: 8px;
      box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
      max-width: 500px;
      width: 100%;
      padding: 40px;
    }

    .payment-header {
      text-align: center;
      margin-bottom: 30px;
    }

    .payment-header h1 {
      font-size: 28px;
      color: #333;
      margin-bottom: 5px;
      font-weight: 300;
    }

    .payment-header p {
      color: #999;
      font-size: 14px;
    }

    .order-summary {
      background: #f9f9f9;
      padding: 20px;
      border-radius: 6px;
      margin-bottom: 30px;
    }

    .summary-row {
      display: flex;
      justify-content: space-between;
      margin-bottom: 10px;
      font-size: 14px;
    }

    .summary-row:last-child {
      margin-bottom: 0;
      padding-top: 10px;
      border-top: 2px solid #ddd;
      font-weight: bold;
      font-size: 16px;
      color: #333;
    }

    .summary-row span:first-child {
      color: #666;
    }

    .summary-row span:last-child {
      color: #333;
      font-weight: 500;
    }

    .payment-method-section {
      margin-bottom: 30px;
    }

    .section-title {
      font-size: 16px;
      font-weight: 500;
      color: #333;
      margin-bottom: 15px;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .method-options {
      display: flex;
      gap: 15px;
      margin-bottom: 20px;
    }

    .method-btn {
      flex: 1;
      padding: 15px;
      border: 2px solid #ddd;
      background: #fff;
      border-radius: 6px;
      cursor: pointer;
      font-size: 14px;
      font-weight: 500;
      color: #666;
      transition: all 0.3s;
      text-align: center;
    }

    .method-btn:hover {
      border-color: #667eea;
      background: #f9f9f9;
    }

    .method-btn.active {
      border-color: #667eea;
      background: #667eea;
      color: #fff;
    }

    .payment-form {
      display: none;
    }

    .payment-form.active {
      display: block;
    }

    .form-group {
      margin-bottom: 20px;
    }

    .form-group label {
      display: block;
      font-size: 13px;
      font-weight: 500;
      color: #333;
      margin-bottom: 8px;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .form-group input,
    .form-group textarea {
      width: 100%;
      padding: 12px;
      border: 1px solid #ddd;
      border-radius: 4px;
      font-size: 14px;
      font-family: Roboto, sans-serif;
      transition: border-color 0.3s;
    }

    .form-group input:focus,
    .form-group textarea:focus {
      outline: none;
      border-color: #667eea;
      box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .card-row {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 15px;
    }

    .card-row.full {
      grid-template-columns: 1fr;
    }

    .error-message {
      color: #f44336;
      font-size: 12px;
      margin-top: 5px;
      display: none;
    }

    .error-message.show {
      display: block;
    }

    .form-group.error input,
    .form-group.error textarea {
      border-color: #f44336;
    }

    .cash-info {
      background: #e3f2fd;
      padding: 15px;
      border-radius: 4px;
      margin-bottom: 20px;
      font-size: 13px;
      color: #1976d2;
      line-height: 1.6;
    }

    .button-group {
      display: flex;
      gap: 15px;
      margin-top: 30px;
    }

    .btn {
      flex: 1;
      padding: 14px;
      border: none;
      border-radius: 4px;
      font-size: 14px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .btn-primary {
      background: #667eea;
      color: #fff;
    }

    .btn-primary:hover:not(:disabled) {
      background: #5568d3;
      box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }

    .btn-primary:disabled {
      background: #ccc;
      cursor: not-allowed;
    }

    .btn-secondary {
      background: #f5f5f5;
      color: #333;
    }

    .btn-secondary:hover {
      background: #e0e0e0;
    }

    .loading {
      display: none;
      text-align: center;
      padding: 20px;
    }

    .loading.show {
      display: block;
    }

    .spinner {
      border: 3px solid #f3f3f3;
      border-top: 3px solid #667eea;
      border-radius: 50%;
      width: 30px;
      height: 30px;
      animation: spin 1s linear infinite;
      margin: 0 auto 10px;
    }

    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }

    .success-message {
      color: #4caf50;
      font-size: 13px;
      margin-top: 10px;
      padding: 10px;
      background: #e8f5e9;
      border-radius: 4px;
      display: none;
    }

    .success-message.show {
      display: block;
    }

    .alert {
      padding: 15px;
      border-radius: 4px;
      margin-bottom: 20px;
      display: none;
    }

    .alert.show {
      display: block;
    }

    .alert.error {
      background: #ffebee;
      color: #c62828;
      border-left: 4px solid #c62828;
    }

    .alert.success {
      background: #e8f5e9;
      color: #2e7d32;
      border-left: 4px solid #2e7d32;
    }

    .receipt {
      background: #f9f9f9;
      padding: 20px;
      border-radius: 6px;
      margin-bottom: 20px;
      display: none;
      text-align: center;
    }

    .receipt.show {
      display: block;
    }

    .receipt h3 {
      color: #4caf50;
      margin-bottom: 10px;
    }

    .receipt p {
      font-size: 13px;
      color: #666;
      margin: 5px 0;
    }

    .back-link {
      text-align: center;
      margin-top: 20px;
    }

    .back-link a {
      color: #667eea;
      text-decoration: none;
      font-size: 13px;
      font-weight: 500;
    }

    .back-link a:hover {
      text-decoration: underline;
    }

    .wallet-buttons {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 15px;
      margin-bottom: 20px;
    }

    .wallet-btn {
      padding: 15px;
      border: 2px solid #ddd;
      background: #fff;
      border-radius: 6px;
      cursor: pointer;
      font-size: 14px;
      font-weight: 500;
      color: #666;
      transition: all 0.3s;
      text-align: center;
    }

    .wallet-btn:hover {
      border-color: #667eea;
      background: #f9f9f9;
    }

    .wallet-btn.active {
      border-color: #667eea;
      background: #667eea;
      color: #fff;
    }

    .wallet-info {
      background: #f0f4ff;
      padding: 12px;
      border-radius: 4px;
      margin-bottom: 20px;
      font-size: 12px;
      color: #667eea;
      text-align: center;
      line-height: 1.5;
    }
  </style>
</head>
<body>
  <div class="payment-container">
    <div class="payment-header">
      <h1>💳 Payment</h1>
      <p>Complete your order payment</p>
    </div>

    <div id="alert-container"></div>

    <div class="order-summary">
      <div class="summary-row">
        <span>Subtotal:</span>
        <span id="summary-subtotal">£0.00</span>
      </div>
      <div class="summary-row">
        <span>Service Charge (15%):</span>
        <span id="summary-service">£0.00</span>
      </div>
      <div class="summary-row">
        <span>VAT (20%):</span>
        <span id="summary-vat">£0.00</span>
      </div>
      <div class="summary-row">
        <span>Total Amount:</span>
        <span id="summary-total">£0.00</span>
      </div>
    </div>

    <div class="payment-method-section">
      <div class="section-title">Payment Method</div>
      <div class="method-options" id="methodOptions">
        <button class="method-btn active" onclick="setPaymentMethod('card')">💳 Credit Card</button>
        <button class="method-btn" id="cashBtn" onclick="setPaymentMethod('cash')">💵 Cash</button>
      </div>

      <!-- Wallet Options (only for menu checkout) -->
      <div id="walletSection" style="display: none; margin-top: 20px;">
        <div class="wallet-info">
          💡 Fast & Secure Payment with Your Phone
        </div>
        <div class="wallet-buttons">
          <button class="wallet-btn" onclick="setPaymentMethod('apple_pay')">🍎 Apple Pay</button>
          <button class="wallet-btn" onclick="setPaymentMethod('google_pay')">🔵 Google Pay</button>
        </div>
      </div>
    </div>

    <!-- Credit Card Form -->
    <form id="cardForm" class="payment-form active" onsubmit="processCardPayment(event)">
      <div class="form-group">
        <label>Cardholder Name</label>
        <input type="text" id="cardholderName" required placeholder="John Doe">
        <div class="error-message" id="cardholderNameError"></div>
      </div>

      <div class="form-group">
        <label>Card Number</label>
        <input type="text" id="cardNumber" placeholder="4532 1234 5678 9010" maxlength="19" required>
        <div class="error-message" id="cardNumberError"></div>
      </div>

      <div class="card-row">
        <div class="form-group">
          <label>Expiry Date (MM/YY)</label>
          <input type="text" id="cardExpiry" placeholder="12/25" maxlength="5" required>
          <div class="error-message" id="cardExpiryError"></div>
        </div>
        <div class="form-group">
          <label>CVV</label>
          <input type="text" id="cardCVV" placeholder="123" maxlength="4" required>
          <div class="error-message" id="cardCVVError"></div>
        </div>
      </div>

      <div class="form-group">
        <label>Billing Address</label>
        <input type="text" id="cardAddress" placeholder="123 Main St, London" required>
        <div class="error-message" id="cardAddressError"></div>
      </div>

      <div class="button-group">
        <button type="button" class="btn btn-secondary" onclick="cancelPayment()">Cancel</button>
        <button type="submit" class="btn btn-primary" id="cardSubmitBtn">Pay £<span id="cardAmount">0.00</span></button>
      </div>
    </form>

    <!-- Cash Form -->
    <form id="cashForm" class="payment-form" onsubmit="processCashPayment(event)">
      <div class="cash-info">
        ℹ️ Please confirm you have received the correct amount. The bill amount is <strong>£<span id="cashAmount">0.00</span></strong>
      </div>

      <div class="form-group">
        <label>Amount Received (£)</label>
        <input type="number" id="cashAmountReceived" placeholder="0.00" step="0.01" min="0" required>
        <div class="error-message" id="cashAmountError"></div>
      </div>

      <div id="cashChange" style="display: none; margin-bottom: 20px;">
        <div class="form-group">
          <label>Change to Give (£)</label>
          <input type="number" id="changeAmount" readonly style="background: #f5f5f5;">
        </div>
      </div>

      <div class="form-group">
        <label>Staff Notes (Optional)</label>
        <textarea id="staffNotes" placeholder="Add any notes about the payment..." rows="3"></textarea>
      </div>

      <div class="button-group">
        <button type="button" class="btn btn-secondary" onclick="cancelPayment()">Cancel</button>
        <button type="submit" class="btn btn-primary" id="cashSubmitBtn">Confirm Payment</button>
      </div>
    </form>

    <div id="loading" class="loading">
      <div class="spinner"></div>
      <p>Processing payment...</p>
    </div>

    <div id="receipt" class="receipt">
      <h3>✓ Payment Successful!</h3>
      <p id="receiptOrderId"></p>
      <p id="receiptAmount"></p>
      <p id="receiptMethod"></p>
    </div>

    <div class="back-link" id="backLink" style="display: none;">
      <a href="javascript:window.history.back()">← Back to Order</a>
    </div>
  </div>

  <script>
    // Configuration
    const API_BASE = window.API_BASE || window.YANJI_CONFIG?.API_BASE || 'https://yanji.tunesbasis.com';
    let paymentMethod = 'card';
    let paymentData = {};

    // Get parameters from sessionStorage (secure, not in URL)
    const sessionData = JSON.parse(sessionStorage.getItem('paymentData') || '{}');
    const source = sessionData.source; // 'menu' or 'dashboard'
    const orderId = sessionData.orderId;
    const tableNumber = sessionData.tableNumber;
    const totalAmount = parseFloat(sessionData.totalAmount) || 0;
    const cartData = sessionData.cartData || null;

    // Initialize page
    document.addEventListener('DOMContentLoaded', function() {
      // Validate that payment data exists in sessionStorage
      if (!source || totalAmount <= 0) {
        alert('Payment data not found. Please return to your cart or dashboard.');
        window.history.back();
        return;
      }
      updateOrderSummary();
      setupFormListeners();
      initializePaymentMethods();
    });

    function initializePaymentMethods() {
      // Hide cash button if from menu (shopping cart)
      if (source === 'menu') {
        document.getElementById('cashBtn').style.display = 'none';
        document.getElementById('walletSection').style.display = 'block';
      } else {
        // From dashboard - show cash, hide wallets
        document.getElementById('walletSection').style.display = 'none';
      }
    }

    function updateOrderSummary() {
      // Calculate breakdown from cart items (for menu) or use dashboard totals
      let subtotal = 0;
      let serviceCharge = 0;
      let vat = 0;

      if (cartData && cartData.items && cartData.items.length > 0) {
        // Menu checkout: calculate from items
        cartData.items.forEach(item => {
          subtotal += item.price * item.quantity;
        });
        serviceCharge = subtotal * 0.15;
        const subtotalWithService = subtotal + serviceCharge;
        vat = subtotalWithService * 0.20;
      } else if (source === 'dashboard') {
        // Dashboard: calculate approximate breakdown from total
        // Assuming: total = subtotal * 1.15 * 1.20
        // So: subtotal = total / (1.15 * 1.20) = total / 1.38
        subtotal = totalAmount / 1.38;
        serviceCharge = subtotal * 0.15;
        vat = (subtotal + serviceCharge) * 0.20;
      }

      // Update summary display
      document.getElementById('summary-subtotal').textContent = '£' + subtotal.toFixed(2);
      document.getElementById('summary-service').textContent = '£' + serviceCharge.toFixed(2);
      document.getElementById('summary-vat').textContent = '£' + vat.toFixed(2);
      document.getElementById('summary-total').textContent = '£' + totalAmount.toFixed(2);
      
      // Update form amounts
      document.getElementById('cardAmount').textContent = totalAmount.toFixed(2);
      document.getElementById('cashAmount').textContent = totalAmount.toFixed(2);
    }

    function setPaymentMethod(method) {
      paymentMethod = method;
      
      // Update button states
      document.querySelectorAll('.method-btn').forEach(btn => btn.classList.remove('active'));
      document.querySelectorAll('.wallet-btn').forEach(btn => btn.classList.remove('active'));
      
      // Find and activate the clicked button
      if (method === 'card') {
        document.querySelectorAll('.method-btn')[0].classList.add('active');
      } else if (method === 'cash') {
        document.querySelectorAll('.method-btn')[1].classList.add('active');
      } else if (method === 'apple_pay' || method === 'google_pay') {
        event.target.classList.add('active');
      }

      // Update forms
      document.getElementById('cardForm').classList.remove('active');
      document.getElementById('cashForm').classList.remove('active');
      
      if (method === 'card') {
        document.getElementById('cardForm').classList.add('active');
      } else if (method === 'cash') {
        document.getElementById('cashForm').classList.add('active');
      } else if (method === 'apple_pay') {
        processApplePayment();
      } else if (method === 'google_pay') {
        processGooglePayment();
      }
    }

    function setupFormListeners() {
      // Card number formatting
      const cardInput = document.getElementById('cardNumber');
      cardInput.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\s/g, '');
        let formattedValue = value.match(/.{1,4}/g)?.join(' ') || value;
        e.target.value = formattedValue;
      });

      // Expiry date formatting
      const expiryInput = document.getElementById('cardExpiry');
      expiryInput.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length >= 2) {
          value = value.substring(0, 2) + '/' + value.substring(2, 4);
        }
        e.target.value = value;
      });

      // CVV only numbers
      document.getElementById('cardCVV').addEventListener('input', function(e) {
        e.target.value = e.target.value.replace(/\D/g, '');
      });

      // Cash amount listener
      document.getElementById('cashAmountReceived').addEventListener('input', function(e) {
        const received = parseFloat(e.target.value) || 0;
        const change = received - totalAmount;
        
        if (change >= 0) {
          document.getElementById('cashChange').style.display = 'block';
          document.getElementById('changeAmount').value = change.toFixed(2);
        } else {
          document.getElementById('cashChange').style.display = 'none';
        }
      });
    }

    function validateCardForm() {
      let isValid = true;
      const errors = {};

      const cardholder = document.getElementById('cardholderName').value.trim();
      if (!cardholder) {
        errors.cardholderName = 'Cardholder name is required';
        isValid = false;
      }

      const cardNumber = document.getElementById('cardNumber').value.replace(/\s/g, '');
      if (!/^\d{16}$/.test(cardNumber)) {
        errors.cardNumber = 'Card number must be 16 digits';
        isValid = false;
      }

      const expiry = document.getElementById('cardExpiry').value;
      if (!/^\d{2}\/\d{2}$/.test(expiry)) {
        errors.cardExpiry = 'Expiry date must be MM/YY format';
        isValid = false;
      } else {
        const [month, year] = expiry.split('/');
        if (month < 1 || month > 12) {
          errors.cardExpiry = 'Invalid month';
          isValid = false;
        }
      }

      const cvv = document.getElementById('cardCVV').value;
      if (!/^\d{3,4}$/.test(cvv)) {
        errors.cardCVV = 'CVV must be 3-4 digits';
        isValid = false;
      }

      const address = document.getElementById('cardAddress').value.trim();
      if (!address) {
        errors.cardAddress = 'Billing address is required';
        isValid = false;
      }

      // Display errors
      clearErrors();
      Object.keys(errors).forEach(field => {
        const errorElement = document.getElementById(field + 'Error');
        if (errorElement) {
          errorElement.textContent = errors[field];
          errorElement.classList.add('show');
          document.getElementById(field).parentElement.classList.add('error');
        }
      });

      return isValid;
    }

    function validateCashForm() {
      let isValid = true;
      const received = parseFloat(document.getElementById('cashAmountReceived').value);

      if (isNaN(received) || received < 0) {
        showAlert('Please enter a valid amount', 'error');
        return false;
      }

      if (received < totalAmount) {
        showAlert('Amount received must be at least £' + totalAmount.toFixed(2), 'error');
        return false;
      }

      return true;
    }

    function clearErrors() {
      document.querySelectorAll('.error-message').forEach(el => el.classList.remove('show'));
      document.querySelectorAll('.form-group').forEach(el => el.classList.remove('error'));
    }

    async function processCardPayment(event) {
      event.preventDefault();
      
      if (!validateCardForm()) {
        return;
      }

      showLoading(true);

      try {
        const paymentPayload = {
          orderId: orderId,
          amount: totalAmount,
          paymentMethod: 'card',
          cardholderName: document.getElementById('cardholderName').value,
          cardNumber: document.getElementById('cardNumber').value.replace(/\s/g, '').slice(-4),
          cardExpiry: document.getElementById('cardExpiry').value,
          billingAddress: document.getElementById('cardAddress').value,
          source: source
        };

        // Create payment record
        const paymentResponse = await fetch(`${API_BASE}/payments/intent`, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({
            orderId: orderId,
            amount: totalAmount,
            paymentMethod: 'card'
          })
        });

        if (!paymentResponse.ok) {
          throw new Error('Failed to create payment intent');
        }

        const payment = await paymentResponse.json();

        // If from menu, create order first
        if (source === 'menu' && cartData) {
          const orderResponse = await fetch(`${API_BASE}/orders`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
              tableNumber: parseInt(tableNumber),
              items: cartData.items,
              comments: cartData.comments || '',
              totalAmount: totalAmount,
              paymentMethod: 'card',
              paymentId: payment.paymentId,
              paymentStatus: 'completed'
            })
          });

          if (!orderResponse.ok) {
            throw new Error('Failed to create order');
          }

          const order = await orderResponse.json();
          showSuccess('Credit card payment processed successfully!', order.orderId, 'Card');
        } else {
          // Update existing order with payment
          const updateResponse = await fetch(`${API_BASE}/orders/${orderId}`, {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
              paymentMethod: 'card',
              paymentId: payment.paymentId,
              paymentStatus: 'completed'
            })
          });

          if (!updateResponse.ok) {
            throw new Error('Failed to update order');
          }

          showSuccess('Credit card payment processed successfully!', orderId, 'Card');
        }
      } catch (error) {
        console.error('Payment error:', error);
        showAlert('Payment failed: ' + error.message, 'error');
        showLoading(false);
      }
    }

    async function processCashPayment(event) {
      event.preventDefault();

      if (!validateCashForm()) {
        return;
      }

      showLoading(true);

      try {
        const amountReceived = parseFloat(document.getElementById('cashAmountReceived').value);
        const change = amountReceived - totalAmount;
        const staffNotes = document.getElementById('staffNotes').value;

        // Create payment record
        const paymentResponse = await fetch(`${API_BASE}/payments/intent`, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({
            orderId: orderId,
            amount: totalAmount,
            paymentMethod: 'cash',
            amountReceived: amountReceived,
            change: change,
            notes: staffNotes
          })
        });

        if (!paymentResponse.ok) {
          throw new Error('Failed to create payment record');
        }

        const payment = await paymentResponse.json();

        // If from menu, create order first
        if (source === 'menu' && cartData) {
          const orderResponse = await fetch(`${API_BASE}/orders`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
              tableNumber: parseInt(tableNumber),
              items: cartData.items,
              comments: cartData.comments || '',
              totalAmount: totalAmount,
              paymentMethod: 'cash',
              paymentId: payment.paymentId,
              paymentStatus: 'completed'
            })
          });

          if (!orderResponse.ok) {
            throw new Error('Failed to create order');
          }

          const order = await orderResponse.json();
          showSuccess(`Cash payment processed. Change: £${change.toFixed(2)}`, order.orderId, 'Cash');
        } else {
          // Update existing order with payment
          const updateResponse = await fetch(`${API_BASE}/orders/${orderId}`, {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
              paymentMethod: 'cash',
              paymentId: payment.paymentId,
              paymentStatus: 'completed',
              amountReceived: amountReceived,
              change: change
            })
          });

          if (!updateResponse.ok) {
            throw new Error('Failed to update order');
          }

          showSuccess(`Cash payment processed. Change: £${change.toFixed(2)}`, orderId, 'Cash');
        }
      } catch (error) {
        console.error('Payment error:', error);
        showAlert('Payment failed: ' + error.message, 'error');
        showLoading(false);
      }
    }

    function showLoading(show) {
      if (show) {
        document.getElementById('loading').classList.add('show');
        document.getElementById('cardSubmitBtn').disabled = true;
        document.getElementById('cashSubmitBtn').disabled = true;
      } else {
        document.getElementById('loading').classList.remove('show');
        document.getElementById('cardSubmitBtn').disabled = false;
        document.getElementById('cashSubmitBtn').disabled = false;
      }
    }

    async function processApplePayment() {
      showLoading(true);
      try {
        // Simulate Apple Pay processing
        // In production, this would use Apple Pay JS API
        const paymentPayload = {
          orderId: orderId,
          amount: totalAmount,
          paymentMethod: 'apple_pay'
        };

        // Create payment record
        const paymentResponse = await fetch(`${API_BASE}/payments/intent`, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(paymentPayload)
        });

        if (!paymentResponse.ok) {
          throw new Error('Failed to process Apple Pay');
        }

        const payment = await paymentResponse.json();

        // Create order from menu
        if (source === 'menu' && cartData) {
          const orderResponse = await fetch(`${API_BASE}/orders`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
              tableNumber: parseInt(tableNumber),
              items: cartData.items,
              comments: cartData.comments || '',
              totalAmount: totalAmount,
              paymentMethod: 'apple_pay',
              paymentId: payment.paymentId,
              paymentStatus: 'completed'
            })
          });

          if (!orderResponse.ok) {
            throw new Error('Failed to create order');
          }

          const order = await orderResponse.json();
          showSuccess('Apple Pay processed successfully!', order.orderId, 'Apple Pay');
        }
      } catch (error) {
        console.error('Apple Pay error:', error);
        showAlert('Apple Pay failed: ' + error.message, 'error');
        showLoading(false);
      }
    }

    async function processGooglePayment() {
      showLoading(true);
      try {
        // Simulate Google Pay processing
        // In production, this would use Google Pay API
        const paymentPayload = {
          orderId: orderId,
          amount: totalAmount,
          paymentMethod: 'google_pay'
        };

        // Create payment record
        const paymentResponse = await fetch(`${API_BASE}/payments/intent`, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(paymentPayload)
        });

        if (!paymentResponse.ok) {
          throw new Error('Failed to process Google Pay');
        }

        const payment = await paymentResponse.json();

        // Create order from menu
        if (source === 'menu' && cartData) {
          const orderResponse = await fetch(`${API_BASE}/orders`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
              tableNumber: parseInt(tableNumber),
              items: cartData.items,
              comments: cartData.comments || '',
              totalAmount: totalAmount,
              paymentMethod: 'google_pay',
              paymentId: payment.paymentId,
              paymentStatus: 'completed'
            })
          });

          if (!orderResponse.ok) {
            throw new Error('Failed to create order');
          }

          const order = await orderResponse.json();
          showSuccess('Google Pay processed successfully!', order.orderId, 'Google Pay');
        }
      } catch (error) {
        console.error('Google Pay error:', error);
        showAlert('Google Pay failed: ' + error.message, 'error');
        showLoading(false);
      }
    }

    function clearSessionData() {
      // Clear sensitive payment data from sessionStorage for security
      sessionStorage.removeItem('paymentData');
    }

    function showSuccess(message, orderId, method) {
      showLoading(false);
      document.getElementById('cardForm').style.display = 'none';
      document.getElementById('cashForm').style.display = 'none';
      
      const receipt = document.getElementById('receipt');
      document.getElementById('receiptOrderId').textContent = 'Order ID: ' + orderId;
      document.getElementById('receiptAmount').textContent = 'Amount: £' + totalAmount.toFixed(2);
      document.getElementById('receiptMethod').textContent = 'Payment Method: ' + method;
      
      receipt.classList.add('show');
      document.getElementById('backLink').style.display = 'block';

      // Clear session data after successful payment
      clearSessionData();

      // Auto-redirect after 3 seconds
      setTimeout(() => {
        if (source === 'menu') {
          window.location.href = 'menu.php?table=' + tableNumber;
        } else {
          window.location.href = 'orders-dashboard.php';
        }
      }, 3000);
    }

    function showAlert(message, type = 'error') {
      const alertContainer = document.getElementById('alert-container');
      const alert = document.createElement('div');
      alert.className = 'alert ' + type + ' show';
      alert.textContent = message;
      alertContainer.innerHTML = '';
      alertContainer.appendChild(alert);

      if (type === 'error') {
        setTimeout(() => alert.remove(), 5000);
      }
    }

    function cancelPayment() {
      if (confirm('Are you sure you want to cancel the payment?')) {
        clearSessionData();
        window.history.back();
      }
    }

    // Clean up sensitive data when leaving the payment page
    window.addEventListener('beforeunload', function() {
      clearSessionData();
    });
  </script>
</body>
</html>
