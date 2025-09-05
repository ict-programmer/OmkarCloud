class NavigationManager {
    constructor() {
        this.treeView = document.getElementById('treeView');
        this.contentArea = document.getElementById('contentArea');
        this.breadcrumb = document.getElementById('breadcrumb');
        this.rightPanel = document.getElementById('rightPanel');
        this.panelContent = document.getElementById('panelContent');
        this.recentItems = document.getElementById('recentItems');
        this.currentFilter = 'all';
        this.recentlyViewed = [];
        
        this.initializeNavigation();
    }
    
    initializeNavigation() {
        this.renderTreeView();
        this.loadRecentlyViewed();
        this.updateStatistics();
    }
    
    renderTreeView() {
        const clusters = getAllClusters();
        let treeHTML = '';
        
        clusters.forEach(cluster => {
            if (this.shouldShowItem('cluster', cluster)) {
                treeHTML += this.renderClusterNode(cluster);
            }
        });
        
        console.log("✅ Final treeHTML length:", treeHTML.length); // 👈 Add this

        this.treeView.innerHTML = treeHTML;

        console.log('📌 TreeView now contains', this.treeView.querySelectorAll('.tree-item').length, 'tree-items');
        console.log('📌 Children containers found:', this.treeView.querySelectorAll('.tree-children').length);
        console.log('📌 Sample last cluster rendered:', clusters.at(-1)?.cluster_name);

        this.addTreeEventListeners();
        
        const childrenContainers = this.treeView.querySelectorAll('.tree-children');
        childrenContainers.forEach(container => {
            const parentArrow = container.previousElementSibling?.querySelector('.tree-toggle');
            if (!parentArrow?.classList.contains('rotate')) {
                container.style.display = 'none';
            }
        });

    }
    
    renderClusterNode(cluster) {
        console.log(`📦 Rendering cluster: ${cluster.cluster_name} (ID: ${cluster.id})`);
        const visibleTables = cluster.tables.filter(table => this.shouldShowItem('table', table));
        const hasChildren = visibleTables.length > 0;
        
        let clusterHTML = `
            <div class="tree-item" data-type="cluster" data-id="${cluster.id}">
                <div class="tree-node" onclick="window.navigationManager.handleTreeItemClick(this.parentElement)">
                    <i class="fas fa-chevron-right tree-toggle ${hasChildren ? 'expandable' : 'hidden'}" data-target="${cluster.id}"></i>
                    <i class="fas fa-server tree-icon"></i>
                    <span class="tree-label">${cluster.cluster_name}</span>
                </div>
            </div>
        `;
        
        // Add tables if they should be shown
        if (hasChildren) {
            const isExpanded = true; // or fetch from state if managing open clusters dynamically
            clusterHTML += `<div class="tree-children" id="children-${cluster.id}" style="display: ${isExpanded ? 'block' : 'none'};">`;
            visibleTables.forEach(table => {
                clusterHTML += this.renderTableNode(table, cluster);
            });
            clusterHTML += '</div>';
        }

        
        return clusterHTML;
    }
    
    renderTableNode(table, cluster) {
        const visibleFields = table.fields || []; // ✅ fallback if not yet loaded
        const hasChildren = true; // ✅ Always show arrow
        
        let tableHTML = `
            <div class="tree-item" data-type="table" data-id="${table.id}" data-cluster-id="${cluster.id}">
                <div class="tree-node" onclick="window.navigationManager.handleTreeItemClick(this.parentElement)">
                    <i class="fas fa-chevron-right tree-toggle expandable" data-target="${table.id}"></i>
                    <i class="fas fa-table tree-icon"></i>
                    <span class="tree-label">${table.table_name}</span>
                </div>
            </div>
        `;
        
        // Add fields if they should be shown
        if (hasChildren) {
            tableHTML += `<div class="tree-children" id="children-${table.id}">`;
            visibleFields.forEach(field => {
                tableHTML += this.renderFieldNode(field, table, cluster);
            });
            tableHTML += '</div>';
        }
        
        return tableHTML;
    }
    
    renderFieldNode(field, table, cluster) {
        return `
            <div class="tree-item" data-type="field" data-id="${table.id}-${field.name}" data-table-id="${table.id}" data-cluster-id="${cluster.id}">
                <div class="tree-node" onclick="window.navigationManager.handleTreeItemClick(this.parentElement)">
                    <i class="fas fa-chevron-right tree-toggle hidden"></i>
                    <i class="fas fa-tags tree-icon"></i>
                    <span class="tree-label">${field.name}</span>
                </div>
            </div>
        `;
    }
    
    shouldShowItem(type, item) {
        if (this.currentFilter === 'all') return true;
        return type === this.currentFilter;
    }

    addTreeEventListeners() {
    const expandableArrows = this.treeView.querySelectorAll('.tree-toggle');

    expandableArrows.forEach(arrow => {
        arrow.addEventListener('click', async (e) => {
            e.stopPropagation();

            const parent = arrow.closest('.tree-item');
            const type = parent?.dataset?.type;

            // 📦 Expand/Collapse Clusters
            if (type === 'cluster') {
                const clusterId = parent.dataset.id;
                const container = document.getElementById(`children-${clusterId}`);
                if (container) {
                    const isVisible = container.style.display === 'block';
                    container.style.display = isVisible ? 'none' : 'block';
                    arrow.classList.toggle('rotate', !isVisible);
                }
                return;
            }

            // 📋 Expand/Load/Collapse Tables
            if (type === 'table') {
                const tableId = parent.dataset.id;
                const clusterId = parent.dataset.clusterId;

                const cluster = getClusterById(clusterId);
                const table = cluster?.databases
                    .flatMap(db => db.tables)
                    .find(t => t.id === tableId);

                if (!table) return;

                const container = document.getElementById(`children-${tableId}`);

                // 👇 Show loader before loading fields
                showLoader();

                // Load fields if not already
                if (!table.fields || table.fields.length === 0) {
                    await loadFieldsForTable(table);
                    if (container) {
                        container.innerHTML = table.fields
                            .map(field => this.renderFieldNode(field, table, cluster))
                            .join('');
                    }
                }

                // Expand or collapse
                if (container) {
                    const isVisible = container.style.display === 'block';
                    container.style.display = isVisible ? 'none' : 'block';
                    arrow.classList.toggle('rotate', !isVisible);
                }

                // 👇 Hide loader after all done
                hideLoader();
            }
        });
    });
}

    async handleTreeItemClick(item) {
        const type = item.dataset.type;
        const id = item.dataset.id;
        const clusterId = item.dataset.clusterId;

        // Remove active class from all items
        this.treeView.querySelectorAll('.tree-item').forEach(i => i.classList.remove('active'));

        // Add active class to clicked item
        item.classList.add('active');

        switch (type) {
            case 'cluster':
                this.showCluster(id);
                break;

            case 'table':
            const tableId = item.dataset.id;
            const clusterId = item.dataset.clusterId;

            const cluster = getClusterById(clusterId);
            const table = cluster?.databases.flatMap(db => db.tables).find(t => t.id === tableId);

            if (!table) return;

            const container = document.getElementById(`children-${table.id}`);

            // ✅ Fetch if not already loaded (but DO NOT expand here)
            if (!table.fields || table.fields.length === 0) {
                showLoader();
                await loadFieldsForTable(table);
                hideLoader();

                if (container) {
                    container.innerHTML = table.fields
                        .map(field => this.renderFieldNode(field, table, cluster))
                        .join('');
                }
            }

            // ✅ Load table details (right panel)
            this.showTable(tableId);
            break;

            case 'field':
                this.showField(id);
                break;
        }
    }

    
    toggleTreeItem(arrow) {
        const targetId = arrow.dataset.target;
        const childrenContainer = document.getElementById(`children-${targetId}`);
        
        if (childrenContainer) {
            const isExpanded = childrenContainer.style.display !== 'none';
            
            if (isExpanded) {
                childrenContainer.style.display = 'none';
                arrow.classList.remove('expanded');
            } else {
                childrenContainer.style.display = 'block';
                arrow.classList.add('expanded');
            }
        }
    }
    
    showCluster(clusterId) {
        const cluster = getClusterById(clusterId);
        if (!cluster) return;
        
        this.updateBreadcrumb([{ name: 'Home', id: 'home' }, { name: cluster.cluster_name, id: clusterId }]);
        this.addToRecentlyViewed('cluster', clusterId, cluster.cluster_name);
        
        const clusterHTML = `
            <div class="cluster-view">
                <div class="content-header">
                    <div class="content-title">
                        <i class="fas fa-server"></i>
                        <h2>${cluster.cluster_name}</h2>
                    </div>
                    <div class="content-actions">
                        <button class="btn-primary" onclick="window.navigationManager.showClusterDetails('${clusterId}')">
                            <i class="fas fa-info-circle"></i>
                            Details
                        </button>
                        <button class="btn-secondary">
                            <i class="fas fa-cog"></i>
                            Settings
                        </button>
                    </div>
                </div>
                
                <div class="description-card">
                    <h3>Description</h3>
                    <p>${cluster.description}</p>
                </div>
                
                <div class="description-card">
                    <h3>Connection Details</h3>
                    <p><strong>Host:</strong> ${cluster.host}</p>
                    <p><strong>Port:</strong> ${cluster.port}</p>
                    <p><strong>Status:</strong> <span style="color: #00ed64;">${cluster.status}</span></p>
                </div>
                
                <div class="description-card">
                    <h3>Tables (${cluster.tables.length})</h3>
                    <div class="tables-grid">
                        ${cluster.tables.map(table => `
                            <div class="table-card" onclick="window.navigationManager.showTable('${table.id}')">
                                <div class="table-card-header">
                                    <i class="fas fa-table"></i>
                                    <span class="table-card-title">${table.table_name}</span>
                                </div>
                                <div class="table-card-description">${table.description}</div>
                                <div class="table-card-meta">
                                    <span>${table.records.toLocaleString()} records</span>
                                    <span>${table.size}</span>
                                </div>
                            </div>
                        `).join('')}
                    </div>
                </div>
            </div>
        `;
        
        this.contentArea.innerHTML = clusterHTML;
        this.updateRightPanel('cluster', cluster);
    }
    
    showClusterDetails(clusterId) {
        const cluster = getClusterById(clusterId);
        if (!cluster) return;
        
        const modalContent = `
            <div class="cluster-details">
                <div class="cluster-header">
                    <div class="cluster-title">
                        <i class="fas fa-server"></i>
                        <h3>${cluster.cluster_name}</h3>
                    </div>
                </div>
                
                <div class="cluster-info">
                    <div class="info-row">
                        <span class="info-label">Description:</span>
                        <span class="info-value">${cluster.description}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Host:</span>
                        <span class="info-value">${cluster.host}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Port:</span>
                        <span class="info-value">${cluster.port}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Status:</span>
                        <span class="info-value"><span class="status-active">${cluster.status}</span></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Tables:</span>
                        <span class="info-value">${cluster.tables.length} tables</span>
                    </div>
                </div>
                
                <div class="cluster-actions">
                    <button class="btn-secondary" onclick="window.navigationManager.copyConnectionString('${clusterId}')">
                        <i class="fas fa-copy"></i>
                        Copy Connection String
                    </button>
                </div>
            </div>
        `;
        
        if (window.app) {
            window.app.showModal(`Cluster: ${cluster.cluster_name}`, modalContent);
        }
    }
    
    copyConnectionString(clusterId) {
        const cluster = getClusterById(clusterId);
        if (!cluster) return;
        
        const connectionString = `mongodb://${cluster.host}:${cluster.port}`;
        
        if (window.app) {
            window.app.copyToClipboard(connectionString);
            window.app.showNotification('Connection string copied to clipboard!', 'success');
        }
    }
    
    showTable(tableId) {
        const tableData = getTableById(tableId);
        if (!tableData) return;
        
        const { table, cluster } = tableData;
        
        this.updateBreadcrumb([
            { name: 'Home', id: 'home' },
            { name: cluster.cluster_name, id: cluster.id },
            { name: table.table_name, id: tableId }
        ]);
        
        this.addToRecentlyViewed('table', tableId, table.table_name);
        
        const tableHTML = `
            <div class="table-view">
                <div class="content-header">
                    <div class="content-title">
                        <i class="fas fa-table"></i>
                        <h2>${table.table_name}</h2>
                    </div>
                    <div class="content-actions">
                        <button class="btn-secondary">
                            <i class="fas fa-download"></i>
                            Export
                        </button>
                    </div>
                </div>
                
                <div class="description-card">
                    <h3>Description</h3>
                    <p>${table.description}</p>
                </div>
                
                <div class="description-card">
                    <h3>Table Information</h3>
                    <p><strong>Cluster:</strong> ${cluster.cluster_name}</p>
                    <p><strong>Records:</strong> ${table.records.toLocaleString()}</p>
                    <p><strong>Size:</strong> ${table.size}</p>
                </div>
                
                <div class="description-card">
                    <h3>Fields (${table.fields.length})</h3>
                    <div class="fields-table">
                        <table>
                            <thead>
                                <tr>
                                    <th>Field Name</th>
                                    <th>Type</th>
                                    <th>Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${table.fields.map(field => `
                                    <tr onclick="window.navigationManager.showField('${table.id}-${field.name}')">
                                        <td class="field-name">${field.name}</td>
                                        <td><span class="field-type">${field.type}</span></td>
                                        <td>${field.description}</td>
                                    </tr>
                                `).join('')}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        `;
        
        this.contentArea.innerHTML = tableHTML;
        this.updateRightPanel('table', table);
    }
    
    showField(fieldId) {
        const [tableId, fieldName] = fieldId.split('-');
        const tableData = getTableById(tableId);
        if (!tableData) return;
        
        const { table, cluster } = tableData;
        const field = table.fields.find(f => f.name === fieldName);
        if (!field) return;
        
        this.updateBreadcrumb([
            { name: 'Home', id: 'home' },
            { name: cluster.cluster_name, id: cluster.id },
            { name: table.table_name, id: tableId },
            { name: field.name, id: fieldId }
        ]);
        
        this.addToRecentlyViewed('field', fieldId, field.name);
        
        // Show field details in modal
        const modalContent = `
            <div class="field-details">
                <div class="field-header">
                    <div class="field-title">
                        <i class="fas fa-tags"></i>
                        <h3>${field.name}</h3>
                    </div>
                </div>
                
                <div class="field-info">
                    <div class="info-row">
                        <span class="info-label">Type:</span>
                        <span class="info-value"><span class="field-type">${field.type}</span></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Table:</span>
                        <span class="info-value">${table.table_name}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Cluster:</span>
                        <span class="info-value">${cluster.cluster_name}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Description:</span>
                        <span class="info-value">${field.description}</span>
                    </div>
                </div>
                
                <div class="field-actions">
                    <button class="btn-primary">
                        <i class="fas fa-edit"></i>
                        Edit Field
                    </button>
                    <button class="btn-secondary">
                        <i class="fas fa-copy"></i>
                        Copy Name
                    </button>
                </div>
            </div>
        `;
        
        if (window.app) {
            window.app.showModal(`Field: ${field.name}`, modalContent);
        }
    }
    
    updateBreadcrumb(items) {
        const breadcrumbHTML = items.map((item, index) => {
            if (index === items.length - 1) {
                return `<span class="breadcrumb-item">${item.name}</span>`;
            } else {
                return `<span class="breadcrumb-item" style="cursor: pointer; color: #3498db;" onclick="window.navigationManager.navigateToBreadcrumb('${item.id}')">${item.name}</span>`;
            }
        }).join('');
        
        this.breadcrumb.innerHTML = breadcrumbHTML;
    }
    
    navigateToBreadcrumb(id) {
        if (id === 'home') {
            this.showWelcomeScreen();
            return;
        }
        
        if (id === 'all-clusters') {
            this.showAllClustersView();
            return;
        }
        
        // Determine type and navigate accordingly
        if (id.includes('-')) {
            // Field ID
            this.showField(id);
        } else if (getClusterById(id)) {
            // Cluster ID
            this.showCluster(id);
        } else {
            // Table ID
            this.showTable(id);
        }
    }
    
    showWelcomeScreen() {
        this.updateBreadcrumb([{ name: 'Home', id: 'home' }]);
        this.contentArea.innerHTML = `
            <div class="welcome-screen">
                <div class="welcome-content">
                    <i class="fas fa-database welcome-icon"></i>
                    <h2>Welcome to MongoDB Manual Book</h2>
                    <p>Your comprehensive reference guide for MongoDB clusters, tables, and fields. Use the navigation panel on the left to browse through your database structure, or use the global search above to quickly find specific information.</p>
                    
                    <div class="quick-stats">
                        <div class="stat-card">
                            <i class="fas fa-server"></i>
                            <div class="stat-info">
                                <span class="stat-number" id="clusterCount">${getStatistics().clusters}</span>
                                <span class="stat-label">Clusters</span>
                            </div>
                        </div>
                        <div class="stat-card">
                            <i class="fas fa-table"></i>
                            <div class="stat-info">
                                <span class="stat-number" id="tableCount">${getStatistics().tables}</span>
                                <span class="stat-label">Tables</span>
                            </div>
                        </div>
                        <div class="stat-card">
                            <i class="fas fa-tags"></i>
                            <div class="stat-info">
                                <span class="stat-number" id="fieldCount">${getStatistics().fields}</span>
                                <span class="stat-label">Fields</span>
                            </div>
                        </div>
                    </div>

                    <div class="quick-actions">
                        <button class="btn-primary" onclick="showAllClusters()">
                            <i class="fas fa-list"></i>
                            Browse All Clusters
                        </button>
                        <button class="btn-secondary" onclick="focusSearch()">
                            <i class="fas fa-search"></i>
                            Start Searching
                        </button>
                    </div>
                </div>
            </div>
        `;
        this.updateRightPanel('welcome', null);
    }
    
    showAllClustersView() {
        const clusters = getAllClusters();
        this.updateBreadcrumb([{ name: 'Home', id: 'home' }, { name: 'All Clusters', id: 'all-clusters' }]);
        
        const clustersHTML = `
            <div class="clusters-view">
                <div class="content-header">
                    <div class="content-title">
                        <i class="fas fa-server"></i>
                        <h2>All Clusters</h2>
                    </div>
                    <div class="content-actions">
                        <button class="btn-secondary" onclick="window.app.exportData()">
                            <i class="fas fa-download"></i>
                            Export All
                        </button>
                    </div>
                </div>
                
                <div class="description-card">
                    <h3>Database Clusters (${clusters.length})</h3>
                    <p>Browse through all available MongoDB clusters and their associated tables and fields.</p>
                </div>
                
                <div class="tables-grid">
                    ${clusters.map(cluster => `
                        <div class="table-card" onclick="window.navigationManager.showCluster('${cluster.id}')">
                            <div class="table-card-header">
                                <i class="fas fa-server"></i>
                                <span class="table-card-title">${cluster.cluster_name}</span>
                            </div>
                            <div class="table-card-description">${cluster.description}</div>
                            <div class="table-card-meta">
                                <span>${cluster.tables.length} tables</span>
                                <span>${cluster.host}</span>
                            </div>
                        </div>
                    `).join('')}
                </div>
            </div>
        `;
        
        this.contentArea.innerHTML = clustersHTML;
        this.updateRightPanel('all-clusters', { count: clusters.length });
    }
    
    updateRightPanel(type, data) {
        let panelHTML = '';
        
        switch (type) {
            case 'cluster':
                panelHTML = `
                    <h4>Cluster Details</h4>
                    <p><strong>Name:</strong> ${data.name}</p>
                    <p><strong>Host:</strong> ${data.host}</p>
                    <p><strong>Port:</strong> ${data.port}</p>
                    <p><strong>Status:</strong> ${data.status}</p>
                    <p><strong>Tables:</strong> ${data.tables.length}</p>
                `;
                break;
            case 'table':
                panelHTML = `
                    <h4>Table Details</h4>
                    <p><strong>Name:</strong> ${data.name}</p>
                    <p><strong>Records:</strong> ${data.records.toLocaleString()}</p>
                    <p><strong>Size:</strong> ${data.size}</p>
                    <p><strong>Fields:</strong> ${data.fields.length}</p>
                `;
                break;
            case 'field':
                panelHTML = `
                    <h4>Field Details</h4>
                    <p><strong>Name:</strong> ${data.name}</p>
                    <p><strong>Type:</strong> ${data.type}</p>
                    <p><strong>Description:</strong> ${data.description}</p>
                `;
                break;
            case 'all-clusters':
                panelHTML = `
                    <h4>All Clusters</h4>
                    <p><strong>Total Clusters:</strong> ${data.count}</p>
                    <p>Click on any cluster to view its details and tables.</p>
                `;
                break;
            default:
                panelHTML = '<p>Select an item to view quick information here.</p>';
        }
        
        this.panelContent.innerHTML = panelHTML;
    }
    
    addToRecentlyViewed(type, id, name) {
        const item = { type, id, name, timestamp: Date.now() };
        
        // Remove if already exists
        this.recentlyViewed = this.recentlyViewed.filter(item => item.id !== id);
        
        // Add to beginning
        this.recentlyViewed.unshift(item);
        
        // Keep only last 10 items
        if (this.recentlyViewed.length > 10) {
            this.recentlyViewed = this.recentlyViewed.slice(0, 10);
        }
        
        this.saveRecentlyViewed();
        this.renderRecentlyViewed();
    }
    
    saveRecentlyViewed() {
        localStorage.setItem('recentlyViewed', JSON.stringify(this.recentlyViewed));
    }
    
    loadRecentlyViewed() {
        const saved = localStorage.getItem('recentlyViewed');
        if (saved) {
            this.recentlyViewed = JSON.parse(saved);
            this.renderRecentlyViewed();
        }
    }
    
    renderRecentlyViewed() {
        if (this.recentlyViewed.length === 0) {
            this.recentItems.innerHTML = '<p style="color: #6c757d; font-size: 0.9rem;">No recent items</p>';
            return;
        }
        
        const recentHTML = this.recentlyViewed.map(item => `
            <div class="recent-item" onclick="window.navigationManager.navigateToRecent('${item.type}', '${item.id}')">
                <i class="fas fa-${this.getIconForType(item.type)}" style="margin-right: 0.5rem; color: #3498db;"></i>
                ${item.name}
            </div>
        `).join('');
        
        this.recentItems.innerHTML = recentHTML;
    }
    
    getIconForType(type) {
        switch (type) {
            case 'cluster': return 'server';
            case 'table': return 'table';
            case 'field': return 'tags';
            default: return 'file';
        }
    }
    
    navigateToRecent(type, id) {
        switch (type) {
            case 'cluster':
                this.showCluster(id);
                break;
            case 'table':
                this.showTable(id);
                break;
            case 'field':
                this.showField(id);
                break;
        }
    }
    
    filterTreeView(filter) {
        this.currentFilter = filter;
        this.renderTreeView();
    }
    
    updateStatistics() {
        const stats = getStatistics();
        document.getElementById('clusterCount').textContent = stats.clusters;
        document.getElementById('tableCount').textContent = stats.tables;
        document.getElementById('fieldCount').textContent = stats.fields;
    }
}

// Global function to show all clusters
function showAllClusters() {
    if (window.navigationManager) {
        window.navigationManager.showAllClustersView();
    }
}

// Initialize navigation manager when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.navigationManager = new NavigationManager();
});

// Export for global use
window.showAllClusters = showAllClusters; 