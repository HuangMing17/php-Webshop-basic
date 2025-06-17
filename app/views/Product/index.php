<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product List</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        img {
            width: 50px;
            height: auto;
        }
        .error {
            color: red;
        }
    </style>
</head>
<body>
    <h2>Product List</h2>
    <table id="productTable">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Description</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Image</th>
                <th>Category</th>
            </tr>
        </thead>
        <tbody id="productBody"></tbody>
    </table>
    <p id="errorMessage" class="error"></p>

    <script>
        // Function to fetch and display products
        async function fetchProducts() {
            try {
                const response = await fetch('http://localhost:81/hoangduyminh/api/product');
                if (!response.ok) {
                    throw new Error('Failed to fetch products');
                }
                const products = await response.json();
                displayProducts(products);
            } catch (error) {
                document.getElementById('errorMessage').textContent = 'Error: ' + error.message;
            }
        }

        // Function to display products in the table
        function displayProducts(products) {
            const tbody = document.getElementById('productBody');
            tbody.innerHTML = ''; // Clear existing content
            products.forEach(product => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${product.id}</td>
                    <td>${product.name}</td>
                    <td>${product.description}</td>
                    <td>${product.SoLuong}</td>
                    <td>${parseFloat(product.price).toFixed(2)}</td>
                    <td><img src="${product.image}" alt="${product.name}"></td>
                    <td>${product.category_name}</td>
                `;
                tbody.appendChild(row);
            });
        }

        // Fetch products when the page loads
        window.onload = fetchProducts;
    </script>
</body>
</html>