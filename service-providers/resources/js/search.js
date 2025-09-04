// Search functionality for Monitoring App
class SearchManager {
    constructor() {
        this.searchInput = document.getElementById('globalSearch');
        this.searchResults = document.getElementById('searchResults');
        this.currentFilter = 'all';
        this.debounceTimer = null;
        
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
    
    performSearch(query) {
        if (!query.trim()) {
            this.hideSearchResults();
            return;
        }
        // Use the global searchData function from data.js
        let results = [];
        if (typeof window.searchData === 'function') {
            results = window.searchData(query);
        }
        // Filter results based on current filter
        if (this.currentFilter !== 'all') {
            results = results.filter(result => result.type === this.currentFilter);
        }
        this.displaySearchResults(results);
    }
    
    displaySearchResults(results) {
        if (results.length === 0) {
            this.searchResults.innerHTML = '<div class="search-result-item">No results found</div>';
            this.showSearchResults();
            return;
        }
        
        const resultsHTML = results.map(result => `
            <div class="search-result-item" data-type="${result.type}" data-id="${result.id}">
                <div class="result-content">
                    <div class="result-header">
                        <span class="result-type ${result.type}">${result.type}</span>
                        <div class="result-title">${result.name}</div>
                    </div>
                    <div class="result-description">${result.path}</div>
                </div>
            </div>
        `).join('');
        
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
        this.hideSearchResults();
        this.searchInput.value = '';
        
        switch (type) {
            case 'cluster':
                this.navigateToCluster(id);
                break;
            case 'table':
                this.navigateToTable(id);
                break;
            case 'field':
                this.navigateToField(id);
                break;
        }
    }
    
    navigateToCluster(clusterId) {
        // This will be handled by the navigation module
        if (window.navigationManager) {
            window.navigationManager.showCluster(clusterId);
        }
    }
    
    navigateToTable(tableId) {
        // This will be handled by the navigation module
        if (window.navigationManager) {
            window.navigationManager.showTable(tableId);
        }
    }
    
    navigateToField(fieldId) {
        // This will be handled by the navigation module
        if (window.navigationManager) {
            window.navigationManager.showField(fieldId);
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