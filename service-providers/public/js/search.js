// Search functionality for MongoDB Manual Book
class SearchManager {
    constructor() {
        this.searchInput = document.getElementById('globalSearch');
        this.searchResults = document.getElementById('searchResults');
        this.currentFilter = 'all';
        this.debounceTimer = null;
        this.searchCache = {
            clusters: [],
            databases: [],
            tables: [],
            fields: [],
            lastUpdated: null
        };
        this.cacheExpiry = 5 * 60 * 1000; // 5 minutes
        
        this.initializeSearch();
    }
    
    initializeSearch() {
        // Add event listeners
        this.searchInput.addEventListener('input', (e) => {
            this.handleSearchInput(e.target.value);
        });
        
        this.searchInput.addEventListener('focus', () => {
            this.showSearchResults();
        });
        
        // Add refresh button event listener
        const refreshBtn = document.getElementById('refreshSearchBtn');
        if (refreshBtn) {
            refreshBtn.addEventListener('click', () => {
                this.refreshCache();
            });
        }
        
        // Close search results when clicking outside
        document.addEventListener('click', (e) => {
            if (!this.searchInput.contains(e.target) && !this.searchResults.contains(e.target)) {
                this.hideSearchResults();
            }
        });
        
        // Handle keyboard navigation
        this.searchInput.addEventListener('keydown', (e) => {
            this.handleKeyboardNavigation(e);
        });
    }
    
    handleSearchInput(query) {
        // Clear previous timer
        if (this.debounceTimer) {
            clearTimeout(this.debounceTimer);
        }
        
        // Debounce search to avoid too many searches
        this.debounceTimer = setTimeout(() => {
            this.performSearch(query);
        }, 300);
    }
    
    async performSearch(query) {
        if (!query.trim()) {
            this.hideSearchResults();
            return;
        }
        
        // Check if we need to refresh cache
        if (this.isCacheExpired()) {
            this.showSearchLoading();
            await this.refreshSearchCache();
        } else {
            // Show quick search indicator
            this.showQuickSearchLoading();
        }
        
        const results = this.searchCachedData(query);
        this.displaySearchResults(results);
    }
    
    showSearchLoading() {
        this.searchResults.innerHTML = `
            <div class="search-result-item loading-results">
                <div class="result-content">
                    <div class="result-header">
                        <div class="loading-spinner"></div>
                        <div class="result-title">Loading search data...</div>
                    </div>
                    <div class="result-description">This may take a moment on first search</div>
                </div>
            </div>
        `;
        this.showSearchResults();
    }
    
    showQuickSearchLoading() {
        this.searchResults.innerHTML = `
            <div class="search-result-item loading-results">
                <div class="result-content">
                    <div class="result-header">
                        <div class="loading-spinner"></div>
                        <div class="result-title">Searching...</div>
                    </div>
                </div>
            </div>
        `;
        this.showSearchResults();
    }
    
    isCacheExpired() {
        if (!this.searchCache.lastUpdated) return true;
        return (Date.now() - this.searchCache.lastUpdated) > this.cacheExpiry;
    }
    
    async refreshSearchCache() {
        try {
            // Clear existing cache
            this.searchCache = {
                clusters: [],
                databases: [],
                tables: [],
                fields: [],
                lastUpdated: null
            };
            
            // Get all clusters
            const clusters = await getAllClusters();
            this.searchCache.clusters = clusters;
            
            // Load data for first 3 clusters only to keep it manageable
            const clustersToLoad = clusters.slice(0, 3);
            
            for (const cluster of clustersToLoad) {
                try {
                    // Load databases for this cluster
                    const databasesResponse = await fetch(`https://dev-master-clusterseed.internetcash.io/api/v2.0.0/clusters_json/${cluster.id}/databases`);
                    if (databasesResponse.ok) {
                        const databasesResult = await databasesResponse.json();
                        if (databasesResult.success && databasesResult.data) {
                            const databases = databasesResult.data.map(db => ({
                                ...db,
                                clusterId: cluster.id,
                                clusterName: cluster.name
                            }));
                            this.searchCache.databases.push(...databases);
                            
                            // Load tables for first 2 databases only
                            const databasesToLoad = databases.slice(0, 2);
                            for (const database of databasesToLoad) {
                                try {
                                    const tablesResponse = await fetch(`https://dev-master-clusterseed.internetcash.io/api/v2.0.0/databases_json/${database.id}/tables`);
                                    if (tablesResponse.ok) {
                                        const tablesResult = await tablesResponse.json();
                                        if (tablesResult.success && tablesResult.data) {
                                            const tables = tablesResult.data.map(table => ({
                                                ...table,
                                                clusterId: cluster.id,
                                                clusterName: cluster.name,
                                                databaseId: database.id,
                                                databaseName: database.database_name
                                            }));
                                            this.searchCache.tables.push(...tables);
                                            
                                            // Load fields for first 2 tables only
                                            const tablesToLoad = tables.slice(0, 2);
                                            for (const table of tablesToLoad) {
                                                try {
                                                    const fieldsResponse = await fetch(`https://dev-master-clusterseed.internetcash.io/api/v2.0.0/tables_json/${table.id}/fields`);
                                                    if (fieldsResponse.ok) {
                                                        const fields = await fieldsResponse.json();
                                                        if (Array.isArray(fields)) {
                                                            const processedFields = fields.map(field => ({
                                                                ...field,
                                                                clusterId: cluster.id,
                                                                clusterName: cluster.name,
                                                                databaseId: database.id,
                                                                databaseName: database.database_name,
                                                                tableId: table.id,
                                                                tableName: table.table_name
                                                            }));
                                                            this.searchCache.fields.push(...processedFields);
                                                        }
                                                    }
                                                } catch (fieldError) {
                                                    console.warn(`Error loading fields for table ${table.id}:`, fieldError);
                                                }
                                            }
                                        }
                                    }
                                } catch (tableError) {
                                    console.warn(`Error loading tables for database ${database.id}:`, tableError);
                                }
                            }
                        }
                    }
                } catch (databaseError) {
                    console.warn(`Error loading databases for cluster ${cluster.id}:`, databaseError);
                }
            }
            
            this.searchCache.lastUpdated = Date.now();
            console.log('Search cache refreshed:', {
                clusters: this.searchCache.clusters.length,
                databases: this.searchCache.databases.length,
                tables: this.searchCache.tables.length,
                fields: this.searchCache.fields.length
            });
            
        } catch (error) {
            console.error('Error refreshing search cache:', error);
        }
    }
    
    searchCachedData(query) {
        const searchTerm = query.toLowerCase();
        const results = [];
        
        // Search in clusters
        this.searchCache.clusters.forEach(cluster => {
            if (cluster.name.toLowerCase().includes(searchTerm) || 
                (cluster.description && cluster.description.toLowerCase().includes(searchTerm))) {
                results.push({
                    type: 'cluster',
                    id: cluster.id,
                    name: cluster.name,
                    description: cluster.description || 'No description',
                    path: `🏠 ${cluster.name}`
                });
            }
        });
        
        // Search in databases
        this.searchCache.databases.forEach(database => {
            if (database.database_name.toLowerCase().includes(searchTerm) ||
                (database.description && database.description.toLowerCase().includes(searchTerm))) {
                results.push({
                    type: 'database',
                    id: database.id,
                    name: database.database_name,
                    description: database.description || 'No description',
                    path: `🏠 ${database.clusterName} > 🗄️ ${database.database_name}`,
                    clusterId: database.clusterId
                });
            }
        });
        
        // Search in tables
        this.searchCache.tables.forEach(table => {
            if (table.table_name.toLowerCase().includes(searchTerm) ||
                (table.description && table.description.toLowerCase().includes(searchTerm))) {
                results.push({
                    type: 'table',
                    id: table.id,
                    name: table.table_name,
                    description: table.description || 'No description',
                    path: `🏠 ${table.clusterName} > 🗄️ ${table.databaseName} > 📊 ${table.table_name}`,
                    clusterId: table.clusterId,
                    databaseId: table.databaseId
                });
            }
        });
        
        // Search in fields
        this.searchCache.fields.forEach(field => {
            if (field.field_name.toLowerCase().includes(searchTerm) ||
                (field.description && field.description.toLowerCase().includes(searchTerm))) {
                results.push({
                    type: 'field',
                    id: `${field.tableId}-${field.field_name}`,
                    name: field.field_name,
                    description: field.description || 'No description',
                    path: `🏠 ${field.clusterName} > 🗄️ ${field.databaseName} > 📊 ${field.tableName} > 🏷️ ${field.field_name}`,
                    clusterId: field.clusterId,
                    databaseId: field.databaseId,
                    tableId: field.tableId
                });
            }
        });
        
        // Filter results based on current filter
        if (this.currentFilter !== 'all') {
            return results.filter(result => result.type === this.currentFilter);
        }
        
        return results;
    }
    
    
    // Utility to highlight search term in text
    highlightTerm(text, term) {
        if (!term) return text;
        const regex = new RegExp(`(${term.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')})`, 'gi');
        return text.replace(regex, '<mark>$1</mark>');
    }
    
    displaySearchResults(results) {
        const query = this.searchInput.value.trim();
        if (results.length === 0) {
            this.searchResults.innerHTML = `
                <div class="search-result-item no-results">
                    <div class="no-results-title">No results found</div>
                    <div class="no-results-desc">Try adjusting your search terms or filters</div>
                </div>
            `;
            this.showSearchResults();
            return;
        }
        const resultsHTML = results.map(result => {
            let dataAttributes = `data-type="${result.type}" data-id="${result.id}"`;
            
            // Add additional context data for fields
            if (result.type === 'field' && result.clusterId && result.databaseId && result.tableId) {
                dataAttributes += ` data-cluster-id="${result.clusterId}" data-database-id="${result.databaseId}" data-table-id="${result.tableId}"`;
            }
            // Add additional context data for tables
            else if (result.type === 'table' && result.clusterId && result.databaseId) {
                dataAttributes += ` data-cluster-id="${result.clusterId}" data-database-id="${result.databaseId}"`;
            }
            // Add additional context data for databases
            else if (result.type === 'database' && result.clusterId) {
                dataAttributes += ` data-cluster-id="${result.clusterId}"`;
            }
            
            return `
                <div class="search-result-item" ${dataAttributes}>
                    <div class="result-content">
                        <div class="result-header">
                            <span class="result-type ${result.type}">${result.type}</span>
                            <div class="result-title">${this.highlightTerm(result.name, query)}</div>
                        </div>
                        <div class="result-description">${this.highlightTerm(result.path, query)}</div>
                    </div>
                </div>
            `;
        }).join('');
        this.searchResults.innerHTML = resultsHTML;
        this.showSearchResults();
        // Add click handlers to search results
        this.addSearchResultHandlers();
    }
    
    addSearchResultHandlers() {
        const resultItems = this.searchResults.querySelectorAll('.search-result-item');
        
        resultItems.forEach(item => {
            item.addEventListener('click', () => {
                const type = item.dataset.type;
                const id = item.dataset.id;
                this.handleSearchResultClick(type, id);
            });
        });
    }
    
    handleSearchResultClick(type, id) {
        // Store context information before hiding search results
        const searchResultItem = document.querySelector(`[data-id="${id}"][data-type="${type}"]`);
        let contextInfo = null;
        
        if (searchResultItem) {
            contextInfo = {
                clusterId: searchResultItem.dataset.clusterId,
                databaseId: searchResultItem.dataset.databaseId,
                tableId: searchResultItem.dataset.tableId
            };
            
            // Extract names from path description for fields
            if (type === 'field') {
                const pathDescription = searchResultItem.querySelector('.result-description').textContent;
                const tableMatch = pathDescription.match(/📊 ([^>]+)/);
                const clusterMatch = pathDescription.match(/🏠 ([^>]+)/);
                const databaseMatch = pathDescription.match(/🗄️ ([^>]+)/);
                
                contextInfo.tableName = tableMatch ? tableMatch[1].trim() : 'Unknown Table';
                contextInfo.clusterName = clusterMatch ? clusterMatch[1].trim() : 'Unknown Cluster';
                contextInfo.databaseName = databaseMatch ? databaseMatch[1].trim() : 'Unknown Database';
            }
        }
        
        this.hideSearchResults();
        this.searchInput.value = '';
        
        switch (type) {
            case 'cluster':
                this.navigateToCluster(id);
                break;
            case 'database':
                this.navigateToDatabase(id, contextInfo);
                break;
            case 'table':
                this.navigateToTable(id, contextInfo);
                break;
            case 'field':
                this.navigateToField(id, contextInfo);
                break;
        }
    }
    
    navigateToCluster(clusterId) {
        // This will be handled by the navigation module
        if (window.navigationManager) {
            window.navigationManager.showCluster(clusterId);
        }
    }
    
    navigateToDatabase(databaseId, contextInfo) {
        // This will be handled by the navigation module
        if (window.navigationManager) {
            if (contextInfo && contextInfo.clusterId) {
                // Call showDatabase with context information
                window.navigationManager.showDatabase(databaseId, contextInfo.clusterId);
            } else {
                // Fallback to just the database ID if context not found
                window.navigationManager.showDatabase(databaseId);
            }
        }
    }
    
    navigateToTable(tableId, contextInfo) {
        // This will be handled by the navigation module
        if (window.navigationManager) {
            if (contextInfo && contextInfo.databaseId && contextInfo.clusterId) {
                // Call showTable with context information
                window.navigationManager.showTable(tableId, contextInfo.databaseId, contextInfo.clusterId);
            } else {
                // Fallback to just the table ID if context not found
                window.navigationManager.showTable(tableId);
            }
        }
    }
    
    navigateToField(fieldId, contextInfo) {
        // This will be handled by the navigation module
        if (window.navigationManager) {
            if (contextInfo && contextInfo.databaseName && contextInfo.tableName && contextInfo.clusterName) {
                // Call showField with all the context information
                window.navigationManager.showField(
                    fieldId, 
                    contextInfo.tableName, 
                    contextInfo.clusterName, 
                    contextInfo.databaseId, 
                    contextInfo.databaseName
                );
            } else {
                // Fallback to just the field ID if context not found
                window.navigationManager.showField(fieldId);
            }
        }
    }
    
    handleKeyboardNavigation(e) {
        const resultItems = this.searchResults.querySelectorAll('.search-result-item');
        const currentIndex = Array.from(resultItems).findIndex(item => item.classList.contains('selected'));
        
        switch (e.key) {
            case 'ArrowDown':
                e.preventDefault();
                this.selectNextResult(resultItems, currentIndex);
                break;
            case 'ArrowUp':
                e.preventDefault();
                this.selectPreviousResult(resultItems, currentIndex);
                break;
            case 'Enter':
                e.preventDefault();
                if (currentIndex >= 0) {
                    resultItems[currentIndex].click();
                }
                break;
            case 'Escape':
                this.hideSearchResults();
                this.searchInput.blur();
                break;
        }
    }
    
    selectNextResult(resultItems, currentIndex) {
        const nextIndex = currentIndex < resultItems.length - 1 ? currentIndex + 1 : 0;
        this.selectResult(resultItems, currentIndex, nextIndex);
    }
    
    selectPreviousResult(resultItems, currentIndex) {
        const prevIndex = currentIndex > 0 ? currentIndex - 1 : resultItems.length - 1;
        this.selectResult(resultItems, currentIndex, prevIndex);
    }
    
    selectResult(resultItems, currentIndex, newIndex) {
        if (currentIndex >= 0) {
            resultItems[currentIndex].classList.remove('selected');
        }
        if (newIndex >= 0) {
            resultItems[newIndex].classList.add('selected');
            resultItems[newIndex].scrollIntoView({ block: 'nearest' });
        }
    }
    
    showSearchResults() {
        this.searchResults.style.display = 'block';
    }
    
    hideSearchResults() {
        this.searchResults.style.display = 'none';
    }
    
    setFilter(filter) {
        this.currentFilter = filter;
        const currentQuery = this.searchInput.value.trim();
        if (currentQuery) {
            this.performSearch(currentQuery);
        }
    }
    
    clearSearch() {
        this.searchInput.value = '';
        this.hideSearchResults();
    }
    
    // Method to manually refresh cache (can be called from UI)
    async refreshCache() {
        this.searchCache.lastUpdated = null; // Force refresh
        this.showSearchLoading();
        await this.refreshSearchCache();
        this.hideSearchResults();
    }
    
    // Method to get cache status
    getCacheStatus() {
        return {
            hasData: this.searchCache.clusters.length > 0,
            lastUpdated: this.searchCache.lastUpdated,
            isExpired: this.isCacheExpired(),
            counts: {
                clusters: this.searchCache.clusters.length,
                databases: this.searchCache.databases.length,
                tables: this.searchCache.tables.length,
                fields: this.searchCache.fields.length
            }
        };
    }
}

// Filter functionality
class FilterManager {
    constructor() {
        this.filterButtons = document.querySelectorAll('.filter-btn');
        this.initializeFilters();
    }
    
    initializeFilters() {
        this.filterButtons.forEach(button => {
            button.addEventListener('click', () => {
                this.setActiveFilter(button);
            });
        });
    }
    
    setActiveFilter(clickedButton) {
        // Remove active class from all buttons
        this.filterButtons.forEach(btn => btn.classList.remove('active'));
        
        // Add active class to clicked button
        clickedButton.classList.add('active');
        
        // Get filter value
        const filter = clickedButton.dataset.filter;
        
        // Update search filter if search manager exists
        if (window.searchManager) {
            window.searchManager.setFilter(filter);
        }
        
        // Update tree view filter if navigation manager exists
        if (window.navigationManager) {
            window.navigationManager.filterTreeView(filter);
        }
    }
}

// Initialize search and filter managers when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.searchManager = new SearchManager();
    window.filterManager = new FilterManager();
});

// Global function to focus search
function focusSearch() {
    const searchInput = document.getElementById('globalSearch');
    if (searchInput) {
        searchInput.focus();
    }
}

// Export for global use
window.focusSearch = focusSearch; 