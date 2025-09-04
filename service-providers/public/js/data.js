// Sample MongoDB Data Structure
const mongoDBData = {
    clusters: [
        {
            id: 'cluster-1',
            name: 'Production Cluster',
            description: 'Main production database cluster for the application',
            host: 'prod-mongodb.example.com',
            port: 27017,
            status: 'active',
            tables: [
                {
                    id: 'table-1',
                    name: 'users',
                    description: 'User accounts and authentication data',
                    size: '2.3 GB',
                    records: 150000,
                    fields: [
                        { name: '_id', type: 'ObjectId', description: 'Unique identifier' },
                        { name: 'username', type: 'String', description: 'User login name' },
                        { name: 'email', type: 'String', description: 'User email address' },
                        { name: 'password_hash', type: 'String', description: 'Hashed password' },
                        { name: 'created_at', type: 'Date', description: 'Account creation date' },
                        { name: 'last_login', type: 'Date', description: 'Last login timestamp' },
                        { name: 'is_active', type: 'Boolean', description: 'Account status' }
                    ]
                },
                {
                    id: 'table-2',
                    name: 'products',
                    description: 'Product catalog and inventory information',
                    size: '5.7 GB',
                    records: 450000,
                    fields: [
                        { name: '_id', type: 'ObjectId', description: 'Unique identifier' },
                        { name: 'name', type: 'String', description: 'Product name' },
                        { name: 'description', type: 'String', description: 'Product description' },
                        { name: 'price', type: 'Number', description: 'Product price' },
                        { name: 'category', type: 'String', description: 'Product category' },
                        { name: 'stock_quantity', type: 'Number', description: 'Available stock' },
                        { name: 'created_at', type: 'Date', description: 'Product creation date' },
                        { name: 'updated_at', type: 'Date', description: 'Last update timestamp' }
                    ]
                },
                {
                    id: 'table-3',
                    name: 'orders',
                    description: 'Customer orders and transaction data',
                    size: '12.8 GB',
                    records: 850000,
                    fields: [
                        { name: '_id', type: 'ObjectId', description: 'Unique identifier' },
                        { name: 'order_number', type: 'String', description: 'Unique order number' },
                        { name: 'customer_id', type: 'ObjectId', description: 'Reference to customer' },
                        { name: 'order_date', type: 'Date', description: 'Order creation date' },
                        { name: 'status', type: 'String', description: 'Order status (pending, processing, shipped, delivered, cancelled)' },
                        { name: 'total_amount', type: 'Number', description: 'Total order amount' },
                        { name: 'shipping_address', type: 'Object', description: 'Shipping address details' },
                        { name: 'billing_address', type: 'Object', description: 'Billing address details' },
                        { name: 'payment_method', type: 'String', description: 'Payment method used' },
                        { name: 'payment_status', type: 'String', description: 'Payment status (pending, paid, failed)' },
                        { name: 'items', type: 'Array', description: 'Array of order items' },
                        { name: 'shipping_cost', type: 'Number', description: 'Shipping cost' },
                        { name: 'tax_amount', type: 'Number', description: 'Tax amount' },
                        { name: 'discount_amount', type: 'Number', description: 'Discount amount applied' },
                        { name: 'notes', type: 'String', description: 'Order notes or special instructions' },
                        { name: 'created_at', type: 'Date', description: 'Order creation timestamp' },
                        { name: 'updated_at', type: 'Date', description: 'Last update timestamp' }
                    ]
                }
            ]
        },
        {
            id: 'cluster-2',
            name: 'Analytics Cluster',
            description: 'Analytics and reporting database cluster',
            host: 'analytics-mongodb.example.com',
            port: 27017,
            status: 'active',
            tables: [
                {
                    id: 'table-4',
                    name: 'analytics_events',
                    description: 'User interaction and analytics events',
                    size: '15.2 GB',
                    records: 2500000,
                    fields: [
                        { name: '_id', type: 'ObjectId', description: 'Unique identifier' },
                        { name: 'user_id', type: 'ObjectId', description: 'Reference to user' },
                        { name: 'event_type', type: 'String', description: 'Type of event' },
                        { name: 'event_data', type: 'Object', description: 'Event specific data' },
                        { name: 'timestamp', type: 'Date', description: 'Event timestamp' },
                        { name: 'session_id', type: 'String', description: 'User session identifier' }
                    ]
                },
                {
                    id: 'table-5',
                    name: 'reports',
                    description: 'Generated reports and analytics data',
                    size: '8.9 GB',
                    records: 75000,
                    fields: [
                        { name: '_id', type: 'ObjectId', description: 'Unique identifier' },
                        { name: 'report_type', type: 'String', description: 'Type of report' },
                        { name: 'report_data', type: 'Object', description: 'Report content' },
                        { name: 'generated_at', type: 'Date', description: 'Report generation date' },
                        { name: 'created_by', type: 'ObjectId', description: 'User who generated report' }
                    ]
                }
            ]
        },
        {
            id: 'cluster-3',
            name: 'Development Cluster',
            description: 'Development and testing environment',
            host: 'dev-mongodb.example.com',
            port: 27017,
            status: 'active',
            tables: [
                {
                    id: 'table-6',
                    name: 'test_data',
                    description: 'Test data for development and testing',
                    size: '1.1 GB',
                    records: 50000,
                    fields: [
                        { name: '_id', type: 'ObjectId', description: 'Unique identifier' },
                        { name: 'test_name', type: 'String', description: 'Name of the test' },
                        { name: 'test_data', type: 'Object', description: 'Test data content' },
                        { name: 'created_at', type: 'Date', description: 'Test data creation date' }
                    ]
                }
            ]
        }
    ]
};

// Helper function to get all clusters from API
async function getAllClusters() {
    try {
        // Show loading spinner
        showLoader();
        
        const response = await fetch('https://dev-master-clusterseed.internetcash.io/api/v2.0.0/list_clusters');
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const result = await response.json();
        
        if (result.success && result.data) {
            // Transform API data to match our expected format
            const transformedClusters = result.data.map(cluster => ({
                id: cluster.id,
                name: cluster.cluster_name,
                description: cluster.description,
                database_count: cluster.database_count,
                tables: [] // Will be loaded on-demand later
            }));
            
            // Hide loader
            hideLoader();
            return transformedClusters;
        } else {
            throw new Error('API response format error');
        }
    } catch (error) {
        console.error('Error fetching clusters:', error);
        hideLoader();
        // Return empty array on error
        return [];
    }
}

// Helper function to get cluster by ID
async function getClusterById(clusterId) {
    try {
        const clusters = await getAllClusters();
        return clusters.find(cluster => cluster.id === clusterId);
    } catch (error) {
        console.error('Error getting cluster by ID:', error);
        return null;
    }
}

// Helper function to get table by ID
function getTableById(tableId) {
    for (const cluster of mongoDBData.clusters) {
        const table = cluster.tables.find(table => table.id === tableId);
        if (table) {
            return { table, cluster };
        }
    }
    return null;
}

// Helper function to search across all data
function searchData(query) {
    const results = [];
    const searchTerm = query.toLowerCase();
    
    // Search in clusters
    mongoDBData.clusters.forEach(cluster => {
        if (cluster.name.toLowerCase().includes(searchTerm) || 
            cluster.description.toLowerCase().includes(searchTerm)) {
            results.push({
                type: 'cluster',
                id: cluster.id,
                name: cluster.name,
                description: cluster.description,
                path: cluster.name
            });
        }
        
        // Search in tables
        cluster.tables.forEach(table => {
            if (table.name.toLowerCase().includes(searchTerm) || 
                table.description.toLowerCase().includes(searchTerm)) {
                results.push({
                    type: 'table',
                    id: table.id,
                    name: table.name,
                    description: table.description,
                    path: `${cluster.name} > ${table.name}`
                });
            }
            
            // Search in fields
            table.fields.forEach(field => {
                if (field.name.toLowerCase().includes(searchTerm) || 
                    field.description.toLowerCase().includes(searchTerm)) {
                    results.push({
                        type: 'field',
                        id: `${table.id}-${field.name}`,
                        name: field.name,
                        description: field.description,
                        path: `${cluster.name} > ${table.name} > ${field.name}`
                    });
                }
            });
        });
    });
    
    return results;
}

// Helper function to get statistics
async function getStatistics() {
    try {
        const clusters = await getAllClusters();
        let totalClusters = clusters.length;
        let totalTables = 0;
        let totalFields = 0;
        
        // For now, we only have cluster data, so tables and fields are 0
        // This will be updated when we implement the other APIs
        clusters.forEach(cluster => {
            totalTables += cluster.tables ? cluster.tables.length : 0;
            if (cluster.tables) {
                cluster.tables.forEach(table => {
                    totalFields += table.fields ? table.fields.length : 0;
                });
            }
        });
        
        return {
            clusters: totalClusters,
            tables: totalTables,
            fields: totalFields
        };
    } catch (error) {
        console.error('Error getting statistics:', error);
        return {
            clusters: 0,
            tables: 0,
            fields: 0
        };
    }
}

// Loader functions
function showLoader() {
    const loader = document.getElementById('loader');
    if (loader) {
        loader.style.display = 'flex';
    }
}

function hideLoader() {
    const loader = document.getElementById('loader');
    if (loader) {
        loader.style.display = 'none';
    }
}

// Export functions for use in other modules
window.mongoDBData = mongoDBData;
window.getAllClusters = getAllClusters;
window.getClusterById = getClusterById;
window.getTableById = getTableById;
window.searchData = searchData;
window.getStatistics = getStatistics;
window.showLoader = showLoader;
window.hideLoader = hideLoader; 