// Navigation functionality for MongoDB Manual Book
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
    
    async initializeNavigation() {
        await this.renderTreeView();
        this.loadRecentlyViewed();
        await this.updateStatistics();
        this.showWelcomeScreen();
    }
    
    async renderTreeView() {
        try {
            const clusters = await getAllClusters();
            let treeHTML = '';
            
            clusters.forEach(cluster => {
                if (this.shouldShowItem('cluster', cluster)) {
                    treeHTML += this.renderClusterNode(cluster);
                }
            });
            
            this.treeView.innerHTML = treeHTML;
            this.addTreeEventListeners();
            
            // Hide all children by default
            const childrenContainers = this.treeView.querySelectorAll('.tree-children');
            childrenContainers.forEach(container => {
                container.style.display = 'none';
            });
        } catch (error) {
            console.error('Error rendering tree view:', error);
            this.treeView.innerHTML = '<div class="error-message">Error loading clusters. Please try again.</div>';
        }
    }
    
    renderClusterNode(cluster) {
        // All clusters have databases, so they're always expandable
        const hasChildren = true;
        
        let clusterHTML = `
            <div class="tree-item" data-type="cluster" data-id="${cluster.id}">
                <div class="tree-node" onclick="window.navigationManager.handleTreeItemClick(this.parentElement)">
                    <i class="fas fa-chevron-right tree-toggle expandable" data-target="${cluster.id}"></i>
                    <i class="fas fa-server tree-icon"></i>
                    <span class="tree-label">${cluster.name}</span>
                </div>
            </div>
        `;
        
        // Add placeholder for databases (will be loaded on expansion)
        clusterHTML += `<div class="tree-children" id="children-${cluster.id}" style="display: none;">
            <div class="loading-placeholder">Loading databases...</div>
        </div>`;
        
        return clusterHTML;
    }
    
    renderTableNode(table, database) {
        // All tables have fields, so they're always expandable
        return `
            <div class="tree-item" data-type="table" data-id="${table.id}" data-database-id="${database.id}">
                <div class="tree-node" onclick="window.navigationManager.handleTreeItemClick(this.parentElement)">
                    <i class="fas fa-chevron-right tree-toggle expandable" data-target="${table.id}"></i>
                    <i class="fas fa-table tree-icon"></i>
                    <span class="tree-label">${table.name}</span>
                </div>
                <div class="tree-children" id="children-${table.id}" style="display: none;">
                    <div class="loading-placeholder">Loading fields...</div>
                </div>
            </div>
        `;
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
        // Add event listeners for expandable arrows
        const expandableArrows = this.treeView.querySelectorAll('.tree-toggle.expandable');
        expandableArrows.forEach(arrow => {
            arrow.addEventListener('click', (e) => {
                e.stopPropagation();
                this.toggleTreeItem(arrow);
            });
        });
    }
    
    handleTreeItemClick(item) {
        const type = item.dataset.type;
        const id = item.dataset.id;
        
        // Remove active class from all items
        this.treeView.querySelectorAll('.tree-item').forEach(i => i.classList.remove('active'));
        
        // Add active class to clicked item
        item.classList.add('active');
        
        // Handle navigation based on type
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
    
    async toggleTreeItem(arrow) {
        const targetId = arrow.dataset.target;
        const childrenContainer = document.getElementById(`children-${targetId}`);
        const treeItem = arrow.closest('.tree-item');
        const itemType = treeItem.dataset.type;
        
        if (childrenContainer) {
            const isExpanded = childrenContainer.style.display !== 'none';
            
            if (isExpanded) {
                // Collapse
                childrenContainer.style.display = 'none';
                arrow.classList.remove('expanded');
            } else {
                // Expand
                if (itemType === 'cluster') {
                    // Load databases for this cluster
                    await this.loadClusterDatabases(targetId, childrenContainer);
                } else if (itemType === 'database') {
                    // Load tables for this database
                    await this.loadDatabaseTables(targetId, childrenContainer);
                } else if (itemType === 'table') {
                    // Load fields for this table
                    await this.loadTableFields(targetId, childrenContainer);
                }
                childrenContainer.style.display = 'block';
                arrow.classList.add('expanded');
            }
        }
    }
    
    showCluster(clusterId) {
        const cluster = getClusterById(clusterId);
        if (!cluster) return;
        
        this.updateBreadcrumb([{ name: 'Home', id: 'home' }, { name: cluster.name, id: clusterId }]);
        this.addToRecentlyViewed('cluster', clusterId, cluster.name);
        
        const clusterHTML = `
            <div class="cluster-view">
                <div class="content-header">
                    <div class="content-title">
                        <i class="fas fa-server"></i>
                        <h2>${cluster.name}</h2>
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
                                    <span class="table-card-title">${table.name}</span>
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
                        <h3>${cluster.name}</h3>
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
            window.app.showModal(`Cluster: ${cluster.name}`, modalContent);
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
            { name: cluster.name, id: cluster.id },
            { name: table.name, id: tableId }
        ]);
        
        this.addToRecentlyViewed('table', tableId, table.name);
        
        const tableHTML = `
            <div class="table-view">
                <div class="content-header">
                    <div class="content-title">
                        <i class="fas fa-table"></i>
                        <h2>${table.name}</h2>
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
                    <p><strong>Cluster:</strong> ${cluster.name}</p>
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
            { name: cluster.name, id: cluster.id },
            { name: table.name, id: tableId },
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
                        <span class="info-value">${table.name}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Cluster:</span>
                        <span class="info-value">${cluster.name}</span>
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
                                <span class="stat-number" id="clusterCount">-</span>
                                <span class="stat-label">Clusters</span>
                            </div>
                        </div>
                        <div class="stat-card">
                            <i class="fas fa-table"></i>
                            <div class="stat-info">
                                <span class="stat-number" id="tableCount">-</span>
                                <span class="stat-label">Tables</span>
                            </div>
                        </div>
                        <div class="stat-card">
                            <i class="fas fa-tags"></i>
                            <div class="stat-info">
                                <span class="stat-number" id="fieldCount">-</span>
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
    
    async showAllClustersView() {
        try {
            const clusters = await getAllClusters();
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
                                <span class="table-card-title">${cluster.name}</span>
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
        } catch (error) {
            console.error('Error loading clusters view:', error);
            this.contentArea.innerHTML = '<div class="error-message">Error loading clusters. Please try again.</div>';
        }
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
    
    async loadClusterDatabases(clusterId, container) {
        try {
            // Show loading state
            container.innerHTML = '<div class="loading-placeholder">Loading databases...</div>';
            
            // Fetch databases for this cluster
            const response = await fetch(`https://dev-master-clusterseed.internetcash.io/api/v2.0.0/clusters_json/${clusterId}/databases`);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const result = await response.json();
            
            if (result.success && result.data) {
                // Transform API data to match our expected format
                const databases = result.data.map(database => ({
                    id: database.id,
                    name: database.database_name,
                    description: database.description,
                    table_count: database.table_count,
                    tables: [] // Will be loaded on-demand later
                }));
                
                // Render databases
                let databasesHTML = '';
                databases.forEach(database => {
                    databasesHTML += this.renderDatabaseNode(database, clusterId);
                });
                
                container.innerHTML = databasesHTML;
                
                // Add event listeners for the new database nodes
                this.addTreeEventListeners();
            } else {
                container.innerHTML = '<div class="error-message">Error loading databases</div>';
            }
        } catch (error) {
            console.error('Error loading databases:', error);
            container.innerHTML = '<div class="error-message">Error loading databases. Please try again.</div>';
        }
    }
    
    renderDatabaseNode(database, clusterId) {
        // All databases have tables, so they're always expandable
        return `
            <div class="tree-item" data-type="database" data-id="${database.id}" data-cluster-id="${clusterId}">
                <div class="tree-node" onclick="window.navigationManager.handleTreeItemClick(this.parentElement)">
                    <i class="fas fa-chevron-right tree-toggle expandable" data-target="${database.id}"></i>
                    <i class="fas fa-database tree-icon"></i>
                    <span class="tree-label">${database.name}</span>
                </div>
                <div class="tree-children" id="children-${database.id}" style="display: none;">
                    <div class="loading-placeholder">Loading tables...</div>
                </div>
            </div>
        `;
    }
    
    async loadDatabaseTables(databaseId, container) {
        try {
            // Show loading state
            container.innerHTML = '<div class="loading-placeholder">Loading tables...</div>';
            
            // Fetch tables for this database
            const response = await fetch(`https://dev-master-clusterseed.internetcash.io/api/v2.0.0/databases_json/${databaseId}/tables`);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const result = await response.json();
            
            if (result.success && result.data) {
                // Transform API data to match our expected format
                const tables = result.data.map(table => ({
                    id: table.id,
                    name: table.table_name,
                    description: table.description,
                    field_count: table.field_count,
                    fields: [] // Will be loaded on-demand later
                }));
                
                // Render tables
                let tablesHTML = '';
                tables.forEach(table => {
                    tablesHTML += this.renderTableNode(table, { id: databaseId });
                });
                
                container.innerHTML = tablesHTML;
                
                // Add event listeners for the new table nodes
                this.addTreeEventListeners();
            } else {
                container.innerHTML = '<div class="error-message">Error loading tables</div>';
            }
        } catch (error) {
            console.error('Error loading tables:', error);
            container.innerHTML = '<div class="error-message">Error loading tables. Please try again.</div>';
        }
    }
    
    async loadTableFields(tableId, container) {
        try {
            console.log('Loading fields for table:', tableId);
            // Show loading state
            container.innerHTML = '<div class="loading-placeholder">Loading fields...</div>';
            
            // Fetch fields for this table
            const response = await fetch(`https://dev-master-clusterseed.internetcash.io/api/v2.0.0/tables_json/${tableId}/fields`);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const fields = await response.json();
            console.log('Fields API response:', fields);
            
            if (Array.isArray(fields) && fields.length > 0) {
                // Transform API data to match our expected format
                const transformedFields = fields.map(field => ({
                    id: field.id,
                    name: field.field_name,
                    type: field.comment ? field.comment.split('~#')[0] : 'Unknown', // Extract type from comment
                    description: field.description || 'No description available'
                }));
                
                console.log('Transformed fields:', transformedFields);
                
                // Render fields
                let fieldsHTML = '';
                transformedFields.forEach(field => {
                    fieldsHTML += this.renderFieldNode(field, { id: tableId }, { id: 'database' });
                });
                
                container.innerHTML = fieldsHTML;
            } else if (Array.isArray(fields) && fields.length === 0) {
                container.innerHTML = '<div class="loading-placeholder">No fields found for this table</div>';
            } else {
                console.error('Unexpected fields response format:', fields);
                container.innerHTML = '<div class="error-message">Error loading fields</div>';
            }
        } catch (error) {
            console.error('Error loading fields:', error);
            container.innerHTML = '<div class="error-message">Error loading fields. Please try again.</div>';
        }
    }
    
    async updateStatistics() {
        try {
            const stats = await getStatistics();
            const clusterCount = document.getElementById('clusterCount');
            const tableCount = document.getElementById('tableCount');
            const fieldCount = document.getElementById('fieldCount');
            
            if (clusterCount) clusterCount.textContent = stats.clusters;
            if (tableCount) tableCount.textContent = stats.tables;
            if (fieldCount) fieldCount.textContent = stats.fields;
        } catch (error) {
            console.error('Error updating statistics:', error);
        }
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