<?php
echo "<h2>🧪 TEST API ENDPOINTS</h2>";

// Test endpoints
$endpoints = [
    'Products' => '/hoangduyminh/api/product',
    'Categories' => '/hoangduyminh/api/category',
    'Product by ID' => '/hoangduyminh/api/product/1',
    'Category by ID' => '/hoangduyminh/api/category/1'
];

foreach ($endpoints as $name => $endpoint) {
    echo "<h3>Testing: $name</h3>";
    echo "URL: <code>$endpoint</code><br>";
    
    $url = 'http://localhost' . $endpoint;
    $response = @file_get_contents($url);
    
    if ($response === FALSE) {
        echo "❌ Failed to fetch data<br>";
        $error = error_get_last();
        if ($error) {
            echo "Error: " . $error['message'] . "<br>";
        }
    } else {
        echo "✅ Response received:<br>";
        echo "<pre>" . htmlspecialchars($response) . "</pre>";
        
        // Try to decode JSON
        $data = json_decode($response, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            if (is_array($data)) {
                echo "📊 Found " . count($data) . " items<br>";
            } else {
                echo "📄 Single item response<br>";
            }
        } else {
            echo "⚠️ Invalid JSON response<br>";
        }
    }
    echo "<hr>";
}

// Direct database check
echo "<h3>🗃️ Direct Database Check:</h3>";

require_once('app/config/database.php');
require_once('app/models/ProductModel.php');
require_once('app/models/CategoryModel.php');

try {
    $db = (new Database())->getConnection();
    
    // Check products
    $productModel = new ProductModel($db);
    $products = $productModel->getProducts();
    echo "Products in database: " . count($products) . "<br>";
    
    if (count($products) > 0) {
        echo "Sample products:<br>";
        foreach (array_slice($products, 0, 3) as $product) {
            echo "- {$product->name} (ID: {$product->id})<br>";
        }
    } else {
        echo "⚠️ No products found in database<br>";
        echo "<a href='#' onclick='createSampleData()'>Create sample data</a><br>";
    }
    
    // Check categories
    $categoryModel = new CategoryModel($db);
    $categories = $categoryModel->getCategory();
    echo "<br>Categories in database: " . count($categories) . "<br>";
    
    if (count($categories) > 0) {
        echo "Sample categories:<br>";
        foreach (array_slice($categories, 0, 3) as $category) {
            echo "- {$category->name} (ID: {$category->id})<br>";
        }
    } else {
        echo "⚠️ No categories found in database<br>";
    }
    
} catch (Exception $e) {
    echo "❌ Database error: " . $e->getMessage() . "<br>";
}

echo "<hr>";
echo "<h3>🔧 Quick Actions:</h3>";
echo "<a href='create_sample_data.php'>Create Sample Data</a><br>";
echo "<a href='/hoangduyminh/Product/home'>Test Product Home Page</a><br>";
echo "<a href='/hoangduyminh/Category/list'>Test Category List Page</a><br>";
?>

<script>
function createSampleData() {
    if (confirm('Create sample products and categories?')) {
        fetch('create_sample_data.php')
        .then(response => response.text())
        .then(data => {
            alert('Sample data created!');
            location.reload();
        });
    }
}
</script>