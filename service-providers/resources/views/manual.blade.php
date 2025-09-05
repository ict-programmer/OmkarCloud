<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Monitoring App</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

</head>
<body>
    <div class="app-container">
        <!-- Header -->
        <header class="header">
            <div class="header-content">
                <div class="logo">
                    <i class="fas fa-database"></i>
                    <h1>Monitoring App</h1>
                </div>
                <div class="search-container">
                    <div class="search-box">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" id="globalSearch" placeholder="Search clusters, tables, fields..." autocomplete="off">
                        <div class="search-results" id="searchResults"></div>
                    </div>
                </div>
                <div class="header-actions">
                    <button class="btn-secondary" id="exportBtn">
                        <i class="fas fa-download"></i>
                        Export
                    </button>
                    <button class="btn-secondary" id="logoutBtn" style="margin-left: 0.5rem;" onclick="performLogout()">
                        <i class="fas fa-sign-out-alt"></i>
                        Logout
                    </button>

                </div>
            </div>
        </header>

        <div class="main-layout">
            <!-- Sidebar -->
            <aside class="sidebar">
                <div class="sidebar-header">
                    <h3>Navigation</h3>
                </div>
                
                <div class="tree-view" id="treeView">
                    <!-- Tree structure will be populated by JavaScript -->
                </div>

                <div class="recently-viewed">
                    <h4>Recently Viewed</h4>
                    <div class="recent-items" id="recentItems">
                        <!-- Recent items will be populated by JavaScript -->
                    </div>
                </div>
            </aside>

            <!-- Main Content -->
            <main class="main-content">
                <div class="breadcrumb" id="breadcrumb">
                    <span class="breadcrumb-item">Home</span>
                </div>

                <div class="content-area" id="contentArea">
                    <!-- Content will be populated by JavaScript -->
                </div>
            </main>

            <!-- Right Panel -->
            <aside class="right-panel" id="rightPanel">
                <div class="panel-header">
                    <h4>Quick Info</h4>
                </div>
                <div class="panel-content" id="panelContent">
                    <p>Select an item to view quick information here.</p>
                </div>
            </aside>
        </div>
    </div>

    <!-- Modal for detailed view -->
    <div class="modal" id="detailModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="modalTitle">Item Details</h3>
                <button class="modal-close" onclick="closeModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body" id="modalBody">
                <!-- Modal content will be populated by JavaScript -->
            </div>
        </div>
    </div>

    <!-- JavaScript Files -->
    <script src="{{ asset('js/data.js') }}"></script>
    <script src="{{ asset('js/search.js') }}"></script>
    <script src="{{ asset('js/navigation.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    
    <script>
        // Global logout function - called by onclick handler
        window.performLogout = async function() {
            console.log('performLogout called!');
            
            try {
                console.log('Making logout request...');
                
                const response = await fetch('/logout', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                
                console.log('Logout response status:', response.status);
                
                if (response.ok) {
                    console.log('Logout successful, redirecting...');
                    // Clear any stored data
                    localStorage.clear();
                    sessionStorage.clear();
                    
                    // Redirect to login page
                    window.location.href = '/login';
                } else {
                    console.error('Logout failed with status:', response.status);
                    // Still redirect to login page
                    window.location.href = '/login';
                }
            } catch (error) {
                console.error('Logout error:', error);
                // Fallback: redirect to login page
                window.location.href = '/login';
            }
        };
        

        
        // Wait for DOM to be fully loaded before setting up event listeners
        document.addEventListener('DOMContentLoaded', async function() {
            console.log('DOM loaded, setting up logout functionality...');
            
            // Debug: Check if logout button exists
            const logoutBtn = document.getElementById('logoutBtn');
            console.log('Logout button found:', !!logoutBtn);
            if (logoutBtn) {
                console.log('Logout button text:', logoutBtn.textContent);
                console.log('Logout button ID:', logoutBtn.id);
                
                // Set up logout functionality
                logoutBtn.addEventListener('click', async function(e) {
                    e.preventDefault();
                    console.log('Logout button clicked!');
                    
                    try {
                        console.log('Making logout request...');
                        
                        const response = await fetch('/logout', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        });
                        
                        console.log('Logout response status:', response.status);
                        console.log('Logout response:', response);
                        
                        if (response.ok) {
                            console.log('Logout successful, redirecting...');
                            // Clear any stored data
                            localStorage.clear();
                            sessionStorage.clear();
                            
                            // Redirect to login page
                            window.location.href = '/login';
                        } else {
                            console.error('Logout failed with status:', response.status);
                            // Try to get response text for debugging
                            const responseText = await response.text();
                            console.error('Response text:', responseText);
                            
                            // Still redirect to login page
                            window.location.href = '/login';
                        }
                    } catch (error) {
                        console.error('Logout error:', error);
                        // Fallback: try direct redirect
                        try {
                            // Clear any stored data
                            localStorage.clear();
                            sessionStorage.clear();
                            
                            // Redirect to login page
                            window.location.href = '/login';
                        } catch (redirectError) {
                            console.error('Redirect failed:', redirectError);
                            // Last resort: reload the page
                            window.location.reload();
                        }
                    }
                });
                
                console.log('Logout event listener attached successfully');
            } else {
                console.error('Logout button not found!');
            }
            
            // Check authentication status
            try {
                const response = await fetch('/check-auth');
                const result = await response.json();
                
                if (!result.authenticated) {
                    // Redirect to login if not authenticated
                    window.location.href = '/login';
                }
            } catch (error) {
                console.error('Auth check failed:', error);
                // Continue showing login form
            }
        });
    </script>
</body>
</html>