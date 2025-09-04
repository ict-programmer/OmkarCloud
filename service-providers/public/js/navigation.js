// Navigation functionality for Monitoring App
class NavigationManager {
    constructor() {
        this.treeView = document.getElementById('treeView');
        this.contentArea = document.getElementById('contentArea');
        this.breadcrumb = document.getElementById('breadcrumb');
        this.rightPanel = document.getElementById('rightPanel');
        this.panelContent = document.getElementById('panelContent');
        this.recentItems = document.getElementById('recentItems');
        this.recentlyViewed = [];
        
        this.initializeNavigation();
    }
    
    async initializeNavigation() {
        await this.renderTreeView();
        this.loadRecentlyViewed();
        this.showWelcomeScreen();
        await this.updateStatistics();
    }
    
    async renderTreeView() {
        try {
            const clusters = await getAllClusters();
            let treeHTML = '';
            
            clusters.forEach(cluster => {
                treeHTML += this.renderClusterNode(cluster);
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
        // All clusters can have databases, so they're always expandable
        const hasChildren = true;
        
        let clusterHTML = `
            <div class="tree-item" data-type="cluster" data-id="${cluster.id}">
                <div class="tree-node" onclick="window.navigationManager.handleTreeItemClick(this.parentElement)">
                    <i class="fas fa-chevron-right tree-toggle expandable" data-target="${cluster.id}"></i>
                    <i class="fas fa-server tree-icon"></i>
                    <span class="tree-label">${cluster.name}</span>
                </div>
                <div class="tree-children" id="children-${cluster.id}" style="display: none;">
                    <div class="loading-placeholder">Loading databases...</div>
                </div>
            </div>
        `;
        
        return clusterHTML;
    }
    
    renderDatabaseNode(database, clusterId) {
        // All databases have tables, so they're always expandable
        console.log('renderDatabaseNode - database:', database.id, 'clusterId:', clusterId);
        
        const databaseHTML = `
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
        
        console.log('Generated database HTML:', databaseHTML);
        return databaseHTML;
    }
    
    renderTableNode(table, databaseId, clusterId) {
        // All tables have fields, so they're always expandable
        // Use table.name as fallback if table_name doesn't exist
        const tableName = table.table_name || table.name || 'Unknown Table';
        
        console.log('renderTableNode - table:', table.id, 'databaseId:', databaseId, 'clusterId:', clusterId);
        
        let tableHTML = `
            <div class="tree-item" data-type="table" data-id="${table.id}" data-database-id="${databaseId}" data-cluster-id="${clusterId}">
                <div class="tree-node" onclick="window.navigationManager.handleTreeItemClick(this.parentElement)">
                    <i class="fas fa-chevron-right tree-toggle expandable" data-target="${table.id}"></i>
                    <i class="fas fa-table tree-icon"></i>
                    <span class="tree-label">${tableName}</span>
                </div>
                <div class="tree-children" id="children-${table.id}" style="display: none;">
                    <div class="loading-placeholder">Loading fields...</div>
                </div>
            </div>
        `;
        
        return tableHTML;
    }
    
    renderFieldNode(field, tableId, databaseId, clusterId) {
        return `
            <div class="tree-item" data-type="field" data-id="${tableId}-${field.name}" data-table-id="${tableId}" data-database-id="${databaseId}" data-cluster-id="${clusterId}">
                <div class="tree-node" onclick="window.navigationManager.handleTreeItemClick(this.parentElement)">
                    <i class="fas fa-chevron-right tree-toggle hidden"></i>
                    <i class="fas fa-tags tree-icon"></i>
                    <span class="tree-label">${field.name}</span>
                </div>
            </div>
        `;
    }
    

    
    addTreeEventListeners() {
        // Add event listeners for expandable arrows
        const expandableArrows = this.treeView.querySelectorAll('.tree-toggle.expandable');
        expandableArrows.forEach(arrow => {
            // Check if this arrow already has a click listener
            if (!arrow.hasAttribute('data-has-listener')) {
                arrow.setAttribute('data-has-listener', 'true');
                arrow.addEventListener('click', async (e) => {
                    e.stopPropagation();
                    await this.toggleTreeItem(arrow);
                });
            }
        });
    }
    
    async handleTreeItemClick(item) {
        const type = item.dataset.type;
        const id = item.dataset.id;
        const clusterId = item.dataset.clusterId;
        const databaseId = item.dataset.databaseId;
        
        // Remove active class from all items
        this.treeView.querySelectorAll('.tree-item').forEach(i => i.classList.remove('active'));
        
        // Add active class to clicked item
        item.classList.add('active');
        
        // Handle navigation based on type
        switch (type) {
            case 'cluster':
                await this.showCluster(id);
                break;
            case 'database':
                await this.showDatabase(id, clusterId);
                break;
            case 'table':
                await this.showTable(id, databaseId, clusterId);
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
                // Collapse - hide children and reset arrow
                childrenContainer.style.display = 'none';
                arrow.classList.remove('expanded');
                arrow.style.transform = 'rotate(0deg)';
                
                // Clear the content when collapsing to save memory
                if (itemType === 'table') {
                    childrenContainer.innerHTML = '<div class="loading-placeholder">Loading fields...</div>';
                } else if (itemType === 'database') {
                    childrenContainer.innerHTML = '<div class="loading-placeholder">Loading tables...</div>';
                } else if (itemType === 'cluster') {
                    childrenContainer.innerHTML = '<div class="loading-placeholder">Loading databases...</div>';
                }
            } else {
                // Expand - show children and rotate arrow
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
                arrow.style.transform = 'rotate(90deg)';
            }
        }
    }
    
        async showCluster(clusterId) {
        try {
            console.log('showCluster called with ID:', clusterId);
            const cluster = await getClusterById(clusterId);
            console.log('Cluster data received:', cluster);
            if (!cluster) {
                console.log('No cluster found, returning early');
                return;
            }
        
            this.updateBreadcrumb([{ name: 'Home', id: 'home' }, { name: cluster.name, id: clusterId }]);
            this.addToRecentlyViewed('cluster', clusterId, cluster.name);
            
            // Fetch databases for this cluster
            const response = await fetch(`https://dev-master-clusterseed.internetcash.io/api/v2.0.0/clusters_json/${clusterId}/databases`);
            let databases = [];
            if (response.ok) {
                const result = await response.json();
                if (result.success && result.data) {
                    databases = result.data;
                }
            }
            
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
                        <h3>Databases (${databases.length})</h3>
                        <div class="tables-grid">
                            ${databases.map(database => `
                                <div class="table-card" onclick="window.navigationManager.showDatabase('${database.id}', '${clusterId}')">
                                    <div class="table-card-header">
                                        <i class="fas fa-database"></i>
                                        <span class="table-card-title">${database.database_name}</span>
                                    </div>
                                    <div class="table-card-description">${database.description || 'No description available'}</div>
                                    <div class="table-card-meta">
                                        <span>${database.table_count || 0} tables</span>
                                        <span>Active</span>
                                    </div>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                </div>
            `;
            
            console.log('Generated cluster HTML:', clusterHTML);
            this.contentArea.innerHTML = clusterHTML;
            console.log('Content area updated');
            this.updateRightPanel('cluster', cluster);
        } catch (error) {
            console.error('Error showing cluster:', error);
            this.contentArea.innerHTML = '<div class="error-message">Error loading cluster details. Please try again.</div>';
        }
    }

    async showDatabase(databaseId, clusterId) {
        try {
            console.log('showDatabase called with ID:', databaseId, 'cluster ID:', clusterId);
            
            // Get cluster info for breadcrumb
            const cluster = await getClusterById(clusterId);
            if (!cluster) return;
            
            // Fetch database details
            const response = await fetch(`https://dev-master-clusterseed.internetcash.io/api/v2.0.0/clusters_json/${clusterId}/databases`);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const result = await response.json();
            if (!result.success || !result.data) {
                throw new Error('Failed to fetch database data');
            }
            
            const database = result.data.find(db => db.id == databaseId);
            if (!database) {
                throw new Error('Database not found');
            }
            
            console.log('Database data received:', database);
            
            // Fetch tables for this database
            const tablesResponse = await fetch(`https://dev-master-clusterseed.internetcash.io/api/v2.0.0/databases_json/${databaseId}/tables`);
            let tables = [];
            if (tablesResponse.ok) {
                const tablesResult = await tablesResponse.json();
                if (tablesResult.success && tablesResult.data) {
                    tables = tablesResult.data;
                }
            }
            
            this.updateBreadcrumb([
                { name: 'Home', id: 'home' }, 
                { name: cluster.name, id: clusterId },
                { name: database.database_name, id: databaseId }
            ]);
            this.addToRecentlyViewed('database', databaseId, database.database_name, clusterId);
            
            const databaseHTML = `
                <div class="database-view">
                    <div class="content-header">
                        <div class="content-title">
                            <i class="fas fa-database"></i>
                            <h2>${database.database_name}</h2>
                        </div>
                        <div class="content-actions">
                            <button class="btn-primary" onclick="window.navigationManager.showDatabaseDetails('${databaseId}', '${clusterId}')">
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
                        <p>${database.description || 'No description available'}</p>
                    </div>
                    
                    <div class="description-card">
                        <h3>Database Information</h3>
                        <p><strong>Cluster:</strong> ${cluster.name}</p>
                        <p><strong>Table Count:</strong> ${tables.length}</p>
                        <p><strong>Status:</strong> <span style="color: #00ed64;">Active</span></p>
                    </div>
                    
                    <div class="description-card">
                        <h3>Tables (${tables.length})</h3>
                        <div class="tables-grid">
                            ${tables.map(table => `
                                <div class="table-card" onclick="window.navigationManager.showTable('${table.id}', '${databaseId}', '${clusterId}')">
                                    <div class="table-card-header">
                                        <i class="fas fa-table"></i>
                                        <span class="table-card-title">${table.table_name || table.name || 'Unknown Table'}</span>
                                    </div>
                                    <div class="table-card-description">${table.description || 'No description available'}</div>
                                    <div class="table-card-meta">
                                        <span>${table.field_count || 0} fields</span>
                                        <span>Active</span>
                                    </div>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                </div>
            `;
            
            console.log('Generated database HTML:', databaseHTML);
            this.contentArea.innerHTML = databaseHTML;
            console.log('Content area updated');
            this.updateRightPanel('database', database);
        } catch (error) {
            console.error('Error showing database:', error);
            this.contentArea.innerHTML = '<div class="error-message">Error loading database details. Please try again.</div>';
        }
    }
    
    async showClusterDetails(clusterId) {
        const cluster = await getClusterById(clusterId);
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
                         <span class="info-value">API Data</span>
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
    
    async copyConnectionString(clusterId) {
        const cluster = await getClusterById(clusterId);
        if (!cluster) return;
        
        const connectionString = `mongodb://${cluster.host}:${cluster.port}`;
        
        if (window.app) {
            window.app.copyToClipboard(connectionString);
            window.app.showNotification('Connection string copied to clipboard!', 'success');
        }
    }

    async showDatabaseDetails(databaseId, clusterId) {
        try {
            const cluster = await getClusterById(clusterId);
            if (!cluster) return;
            
            const response = await fetch(`https://dev-master-clusterseed.internetcash.io/api/v2.0.0/clusters_json/${clusterId}/databases`);
            if (!response.ok) return;
            
            const result = await response.json();
            if (!result.success || !result.data) return;
            
            const database = result.data.find(db => db.id == databaseId);
            if (!database) return;
            
            const modalContent = `
                <div class="database-details">
                    <div class="database-header">
                        <div class="database-title">
                            <i class="fas fa-database"></i>
                            <h3>${database.database_name}</h3>
                        </div>
                    </div>
                    
                    <div class="database-info">
                        <div class="info-row">
                            <span class="info-label">Description:</span>
                            <span class="info-value">${database.description || 'No description available'}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Cluster:</span>
                            <span class="info-value">${cluster.name}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Table Count:</span>
                            <span class="info-value">${database.table_count || 0} tables</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Status:</span>
                            <span class="info-value"><span class="status-active">Active</span></span>
                        </div>
                    </div>
                </div>
            `;
            
            if (window.app) {
                window.app.showModal(`Database: ${database.database_name}`, modalContent);
            }
        } catch (error) {
            console.error('Error showing database details:', error);
        }
    }
    

    
        async showTable(tableId, databaseId, clusterId) {
        try {
            console.log('showTable called with ID:', tableId, 'database ID:', databaseId, 'cluster ID:', clusterId);
            
            // Get cluster info for breadcrumb
            const cluster = await getClusterById(clusterId);
            if (!cluster) return;
            
            // Fetch table details and fields
            const response = await fetch(`https://dev-master-clusterseed.internetcash.io/api/v2.0.0/databases_json/${databaseId}/tables`);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const result = await response.json();
            if (!result.success || !result.data) {
                throw new Error('Failed to fetch table data');
            }
            
            const table = result.data.find(t => t.id == tableId);
            if (!table) {
                throw new Error('Table not found');
            }
            
            console.log('Table data received:', table);
            
            // Use table.name as fallback if table_name doesn't exist
            const tableName = table.table_name || table.name || 'Unknown Table';
            
            // Get database name for breadcrumb
            let databaseName = 'Unknown Database';
            if (databaseId) {
                try {
                    const dbResponse = await fetch(`https://dev-master-clusterseed.internetcash.io/api/v2.0.0/clusters_json/${clusterId}/databases`);
                    if (dbResponse.ok) {
                        const dbResult = await dbResponse.json();
                        if (dbResult.success && dbResult.data) {
                            const database = dbResult.data.find(db => db.id == databaseId);
                            if (database) {
                                databaseName = database.database_name;
                            }
                        }
                    }
                } catch (error) {
                    console.log('Could not fetch database name:', error);
                }
            }
            
            this.updateBreadcrumb([
                { name: 'Home', id: 'home' },
                { name: cluster.name, id: clusterId },
                { name: databaseName, id: databaseId },
                { name: tableName, id: tableId }
            ]);
            
            this.addToRecentlyViewed('table', tableId, tableName, clusterId, databaseId);
            
            // Fetch fields for this table
            const fieldsResponse = await fetch(`https://dev-master-clusterseed.internetcash.io/api/v2.0.0/tables_json/${tableId}/fields`);
            let fields = [];
            if (fieldsResponse.ok) {
                const fieldsData = await fieldsResponse.json();
                if (Array.isArray(fieldsData)) {
                    fields = fieldsData.map(field => ({
                        id: field.id,
                        name: field.field_name,
                        type: field.comment ? field.comment.split('~#')[0] : 'Unknown',
                        description: field.description || 'No description available'
                    }));
                }
            }
            
            const tableHTML = `
                <div class="table-view">
                    <div class="content-header">
                        <div class="content-title">
                            <i class="fas fa-table"></i>
                            <h2>${tableName}</h2>
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
                        <p>${table.description || 'No description available'}</p>
                    </div>
                    
                    <div class="description-card">
                        <h3>Table Information</h3>
                        <p><strong>Cluster:</strong> ${cluster.name}</p>
                        <p><strong>Field Count:</strong> ${fields.length}</p>
                        <p><strong>Status:</strong> <span style="color: #00ed64;">Active</span></p>
                    </div>
                    
                    <div class="description-card">
                        <h3>Fields (${fields.length})</h3>
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
                                                                         ${fields.map(field => `
                                         <tr onclick="window.navigationManager.showField('${tableId}-${field.name}', '${tableName}', '${cluster.name}', '${databaseId}')">
                                             <td>${field.name}</td>
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
            
            console.log('Generated table HTML:', tableHTML);
            this.contentArea.innerHTML = tableHTML;
            console.log('Content area updated');
            this.updateRightPanel('table', table);
            
            // DO NOT expand the table in navigation when clicking on table name
            // Only expand when clicking on the arrow
        } catch (error) {
            console.error('Error showing table:', error);
            this.contentArea.innerHTML = '<div class="error-message">Error loading table details. Please try again.</div>';
        }
    }
    
         showField(fieldId, tableName, clusterName, databaseId, databaseName = null) {
         const [tableId, fieldName] = fieldId.split('-');
         
         // Get the table item to extract database and cluster info
         const tableItem = document.querySelector(`[data-id="${tableId}"][data-type="table"]`);
         let actualDatabaseName = databaseName || 'Unknown';
         let actualClusterName = clusterName || 'Unknown';
         let actualTableName = tableName || 'Unknown';
         
         if (tableItem) {
             const actualDatabaseId = tableItem.dataset.databaseId;
             const actualClusterId = tableItem.dataset.clusterId;
             
             // Use passed databaseName if available, otherwise fall back to DOM lookup
             if (!actualDatabaseName || actualDatabaseName === 'Unknown') {
                 if (databaseId) {
                     const databaseItem = document.querySelector(`[data-id="${databaseId}"][data-type="database"]`);
                     if (databaseItem) {
                         const databaseLabel = databaseItem.querySelector('.tree-label');
                         if (databaseLabel && databaseLabel.textContent) {
                             actualDatabaseName = databaseLabel.textContent;
                         }
                     }
                 } else if (actualDatabaseId) {
                     const databaseItem = document.querySelector(`[data-id="${actualDatabaseId}"][data-type="database"]`);
                     if (databaseItem) {
                         const databaseLabel = databaseItem.querySelector('.tree-label');
                         if (databaseLabel && databaseLabel.textContent) {
                             actualDatabaseName = databaseLabel.textContent;
                         }
                     }
                 }
             }
             
             // Find the cluster item to get its actual name
             if (actualClusterId) {
                 const clusterItem = document.querySelector(`[data-id="${actualClusterId}"][data-type="cluster"]`);
                 if (clusterItem) {
                     const clusterLabel = clusterItem.querySelector('.tree-label');
                     if (clusterLabel && clusterLabel.textContent) {
                         actualClusterName = clusterLabel.textContent;
                     }
                 }
             }
             
             // If tableName wasn't provided, try to get it from the DOM
             if (!tableName && tableItem) {
                 const tableLabel = tableItem.querySelector('.tree-label');
                 if (tableLabel && tableLabel.textContent) {
                     actualTableName = tableLabel.textContent;
                 }
             }
         }
         
         // If we still don't have proper names, try to get them from the field's parent containers
         if (actualTableName === 'Unknown' || actualClusterName === 'Unknown' || actualDatabaseName === 'Unknown') {
             // Find the field item in the tree to get its context
             const fieldItem = document.querySelector(`[data-id="${fieldId}"][data-type="field"]`);
             if (fieldItem) {
                 const fieldTableId = fieldItem.dataset.tableId;
                 const fieldDatabaseId = fieldItem.dataset.databaseId;
                 const fieldClusterId = fieldItem.dataset.clusterId;
                 
                 if (fieldTableId && actualTableName === 'Unknown') {
                     const fieldTableItem = document.querySelector(`[data-id="${fieldTableId}"][data-type="table"]`);
                     if (fieldTableItem) {
                         const tableLabel = fieldTableItem.querySelector('.tree-label');
                         if (tableLabel && tableLabel.textContent) {
                             actualTableName = tableLabel.textContent;
                         }
                     }
                 }
                 
                 if (fieldDatabaseId && actualDatabaseName === 'Unknown') {
                     const fieldDatabaseItem = document.querySelector(`[data-id="${fieldDatabaseId}"][data-type="database"]`);
                     if (fieldDatabaseItem) {
                         const databaseLabel = fieldDatabaseItem.querySelector('.tree-label');
                         if (databaseLabel && databaseLabel.textContent) {
                             actualDatabaseName = databaseLabel.textContent;
                         }
                     }
                 }
                 
                 if (fieldClusterId && actualClusterName === 'Unknown') {
                     const fieldClusterItem = document.querySelector(`[data-id="${fieldClusterId}"][data-type="cluster"]`);
                     if (fieldClusterItem) {
                         const clusterLabel = fieldClusterItem.querySelector('.tree-label');
                         if (clusterLabel && clusterLabel.textContent) {
                             actualClusterName = clusterLabel.textContent;
                         }
                     }
                 }
             }
         }
         
         this.updateBreadcrumb([
             { name: 'Home', id: 'home' },
             { name: actualClusterName, id: 'cluster' },
             { name: actualTableName, id: tableId },
             { name: fieldName, id: fieldId }
         ]);
         
         this.addToRecentlyViewed('field', fieldId, fieldName);
         
         // Show field details in modal
         const modalContent = `
             <div class="field-details">
                 <div class="field-header">
                     <div class="field-title">
                         <i class="fas fa-tags"></i>
                         <h3>${fieldName}</h3>
                     </div>
                 </div>
                 
                 <div class="field-info">
                     <div class="info-row">
                         <span class="info-label">Field Name:</span>
                         <span class="info-value">${fieldName}</span>
                     </div>
                     <div class="info-row">
                         <span class="info-label">Table:</span>
                         <span class="info-value">${actualTableName}</span>
                     </div>
                     <div class="info-row">
                         <span class="info-label">Database:</span>
                         <span class="info-value">${actualDatabaseName}</span>
                     </div>
                     <div class="info-row">
                         <span class="info-label">Cluster:</span>
                         <span class="info-value">${actualClusterName}</span>
                     </div>
                     
                 </div>
                 
                 <div class="field-actions">
                     <button class="btn-secondary">
                         <i class="fas fa-copy"></i>
                         Copy Name
                     </button>
                 </div>
             </div>
         `;
         
         if (window.app) {
             window.app.showModal(`Field: ${fieldName}`, modalContent);
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
    
    async navigateToBreadcrumb(id) {
        if (id === 'home') {
            this.showWelcomeScreen();
            await this.updateStatistics();
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
        } else {
            // Try to determine if it's a cluster, database, or table
            try {
                // First try to get cluster info
                const cluster = await getClusterById(id);
                if (cluster) {
                    // It's a cluster ID
                    await this.showCluster(id);
                    return;
                }
                
                // If not a cluster, check if it's a database by looking for it in the DOM
                const databaseItem = document.querySelector(`[data-id="${id}"][data-type="database"]`);
                if (databaseItem) {
                    // It's a database ID, get the cluster ID and show database
                    const clusterId = databaseItem.dataset.clusterId;
                    if (clusterId) {
                        await this.showDatabase(id, clusterId);
                        return;
                    }
                }
                
                // If not a database, check if it's a table by looking for it in the DOM
                const tableItem = document.querySelector(`[data-id="${id}"][data-type="table"]`);
                if (tableItem) {
                    // It's a table ID, get the database ID and cluster ID and show table
                    const databaseId = tableItem.dataset.databaseId;
                    const clusterId = tableItem.dataset.clusterId;
                    if (databaseId && clusterId) {
                        await this.showTable(id, databaseId, clusterId);
                        return;
                    }
                }
                
                // If we can't determine the type, try to show as table (fallback)
                console.log('Could not determine type for ID:', id, 'trying as table...');
                this.showTable(id);
                
            } catch (error) {
                console.error('Error navigating to breadcrumb:', error);
                // Fallback to table view
                this.showTable(id);
            }
        }
    }
    
    showWelcomeScreen() {
        this.updateBreadcrumb([{ name: 'Home', id: 'home' }]);
        this.contentArea.innerHTML = `
            <div class="welcome-screen">
                <div class="welcome-content">
                    <i class="fas fa-database welcome-icon"></i>
                    <h2>Welcome to Monitoring App</h2>
                    <p>Your comprehensive monitoring solution for clusters, tables, and fields. Use the navigation panel on the left to browse through your system structure, or use the global search above to quickly find specific information.</p>
                    
                    <div class="quick-stats">
                        <div class="stat-card">
                            <i class="fas fa-server"></i>
                            <div class="stat-info">
                                <span class="stat-number" id="clusterCount">-</span>
                                <span class="stat-label">Clusters</span>
                            </div>
                        </div>
                    </div>

                    <div class="quick-actions" style="display: flex; justify-content: center; gap: 1rem; margin-top: 2rem;">
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
                        <button class="btn-secondary" onclick="(async () => { await window.app.exportData(); })()">
                            <i class="fas fa-download"></i>
                            Export All
                        </button>
                    </div>
                </div>
                
                <div class="description-card">
                    <h3>Database Clusters (${clusters.length})</h3>
                    <p>Browse through all available clusters and their associated tables and fields.</p>
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
                                <span>API Cluster</span>
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
                      <p><strong>Tables:</strong> API Data</p>
                  `;
                  break;
             case 'database':
                 panelHTML = `
                     <h4>Database Details</h4>
                     <p><strong>Name:</strong> ${data.database_name}</p>
                     <p><strong>Table Count:</strong> ${data.table_count || 0}</p>
                     <p><strong>Status:</strong> Active</p>
                 `;
                 break;
             case 'table':
                 const tableName = data.table_name || data.name || 'Unknown Table';
                 panelHTML = `
                     <h4>Table Details</h4>
                     <p><strong>Name:</strong> ${tableName}</p>
                     <p><strong>Field Count:</strong> ${data.field_count || 0}</p>
                     <p><strong>Status:</strong> Active</p>
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
    
    addToRecentlyViewed(type, id, name, clusterId = null, databaseId = null) {
        const item = { type, id, name, timestamp: Date.now() };
        if (clusterId) {
            item.clusterId = clusterId;
        }
        if (databaseId) {
            item.databaseId = databaseId;
        }
        
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
            <div class="recent-item" onclick="(async () => { await window.navigationManager.navigateToRecent('${item.type}', '${item.id}'); })()">
                <i class="fas fa-${this.getIconForType(item.type)}" style="margin-right: 0.5rem; color: #3498db;"></i>
                ${item.name}
            </div>
        `).join('');
        
        this.recentItems.innerHTML = recentHTML;
    }
    
    getIconForType(type) {
        switch (type) {
            case 'cluster': return 'server';
            case 'database': return 'database';
            case 'table': return 'table';
            case 'field': return 'tags';
            default: return 'file';
        }
    }
    
    async navigateToRecent(type, id) {
        switch (type) {
            case 'cluster':
                await this.showCluster(id);
                break;
            case 'database':
                // For database, we need clusterId which is stored in the recent item
                const recentDatabaseItem = this.recentlyViewed.find(item => item.id === id);
                if (recentDatabaseItem && recentDatabaseItem.clusterId) {
                    await this.showDatabase(id, recentDatabaseItem.clusterId);
                }
                break;
            case 'table':
                // For table, we need databaseId and clusterId which are stored in the recent item
                const recentTableItem = this.recentlyViewed.find(item => item.id === id);
                if (recentTableItem && recentTableItem.databaseId && recentTableItem.clusterId) {
                    await this.showTable(id, recentTableItem.databaseId, recentTableItem.clusterId);
                }
                break;
            case 'field':
                this.showField(id);
                break;
        }
    }
    

    
    async updateStatistics() {
        try {
            // Only get clusters count from API
            const clusters = await getAllClusters();
            const clusterCount = document.getElementById('clusterCount');
            
            if (clusterCount && clusters) {
                clusterCount.textContent = clusters.length;
            }
        } catch (error) {
            console.log('Error updating statistics:', error);
            const clusterCount = document.getElementById('clusterCount');
            if (clusterCount) {
                clusterCount.textContent = 'Error';
            }
        }
    }
    
    async loadClusterDatabases(clusterId, container) {
        try {
            // Show loading state
            container.innerHTML = '<div class="loading-placeholder">Loading databases...</div>';
            
            console.log('loadClusterDatabases - clusterId:', clusterId);
            
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
        console.log('renderDatabaseNode - database:', database.id, 'clusterId:', clusterId);
        
        const databaseHTML = `
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
        
        console.log('Generated database HTML:', databaseHTML);
        return databaseHTML;
    }
    
    async loadDatabaseTables(databaseId, container) {
        try {
            // Show loading state
            container.innerHTML = '<div class="loading-placeholder">Loading tables...</div>';
            
            // Get clusterId from the database's parent container - improved method
            const databaseItem = container.parentElement;
            const clusterId = databaseItem.dataset.clusterId;
            
            console.log('loadDatabaseTables - databaseId:', databaseId, 'clusterId:', clusterId, 'databaseItem:', databaseItem);
            
            if (!clusterId) {
                console.error('clusterId not found for database:', databaseId);
                container.innerHTML = '<div class="error-message">Error: clusterId not found</div>';
                return;
            }
            
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
                    tablesHTML += this.renderTableNode(table, databaseId, clusterId);
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
            
            // Get databaseId and clusterId from the table's parent container
            const tableItem = container.closest('.tree-children').previousElementSibling;
            const databaseId = tableItem.dataset.databaseId;
            const clusterId = tableItem.dataset.clusterId;
            
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
                    fieldsHTML += this.renderFieldNode(field, tableId, databaseId, clusterId);
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
    

}

// Global function to show all clusters
async function showAllClusters() {
    if (window.navigationManager) {
        await window.navigationManager.showAllClustersView();
    }
}

// Initialize navigation manager when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.navigationManager = new NavigationManager();
});

// Export for global use
window.showAllClusters = showAllClusters; 