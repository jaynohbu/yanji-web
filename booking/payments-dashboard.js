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

    async loadPayments() {
        const loadingIndicator = document.getElementById('loadingIndicator');
        loadingIndicator.style.display = 'block';

        try {
            const response = await fetch(`${this.apiBaseUrl}/payments`, {
                method: 'GET',
                headers: {
                    'Authorization': `Bearer ${localStorage.getItem('token')}`,
                    'Content-Type': 'application/json'
                }
            });

            if (!response.ok) {
                if (response.status === 404) {
                    this.showEmptyState();
                    return;
                }
                throw new Error(`API error: ${response.status}`);
            }

            const data = await response.json();
            const payments = Array.isArray(data) ? data : (data.data || data.payments || []);
            
            // Transform payment records to dashboard format
            this.payments = payments.map(payment => ({
                id: payment.paymentId || payment.id,
                orderId: payment.orderId || 'N/A',
                amount: payment.amount ? Math.round(payment.amount / 100) : 0,
                status: payment.status || 'pending',
                method: payment.method || payment.sourceId || 'Unknown',
                createdAt: payment.createdAt || payment.date,
                squarePaymentId: payment.squarePaymentId || '',
                notes: payment.notes || ''
            })).filter(p => p.id);
            
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
                <td class="payment-id">${this.escapeHtml(payment.id || 'N/A')}</td>
                <td class="payment-amount">$${this.formatAmount(payment.amount || 0)}</td>
                <td>
                    <span class="payment-status status-${payment.status?.toLowerCase() || 'pending'}">
                        ${payment.status || 'Pending'}
                    </span>
                </td>
                <td class="payment-method">${this.escapeHtml(payment.method || 'Unknown')}</td>
                <td class="payment-date">${this.formatDate(payment.createdAt || payment.date)}</td>
                <td class="actions">
                    ${payment.status?.toLowerCase() !== 'refunded' ? `
                        <button class="btn btn-danger btn-small" onclick="paymentDashboard.openRefundModal('${this.escapeHtml(payment.id)}', ${payment.amount || 0})">
                            Refund
                        </button>
                    ` : '<span style="color: #888; font-size: 11px;">Refunded</span>'}
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

    openRefundModal(paymentId, amount) {
        document.getElementById('refundPaymentId').value = paymentId;
        document.getElementById('refundAmount').value = amount;
        document.getElementById('refundAmount').max = amount;
        document.getElementById('refundReason').value = '';
        document.getElementById('refundNotes').value = '';

        this.selectedRefund = { paymentId, amount };
        this.openModal('refundModal');
    }

    async submitRefund() {
        if (!this.selectedRefund) return;

        const amount = parseFloat(document.getElementById('refundAmount').value);
        const reason = document.getElementById('refundReason').value;
        const notes = document.getElementById('refundNotes').value;

        if (!amount || amount <= 0 || amount > this.selectedRefund.amount) {
            alert('Please enter a valid refund amount');
            return;
        }

        if (!reason) {
            alert('Please select a reason for the refund');
            return;
        }

        try {
            const response = await fetch(`${this.apiBaseUrl}/payments/${this.selectedRefund.paymentId}/refund`, {
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${localStorage.getItem('token')}`,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    amount,
                    reason,
                    notes
                })
            });

            if (!response.ok) {
                throw new Error(`Refund failed: ${response.status}`);
            }

            alert('✓ Refund processed successfully!');
            this.closeModal('refundModal');
            this.loadPayments();
        } catch (error) {
            console.error('Error processing refund:', error);
            alert('Error processing refund: ' + error.message);
        }
    }

    viewPaymentDetails(paymentId) {
        const payment = this.payments.find(p => p.id === paymentId);
        if (!payment) return;

        const details = `
Payment ID: ${payment.id}
Amount: $${this.formatAmount(payment.amount || 0)}
Status: ${payment.status || 'Pending'}
Method: ${payment.method || 'Unknown'}
Date: ${this.formatDate(payment.createdAt || payment.date)}
Order ID: ${payment.orderId || 'N/A'}

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
