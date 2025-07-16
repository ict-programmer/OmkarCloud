// 🔁 Empty container for dynamic data
const mongoDBData = {
    clusters: []  // Will be filled after API calls
};

// 🧪 For testing: expose to window
window.mongoDBData = mongoDBData;

// 🌐 Base API URL
const BASE_URL = "https://dev-master-clusterseed.internetcash.io/api/v2.0.0";

// 🚀 Step 2: Load all clusters dynamically from API
async function loadAllClustersFromAPI() {
    // showLoader();
    try {
        const response = await fetch(`${BASE_URL}/list_clusters`);
        if (!response.ok) throw new Error('Failed to fetch clusters');

        const raw = await response.json();
        const clusterList = raw.data; // 👈 confirmed structure

        mongoDBData.clusters = clusterList.map(cluster => ({
            id: String(cluster.id),
            unique_id: cluster.unique_id,
            cluster_name: cluster.cluster_name,
            name: cluster.cluster_name,
            description: cluster.description || "na",
            host: "na",        // static placeholder
            port: 27017,       // default value
            status: "active",  // placeholder
            tables: []         // to be populated next
        }));

        console.log("✅ Clusters loaded:", mongoDBData.clusters);
    } catch (err) {
        console.error("❌ Error loading clusters:", err);
    }
}

// 🔁 Load databases for one cluster
async function loadDatabasesForCluster(cluster) {
    try {
        const response = await fetch(`${BASE_URL}/clusters_json/${cluster.id}/databases`);
        if (!response.ok) throw new Error(`Failed to load databases for cluster ${cluster.cluster_name}`);

        const raw = await response.json();
        const databaseList = raw.data;

        // Attach accurately to the cluster
        cluster.databases = databaseList.map(db => ({
            id: String(db.id),
            unique_id: db.unique_id,
            database_name: db.database_name,
            description: db.description || "na",
            tables: []  // placeholder for next step
        }));

        console.log(`✅ Loaded databases for cluster "${cluster.cluster_name}"`, cluster.databases);
    } catch (err) {
        console.error(`❌ Error loading databases for cluster "${cluster.cluster_name}":`, err);
    }
}

// 🔁 Load tables for one database
async function loadTablesForDatabase(database) {
    try {
        const response = await fetch(`${BASE_URL}/databases_json/${database.id}/tables`);

        if (response.status === 404) {
            console.warn(`⚠️ No tables found for database "${database.database_name}". Skipping...`);
            database.tables = [];
            return;
        }

        if (!response.ok) throw new Error(`Failed to load tables for database ${database.database_name}`);

        const raw = await response.json();
        const tableList = raw.data;

        database.tables = tableList.map(table => ({
            id: String(table.id),
            unique_id: table.unique_id,
            table_name: table.table_name,
            description: table.description || "na",
            size: "na",         // static placeholder
            records: 0,         // static placeholder
            fields: []          // to be loaded later
        }));

        console.log(`✅ Loaded tables for database "${database.database_name}"`, database.tables);
    } catch (err) {
        console.error(`❌ Error loading tables for database "${database.database_name}":`, err);
    }
}

// 🔁 Load fields for a given table
// 🔁 Load fields for one table
async function loadFieldsForTable(table) {
    try {
        const response = await fetch(`${BASE_URL}/tables_json/${table.id}/fields`);
        if (!response.ok) throw new Error(`Failed to load fields for table ${table.table_name}`);

        const fieldList = await response.json();

        if (!Array.isArray(fieldList)) {
            console.warn(`⚠️ No fields array received for table "${table.table_name}". Response:`, raw);
            table.fields = [];
            return;
        }

        table.fields = fieldList.map(field => ({
            name: field.field_name,
            type: field.field_type,
            description: field.description || "na"
        }));

        console.log(`✅ Loaded fields for table "${table.table_name}"`, table.fields);
    } catch (err) {
        console.error(`❌ Error loading fields for table "${table.table_name}":`, err);
    }
}












// Helper function to get all clusters
function getAllClusters() {
    return mongoDBData.clusters;
}

// Helper function to get cluster by ID
function getClusterById(clusterId) {
    return mongoDBData.clusters.find(cluster => cluster.id === clusterId);
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
        if ((cluster.name && cluster.name.toLowerCase().includes(searchTerm)) ||
            (cluster.description && cluster.description.toLowerCase().includes(searchTerm))) {
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
            if ((table.name && table.name.toLowerCase().includes(searchTerm)) || 
                (table.description && table.description.toLowerCase().includes(searchTerm))) {
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
                if ((field.name && field.name.toLowerCase().includes(searchTerm)) || 
                    (field.description && field.description.toLowerCase().includes(searchTerm))) {
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
function getStatistics() {
    let totalClusters = mongoDBData.clusters.length;
    let totalTables = 0;
    let totalFields = 0;
    
    mongoDBData.clusters.forEach(cluster => {
        totalTables += cluster.tables.length;
        cluster.tables.forEach(table => {
            totalFields += table.fields.length;
        });
    });
    
    return {
        clusters: totalClusters,
        tables: totalTables,
        fields: totalFields
    };
}






function showLoader() {
    const loader = document.getElementById('loader');
    if (loader) loader.style.display = 'flex';
}

function hideLoader() {
    const loader = document.getElementById('loader');
    if (loader) loader.style.display = 'none';
}

window.showLoader = showLoader;
window.hideLoader = hideLoader;

function renderTestClusterList() {
    const container = document.getElementById('treeView');
    if (!container) return;

    const clusters = getAllClusters();
    if (!clusters.length) {
        container.innerHTML = "<p>🚫 No clusters loaded.</p>";
        return;
    }

    // Render simple list of cluster names
    const ul = document.createElement("ul");

    clusters.forEach(cluster => {
        const li = document.createElement("li");
        li.textContent = `📦 ${cluster.cluster_name}`;
        ul.appendChild(li);
    });

    container.innerHTML = ""; // Clear previous content
    container.appendChild(ul);
}






// Export functions for use in other modules
window.mongoDBData = mongoDBData;
window.getAllClusters = getAllClusters;
window.getClusterById = getClusterById;
window.getTableById = getTableById;
window.searchData = searchData;
window.getStatistics = getStatistics; 



document.addEventListener('DOMContentLoaded', async () => {
    showLoader(); // 👈 show loader before fetch
    await loadAllClustersFromAPI();

    for (const cluster of mongoDBData.clusters) {
        try {
            console.log(`🔍 Processing cluster: ${cluster.cluster_name} (${cluster.id})`);

            cluster.tables = [];
            
            await loadDatabasesForCluster(cluster);
            console.log(`✅ Loaded databases for cluster "${cluster.cluster_name}"`);
            for (const db of cluster.databases) {
                await loadTablesForDatabase(db);
                console.log(`✅ Loaded tables for database "${db.database_name}"`);
                for (const table of db.tables) {
                    cluster.tables.push(table);
                }
            }

            console.log(`📦 Finished cluster "${cluster.cluster_name}"`);
        } catch (err) {
            console.error(`❌ Error in cluster "${cluster.cluster_name}":`, err);
        }
    }

    // ✅ Now data is fully ready for the tree
    if (window.navigationManager && typeof window.navigationManager.initializeNavigation === 'function') {
        window.navigationManager.initializeNavigation();
        hideLoader(); // ✅ hide loader after rendering nav
    } else {
        console.warn("⚠️ navigationManager is not ready");
        hideLoader(); // fallback hide to avoid stuck loader
    }

});


