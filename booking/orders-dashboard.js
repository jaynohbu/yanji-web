/**
 * Order Management Dashboard - JavaScript
 * Multi-platform order management with real-time updates
 */

class OrderDashboard {
    constructor() {
        this.apiBaseUrl = window.API_BASE || window.YANJI_CONFIG?.API_BASE || 'https://yanji.tunesbasis.com';
        this.orders = [];
        this.filteredOrders = [];
        this.menuItems = [];
        this.tables = [];
        this.selectedItems = [];
        this.currentEditingId = null;
        this.refreshInterval = null;
        this.init();
    }

    init() {
        this.setupEventListeners();
        this.loadTables();
        this.loadMenuItems();
        this.loadOrders();
        this.startAutoRefresh();
    }

    setupEventListeners() {
        // Buttons
        document.getElementById('addOrderBtn')?.addEventListener('click', () => this.openOrderModal());
        document.getElementById('clearFiltersBtn')?.addEventListener('click', () => this.clearFilters());

        // Form
        document.getElementById('orderForm')?.addEventListener('submit', (e) => this.handleOrderSubmit(e));
        document.getElementById('paymentForm')?.addEventListener('submit', (e) => this.handlePayment(e));

        // Platform change
        document.getElementById('platform')?.addEventListener('change', () => this.updateFormFields());

        // Order type change
        document.getElementById('orderType')?.addEventListener('change', () => this.updateFormFields());

        // Filters
        document.getElementById('searchInput')?.addEventListener('input', () => this.applyFilters());
        document.getElementById('showProcessedCheckbox')?.addEventListener('change', () => this.applyFilters());

        // Menu item selection
        document.getElementById('menuItemSelect')?.addEventListener('change', (e) => this.onMenuItemSelected(e));

        // Close buttons for modals
        document.querySelectorAll('.close-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const modal = e.target.closest('.modal');
                if (modal) this.closeModal(modal.id);
            });
        });

        // Cancel buttons for modals
        document.querySelectorAll('.btn-secondary').forEach(btn => {
            if (btn.textContent.trim() === 'Cancel') {
                btn.addEventListener('click', (e) => {
                    const modal = e.target.closest('.modal');
                    if (modal) this.closeModal(modal.id);
                });
            }
        });
    }

    async loadMenuItems() {
        try {
            const response = await fetch(`${this.apiBaseUrl}/menu`);
            if (!response.ok) throw new Error('Failed to load menu');
            
            const data = await response.json();
            console.log('Menu data loaded:', data);
            
            // Flatten menu items from all sections
            this.menuItems = [];
            if (data.sections && Array.isArray(data.sections)) {
                data.sections.forEach(section => {
                    if (section.items && Array.isArray(section.items)) {
                        section.items.forEach(item => {
                            this.menuItems.push({
                                ...item,
                                sectionName: section.names?.en || section.sectionId
                            });
                        });
                    }
                });
            }

            // Populate menu select dropdown
            const select = document.getElementById('menuItemSelect');
            if (select) {
                select.innerHTML = '<option value="">Choose an item...</option>';
                this.menuItems.forEach(item => {
                    const name = item.names?.en || item.itemId;
                    const price = item.price || 0;
                    const section = item.sectionName || '';
                    const option = document.createElement('option');
                    option.value = item.itemId;
                    option.textContent = `${name} - £${price.toFixed(2)} (${section})`;
                    option.dataset.price = price;
                    option.dataset.name = name;
                    select.appendChild(option);
                });
            }
        } catch (error) {
            console.error('Error loading menu:', error);
        }
    }

    async loadTables() {
        try {
            const response = await fetch(`${this.apiBaseUrl}/tables`);
            if (!response.ok) throw new Error('Failed to load tables');
            
            const data = await response.json();
            console.log('Tables data loaded:', data);
            
            this.tables = Array.isArray(data) ? data : [];

            // Populate table select dropdown
            const select = document.getElementById('tableNumber');
            if (select) {
                select.innerHTML = '<option value="">Select a table...</option>';
                this.tables.forEach(tableConfig => {
                    const table = tableConfig.table;
                    if (table) {
                        const tableId = table.id;
                        const tableName = table.name || `Table ${tableId}`;
                        const capacity = `${table.minGuests}-${table.maxGuests} guests`;
                        
                        const option = document.createElement('option');
                        option.value = tableId;
                        option.textContent = `${tableName} (${capacity})`;
                        select.appendChild(option);

                        // Add split table options if available
                        if (table.splittable && table.parts && Array.isArray(table.parts)) {
                            table.parts.forEach(part => {
                                const partOption = document.createElement('option');
                                // Convert split table IDs: 8a->81, 8b->82, 9a->91, 9b->92, etc.
                                const tableNum = table.id;
                                const letterChar = part.id.slice(-1); // Get last character (a, b, c, etc.)
                                const letterNum = letterChar.charCodeAt(0) - 'a'.charCodeAt(0) + 1; // a=1, b=2, c=3, etc.
                                const numericId = tableNum * 10 + letterNum;
                                
                                partOption.value = numericId;
                                partOption.textContent = `  └─ ${part.name} (${part.minGuests}-${part.maxGuests} guests)`;
                                select.appendChild(partOption);
                            });
                        }
                    }
                });
            }
        } catch (error) {
            console.error('Error loading tables:', error);
        }
    }

    onMenuItemSelected(e) {
        // Reset the selection after user picks an item
        // The item will be added via the "Add" button
    }

    addItemToOrder() {
        const select = document.getElementById('menuItemSelect');
        const quantity = parseInt(document.getElementById('itemQuantity').value) || 1;

        if (!select.value) {
            this.showToast('Please select an item', 'error');
            return;
        }

        const itemId = select.value;
        const menuItem = this.menuItems.find(m => m.itemId === itemId);
        
        if (!menuItem) {
            this.showToast('Item not found', 'error');
            return;
        }

        // Add to selected items
        const itemName = menuItem.names?.en || menuItem.itemId;
        this.selectedItems.push({
            itemId: itemId,
            name: itemName,
            price: menuItem.price,
            quantity: quantity
        });

        // Update display
        this.renderSelectedItems();

        // Reset form
        select.value = '';
        document.getElementById('itemQuantity').value = '1';
    }

    renderSelectedItems() {
        const container = document.getElementById('selectedItemsList');
        
        if (this.selectedItems.length === 0) {
            container.innerHTML = '<p style="color: #888; font-size: 13px;">No items selected yet</p>';
            return;
        }

        let html = '<div style="background: #1a1a2e; padding: 15px; border-radius: 8px;">';
        let total = 0;

        this.selectedItems.forEach((item, index) => {
            const subtotal = item.price * item.quantity;
            total += subtotal;
            html += `
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 8px 0; border-bottom: 1px solid #333;">
                    <div>
                        <div style="color: #fff;">${item.name}</div>
                        <div style="color: #888; font-size: 12px;">x${item.quantity} @ £${item.price.toFixed(2)}</div>
                    </div>
                    <div style="display: flex; gap: 10px; align-items: center;">
                        <div style="color: #4ecdc4; min-width: 60px; text-align: right;">£${subtotal.toFixed(2)}</div>
                        <button type="button" class="btn btn-danger btn-small" onclick="dashboard.removeSelectedItem(${index})">✕</button>
                    </div>
                </div>
            `;
        });

        html += `
            <div style="display: flex; justify-content: space-between; padding-top: 10px; border-top: 2px solid #333; margin-top: 10px; color: #4ecdc4; font-weight: bold;">
                <span>Subtotal:</span>
                <span>£${total.toFixed(2)}</span>
            </div>
        </div>`;

        container.innerHTML = html;
    }

    removeSelectedItem(index) {
        this.selectedItems.splice(index, 1);
        this.renderSelectedItems();
    }

    async loadOrders() {
        try {
            const response = await fetch(`${this.apiBaseUrl}/orders`);
            if (!response.ok) throw new Error('Failed to load orders');
            
            const data = await response.json();
            console.log('API Response:', data); // Debug log
            
            // Handle both array and object responses
            this.orders = Array.isArray(data) ? data : (data.orders || data.data || []);
            
            // Validate orders have required fields
            this.orders = this.orders.filter(order => {
                if (!order || !order.orderId) {
                    console.warn('Invalid order skipped:', order);
                    return false;
                }
                return true;
            });
            
            // Sort by created date (newest first)
            this.orders.sort((a, b) => {
                const dateA = new Date(a.createdAt || 0);
                const dateB = new Date(b.createdAt || 0);
                return dateB - dateA;
            });
            
            this.applyFilters();
            this.renderOrders();
        } catch (error) {
            console.error('Error loading orders:', error);
            this.showToast('Error loading orders: ' + error.message, 'error');
            this.renderEmpty();
        }
    }

    applyFilters() {
        const searchTerm = document.getElementById('searchInput')?.value.toLowerCase() || '';
        const showProcessed = document.getElementById('showProcessedCheckbox')?.checked || false;

        this.filteredOrders = this.orders.filter(order => {
            // Filter by processed status
            const isCompleted = order.status === 'completed';
            const isCancelled = order.status === 'cancelled';
            const isProcessed = isCompleted || isCancelled;
            
            if (!showProcessed && isProcessed) return false;

            // Filter by search term
            if (searchTerm) {
                const searchFields = [
                    order.orderId,
                    order.customerName,
                    order.customerPhone,
                    order.platform,
                    order.orderType
                ].map(f => (f || '').toLowerCase());

                if (!searchFields.some(f => f.includes(searchTerm))) return false;
            }

            return true;
        });

        this.renderOrders();
    }

    clearFilters() {
        document.getElementById('searchInput').value = '';
        document.getElementById('showProcessedCheckbox').checked = false;
        this.applyFilters();
    }

    renderOrders() {
        const container = document.getElementById('ordersContainer');
        
        if (this.filteredOrders.length === 0) {
            container.innerHTML = `
                <div class="empty-state">
                    <div class="empty-state-icon">📋</div>
                    <div class="empty-state-title">No Orders Found</div>
                    <p>No orders match your search criteria</p>
                </div>
            `;
            return;
        }

        container.innerHTML = this.filteredOrders.map(order => this.renderOrderCard(order)).join('');
    }

    renderOrderCard(order) {
        // Validate order has required fields
        if (!order || !order.orderId) {
            console.warn('Skipping invalid order:', order);
            return '';
        }

        const statusClass = order.status === 'completed' ? 'completed' : 
                           order.status === 'cancelled' ? 'cancelled' : '';
        
        const statusBadgeClass = order.status === 'completed' ? 'completed' :
                                order.status === 'cancelled' ? 'pending' : 'pending';

        const createdDate = order.createdAt ? new Date(order.createdAt).toLocaleString() : 'N/A';
        const subtotal = Array.isArray(order.items) 
            ? order.items.reduce((sum, item) => sum + (parseFloat(item.price) || 0), 0)
            : 0;
        const serviceCharge = subtotal * 0.15;
        const vat = (subtotal + serviceCharge) * 0.20;
        const total = subtotal + serviceCharge + vat;

        let itemsHTML = Array.isArray(order.items) && order.items.length > 0
            ? order.items.map(item => {
                const name = typeof item === 'string' ? item : (item.name || 'Item');
                const price = typeof item === 'string' ? '0.00' : (item.price || 0);
                return `<div class="item-line">
                    <span>${name}</span>
                    <span>£${parseFloat(price).toFixed(2)}</span>
                </div>`;
            }).join('')
            : '<div class="item-line">No items</div>';

        const orderId = order.orderId ? order.orderId.substring(0, 8) : 'UNKNOWN';
        const customerName = order.customerName || 'Guest';
        const customerPhone = order.customerPhone || 'N/A';
        const platform = order.platform || 'web';
        const orderType = order.orderType || 'dine_in';
        const tableNumber = order.tableNumber || 'N/A';
        const deliveryAddress = order.deliveryAddress || 'N/A';
        const kitchenNotes = order.kitchenNotes || '';

        return `
            <div class="order-card ${statusClass}">
                <div class="order-header">
                    <div class="order-title">
                        <div class="order-id">#${orderId}</div>
                        <div class="order-customer">${customerName} • ${customerPhone}</div>
                    </div>
                    <div class="order-meta">
                        <span class="badge badge-platform">${platform}</span>
                        <span class="badge badge-type">${orderType === 'dine_in' ? 'Dine-In' : 'Delivery'}</span>
                        <span class="badge badge-status ${statusBadgeClass}">${order.status || 'pending'}</span>
                    </div>
                </div>

                <div class="order-details">
                    <div class="detail">
                        <div class="detail-label">Date & Time</div>
                        <div class="detail-value">${createdDate}</div>
                    </div>
                    ${orderType === 'dine_in' ? `
                        <div class="detail">
                            <div class="detail-label">Table</div>
                            <div class="detail-value">${tableNumber}</div>
                        </div>
                    ` : `
                        <div class="detail">
                            <div class="detail-label">Delivery Address</div>
                            <div class="detail-value">${deliveryAddress}</div>
                        </div>
                    `}
                    ${kitchenNotes ? `
                        <div class="detail">
                            <div class="detail-label">Kitchen Notes</div>
                            <div class="detail-value">${kitchenNotes}</div>
                        </div>
                    ` : ''}
                </div>

                <div class="order-items">
                    <div class="order-items-title">Items</div>
                    ${itemsHTML}
                    <div class="order-total">
                        <span>Total</span>
                        <span>£${total.toFixed(2)}</span>
                    </div>
                </div>

                <div class="order-actions">
                    ${order.status !== 'completed' && order.status !== 'cancelled' ? `
                        <button class="btn btn-warning btn-small" onclick="dashboard.openPaymentModal('${order.orderId}')">💳 Payment</button>
                        <button class="btn btn-success btn-small" onclick="dashboard.completeOrder('${order.orderId}')">✓ Complete</button>
                        <button class="btn btn-secondary btn-small" onclick="dashboard.editOrder('${order.orderId}')">✏️ Edit</button>
                        <button class="btn btn-danger btn-small" onclick="dashboard.cancelOrder('${order.orderId}')">✕ Cancel</button>
                    ` : ''}
                </div>
            </div>
        `;
    }

    renderEmpty() {
        document.getElementById('ordersContainer').innerHTML = `
            <div class="empty-state">
                <div class="empty-state-icon">⚠️</div>
                <div class="empty-state-title">Unable to Load Orders</div>
                <p>Please try again later</p>
            </div>
        `;
    }

    openOrderModal(orderId = null) {
        this.currentEditingId = orderId;
        this.selectedItems = []; // Reset selected items
        const modal = document.getElementById('orderModal');
        const form = document.getElementById('orderForm');
        const title = document.getElementById('modalTitle');

        if (orderId) {
            const order = this.orders.find(o => o.orderId === orderId);
            if (order) {
                title.textContent = 'Edit Order';
                document.getElementById('platform').value = order.platform || '';
                document.getElementById('orderType').value = order.orderType || '';
                document.getElementById('customerName').value = order.customerName || '';
                document.getElementById('customerPhone').value = order.customerPhone || '';
                document.getElementById('tableNumber').value = order.tableNumber || '';
                document.getElementById('deliveryAddress').value = order.deliveryAddress || '';
                document.getElementById('kitchenNotes').value = order.kitchenNotes || '';
                
                // Load existing items
                if (order.items && Array.isArray(order.items)) {
                    this.selectedItems = order.items.map(item => {
                        if (typeof item === 'string') {
                            // Parse string format "name: £price"
                            const match = item.match(/^(.*?):\s*£?([0-9.]+)$/);
                            if (match) {
                                return {
                                    name: match[1].trim(),
                                    price: parseFloat(match[2]),
                                    quantity: 1
                                };
                            }
                            return { name: item, price: 0, quantity: 1 };
                        }
                        return item;
                    });
                }
            }
        } else {
            title.textContent = 'New Order';
            form.reset();
        }

        this.renderSelectedItems();
        this.updateFormFields();
        modal.classList.add('active');
    }

    updateFormFields() {
        const platform = document.getElementById('platform').value;
        const orderType = document.getElementById('orderType').value;
        const customerNameInput = document.getElementById('customerName');
        const customerPhoneInput = document.getElementById('customerPhone');
        const tableField = document.getElementById('tableNumberField');
        const deliveryField = document.getElementById('deliveryAddressField');

        // Handle platform-specific logic
        if (platform === 'web') {
            // Web orders don't need customer info
            customerNameInput.value = 'staff';
            customerNameInput.required = false;
            customerNameInput.style.opacity = '0.5';
            customerNameInput.style.pointerEvents = 'none';
            
            customerPhoneInput.value = '+447910754793'; // Restaurant phone number
            customerPhoneInput.required = false;
            customerPhoneInput.style.opacity = '0.5';
            customerPhoneInput.style.pointerEvents = 'none';
        } else {
            // Other platforms require customer info
            customerNameInput.required = true;
            customerNameInput.style.opacity = '1';
            customerNameInput.style.pointerEvents = 'auto';
            
            customerPhoneInput.required = true;
            customerPhoneInput.style.opacity = '1';
            customerPhoneInput.style.pointerEvents = 'auto';

            // Clear auto-filled values if user switches platforms
            if (customerNameInput.value === 'staff') {
                customerNameInput.value = '';
            }
            if (customerPhoneInput.value === '+447910754793') {
                customerPhoneInput.value = '';
            }
        }

        // Handle order type fields
        if (orderType === 'dine_in') {
            tableField.style.display = 'block';
            deliveryField.style.display = 'none';
            document.getElementById('tableNumber').required = false;
            document.getElementById('deliveryAddress').required = false;
        } else if (orderType === 'delivery') {
            tableField.style.display = 'none';
            deliveryField.style.display = 'block';
            document.getElementById('tableNumber').required = false;
            document.getElementById('deliveryAddress').required = true;
        } else {
            tableField.style.display = 'none';
            deliveryField.style.display = 'none';
        }
    }

    async handleOrderSubmit(e) {
        e.preventDefault();
        
        const platform = document.getElementById('platform').value;
        const orderType = document.getElementById('orderType').value;
        const customerName = document.getElementById('customerName').value;
        const customerPhone = document.getElementById('customerPhone').value;
        const tableNumberInput = document.getElementById('tableNumber').value;
        
        // Convert tableNumber: if it's a numeric string (1-9), convert to number; otherwise keep as string (for split tables like 81, 82)
        let tableNumber = tableNumberInput;
        if (!isNaN(tableNumberInput)) {
            tableNumber = parseInt(tableNumberInput, 10);
        }
        
        const deliveryAddress = document.getElementById('deliveryAddress').value;
        const kitchenNotes = document.getElementById('kitchenNotes').value;

        if (!platform || !orderType || !customerName || !customerPhone) {
            this.showToast('Please fill in all required fields', 'error');
            return;
        }

        if (this.selectedItems.length === 0) {
            this.showToast('Please add at least one item', 'error');
            return;
        }

        // Format items for API - must include itemId to match menu shopping cart format
        const items = this.selectedItems.map(item => ({
            itemId: item.itemId,
            name: item.name,
            price: item.price,
            quantity: item.quantity
        }));

        const orderData = {
            platform,
            orderType,
            customerName,
            customerPhone,
            items,
            kitchenNotes,
            ...(orderType === 'dine_in' && { tableNumber }),
            ...(orderType === 'delivery' && { deliveryAddress })
        };

        try {
            const endpoint = this.currentEditingId 
                ? `/orders/${this.currentEditingId}`
                : '/orders/create-from-dashboard';
            const method = this.currentEditingId ? 'PUT' : 'POST';

            const response = await fetch(`${this.apiBaseUrl}${endpoint}`, {
                method,
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(orderData)
            });

            if (!response.ok) throw new Error('Failed to save order');

            this.showToast(this.currentEditingId ? 'Order updated!' : 'Order created!');
            this.closeModal('orderModal');
            this.selectedItems = []; // Clear selected items
            this.loadOrders();
        } catch (error) {
            console.error('Error saving order:', error);
            this.showToast('Error saving order: ' + error.message, 'error');
        }
    }

    editOrder(orderId) {
        this.openOrderModal(orderId);
    }

    async showConfirm(message, title = 'Confirm') {
        return new Promise((resolve) => {
            const modal = document.getElementById('confirm-modal');
            const titleEl = document.getElementById('confirm-title');
            const messageEl = document.getElementById('confirm-message');
            const okBtn = document.getElementById('confirm-ok-btn');
            const cancelBtn = document.getElementById('confirm-cancel-btn');

            titleEl.textContent = title;
            messageEl.textContent = message;

            modal.classList.add('active');

            const cleanup = () => {
                modal.classList.remove('active');
                okBtn.removeEventListener('click', onOk);
                cancelBtn.removeEventListener('click', onCancel);
            };

            const onOk = () => {
                cleanup();
                resolve(true);
            };

            const onCancel = () => {
                cleanup();
                resolve(false);
            };

            okBtn.addEventListener('click', onOk);
            cancelBtn.addEventListener('click', onCancel);
        });
    }

    async updateOrderStatus(orderId, status) {
        const statusLabels = {
            pending: 'Pending',
            preparing: 'Preparing',
            ready: 'Ready',
            completed: 'Completed',
            cancelled: 'Cancelled'
        };

        const confirm_msg = `Mark order as ${statusLabels[status]}?`;
        if (!(await this.showConfirm(confirm_msg, 'Update Order Status'))) return;

        try {
            const response = await fetch(`${this.apiBaseUrl}/orders/${orderId}`, {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ status: status })
            });

            if (!response.ok) throw new Error('Failed to update order status');

            this.showToast(`Order marked as ${statusLabels[status]}!`);
            this.loadOrders();
        } catch (error) {
            console.error('Error updating order status:', error);
            this.showToast('Error updating order: ' + error.message, 'error');
        }
    }

    async completeOrder(orderId) {
        if (!(await this.showConfirm('Mark this order as completed?', 'Complete Order'))) return;

        try {
            const response = await fetch(`${this.apiBaseUrl}/orders/${orderId}`, {
                method: 'PATCH',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ status: 'completed' })
            });

            if (!response.ok) throw new Error('Failed to update order');

            this.showToast('Order marked as completed!');
            this.loadOrders();
        } catch (error) {
            console.error('Error completing order:', error);
            this.showToast('Error updating order: ' + error.message, 'error');
        }
    }

    async cancelOrder(orderId) {
        if (!(await this.showConfirm('Cancel this order?', 'Cancel Order'))) return;

        try {
            const response = await fetch(`${this.apiBaseUrl}/orders/${orderId}`, {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ status: 'cancelled' })
            });

            if (!response.ok) throw new Error('Failed to cancel order');

            this.showToast('Order cancelled!');
            this.loadOrders();
        } catch (error) {
            console.error('Error cancelling order:', error);
            this.showToast('Error cancelling order: ' + error.message, 'error');
        }
    }

    openPaymentModal(orderId) {
        const order = this.orders.find(o => o.orderId === orderId);
        if (!order) return;

        const subtotal = order.items?.reduce((sum, item) => sum + (parseFloat(item.price) || 0), 0) || 0;
        const serviceCharge = subtotal * 0.15;
        const vat = (subtotal + serviceCharge) * 0.20;
        const total = subtotal + serviceCharge + vat;

        // Update breakdown display
        document.getElementById('breakdownSubtotal').textContent = subtotal.toFixed(2);
        document.getElementById('breakdownServiceCharge').textContent = serviceCharge.toFixed(2);
        document.getElementById('breakdownVAT').textContent = vat.toFixed(2);
        document.getElementById('paymentAmountDisplay').textContent = total.toFixed(2);
        
        // Store orderId and total for later use
        const cashOrderTotalField = document.getElementById('cashOrderTotal');
        if (cashOrderTotalField) {
            cashOrderTotalField.value = total.toFixed(2);
            cashOrderTotalField.dataset.orderId = orderId;
        }
        
        document.getElementById('paymentMethodSection').style.display = 'block';
        document.getElementById('cashPaymentForm').style.display = 'none';
        document.getElementById('paymentModal').classList.add('active');
    }

    selectPaymentMethod(method) {
        if (method === 'credit_card') {
            // Redirect to checkout payment page
            this.handlePaymentRedirect();
        } else if (method === 'cash') {
            // Show cash payment form with breakdown
            document.getElementById('paymentMethodSection').style.display = 'none';
            document.getElementById('cashPaymentForm').style.display = 'block';
            
            // Get orderId from the hidden field (it was stored in openPaymentModal)
            const cashOrderTotalField = document.getElementById('cashOrderTotal');
            const orderId = cashOrderTotalField?.dataset.orderId;
            
            // Update cash form breakdown with same values as selection screen
            const subtotal = document.getElementById('breakdownSubtotal').textContent;
            const serviceCharge = document.getElementById('breakdownServiceCharge').textContent;
            const vat = document.getElementById('breakdownVAT').textContent;
            const total = document.getElementById('paymentAmountDisplay').textContent;
            
            document.getElementById('cashBreakdownSubtotal').textContent = subtotal;
            document.getElementById('cashBreakdownServiceCharge').textContent = serviceCharge;
            document.getElementById('cashBreakdownVAT').textContent = vat;
            document.getElementById('cashBreakdownTotal').textContent = total;
            
            // Preserve orderId when updating value
            if (cashOrderTotalField) {
                cashOrderTotalField.value = total;
                if (orderId) {
                    cashOrderTotalField.dataset.orderId = orderId;
                }
            }
            
            document.getElementById('cashAmountReceived').value = '';
            document.getElementById('changeDisplay').style.display = 'none';
        }
    }

    cancelPaymentMethod() {
        // Go back to method selection
        document.getElementById('paymentMethodSection').style.display = 'block';
        document.getElementById('cashPaymentForm').style.display = 'none';
    }

    calculateChange() {
        const totalAmount = parseFloat(document.getElementById('cashOrderTotal').value) || 0;
        const amountReceived = parseFloat(document.getElementById('cashAmountReceived').value) || 0;
        
        if (amountReceived > 0) {
            const change = amountReceived - totalAmount;
            document.getElementById('changeAmount').value = change.toFixed(2);
            document.getElementById('changeDisplay').style.display = 'block';
            
            // Change color based on whether overpayment or exact
            if (change < 0) {
                document.getElementById('changeAmount').style.color = '#e74c3c'; // Red for insufficient
            } else {
                document.getElementById('changeAmount').style.color = '#27ae60'; // Green for valid
            }
        } else {
            document.getElementById('changeDisplay').style.display = 'none';
        }
    }

    async processCashPayment(event) {
        event.preventDefault();

        const cashOrderTotalField = document.getElementById('cashOrderTotal');
        const orderId = cashOrderTotalField?.dataset.orderId;
        const totalAmount = parseFloat(cashOrderTotalField?.value);
        const amountReceived = parseFloat(document.getElementById('cashAmountReceived').value);
        const staffNotes = document.getElementById('staffNotes').value;

        if (!orderId) {
            this.showToast('Order information missing', 'error');
            return;
        }

        if (!amountReceived) {
            this.showToast('Please enter the amount received', 'error');
            return;
        }

        if (amountReceived < totalAmount) {
            this.showToast('Amount received must be at least £' + totalAmount.toFixed(2), 'error');
            return;
        }

        const change = amountReceived - totalAmount;
        const API_BASE = window.API_BASE || window.YANJI_CONFIG?.API_BASE || 'https://yanji.tunesbasis.com';

        try {
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

            // Update order with payment status and mark as preparing
            const updateResponse = await fetch(`${API_BASE}/orders/${orderId}`, {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    status: 'preparing',
                    paymentStatus: 'completed',
                    paymentMethod: 'cash',
                    paymentId: payment.paymentId || payment.id
                })
            });

            if (!updateResponse.ok) {
                throw new Error('Failed to update order payment status');
            }

            // Show success message with change
            this.showToast(`✅ Cash payment processed successfully. Change: £${change.toFixed(2)}`, 'success');
            
            // Close modal and refresh orders
            closeModal('paymentModal');
            
            // Wait a moment then reload orders
            setTimeout(() => {
                this.loadOrders();
            }, 500);

        } catch (error) {
            this.showToast('❌ Payment processing failed: ' + error.message, 'error');
        }
    }

    async handlePaymentRedirect() {
        const cashOrderTotalField = document.getElementById('cashOrderTotal');
        const amount = parseFloat(cashOrderTotalField?.value);
        const orderId = cashOrderTotalField?.dataset.orderId;

        if (!amount || !orderId) {
            this.showToast('Invalid payment information', 'error');
            return;
        }

        // Store payment data securely in sessionStorage (not in URL)
        sessionStorage.setItem('paymentData', JSON.stringify({
            source: 'dashboard',
            orderId: orderId,
            totalAmount: amount
        }));

        // Redirect to payment processor page (clean URL)
        window.location.href = 'checkout-payment.php';
    }

    async handlePayment(e) {
        e.preventDefault();

        const method = document.getElementById('paymentMethod').value;
        const amount = parseFloat(document.getElementById('paymentAmount').value);
        const notes = document.getElementById('paymentNotes').value;
        const orderId = document.getElementById('paymentAmount').dataset.orderId;

        if (!method) {
            this.showToast('Please select a payment method', 'error');
            return;
        }

        try {
            // Here you would process the payment
            // For now, just update the order
            const response = await fetch(`${this.apiBaseUrl}/orders/${orderId}`, {
                method: 'PATCH',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ 
                    status: 'completed',
                    paymentMethod: method,
                    paymentAmount: amount,
                    paymentNotes: notes
                })
            });

            if (!response.ok) throw new Error('Failed to process payment');

            this.showToast('Payment processed!');
            this.closeModal('paymentModal');
            this.loadOrders();
        } catch (error) {
            console.error('Error processing payment:', error);
            this.showToast('Error processing payment: ' + error.message, 'error');
        }
    }

    startAutoRefresh() {
        this.refreshInterval = setInterval(() => this.loadOrders(), 5000);
    }

    showToast(message, type = 'success') {
        const toast = document.getElementById('toast');
        toast.textContent = message;
        toast.className = 'toast active ' + (type === 'error' ? 'error' : '');
        
        setTimeout(() => {
            toast.classList.remove('active');
        }, 3000);
    }

    destroy() {
        if (this.refreshInterval) clearInterval(this.refreshInterval);
    }

    closeModal(modalId) {
        document.getElementById(modalId)?.classList.remove('active');
    }
}

// Global helper functions (kept for backwards compatibility)
let dashboard;
window.addEventListener('load', () => {
    dashboard = new OrderDashboard();
});

window.addEventListener('beforeunload', () => {
    if (dashboard) dashboard.destroy();
});
