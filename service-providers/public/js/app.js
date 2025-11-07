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
    
    async exportData() {
        try {
            // Show loading notification
            this.showNotification('Preparing export...', 'info');
            
            // Get the export button and transform it to stop button
            const exportButton = document.querySelector('button[onclick*="exportData"]');
            if (exportButton) {
                exportButton.innerHTML = '<i class="fas fa-stop"></i> Stop Export';
                exportButton.className = 'btn-danger';
                exportButton.style.background = '#dc3545';
                exportButton.style.color = 'white';
                exportButton.onclick = () => this.stopExport();
            }
            
            // Wait for clusters data to be fetched
            const clusters = await getAllClusters();
            
            // Fetch complete data hierarchy for each cluster
            const completeData = {
                clusters: [],
                exportDate: new Date().toISOString(),
                version: '1.0.0',
                totalClusters: 0,
                totalDatabases: 0,
                totalTables: 0,
                totalFields: 0
            };
            
            let totalDatabases = 0;
            let totalTables = 0;
            let totalFields = 0;
            
            // Initialize stop flag
            this.exportStopped = false;
            
            // Process each cluster to get complete data
            for (const cluster of clusters) {
                // Check if export was stopped
                if (this.exportStopped) {
                    console.log('Export stopped by user');
                    return;
                }
                
                try {
                    console.log(`Processing cluster: ${cluster.name} (ID: ${cluster.id})`);
                    
                    // Fetch databases for this cluster
                    const databasesResponse = await fetch(`https://dev-master-clusterseed.internetcash.io/api/v2.0.0/clusters_json/${cluster.id}/databases`);
                    
                    if (!databasesResponse.ok) {
                        console.log(`Skipping cluster ${cluster.id} - databases API returned ${databasesResponse.status}`);
                        continue;
                    }
                    
                    const databasesResult = await databasesResponse.json();
                    let databases = [];
                    
                    if (databasesResult.success && databasesResult.data && databasesResult.data.length > 0) {
                        console.log(`Cluster ${cluster.name} has ${databasesResult.data.length} databases`);
                        
                        // Process each database to get tables
                        for (const database of databasesResult.data) {
                            // Check if export was stopped
                            if (this.exportStopped) {
                                console.log('Export stopped by user');
                                return;
                            }
                            
                            try {
                                console.log(`Processing database: ${database.database_name} (ID: ${database.id})`);
                                
                                // Fetch tables for this database
                                const tablesResponse = await fetch(`https://dev-master-clusterseed.internetcash.io/api/v2.0.0/databases_json/${database.id}/tables`);
                                
                                if (!tablesResponse.ok) {
                                    console.log(`Skipping database ${database.id} - tables API returned ${tablesResponse.status}`);
                                    continue;
                                }
                                
                                const tablesResult = await tablesResponse.json();
                                let tables = [];
                                
                                if (tablesResult.success && tablesResult.data && tablesResult.data.length > 0) {
                                    console.log(`Database ${database.database_name} has ${tablesResult.data.length} tables`);
                                    
                                    // Process each table to get fields
                                    for (const table of tablesResult.data) {
                                        // Check if export was stopped
                                        if (this.exportStopped) {
                                            console.log('Export stopped by user');
                                            return;
                                        }
                                        
                                        try {
                                            console.log(`Processing table: ${table.table_name} (ID: ${table.id})`);
                                            
                                            // Fetch fields for this table
                                            const fieldsResponse = await fetch(`https://dev-master-clusterseed.internetcash.io/api/v2.0.0/tables_json/${table.id}/fields`);
                                            
                                            if (!fieldsResponse.ok) {
                                                console.log(`Skipping table ${table.id} - fields API returned ${fieldsResponse.status}`);
                                                continue;
                                            }
                                            
                                            const fieldsData = await fieldsResponse.json();
                                            let fields = [];
                                            
                                            if (Array.isArray(fieldsData) && fieldsData.length > 0) {
                                                fields = fieldsData.map(field => ({
                                                    id: field.id,
                                                    name: field.field_name,
                                                    type: field.comment ? field.comment.split('~#')[0] : 'Unknown',
                                                    description: field.description || 'No description available'
                                                }));
                                                totalFields += fields.length;
                                                console.log(`Table ${table.table_name} has ${fields.length} fields`);
                                            } else {
                                                console.log(`Table ${table.table_name} has no fields`);
                                            }
                                            
                                            // Add table with fields (even if empty)
                                            table.fields = fields;
                                            tables.push(table);
                                            totalTables++;
                                            
                                        } catch (error) {
                                            console.log(`Error processing table ${table.id}:`, error.message);
                                            continue;
                                        }
                                    }
                                } else {
                                    console.log(`Database ${database.database_name} has no tables`);
                                }
                                
                                // Add database with tables (even if empty)
                                database.tables = tables;
                                databases.push(database);
                                totalDatabases++;
                                
                            } catch (error) {
                                console.log(`Error processing database ${database.id}:`, error.message);
                                continue;
                            }
                        }
                    } else {
                        console.log(`Cluster ${cluster.name} has no databases`);
                    }
                    
                    // Add cluster with databases (even if empty)
                    const completeCluster = {
                        ...cluster,
                        databases: databases
                    };
                    
                    completeData.clusters.push(completeCluster);
                    
                } catch (error) {
                    console.log(`Error processing cluster ${cluster.id}:`, error.message);
                    continue;
                }
            }
            
            // Update totals
            completeData.totalClusters = completeData.clusters.length;
            completeData.totalDatabases = totalDatabases;
            completeData.totalTables = totalTables;
            completeData.totalFields = totalFields;
            
            const dataStr = JSON.stringify(completeData, null, 2);
            const dataBlob = new Blob([dataStr], { type: 'application/json' });
            
            const link = document.createElement('a');
            link.href = URL.createObjectURL(dataBlob);
            link.download = `mongodb-complete-data-${new Date().toISOString().split('T')[0]}.json`;
            link.click();
            
            // Clean up the URL object
            URL.revokeObjectURL(link.href);
            
            this.showNotification(`Export completed! ${completeData.totalClusters} clusters, ${completeData.totalDatabases} databases, ${completeData.totalTables} tables, ${completeData.totalFields} fields`, 'success');
        } catch (error) {
            console.error('Export failed:', error);
            this.showNotification('Export failed. Please try again.', 'error');
        } finally {
            // Restore the export button
            const exportButton = document.querySelector('button[onclick*="exportData"]');
            if (exportButton) {
                exportButton.innerHTML = '<i class="fas fa-download"></i> Export All';
                exportButton.className = 'btn-secondary';
                exportButton.style.background = '';
                exportButton.style.color = '';
                exportButton.onclick = () => this.exportData();
            }
        }
    }
    

    
    stopExport() {
        // Set a flag to stop the export
        this.exportStopped = true;
        
        // Show notification
        this.showNotification('Export stopped by user', 'warning');
        
        // Restore the export button immediately
        const exportButton = document.querySelector('button[onclick*="exportData"]');
        if (exportButton) {
            exportButton.innerHTML = '<i class="fas fa-download"></i> Export All';
            exportButton.className = 'btn-secondary';
            exportButton.style.background = '';
            exportButton.style.color = '';
            exportButton.onclick = () => this.exportData();
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