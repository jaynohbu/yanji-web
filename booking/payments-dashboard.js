/**
 * Payment Management Dashboard - JavaScript
 * Handle payment history, refunds, and transaction management
 */

class PaymentDashboard {
    constructor() {
        this.apiBaseUrl = window.API_BASE || window.YANJI_CONFIG?.API_BASE || 'https://yanji.tunesbasis.com';
        this.payments = [];
        this.filteredPayments = [];
        this.selectedRefund = null;
        this.refreshInterval = null;
        this.init();
    }

    init() {
        this.setupEventListeners();
        this.loadPayments();
        this.startAutoRefresh();
    }

    setupEventListeners() {
        document.getElementById('searchInput')?.addEventListener('input', () => this.applyFilters());
        document.getElementById('statusFilter')?.addEventListener('change', () => this.applyFilters());

        // Close buttons for modals
        document.querySelectorAll('.close-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const modal = e.target.closest('.modal');
                if (modal) this.closeModal(modal.id);
            });
        });

        // Close modal when clicking outside
        document.querySelectorAll('.modal').forEach(modal => {
            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    this.closeModal(modal.id);
                }
            });
        });
    }

    // Helper function to get table display name
    getTableName(tableNumber) {
        if (!tableNumber || tableNumber === 'N/A') return 'N/A';
        
        const num = parseInt(tableNumber);
        if (isNaN(num)) return String(tableNumber);
        
        const base = Math.floor(num / 100);
        const suffix = num % 100;
        
        if (base > 0) {
            if (suffix === 1) return base + 'A';
            if (suffix === 2) return base + 'B';
            if (suffix === 3) return base + 'C';
            if (suffix === 4) return base + 'D';
        }
        
        return String(tableNumber);
    }

    async loadPayments() {
        const loadingIndicator = document.getElementById('loadingIndicator');
        loadingIndicator.style.display = 'block';

        try {
            // Load both payments and orders
            const paymentsResponse = await fetch(`${this.apiBaseUrl}/payments`, {
                method: 'GET',
                headers: {
                    'Authorization': `Bearer ${localStorage.getItem('token')}`,
                    'Content-Type': 'application/json'
                }
            });

            const ordersResponse = await fetch(`${this.apiBaseUrl}/orders`, {
                method: 'GET',
                headers: {
                    'Authorization': `Bearer ${localStorage.getItem('token')}`,
                    'Content-Type': 'application/json'
                }
            });

            if (!paymentsResponse.ok || !ordersResponse.ok) {
                if (paymentsResponse.status === 404) {
                    this.showEmptyState();
                    return;
                }
                throw new Error(`API error: ${paymentsResponse.status || ordersResponse.status}`);
            }

            const paymentsData = await paymentsResponse.json();
            const ordersData = await ordersResponse.json();
            
            const payments = Array.isArray(paymentsData) ? paymentsData : (paymentsData.data || paymentsData.payments || []);
            const orders = Array.isArray(ordersData) ? ordersData : (ordersData.data || ordersData.orders || []);
            
            // Create a map of orders by orderId for quick lookup
            const ordersMap = {};
            orders.forEach(order => {
                ordersMap[order.orderId] = order;
            });
            
            // Transform payment records to dashboard format and enrich with order data
            this.payments = payments.map(payment => {
                const order = ordersMap[payment.orderId] || {};
                const tableNumber = order.tableNumber || 'N/A';
                const tableName = this.getTableName(tableNumber);
                
                // Calculate remaining balance if refunded
                let remainingBalance = 0;
                if (payment.status === 'refunded' && payment.refundAmount) {
                    remainingBalance = Math.max(0, (payment.amount || 0) - (payment.refundAmount || 0));
                }
                
                return {
                    id: payment.paymentId || payment.id,
                    orderId: payment.orderId || 'N/A',
                    amount: payment.amount ? Math.round(payment.amount / 100) : 0,
                    status: payment.status || 'pending',
                    method: payment.method || payment.sourceId || 'Unknown',
                    createdAt: payment.createdAt || payment.date,
                    squarePaymentId: payment.squarePaymentId || '',
                    notes: payment.notes || '',
                    refundAmount: payment.refundAmount || 0,
                    remainingBalance: remainingBalance,
                    customerName: order.customerName || 'Guest',
                    tableNumber: tableNumber,
                    tableName: tableName
                };
            }).filter(p => p.id);
            
            this.applyFilters();
            this.updateSummary();
            this.renderPayments();
        } catch (error) {
            console.error('Error loading payments:', error);
            this.showEmptyState();
        } finally {
            loadingIndicator.style.display = 'none';
        }
    }

    applyFilters() {
        const searchTerm = document.getElementById('searchInput')?.value.toLowerCase() || '';
        const statusFilter = document.getElementById('statusFilter')?.value || '';

        this.filteredPayments = this.payments.filter(payment => {
            const matchesSearch = !searchTerm || 
                (payment.id && payment.id.includes(searchTerm)) ||
                (payment.orderId && payment.orderId.includes(searchTerm)) ||
                (payment.customerName && payment.customerName.toLowerCase().includes(searchTerm));
            
            const matchesStatus = !statusFilter || 
                (payment.status && payment.status.toLowerCase() === statusFilter.toLowerCase());

            return matchesSearch && matchesStatus;
        });

        this.renderPayments();
    }

    renderPayments() {
        const paymentsTable = document.getElementById('paymentsTable');
        const paymentsBody = document.getElementById('paymentsBody');
        const emptyState = document.getElementById('emptyState');

        if (this.filteredPayments.length === 0) {
            paymentsTable.style.display = 'none';
            emptyState.style.display = 'block';
            return;
        }

        paymentsTable.style.display = 'table';
        emptyState.style.display = 'none';

        paymentsBody.innerHTML = this.filteredPayments.map(payment => `
            <tr>
                <td class="payment-customer">
                    <div>${this.escapeHtml(payment.customerName || 'Guest')}</div>
                    <div style="font-size: 12px; color: #888;">Table ${this.escapeHtml(payment.tableName)}</div>
                </td>
                <td class="payment-amount">£${this.formatAmount(payment.amount || 0)}</td>
                ${payment.status === 'refunded' && payment.remainingBalance > 0 ? `
                    <td class="payment-balance">£${this.formatAmount(payment.remainingBalance)}</td>
                ` : `
                    <td class="payment-balance">-</td>
                `}
                <td>
                    <span class="payment-status status-${payment.status?.toLowerCase() || 'pending'}">
                        ${payment.status || 'Pending'}
                    </span>
                </td>
                <td class="payment-method">${this.escapeHtml(payment.method || 'Unknown')}</td>
                <td class="payment-date">${this.formatDate(payment.createdAt || payment.date)}</td>
                <td class="actions">
                    <button class="btn btn-secondary btn-small" onclick="paymentDashboard.viewPaymentDetails('${this.escapeHtml(payment.id)}')">
                        Details
                    </button>
                </td>
            </tr>
        `).join('');
    }

    updateSummary() {
        const total = this.payments.length;
        const totalAmount = this.payments.reduce((sum, p) => sum + (p.amount || 0), 0);
        const completed = this.payments.filter(p => p.status?.toLowerCase() === 'completed').length;
        const refunded = this.payments.filter(p => p.status?.toLowerCase() === 'refunded').length;

        document.getElementById('totalCount').textContent = total;
        document.getElementById('totalRevenue').textContent = `$${this.formatAmount(totalAmount)}`;
        document.getElementById('completedCount').textContent = completed;
        document.getElementById('refundedCount').textContent = refunded;
    }

    viewPaymentDetails(paymentId) {
        const payment = this.payments.find(p => p.id === paymentId);
        if (!payment) return;

        const details = `
Customer: ${payment.customerName || 'Guest'}
Table: ${payment.tableName}
Amount: £${this.formatAmount(payment.amount || 0)}
${payment.status === 'refunded' && payment.remainingBalance > 0 ? `Remaining Balance: £${this.formatAmount(payment.remainingBalance)}` : ''}
Status: ${payment.status || 'Pending'}
Method: ${payment.method || 'Unknown'}
Date: ${this.formatDate(payment.createdAt || payment.date)}
Order ID: ${payment.orderId || 'N/A'}
Payment ID: ${payment.id}

${payment.notes ? `Notes: ${payment.notes}` : ''}
        `.trim();

        alert(details);
    }

    clearFilters() {
        document.getElementById('searchInput').value = '';
        document.getElementById('statusFilter').value = '';
        this.applyFilters();
    }

    openModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.add('show');
            document.body.style.overflow = 'hidden';
        }
    }

    closeModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.remove('show');
            document.body.style.overflow = 'auto';
        }
    }

    startAutoRefresh() {
        // Refresh every 30 seconds
        this.refreshInterval = setInterval(() => this.loadPayments(), 30000);
    }

    formatAmount(amount) {
        return parseFloat(amount).toFixed(2);
    }

    formatDate(dateString) {
        if (!dateString) return 'N/A';
        try {
            return new Date(dateString).toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'short',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        } catch {
            return dateString;
        }
    }

    escapeHtml(text) {
        if (!text) return '';
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return String(text).replace(/[&<>"']/g, m => map[m]);
    }

    showEmptyState() {
        document.getElementById('paymentsTable').style.display = 'none';
        document.getElementById('emptyState').style.display = 'block';
    }
}

// Initialize dashboard when page loads
let paymentDashboard;
document.addEventListener('DOMContentLoaded', () => {
    paymentDashboard = new PaymentDashboard();
});

// Cleanup on page unload
window.addEventListener('beforeunload', () => {
    if (paymentDashboard && paymentDashboard.refreshInterval) {
        clearInterval(paymentDashboard.refreshInterval);
    }
});
