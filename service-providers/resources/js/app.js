import './bootstrap';

// Main application file for Monitoring App
class MongoDBManualBook {
    constructor() {
        this.modal = document.getElementById('detailModal');
        this.modalTitle = document.getElementById('modalTitle');
        this.modalBody = document.getElementById('modalBody');
        this.exportBtn = document.getElementById('exportBtn');
        
        this.initializeApp();
    }
    
    initializeApp() {
        this.setupEventListeners();
        this.setupKeyboardShortcuts();
        this.initializeExport();
        this.initializeCopyFunctionality();
    }
    
    setupEventListeners() {
        // Export button
        if (this.exportBtn) {
            this.exportBtn.addEventListener('click', () => {
                this.exportData();
            });
        }
        
        // Modal close button
        const modalClose = document.querySelector('.modal-close');
        if (modalClose) {
            modalClose.addEventListener('click', () => {
                this.closeModal();
            });
        }
        
        // Close modal when clicking outside
        if (this.modal) {
            this.modal.addEventListener('click', (e) => {
                if (e.target === this.modal) {
                    this.closeModal();
                }
            });
        }
        
        // Handle escape key to close modal
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                this.closeModal();
            }
        });
    }
    
    setupKeyboardShortcuts() {
        document.addEventListener('keydown', (e) => {
            // Ctrl/Cmd + K to focus search
            if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                e.preventDefault();
                focusSearch();
            }
            
            // Ctrl/Cmd + E to export
            if ((e.ctrlKey || e.metaKey) && e.key === 'e') {
                e.preventDefault();
                this.exportData();
            }
        });
    }
    
    initializeExport() {
        // Add export functionality
        console.log('Export functionality initialized');
    }
    
    initializeCopyFunctionality() {
        // Add copy functionality for field names
        document.addEventListener('click', (e) => {
            if (e.target.closest('.btn-secondary') && e.target.closest('.field-actions')) {
                const fieldName = e.target.closest('.field-details').querySelector('.field-title h3').textContent;
                this.copyToClipboard(fieldName);
                this.showNotification('Field name copied to clipboard!', 'success');
            }
        });
    }
    
    copyToClipboard(text) {
        if (navigator.clipboard) {
            navigator.clipboard.writeText(text);
        } else {
            // Fallback for older browsers
            const textArea = document.createElement('textarea');
            textArea.value = text;
            document.body.appendChild(textArea);
            textArea.select();
            document.execCommand('copy');
            document.body.removeChild(textArea);
        }
    }
    
    exportData() {
        try {
            const data = {
                clusters: getAllClusters(),
                exportDate: new Date().toISOString(),
                version: '1.0.0'
            };
            
            const dataStr = JSON.stringify(data, null, 2);
            const dataBlob = new Blob([dataStr], { type: 'application/json' });
            
            const link = document.createElement('a');
            link.href = URL.createObjectURL(dataBlob);
            link.download = `mongodb-manual-${new Date().toISOString().split('T')[0]}.json`;
            link.click();
            
            this.showNotification('Data exported successfully!', 'success');
        } catch (error) {
            console.error('Export failed:', error);
            this.showNotification('Export failed. Please try again.', 'error');
        }
    }
    
    showModal(title, content) {
        if (this.modal && this.modalTitle && this.modalBody) {
            this.modalTitle.textContent = title;
            this.modalBody.innerHTML = content;
            this.modal.style.display = 'block';
            
            // Focus trap for accessibility
            this.setupModalFocusTrap();
        }
    }
    
    closeModal() {
        if (this.modal) {
            this.modal.style.display = 'none';
            this.modalTitle.textContent = '';
            this.modalBody.innerHTML = '';
        }
    }
    
    setupModalFocusTrap() {
        const focusableElements = this.modal.querySelectorAll(
            'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'
        );
        
        if (focusableElements.length > 0) {
            const firstElement = focusableElements[0];
            const lastElement = focusableElements[focusableElements.length - 1];
            
            firstElement.focus();
            
            this.modal.addEventListener('keydown', (e) => {
                if (e.key === 'Tab') {
                    if (e.shiftKey) {
                        if (document.activeElement === firstElement) {
                            e.preventDefault();
                            lastElement.focus();
                        }
                    } else {
                        if (document.activeElement === lastElement) {
                            e.preventDefault();
                            firstElement.focus();
                        }
                    }
                }
            });
        }
    }
    
    showNotification(message, type = 'info') {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.innerHTML = `
            <div class="notification-content">
                <i class="fas fa-${this.getNotificationIcon(type)}"></i>
                <span>${message}</span>
                <button class="notification-close" onclick="this.parentElement.parentElement.remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
        
        // Add styles
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: ${this.getNotificationColor(type)};
            color: white;
            padding: 1rem 1.5rem;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            z-index: 10000;
            max-width: 400px;
            animation: slideIn 0.3s ease-out;
        `;
        
        // Add to page
        document.body.appendChild(notification);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            if (notification.parentElement) {
                notification.remove();
            }
        }, 5000);
    }
    
    getNotificationIcon(type) {
        switch (type) {
            case 'success': return 'check-circle';
            case 'error': return 'exclamation-circle';
            case 'warning': return 'exclamation-triangle';
            default: return 'info-circle';
        }
    }
    
    getNotificationColor(type) {
        switch (type) {
            case 'success': return '#28a745';
            case 'error': return '#dc3545';
            case 'warning': return '#ffc107';
            default: return '#17a2b8';
        }
    }
    
    // Utility function to format file size
    formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }
    
    // Utility function to format date
    formatDate(date) {
        return new Date(date).toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    }
}

// Global functions for modal
function closeModal() {
    if (window.app) {
        window.app.closeModal();
    }
}

function showModal(title, content) {
    if (window.app) {
        window.app.showModal(title, content);
    }
}

// Initialize application when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    // Add CSS for notifications
    const notificationStyles = document.createElement('style');
    notificationStyles.textContent = `
        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        .notification-content {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .notification-close {
            background: none;
            border: none;
            color: white;
            cursor: pointer;
            padding: 0.25rem;
            margin-left: auto;
        }
        
        .notification-close:hover {
            opacity: 0.8;
        }
        
        .search-result-item.selected {
            background-color: #e3f2fd;
        }
    `;
    document.head.appendChild(notificationStyles);
    
    // Initialize the main application
    window.app = new MongoDBManualBook();
    
    // Show welcome message
    setTimeout(() => {
        if (window.app) {
            window.app.showNotification('Welcome to Monitoring App! Use Ctrl+K to search.', 'info');
        }
    }, 1000);
});

// Export global functions
window.closeModal = closeModal;
window.showModal = showModal; 